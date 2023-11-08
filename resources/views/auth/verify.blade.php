@extends('layouts.auth')
@section('title')
Agent - Verification
@endsection
@section('content')

<div class="form-content">

    <div class="details">
        <h2>{{ __('Verify Your Email Address') }}</h2>
    
        {{-- <div class="onboarding-title">
            <h3></h3>
        </div> --}}
    
        <div class="onboarding-steps">
            <div class="step-on active"></div>
         
        </div>
    </div>
    
    @if (session('resent'))
    <div class="alert alert-success" role="alert">
        {{ __('A fresh verification link has been sent to your email address.') }}
    </div>
    @endif
    
    {{ __('Please check your email for a verification link.') }}
    {{ __('If you did not receive the email') }},
    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
    @csrf
    
    <div class="button-wrapper">
    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Click here to resend email') }}</button>
    </div>
    </form>
  
    
</div>


@endsection





