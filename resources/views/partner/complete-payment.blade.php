@extends('layouts.main.master')

@section('main_content')

	@section('header_css')
	    <style type="text/css">
	    	.help-block{
	    		color:red !important;
	    	}
	    	#skip-package{
	    		display: none;
	    	}
	    </style>
	@endsection

        <!-- Main Section -->
        <main>
            <!-- Top of the main section -->
            <div class="topbar">
                <div class="mobile-logo">
                    <a href="{{ route('partner.dashboard') }}">
                        <img src="{{ url('/') }}/assets/img/prokip-logo.svg" alt="logo">
                    </a>
               </div> 

                <div class="welcome-message">
                    <h3>Profile Completion</h3>
                </div>

                <div class="top-right">
                    @include('layouts.main.top-bar-right')
                </div>
            </div>

            <!-- Main content -->
            <div class="main-container">
                @include('layouts.main.alert')

                <div class="card-wrapper">
                    <div class="card-title">
                        <div class="title">
                            <h1>Package Payment</h1>
                            <!-- <p>Settings</p> -->
                        </div>
                    </div>

                    <div class="profile-page-information">
                        <div class="avatar">
                            @if(!empty($user->passport_photo))
                                <img src="{{ url('/users/passports', $user->passport_photo) }}" alt="">
                            @else
                                <img src="{{ url('/') }}/assets/img/user-profile.png" alt="">
                            @endif
                        </div>

                        <div class="profile-user-name">
                            <span class="user-name">{{ $user->first_name }} {{ $user->last_name }}</span>
                            <span class="email"> {{ $user->email }}</span>
                        </div>

                        <!-- <div class="upload-avatar-btn">
                            <button type="button"> Upload Avatar</button>
                        </div> -->
                    </div>


				    <form method="POST" action="{{ route('partner.register-complete') }}">
				        @csrf

                        <input type="hidden" name="email" value="{{ $user->email }}">

			            <div class="form-box" style="margin-top:50px">
			                <label for="payment_medium">Payment Medium</label>
			                <select name="payment_medium" id="payment_medium">
			                    <option value="online">I will pay online</option>
			                    <option value="offline">I have already paid offline</option>
			                </select>
				            @if ($errors->has('payment_medium'))
				                <span class="help-block">
				                    <strong>{{ $errors->first('payment_medium') }}</strong>
				                </span>
				            @endif
			            </div>

			            <div id="skip-package">
				            <a href="#" style="
					            font-size: 13px;
					            color: #c78f00;
					            text-align: left;
					            display: flex;
					            justify-content: flex-end;
					            padding-bottom: 2px;
				            ">How do I get this code</a>

                            <div class="form-box">
				                <label for="partner_code">Partner Code</label>
				                <input type="text" name="partner_code" id="partner_code" placeholder="Enter Code">

				                <br>
				                <label class="help-block" id="code-info"></label>
					            @if ($errors->has('partner_code'))
					                <span class="help-block">
					                    <strong>{{ $errors->first('partner_code') }}</strong>
					                </span>
					            @endif
				            </div>
			            </div>

			            <div class="footer-btn">
			                <button class="max-btn">Proceed</button>
			            </div><br>
			        </form>
                </div>                
            </div>
        </main>


@endsection


@section('footer_js')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script type="text/javascript">
        $(function () {
        	$('#payment_medium').change(function(){
        		var pm = $(this).val();

        		if(pm == 'online'){
        			$('#select-package').show();
        			$('#skip-package').hide();
        			$('#code-info').html("");
        			$('#partner_code').val("");
        		}else{
        			$('#select-package').hide();
        			$('#skip-package').show();
        		}
        	});

        	$('#partner_code').keyup(function(){
        		var code = $(this).val();
        		var email = "{{ $user->email }}";

        		if(code.length>6 && email){
        			$.ajax({
        				url: "{{ route('partner.code.verify') }}?code="+code+"&email="+email,
        				type: "GET",
        				beforeSend: function(data){
    						$('#code-info').html('Verifying your code...');
        				},
        				success: function(data){
        					$('#code-info').html(data);
        				},
        				error: function(data){
        					$('#code-info').html(data ?? "Oop!! Something went wrong!");
        				}
        			});
        		}else if(!email){
        			$('#code-info').html('Please enter your email to verify code...');
        		}
        	});
        });
    </script>
@endsection