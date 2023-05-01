<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predpoveď počasia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
                <h2 class="pb-3">Adresa</h2>

                @if (session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error_message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('visitors_create') }}" method="post">
                    @csrf
                    <div class="row">
                        <div>
                            <label for="input-address" class="form-label">Zadaj adresu</label>
                            <input type="text" name="address" id="input-address" class="form-control" required>
                        </div>
                        <div class="d-grid mx-auto mt-3">
                            <button type="submit" class="btn btn-primary">Vyhľadaj</button>
                        </div>
                    </div>
                </form>
            </div>

            <footer class="bg-clip-content py-1 ps-2 text-white dark-blue-color">© 2023 Viktor Bojda</footer>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>