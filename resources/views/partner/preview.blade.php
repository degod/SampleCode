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

        .lds-ring {
          display: inline-block;
          position: relative;
          width: 70px;
          height: 60px;
        }
        .lds-ring div {
          box-sizing: border-box;
          display: block;
          position: absolute;
          width: 40px;
          height: 40px;
          margin: 5px;
          border: 3px solid #000;
          border-radius: 50%;
          animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
          border-color: #fff transparent transparent transparent;
        }
        .lds-ring div:nth-child(1) {
          animation-delay: -0.45s;
        }
        .lds-ring div:nth-child(2) {
          animation-delay: -0.3s;
        }
        .lds-ring div:nth-child(3) {
          animation-delay: -0.15s;
        }
        @keyframes lds-ring {
          0% {
            transform: rotate(0deg);
          }
          100% {
            transform: rotate(360deg);
          }
        }
    </style>
</head>
<body>

    <div class="login-layout">
        @include('layouts.main.alert')

        <div class="form-content" style="margin: 100px 0;">

			<div class="title">
            	<h3 class="">Pricing & Plan</h3>
				<p>Review Your selected Plan</p>
			</div>

			<div class="progress" style="width: 100%">
				<div class="item active"></div>
				<div class="item active"></div>
				<div class="item active"></div>
				<div class="item"></div>
			</div>

		    <form method="POST" action="{{ route('partner.onboard.complete') }}" id="paymentForm">
		        @csrf
				<div class="cart-details">
					<div class="item">
						<div class="item-first">
							<span class="title">Plan</span>
						</div>

						<div class="item-second">
							<span class="title">Price</span>
						</div>
					</div>
					<a href="{{ route('partner.onboard.package') }}" class="change-plan">Click here to Change Plans</a>

					<div class="item">
						<div class="item-first">
							<span class="content">{{ strtoupper($package) }} Package</span>
							<input type="hidden" value="{{ $package }}" name="package" />
						</div>
						<div class="item-second">
							<span class="content">₦ {{ number_format($packagePrice) }}</span>
						</div>
					</div>

		            <div id="coupon_contain"></div>

		            <div class="item">
						<div class="item-first">
							<span class="title">Subtotal</span>
						</div>
						<div class="item-second">
							<span class="content">₦ {{ number_format($packagePrice) }}</span>
						</div>
		            </div>

		            <div class="item">
						<div class="item-first">
							<span class="title">TOTAL</span>
						</div>
						<div class="item-second">
							<span class="title" id="total">₦ <b id="total_amount">{{ number_format($packagePrice) }}</b></span>
              	<input type="hidden" value="{{ $packagePrice }}" id="original_amount" />
              	<input type="hidden" value="{{ $packagePrice }}" id="amount" name="amount"/>
              	<input type="hidden" value="" id="transactionId" name="transactionId"/>
						</div>
		            </div>
		            <div id="code-info"></div>

					<div class="cart-coupon">
    					<input type="text" name="coupon_code" id="coupon" placeholder="Coupon Code" />
		                <button class="coupon-btn" id="apply_btn" type="button">Apply</button>
					</div>
		            <br>

					<div class="cart-payment-method">
						<a href="#">
							<div class="bank-transfer" onclick="fetchAccount()">
								<img src="{{ url('/') }}/assets2/img/mdi_bank-transfer-in.png"/>

								<span>Pay With Bank Transfer</span>
							</div>
						</a>

						<div class="card-payment" onclick="pickCard()">
							<img src="{{ url('/') }}/assets2/img/iconfinder_card_master_mastercard_380809 1.png"/>

							<span>Pay With Card</span>
						</div>
					</div>
			    </div>
	        </form>
        </div>
    </div>
    
    <!-- share button modal -->
    <dialog id="bankAccountModal" class="virtual-account-modal">
    	<a onclick="document.getElementById('paymentForm').submit();">
    		<img src="{{ url('/') }}/assets2/img/cancel-icon.png"/>
    	</a>

			<div class="account_content">
				Fetching details...
			</div>

			<div class="account_progress">
				<div class="title">
					<h3>MNFY/Prokip Limited-Sub</h3>
				</div>

				<div class="preloading-image">
					<img src="{{ url('/') }}/assets2/img/circle-loader.gif" alt="" width="70px" />
				</div>

				<span class="processing-transfer">Processing Transfer</span>
			</div>
			<center style="margin: 15px 0;">
				<small>This process may take a while in some cases. Make sure to keep this tab open to automatically confirm your payment</small>
			</center>
		</dialog>
    <!-- end share button modal -->


	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="{{ url('/') }}/asset2/js/main.js"></script>
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>

	<script type="text/javascript">
		var transactionId = Math.round(+new Date()/1000);
		var amt = 0;
		var coup = '{{ \Auth::user()->coupon_code }}';

      function formatNGN(num) {
          var p = num.toFixed(2).split(".");
          return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
              return num + (num != "-" && i && !(i % 3) ? "," : "") + acc;
          }, "") + "." + p[1];
      }
    	
    	function removeCoupon(){
    		amt = $('#original_amount').val();
				$('#total_amount').html(amt);
				$('#coupon_contain').html("");
				$('#code-info').html('');
				$('#amount').val(amt);
    	}

        $(function () {
        	if(coup){
        		$('#coupon').val(coup);
        		setTimeout(function(){
        			$('#apply_btn').trigger('click');
        		},500)
        	}

        	$('#apply_btn').click(function(){
        		var code = $('#coupon').val();
        		amt = $('#original_amount').val();

        		if(code.length){
        			$.ajax({
        				url: "{{ route('partner.coupon.verify') }}?code="+code+"&amount="+amt,
        				type: "GET",
        				beforeSend: function(data){
        					$('#total_amount').html(amt);
        					$('#amount').val(amt);
        					$('#coupon_contain').html("");
        					$('#code-info').html('Verifying your coupon code...');
        				},
        				success: function(data){
        					if(data.status && data.status==true){
	        					$('#code-info').html(`
	        						<div class="coupon-code-status">
										<img src="{{ url('/') }}/assets2/img/check-circle.svg"/>
										<span>Coupon Code Applied Successfully</span>
									</div>
        						`);
	        					$('#total_amount').html(formatNGN(data.amount));
	        					$('#coupon_contain').html(`
	        						<div class="item">
		        						<div class="item-first">
						                	<span class="content">Coupon: `+code+` (`+data.percent+`% off)</span>
						                </div>
						                <div class="item-second">
						                	<span class="content" style="color:red;">
						                		- ₦ `+formatNGN(data.discount)+`
					                		</span>
											<button class="remove-coupon" onclick="removeCoupon()" type="button">Remove</button>
						                </div>
					                </div>
	    						`);
	    						$('#amount').val(data.amount);
        					}else{
        						$('#code-info').html(data.message);
        					}
        				},
        				error: function(data){
        					$('#code-info').html(data.message ?? "Oop!! Something went wrong!");
        				}
        			});
        		}else if(!code){
        			$('#code-info').html('Please enter your coupon to apply...');
        		}
        	});
        });
	</script>

    <script type="text/javascript">
    	function fetchAccount(){
    		var amount = $('#amount').val();
    		transactionId = Math.round(+new Date()/1000);
    		$('#transactionId').val(transactionId);

    		$.ajax({
    			type: 'GET',
    			url: "{{ url('fetch-account') }}/"+transactionId+"/"+amount+"/Subscription for {{$package}} Package",
    			beforeSend: function(){
    				bankAccountModal.showModal()
    				$('.account_content').html('<h2>Fetching details...</h2');
    			},
    			success: function(e){
    				if(e.accountName != undefined){
    					$('.account_content').html(`
							<h2>Transfer ₦`+formatNGN(e.amount)+` to `+e.bankName+`</h2>
							<h1>`+e.accountNumber+`</h1>
						`);

			            setInterval(function(){
			                $.ajax({
			                    url: '{{ url("fund-check") }}/'+transactionId,
			                    type: 'GET',
			                    success: function(e){
			                        if(e.status_code == 'success'){
			                            document.getElementById('paymentForm').submit();
			                        }
			                    }
			                });
			            }, 5000);
    				}else{
    					$('.account_content').html('<h3>Error: Please try again later!</h3>');
			            setTimeout(function(){
			                document.getElementById('paymentForm').submit();
			            }, 10000);
    				}
    			}
    		});
    	}

    	function pickCard(){
    		var amount = $('#amount').val();
    		transactionId = Math.round(+new Date()/1000);
    		$('#transactionId').val(transactionId);

			MonnifySDK.initialize({
			    amount: amount,
			    currency: "NGN",
			    reference: transactionId,
			    customerFullName: "{{ \Auth::user()->first_name.' '.\Auth::user()->last_name }}",
			    customerEmail: "{{ \Auth::user()->email }}",
			    customerMobileNumber: "{{ \Auth::user()->phone }}",
			    apiKey: "{{ env('MONNIFY_API_KEY') }}",
			    contractCode: "{{ env('MONNIFY_CONTRACT') }}",
			    paymentDescription: "Subscription for {{$package}} Package",
			    paymentMethods: ['CARD'],
			    onComplete: function(response){
			        document.getElementById('paymentForm').submit();
			    },
			    onClose: function(data){
			        document.getElementById('paymentForm').submit();
			    },
			    onCancel: function(data){
			        document.getElementById('paymentForm').submit();
			    }
			});
    	}
    </script>
</body>
</html>