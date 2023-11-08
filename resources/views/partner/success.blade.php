<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Onboarding - Success</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets2/css/style.css">
    <!-- font awesome -->
</head>
<body>

		<div class="cart-wrapper">
			<div class="cart-success">
				<img src="{{ url('/') }}/assets2/img/cart-success.svg" alt="" />

				<div class="content">
					<h2>You’ve Successfully Signed Up</h2>
					<p>And your Payment has been Received!</p>

					<a href="{{ route('partner.home') }}" class="cart-success-btn">Proceed To the Dashboard</a>
				</div>
			</div>
		</div>
</body>
</html>