<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Package Selection</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/style.css">

</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="{{ url('/') }}/assets/img/prokip-logo.svg" alt="">
        </div>
        <div class="top-right">
            <div class="options">
	        	@if(\Auth::user())
	                <a href="{{ route('partner.dashboard') }}" class="item hide-mobile-only">
	                    <img src="{{ url('/') }}/assets/img/dashboard-icon.svg" height="30">
	                </a>
	        	@else
	                <a href="{{ route('login') }}" class="item">
	                    <img src="{{ url('/') }}/assets/img/icons/log-in-04.svg" width="34px">
	                </a>
        		@endif
            </div>
        </div>
    </nav>

    <div class="package-page-title">
        <h2>Select your preferred Package</h2>
    </div>

    <div class="packages-wrapper">
    	@foreach($packages as $id=>$package)
	        <div class="packages-item">
	            <div class="content">
	                <div class="head">
	                    <h3>{{ $package->name }}</h3>
	                    <p>{{ $package->short_description }}</p>
	                </div>

	                <div class="price-details">
	                    <h1>₦ {{ number_format($package->amount) }} <span>/One-off</span></h1>
	                    <p>No Renewal Required</p>
	                </div>

	                <div class="package-features">
	                	@foreach($package->benefits as $benefit)
		                    <div class="item">
		                        <img src="{{ url('/') }}/assets/img/check.svg" alt="">
		                        <span>{{ $benefit }}</span>
		                    </div>
	                	@endforeach
	                </div>
	            </div>

	        	@if(\Auth::user())
	                <a href="{{ route('partner.dashboard') }}" class="package-link">
	            		<img src="{{ url('/') }}/assets/img/dashboard-icon.svg"> &nbsp; Continue
	            	</a>
	        	@else
	            	<a href="{{ route('partner.register.form', ['package'=>$id]) }}" class="package-link">Get Started</a>
	        	@endif
	        </div>
    	@endforeach
    </div>
</body>
</html>