<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Štatistika návštevnosti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div class="container-xl">
        <header>
            <h1 class="page-content text-center py-3 my-3">Bojda Weather Forecast</h1>
        </header>

        <div class="page-content my-3">
            <nav class="navbar navbar-dark dark-blue-color">
                <div class="container-fluid">
                    <button class="navbar-toggler border-gray" type="button" data-bs-toggle="collapse" data-bs-target="#nav-toggle" 
                    aria-controls="nav-toggle" aria-expanded="false" aria-label="Zobraz menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </nav>
            <nav>
                <div class="collapse" id="nav-toggle">
                    <div class="row dark-blue-color mx-0">
                        <a class="col-12 col-md-4 py-3 {{ Route::is('address_view') ? 'nav-button-active' : '' }}
                            d-flex justify-content-center" href="{{ route('address_view') }}">Adresa</a>
                        <a class="col-12 col-md-4 py-3 {{ Route::is('weather_view') ? 'nav-button-active' : '' }}
                            d-flex justify-content-center" href="{{ route('weather_view') }}">Predpoveď počasia</a>
                        <a class="col-12 col-md-4 py-3 {{ Route::is('statistics_view') ? 'nav-button-active' : '' }}
                            d-flex justify-content-center" href="{{ route('statistics_view') }}">Štatistika návštevnosti</a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="page-content">
            <div class="p-3">
                <div class="row">
                    <h2 class="pb-3">Štatistika návštevnosti</h2>
                </div>

                <div class="row mx-2">
                    <h4 class="ps-0">Návštevnosť podľa krajín</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Krajina</th>
                                <th>Vlajka</th>
                                <th>Počet návštev</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($country_data as $item)
                            <tr>
                                <td><a class="clickable" onclick="showLocaleStatsModal('{{ $item['country'] }}')">{{ $item['country'] }}</a></td>
                                <td><img src="http://www.geonames.org/flags/x/{{ strtolower($item['country_code']) }}.gif" style="height: 100%;" alt="Vlajka krajiny"></td>
                                <td>{{ $item['count'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row my-4 mx-2">
                    <h4 class="ps-0">Lokácie návštevníkov</h4>
                    <div id="map" style="height: 400px"></div>
                </div>

                <div class="row mt-5 mx-2">
                    <h4 class="ps-0">Návštevnosť podľa času</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>06:00-15:00</th>
                                <th>15:00-21:00</th>
                                <th>21:00-24:00</th>
                                <th>00:00-06:00</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $time_data['count_6_to_15'] }}</td>
                                <td>{{ $time_data['count_15_to_21'] }}</td>
                                <td>{{ $time_data['count_21_to_24'] }}</td>
                                <td>{{ $time_data['count_0_to_6'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <footer class="bg-clip-content py-1 ps-2 text-white dark-blue-color">© 2023 Viktor Bojda</footer>
        </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Lokácia</th>
                                <th>Počet návštev</th>
                            </tr>
                        </thead>
                        <tbody id="modal-table-body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const localeData = @json($locale_data);
        const visitorData = @json($gps_data);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="{{ asset('js/statistics.js') }}"></script>
</body>
</html>