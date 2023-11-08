<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Onboarding - Failed</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets2/css/style.css">
    <!-- font awesome -->
</head>
<body>
	<div class="cart-wrapper">
		<div class="cart-success">
			<img src="{{ url('/') }}/assets2/img/payment-failed.svg" alt="" />

			<div class="content">
				<h2>Payment Failed</h2>
				<p>Sorry, We could not process Your Payment</p>

				<div class="payment-failed-btns">
					<a href="{{ route('partner.home') }}" class="cart-retry-btn">
						Proceed To Dashboard
					</a>

                    @if(\Auth::user()->package)
						<a href="{{ route('partner.onboard.preview',['package'=>\Auth::user()->package]) }}" class="cart-success-btn">
                    @else
						<a href="{{ route('partner.onboard.package') }}" class="cart-success-btn">
                    @endif
						Retry
					</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>