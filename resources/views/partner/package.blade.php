<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Package Selection</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets2/css/style.css">

</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="{{ url('/') }}/assets2/img/prokip-logo.svg" alt="">
        </div>
        <div class="top-right">
            <div class="options">
                <a href="{{ route('partner.dashboard') }}" class="item hide-mobile-only">
                    <img src="{{ url('/') }}/assets2/img/dashboard-icon.svg" height="30">
                </a>
            </div>
        </div>
    </nav>

	<div class="package-page-title">
		<h2>Choose Your Preferred Plan</h2>
		<p>
			Choose the plan you want to go for and click on the get started buttion to proceed to cart
		</p>

		<div class="progress">
			<div class="item active"></div>
			<div class="item active"></div>
			<div class="item"></div>
			<div class="item"></div>
		</div>
	</div>

    <div class="packages-wrapper">
    	@foreach($packages as $id=>$package)
	        <div class="packages-item {{ ($id=='elite')?'recommended':'list' }}">
	            <div class="content">
					<div class="head">
						<div>
		                    <h3>{{ $package->name }}</h3>
		                    <p>{{ $package->short_description }}</p>
						</div>

						@if($id=='elite')
							<span class="recommended-text">Recommended</span>
						@endif
					</div>

	                <div class="price-details">
	                    <h1>₦ {{ number_format($package->amount) }} <span>/One-off</span></h1>
	                    <p>No Renewal Required</p>
	                </div>

	                <div class="package-features">
	                	@foreach($package->benefits as $benefit)
		                    <div class="item">
		                        <img src="{{ url('/') }}/assets2/img/check.svg" alt="">
		                        <span>{{ $benefit }}</span>
		                    </div>
	                	@endforeach
	                </div>
	            </div>

	        	<a href="{{ route('partner.onboard.preview', ['package'=>$id]) }}" class="package-link">Get Started</a>
	        </div>
    	@endforeach
    </div>
</body>
</html>