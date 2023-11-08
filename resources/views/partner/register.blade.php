<?php
	
	usort($codes, function($a, $b) {
	    return strcmp($a->dial_code, $b->dial_code);
	});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Onboarding</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets2/css/style.css">
    <!-- font awesome -->

    <style type="text/css">
    	.help-block{
    		color:red !important;
    	}
    	#skip-package{
    		display: none;
    	}
    </style>
</head>
<body>

    <div class="login-layout">
        @include('layouts.main.alert')

        <div class="form-content" style="margin: 100px 0;">

            <h3 class="auth-text-heading">Prokip Partner</h3>

			<p>Create Your Account</p>

			<div class="progress">
				<div class="item active"></div>
				<div class="item"></div>
				<div class="item"></div>
				<div class="item"></div>
			</div>

		    <form method="POST" action="{{ route('register') }}">
		        @csrf
	            <div class="input-wrapper">
	                <label for="first_name">First Name</label>
	                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}">
		            @if ($errors->has('first_name'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('first_name') }}</strong>
		                </span>
		            @endif
	            </div>

	            <div class="input-wrapper">
	                <label for="last_name">Last Name</label>
	                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}">
		            @if ($errors->has('last_name'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('last_name') }}</strong>
		                </span>
		            @endif
	            </div>

	            <div class="input-wrapper">
	                <label for="email">Email</label>
	                <input type="text" name="email" id="email" value="{{ old('email') }}">
		            @if ($errors->has('email'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('email') }}</strong>
		                </span>
		            @endif
	            </div>

				<div class="country-code-form">
					<select name="country" id="countries">
	                    @foreach($codes as $c)
	                    	<option value="{{$c->name}}" {{ ($c->dial_code=='+234')?'selected':'' }}>
	                    		{{ $c->dial_code }} ({{$c->name}})
	                    	</option>
	                    @endforeach
					</select>

	                <input type="number" name="phone" id="phone" value="{{ old('phone') }}" placeholder="000 0000 000"/>
		            @if ($errors->has('country'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('country') }}</strong>
		                </span>
		            @endif
		            @if ($errors->has('phone'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('phone') }}</strong>
		                </span>
		            @endif
	            </div>

	            <div class="input-wrapper">
	                <label for="username">Username</label>
	                <input type="text" name="username" id="username" value="{{ old('username') }}">
		            @if ($errors->has('username'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('username') }}</strong>
		                </span>
		            @endif
	            </div>

	            <div class="input-wrapper">
	                <label for="password">Password</label>
	                <input type="password" name="password" id="password" class="password-switch">
		            @if ($errors->has('password'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('password') }}</strong>
		                </span>
		            @endif
	            </div>

            	@if(!empty($referralCode) && !empty($referrerName))
	                <input type="hidden" name="referral_code" value="{{ $referralCode }}">
	            @else
	            	<div class="input-wrapper">
	            		@if(!empty($referralCode))
		                	<label class="help-block">Referral Code is INVALID</label>
		            	@else
		                	<label for="referral_code">Referral Code (OPTIONAL)</label>
		            	@endif
		                <input type="text" name="referral_code" id="referral_code" value="{{ $referralCode ?? old('referral_code') }}">
			            @if ($errors->has('referral_code'))
			                <span class="help-block">
			                    <strong>{{ $errors->first('referral_code') }}</strong>
			                </span>
			            @endif
	            	</div>
	            @endif

	            <div class="button-wrapper">
	                <button>Proceed</button>
	            </div><br>

	            <small><a href="{{ route('login') }}">Already signed up? Login here</a></small><hr>
	            <!-- <small>By signing up, I accept the Prokip <a href="#">Terms of Service</a> and acknowledge the <a>Privacy Policy</a>.</small> -->
				<p class="terms">
					By signing up, I accept the Prokip
					<a href="#">Terms of Service</a> and acknowledge the
					<a href="#">Privacy Policy</a>.
				</p>
	        </form>
        </div>
    </div>
    
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
</body>
</html>