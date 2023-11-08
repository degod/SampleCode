<?php
    date_default_timezone_set('Africa/Lagos');
        
    $style = "style2.css";
    $link = route('partner.register.form').'?ref='.\Auth::user()->username;
    $share_url = "Hi friends, come join me in earning and making good money. Register with my link to begin - $link. Thank you! - ";
?>
@extends('layouts.main.master')

@section('style'){{ $style }}@endsection

@section('main_content')

        <!-- Main Section -->
        <main>
            <div class="topbar">
                <div class="mobile-logo">
                    <a href="{{ route('partner.dashboard') }}">
                        <img src="{{ url('/') }}/assets/img/prokip-logo.svg" alt="logo">
                    </a>
               </div> 

                <div class="welcome-message">
                    <h3>Dashboard - {{ \Auth::user()->first_name }} {{ \Auth::user()->last_name }}</h3>
                </div>

                <div class="top-right">
                    @include('layouts.main.top-bar-right')
                </div>
            </div>


            <!-- Main content -->
            <div class="main-container">
                @include('layouts.main.alert')

                <div class="mobile-welcome-message">
                    <h3>Welcome {{ \Auth::user()->first_name }} {{ \Auth::user()->last_name }}</h3>
                    <h6>{{ date('jS F, Y | h:i A') }}</h6>
                </div>
                <div class="overview-filter">
                    <div class="title">
                        <h1>
                            <small class="mobile-welcome-message">Earnings Balance<br></small>
                            ₦{{ number_format($earningsBalance, 2) }} 
                            @if(\Auth::user()->payment_status == 'PAID')
                                <a href="#" class="primary-button" style="float:right;height:45px;margin-left:20px;" data-micromodal-trigger="modal-withdraw">
                                    WITHDRAW
                                </a>
                            @else
                                @if(\Auth::user()->package)
                                    <a href="{{ route('partner.onboard.preview',['package'=>\Auth::user()->package]) }}" class="primary-button" style="float:right;height:45px;margin-left:20px;">
                                @else
                                    <a href="{{ route('partner.onboard.package') }}" class="primary-button" style="float:right;height:45px;margin-left:20px;">
                                @endif
                                    COMPLETE PAYMENT
                                </a>
                            @endif
                        </h1>
                        <p>Withdrawable Balance</p>
                    </div>

                    <div class="filter">
                        @if(\Auth::user()->payment_status == 'PAID')
                            <div class="affiliate-link-wrapper">
                                <h3 class="title">Signup Link</h3>
                                <div class="form-wrapper">
                                    <input type="text" name="" id="" class="input-link copyarea" value="{{ route('partner.register.form') }}?ref={{ \Auth::user()->username }}">

                                    <div class="affiliate-buttons">
                                        <button class="copybtn">
                                            <img src="{{ url('/') }}/assets/img/copy-icon.svg" alt="">
                                        </button>

                                        <button data-micromodal-trigger="modal-1">
                                            <img src="{{ url('/') }}/assets/img/view-icon.svg" alt="">
                                        </button>

                                        <button  data-micromodal-trigger="modal-share">
                                            <img src="{{ url('/') }}/assets/img/share-icon.svg" alt="">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="quick-data">
                    <div class="item">
                        <div class="head">
                            <img src="{{ url('/') }}/assets/img/icons/sales.svg" alt="">
                            <span>All Time</span>
                        </div>
                        <div class="body">
                            <div class="data-name">
                                <h5>My Signups</h5>
                                <h3>{{ $totalRefers }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="head">
                            <img src="{{ url('/') }}/assets/img/icons/Pending-payment.svg" alt="">
                            <span>All Time</span>
                        </div>
                        <div class="body">
                            <div class="data-name">
                                <h5>My Network</h5>
                                <h3>{{ $networkTotal }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="head">
                            <img src="{{ url('/') }}/assets/img/icons/Expenses-icon.svg" alt="">
                            <span>All Time</span>
                        </div>
                        <div class="body">
                            <div class="data-name">
                                <h5>Businesses</h5>
                                <h3>{{ $totalBusinesses }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="head">
                            <img src="{{ url('/') }}/assets/img/icons/Purchase.svg" alt="">
                            <span>All Time</span>
                        </div>
                        <div class="body">
                            <div class="data-name">
                                <h5>My Rank</h5>
                                <small>Coming Soon...</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-title">
                    <h2>Commissions</h2>
                </div>
                <div class="quick-data second-row" style="margin-top:0">
                    <div class="item">
                        <div class="head">
                            <img src="{{ url('/') }}/assets/img/icons/sales.svg" alt="">
                            <span>All Time</span>
                        </div>
                        <div class="body">
                            <div class="data-name">
                                <h5>Signup</h5>
                                <h3>₦0.00</h3>
                                <!-- <small>Referral partner commissions</small> -->
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="head">
                            <img src="{{ url('/') }}/assets/img/icons/Pending-payment.svg" alt="">
                            <span>All Time</span>
                        </div>
                        <div class="body">
                            <div class="data-name">
                                <h5>Sales</h5>
                                <h3>₦0.00</h3>
                                <!-- <small>For the businesses you onboarded using license</small> -->
                            </div>
                            <div class="growth-stage">
                                <img src="{{ url('/') }}/assets/img/icons/growth.svg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="head">
                            <img src="{{ url('/') }}/assets/img/icons/Purchase.svg" alt="">
                            <span>All Time</span>
                        </div>
                        <div class="body">
                            <div class="data-name">
                                <h5>Renewal</h5>
                                <h3>₦0.00</h3>
                                <!-- <small>Sum of everything on this row</small> -->
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="head">
                            <img src="{{ url('/') }}/assets/img/icons/Expenses-icon.svg" alt="">
                            <span>All Time</span>
                        </div>
                        <div class="body">
                            <div class="data-name">
                                <h5>Transactions</h5>
                                <h3>₦0.00</h3>
                                <!-- <small>Promonie/SMS/Funding Commission</small> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-title">
                    <h2>Performance (on total commissions)</h2>
                </div>

                <div class="graph-card-wrapper">
                    <div id="chart"></div>

                    <div class="extra-data">
                        <div class="item">
                            <div class="head">
                                <img src="{{ url('/') }}/assets/img/icons/money-out.svg" alt="">
                                <span>All Time</span>
                            </div>
                            <div class="body">
                                <div class="data-name">
                                    <h5>Network Commission</h5>
                                    <h3>₦{{ number_format(0, 2) }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="head">
                                <img src="{{ url('/') }}/assets/img/icons/money-in.svg" alt="">
                                <span>All Time</span>
                            </div>
                            <div class="body">
                                <div class="data-name">
                                    <h5>Bonus Points (BP)</h5>
                                    <h3>0 pts</h3>
                                </div>
                                <div class="growth-stage">
                                    <span>Coming Soon...</span>
                                    <img src="{{ url('/') }}/assets/img/icons/growth.svg" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="head">
                                <img src="{{ url('/') }}/assets/img/icons/net-profit.svg" alt="">
                                <span>All Time</span>
                            </div>
                            <div class="body">
                                <div class="data-name">
                                    <h5>Total Commission</h5>
                                    <h3>₦{{ number_format($totalEarnings, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- copylink modal -->
        <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1">
              <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                  <h2 class="modal__title" id="modal-1-title">
                    Copy Referral
                  </h2>
                  <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                  <div class="view-affiliate-modal">
                    <div class="form-box">
                        <input type="text" name="" id="" value="{{ route('partner.register.form') }}?ref={{ \Auth::user()->username }}">

                        <button class="copy-link" id="modal-copybtn">Copy Link</button>
                    </div>
                  </div>
                </main>
              </div>
            </div>
        </div>
        <!-- end copylink modal -->

        <!-- share button modal -->
        <div class="modal micromodal-slide" id="modal-share" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1">
              <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                  <h2 class="modal__title" id="modal-1-title">
                    Click to Share
                  </h2>
                  <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                  <div class="share-affiliate-modal">
                    <div class="a2a_kit a2a_default_style">
                        <a class="a2a_button_facebook">
                            <img src="{{ url('/') }}/assets/img/icons8-facebook-96.png" alt="">
                        </a>
                        <a class="a2a_button_twitter">
                            <img src="{{ url('/') }}/assets/img/icons8-twitter-96.png" alt="">
                        </a>
                        <a class="a2a_button_whatsapp">
                            <img src="{{ url('/') }}/assets/img/icons8-whatsapp-96.png" alt="">
                        </a>
                        <a class="a2a_button_linkedin">
                            <img src="{{ url('/') }}/assets/img/icons8-linkedin-circled-96.png" alt="">
                        </a>
                        <a class="a2a_dd" href="https://www.addtoany.com/share">
                            <img src="https://static.addtoany.com/buttons/custom/addtoany-icon-long-shadow.png" border="0" alt="Share" width="27">
                        </a>
                    </div>
                  </div>
                </main>
              </div>
            </div>
        </div>
        <!-- end share button modal -->

        <!-- share button modal -->
        <div class="modal micromodal-slide" id="modal-withdraw" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1">
              <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                  <h2 class="modal__title" id="modal-1-title">
                    Enter Withdrawal Amount
                  </h2>
                  <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                  <div class="view-affiliate-modal">
                    <div class="form-box">
                        <form action="{{ route('partner.earnings.withdraw') }}" method="POST">
                            @csrf
                            <label>Not more than ₦{{ number_format($earningsBalance, 2) }}</label>
                            <input type="text" name="amount" value="{{ $earningsBalance }}">

                            <button class="copy-link" id="modal-copybtn">Withdraw Now</button>
                        </form>
                    </div>
                  </div>
                </main>
              </div>
            </div>
        </div>
        <!-- end share button modal -->
@endsection


@section('footer_js')
    <!-- Javascript file -->
    <script type="text/javascript">
        var a2a_config = a2a_config || {};
        a2a_config.linkname = "{{ $share_url }}";
    </script>
    <script async src="https://static.addtoany.com/menu/page.js"></script>
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>

    <script type="text/javascript">
        var copyTextareaBtn = document.querySelector('#modal-copybtn');
        copyTextareaBtn.addEventListener('click', function(event) {
          var copyTextarea = document.querySelector('.copyarea');
          copyTextarea.select();
          try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
            alert('Text copied to clipboard!');
          } catch (err) {
            console.log('Oops, unable to copy');
          }
        });


        // Chart
        var options = {
        series: [{
        name: 'series1',
        data: [1, 20, 30, 150, 155, 160, 170, 200, 190, 180, 200, 250, 300, 350, 400],
        fill:['#F44336']
        }],
        chart: {
        height: 350,
        type: 'area',
        zoom: {
          enabled: false
        }
        },
        dataLabels: {
        enabled: false
        },
        stroke: {
        curve: 'smooth'
        },
        xaxis: {
        type: 'datetime',
        categories: ["1 May", "2 May", "3 May", "4 May", "5 May", "6 May", "7 May", "8 May", "9 May", "10 May", "11 May", "12 May", "13 May", "14 May", "15 May"]
        },
        tooltip: {
        x: {
          format: 'dd/MM/yy HH:mm'
        },
        },
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endsection