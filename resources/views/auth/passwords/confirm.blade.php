@extends('layouts.auth')
@section('title')
Agent - Confirmation
@endsection
@section('content')

<div class="form-content">

    <h3 class="auth-text-heading">Password Confirmation</h3>
    @if (session('status'))
       <p class="alert alert-success">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="input-wrapper">
            <label for="email">Password</label>
            <input type="password" name="password" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus class="email @error('email') is-invalid @enderror">
                @error('password')
                    <span  style="color: red">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>

        
        <div class="button-wrapper">
            <button type="submit">Continue</button>
        </div>
    
    
        
    </form>

    @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif

    {{-- <div class="form-footer">
        <p>New to Prokip? <a href="{{route('register')}}">Create an Account</a></p>
    </div> --}}
    
</div>


@endsection




