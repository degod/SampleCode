
                    <div class="options">
                        <!-- <button class="item">
                            <img src="{{ url('/') }}/assets/img/icons/notification.svg" alt="">
                        </button> -->

                        <button class="item" onclick="toggleMenu()">
                            <img src="{{ url('/') }}/assets/img/user-profile.png" height="35px">
                        </button>

                        <!-- Sub Menu -->
                        <div class="topbar-sub-menu-wrap" id="subMenu">
                            <div class="topbar-sub-menu">
                                @if(\Auth::user()->payment_status == 'PAID')
                                    <a href="{{ route('partner.profile') }}" class="sub-menu-link">
                                @else
                                    <a href="{{ route('partner.payment.complete') }}" class="sub-menu-link">
                                @endif
                                    <img src="{{ url('/') }}/assets/img/user-profile.png" width="16px">
                                    <span>Profile</span>
                                </a>
                                <a href="{{ route('partner.change.account.password') }}" class="sub-menu-link">
                                    <img src="{{ url('/') }}/asset/img/icons/password.svg" width="16px">
                                    <span>Change Password</span>
                                </a>
                                
                                <form action="{{ route('logout') }}" method="post" id="logoutForm">@csrf</form>
                                <a href="#" onclick="document.getElementById('logoutForm').submit()" class="sub-menu-link">
                                    <img src="{{ url('/') }}/assets/img/icons/log-out-04.svg" width="14px"> 
                                    <span>Sign Out</span> 
                                </a>
                            </div>
                        </div>
                        <!-- End of Submenu -->

                        <button class="item display-mobile-only"  id="menu-btn">
                            <img src="{{ url('/') }}/assets/img/icons/menu-flex.svg" alt="">
                        </button>
                    </div>
                