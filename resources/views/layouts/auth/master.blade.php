<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/style.css">
</head>
<body>
    
    <!-- Main container will be grid to divide the sidebar and the main section -->
    <div class="container">
        <!-- Sidebar container -->
        <aside>
            <!-- Sidebar top -->
            <div class="top">
                <!-- Business Name -->
                <div class="logo">
                     <img src="{{ url('/') }}/assets/img/prokip-logo.svg" alt="logo">
                </div> 
 
                <button id="close-btn">
                 <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <path d="M18.4325 6.90405L6.4325 18.9041" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                     <path d="M6.4325 6.90405L18.4325 18.9041" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                     </svg> 
                </button>
             </div>

            <!-- Sidebar content & links -->
            <div class="sidebar">
                <div class="links">
                    <a href="#" class="active">
                        <img src="/Agent/{{ url('/') }}/assets/img/dashboard-icon-active.svg" alt="">
                        <h3>Dashboard</h3>
                    </a>

                    <a href="#">
                        <img src="/Agent/{{ url('/') }}/assets/img/customers-icon.svg" alt="">
                        <h3>Profile</h3>
                    </a>

                    <a href="#">
                        <img src="/Agent/{{ url('/') }}/assets/img/performance.svg" alt="">
                        <h3>Networks</h3>
                    </a>

                    <a href="#">
                        <img src="/Agent/{{ url('/') }}/assets/img/earnings-icon.svg" alt="">
                        <h3>Earnings</h3>
                    </a>

                    <form action="{{ route('logout') }}" method="post" id="logoutForm">@csrf</form>
                    <a href="#" onclick="document.getElementById('logoutForm').submit()">
                        <img src="/Agent/{{ url('/') }}/assets/img/earnings-icon.svg" alt="">
                        <h3>Logout</h3>
                    </a>
                </div>
            </div>
        </aside>


        @yield('main_content')


    </div>
    <!-- Javascript file -->
    <script src="{{ url('/') }}/assets/javascript/main.js"></script>
</body>
</html>