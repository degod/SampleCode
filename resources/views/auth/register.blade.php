{{-- @extends('layouts.auth')
@section('title')
Agent - Register
@endsection

@section('content')

<div class="details">
    <h2>Sign Up</h2>
    <p>Create account and keep track.</p>

    <div class="onboarding-title">
        <h3>Personal Information</h3>
    </div>

    <div class="onboarding-steps">
        <div class="step-on active"></div>
        <div class="step-on"></div>
        <div class="step-on"></div>
        <div class="step-on"></div>
    </div>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="input-wrapper">
        <input type="text" name="name" placeholder="Enter Fullname" value="{{ old('name') }}" class="full-name">
        @error('name')
            <span  style="color: red">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="input-wrapper">
        <input type="number" name="phone_number" placeholder="Enter Phone Number" value="{{ old('phone_number') }}" required autocomplete="phone_number" autofocus class="phone_number @error('phone_number') is-invalid @enderror">
        @error('phone_number')
            <span  style="color: red">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="input-wrapper">
        <input type="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus class="email @error('email') is-invalid @enderror">
        @error('email')
            <span  style="color: red">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="input-wrapper">
        <input type="password" name="password" placeholder="Enter Password" class="password @error('password') is-invalid @enderror">
        @error('password')
            <span style="color: red">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="button-wrapper">
        <button>Continue</button>
    </div>
</form>

<div class="form-footer" style="text-align: center; padding-bottom: 20px;">
    <p style="padding-bottom: 24px;">By signing up, I accept the ProWebPOS <a href="#">Terms of Service</a> and acknowledge the <a href="#">Privacy Policy</a>.</p>

    <div style="display: flex; justify-content: center; align-items: center;">
        <hr style="width: 326px; border: 1px solid #ccc;">
    </div>

    <p style="padding: 14px 0px;">Already Have an account? <a href="{{route('login')}}" style="font-weight: 600;">Sign In</a></p>
</div>
@endsection



 --}}




@extends('layouts.auth')
@section('title')
Agent - Register
@endsection
@section('content')

<div class="form-content">

    <h3 class="auth-text-heading">Register
    </h3>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="input-wrapper">
            <label for="email">Fullname</label>
            <input type="text" id="name" name="name" placeholder="Enter Full name" value="{{ old('name') }}" required autocomplete="name" autofocus class="name @error('name') is-invalid @enderror">
            @error('name')
                <span  style="color: red">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-wrapper">
            <label for="email">Phone Number</label>
            <input type="number" id="phone" name="phone_number" placeholder="Enter Phone Number" value="{{ old('phone_number') }}" required autocomplete="number" autofocus class="number @error('number') is-invalid @enderror">
            @error('phone_number')
                <span  style="color: red">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-wrapper">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus class="email @error('email') is-invalid @enderror">
            @error('email')
                <span  style="color: red">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-wrapper">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="password-switch">
            <button id="show-password" type="button">
                <img src="{{asset('asset/img/icons/eye.png')}}" alt="">
            </button>

            @error('password')
                <span style="color: red">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <a href="{{ route('password.request') }}" class="forget-password">Forget Password?</a>

        <div class="button-wrapper">
            <button>Register</button>
        </div>
    </form>

    <div class="form-footer">
        <p>Already have an Account? <a href="{{route('login')}}">Login</a></p>
    </div>
    
</div>


@endsection




