<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/style.css">
    <!-- font awesome -->

    <style type="text/css">
    	.help-block{
    		color:red !important;
    	}
    </style>
</head>
<body>

    <div class="login-layout">

        <div class="form-content" style="margin: 100px 0; ">

            <h3 class="auth-text-heading">Login</h3>
            @include('layouts.main.alert')

		    <form method="POST" action="{{ route('login') }}">
		        @csrf
                <div class="input-wrapper">
                    <label for="name">Email or Username</label>
                    <input type="text" name="username" id="name" value="{{ old('username') }}">
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

                <div class="button-wrapper">
                    <button>Login</button>
                </div><br>
                <small><a href="{{ route('partner.register.form') }}">Don't have an account? Sign up</a></small>
                <small style="float:right"><a href="{{ route('password.request') }}">Forgot Password?</a></small>
            </form>
        </div>
        

    </div>
    
    <script src="asset/js/main.js"></script>
</body>
</html>