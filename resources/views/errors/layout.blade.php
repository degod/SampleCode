<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{asset('asset/css/style.css')}}">
</head>
<body>

    <div class="error-page">
        <div>
            <img src="{{asset('asset/img/pictures/disconnect.svg')}}" alt="" class="error-icon">
        </div>

        <div class="error-pattern">
            <h1>@yield('title')</h1>
            <p>@yield('message')</p>
            <a href="{{route('home')}}">Go Home</a>
        </div>
    </div>

    <!-- Javascript file -->
    <script src="{{asset('asset/js/main.js')}}"></script>
</body>
</html>


