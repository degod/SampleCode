<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/@yield('style','style.css')?tok={{ rand(1000,9999) }}">

    @yield('header_css')
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
                    <a href="{{ route('partner.dashboard') }}">
                        <img src="{{ url('/') }}/assets/img/prokip-logo.svg" alt="logo">
                    </a>
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
                    <a href="{{ route('partner.dashboard') }}" class="{{ ($routeName=='partner.dashboard')?'active':'' }}">
                        <img src="{{ url('/') }}/assets/img/dashboard-icon.svg" alt="">
                        <h3>Dashboard</h3>
                    </a>

                    @if(\Auth::user()->payment_status == 'PAID')
                        <a href="{{ route('partner.networks') }}" class="{{ ($routeName=='partner.networks')?'active':'' }}">
                            <img src="{{ url('/') }}/assets/img/performance.svg" alt="">
                            <h3>Networks</h3>
                        </a>

                        <a href="{{ route('partner.businesses') }}" class="{{ (in_array($routeName, ['partner.businesses','partner.businesses.top','partner.businesses.renewal','partner.businesses.inactive','partner.business.view']))?'active':'' }}">
                            <img src="{{ url('/') }}/assets/img/contacts.svg" alt="">
                            <h3>Businesses</h3>
                        </a>

                        <a href="{{ route('partner.pos-terminal') }}" class="{{ ($routeName=='partner.pos-terminal')?'active':'' }}">
                            <img src="{{ url('/') }}/assets/img/Quotation.svg" alt="">
                            <h3>
                                POS Terminals 
                                <!-- <span class="badge rounded-pill" style="color:#011530;background-color:#EFEFEF;font-size:10px;padding:2px;border-radius:4px;">Coming soon</span> -->
                            </h3>
                        </a>

                        <a href="{{ route('partner.earnings') }}" class="{{ ($routeName=='partner.earnings')?'active':'' }}">
                            <img src="{{ url('/') }}/assets/img/earnings-icon.svg" alt="">
                            <h3>Earnings</h3>
                        </a>

                        <a href="{{ route('partner.licenses') }}" class="{{ ($routeName=='partner.licenses')?'active':'' }}">
                            <img src="{{ url('/') }}/assets/img/task-square.svg" alt="">
                            <h3>My Licenses</h3>
                        </a>

                        <a href="{{ route('partner.wallet') }}" class="{{ ($routeName=='partner.wallet')?'active':'' }}">
                            <img src="{{ url('/') }}/assets/img/account.svg" alt="">
                            <h3>My Wallet</h3>
                        </a>
                    @else
                        <a href="{{ route('partner.payment.complete') }}" class="{{ ($routeName=='partner.payment.complete')?'active':'' }}">
                            <img src="{{ url('/') }}/assets/img/earnings-icon.svg" alt="">
                            <h3>Complete Payment</h3>
                        </a>
                    @endif

                    <!-- <form action="{{ route('logout') }}" method="post" id="logoutForm">@csrf</form>
                    <a href="#" onclick="document.getElementById('logoutForm').submit()">
                        <img src="{{ url('/') }}/assets/img/logout-icon.svg" alt="">
                        <h3>Logout</h3>
                    </a> -->
                </div>

                <div class="sidebar__footer">
                    <div class="links" style="display:flex">
                        <a href="#">Knowledge Base</a>
                        <a href="#">Support</a>
                    </div>
                </div>
            </div>
        </aside>


        @yield('main_content')

        <div class="copybtn hide"></div>
    </div>
    <!-- Javascript file -->
    <!-- Chart library from apexcharts.com -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @yield('footer_js')
    <script src="{{ url('/') }}/assets/javascript/main.js?tok={{ rand(1000,9999) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js" integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>