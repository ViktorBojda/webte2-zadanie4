<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Visitor;

class VisitorController extends Controller
{
    public function createVisitor(Request $request) {
        if (!$request->has('address') || !$request->address)
            return redirect()->back()->with('error_message', 'Address must not be empty.');

        $response = Http::get('https://nominatim.openstreetmap.org/search.php', [
            'q' => $request->address,
            'limit' => 1,
            'format' => 'jsonv2'
        ]);
        $open_street_data = $response->json()[0];

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $open_street_data['lat'],
            'lon' => $open_street_data['lon'],
            'appid' => env('WEATHER_API_KEY'),
            'units' => 'metric'
        ]);
        $weather_data = $response->json();

        $response = Http::withUrlParameters([
            'endpoint' => 'https://restcountries.com/v3.1/alpha',
            'country_code' => $weather_data['sys']['country']
        ])->get('{+endpoint}/{country_code}');
        $country_data = $response->json()[0];

        $ip = $request->ip();
        $local_date_time = date('Y-m-d H:i:s', time() + $weather_data['timezone']);
        $visitor = Visitor::where('ip_address', $ip)->orderBy('created_at_local')->first();  

        if ($visitor && date('Y-m-d', strtotime($visitor->created_at_local)) == date('Y-m-d', strtotime($local_date_time))) {}
        else {
            $visitor = new Visitor;
            $visitor->ip_address = $ip;
            $visitor->latitude = $open_street_data['lat'];
            $visitor->longitude = $open_street_data['lon'];
            $visitor->locale = $weather_data['name'];
            $visitor->country = $country_data['name']['common'];
            $visitor->country_code = $weather_data['sys']['country'];
            $visitor->created_at_local = $local_date_time;
            $visitor->save();
        }
        
        return redirect()->route('weather_view')->with(['weather' => $weather_data, 'country' => $country_data]);
    }

    public function getStatsVisitor(Request $request) {
        $country_data = Visitor::selectRaw('country, country_code, COUNT(*) as count')
                    ->groupBy('country', 'country_code')
                    ->get();

        $raw_locale_data = Visitor::selectRaw('country, locale, COUNT(*) as count')
                    ->groupBy('country', 'locale')
                    ->get();
        $raw_locale_data = json_decode($raw_locale_data);
        $locale_data = array();

        foreach ($raw_locale_data as $row) {
            if (isset($locale_data[$row->country]))
                array_push($locale_data[$row->country], array('locale' => $row->locale, 'count' => $row->count));
            else 
                $locale_data[$row->country] = array(array('locale' => $row->locale, 'count' => $row->count));
        }

        $gps_data = Visitor::select('latitude', 'longitude')->get();

        $count_6_to_15 = Visitor::whereRaw('HOUR(created_at_local) >= 6 AND HOUR(created_at_local) < 15')->count();
        $count_15_to_21 = Visitor::whereRaw('HOUR(created_at_local) >= 15 AND HOUR(created_at_local) < 21')->count();
        $count_21_to_24 = Visitor::whereRaw('HOUR(created_at_local) >= 21 AND HOUR(created_at_local) < 24')->count();
        $count_0_to_6 = Visitor::whereRaw('HOUR(created_at_local) >= 0 AND HOUR(created_at_local) < 6')->count();
        $time_data = [
            'count_6_to_15' => $count_6_to_15,
            'count_15_to_21' => $count_15_to_21,
            'count_21_to_24' => $count_21_to_24,
            'count_0_to_6' => $count_0_to_6
        ];

        return view('statistics', [
            'country_data' => $country_data,
            'locale_data' => $locale_data,
            'gps_data' => $gps_data,
            'time_data' => $time_data
        ]);        
    }
}
