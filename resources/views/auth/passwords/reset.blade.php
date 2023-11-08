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

            <h3 class="auth-text-heading">{{ __('Reset Password') }}</h3>
            @if (session('status'))
               <p class="alert alert-success">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
            
                <input type="hidden" name="token" value="{{ $token }}">
            
                <div class="input-wrapper">
                    <input id="email" name="email" type="email" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus class="email @error('email') is-invalid @enderror">
                    @error('email')
                        <span  style="color: red">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
                <div class="input-wrapper">
                    <input id="password" type="password" placeholder="Enter New Password"  name="password">
                    @error('password')
                        <span  style="color: red">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
            
                <div class="input-wrapper">
                    <input id="password" type="password" name="password_confirmation" placeholder="Confirm New Password">
                    @error('password')
                        <span  style="color: red">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
                <div class="button-wrapper">
                    <button type="submit"> {{ __('Send Password Reset Link') }}</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="asset/js/main.js"></script>
</body>
</html>

