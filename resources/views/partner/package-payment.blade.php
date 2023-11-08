<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Onboarding</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/style.css">
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

        <div class="form-content" style="margin: 100px 0;">

            <h3 class="auth-text-heading">Proceed Onboarding on Prokip</h3>

            <div class="button-wrapper">
                <button onclick="fetchAccount()">Pay ₦{{number_format($paymentInfo->amount)}} with Bank Transfer</button>
            </div>
            <div class="button-wrapper">
                <button onclick="pickCard()">Pay ₦{{number_format($paymentInfo->amount)}} with Card</button>
            </div>
		    <form method="POST" action="{{ route('partner.confirm-payment') }}" id="paymentForm">
		        @csrf
		        <input type="hidden" name="transactionId" value="{{ $paymentInfo->transactionId }}">

	            <div class="button-wrapper">
	                <button>Continue without Payment</button>
	            </div>
	        </form>
        </div>
    </div>


    <!-- share button modal -->
    <div class="modal micromodal-slide" id="modal-withdraw" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1">
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="modal__title" id="modal-1-title">
                Package Payment
              </h2>
              <button class="modal__close" onclick="document.getElementById('paymentForm').submit();"></button>
            </header>
            <main class="modal__content" id="modal-1-content">
              <div class="view-affiliate-modal"></div>
            </main>
          </div>
        </div>
    </div>
    <!-- end share button modal -->
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="asset/js/main.js"></script>
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>

    <script type="text/javascript">
    	function fetchAccount(){
    		$.ajax({
    			type: 'GET',
    			url: "{{ route('partner.wallet.fetch-account',['transactionId'=>$paymentInfo->transactionId,'amount'=>$paymentInfo->amount,'description'=>$paymentInfo->description]) }}",
    			beforeSend: function(){
    				MicroModal.show('modal-withdraw');
    				$('.view-affiliate-modal').html('<center><div class="lds-ring"><div></div><div></div><div></div><div></div></div></center>');
    			},
    			success: function(e){
    				if(e.accountName != undefined){
    					$('.view-affiliate-modal').html(`
    						<div class="" style="margin-top:30px;">
                            <div class="form-box">
                                <p>Bank</p>
                                <label>`+e.bankName+`</label>
                            </div>
                        </div>
                        <div class="">
                            <div class="form-box">
                                <p>Account Number</p>
                                <label>`+e.accountNumber+`</label>
                            </div>
                        </div>
                        <div class="">
                            <div class="form-box">
                                <p>Transfer Amount</p>
                                <label>₦`+e.amount+`</label>
                            </div>
                        </div>
                        <small>We are checking in the background if you have made this payment. Please do not refresh or close this modal!</small>
						`);

			            setInterval(function(){
			                $.ajax({
			                    url: '{{ route("partner.wallet.ajax-fund-check", ["ref"=>$paymentInfo->transactionId]) }}',
			                    type: 'GET',
			                    success: function(e){
			                        if(e.status_code == 'success'){
			                            document.getElementById('paymentForm').submit();
			                        }
			                    }
			                });
			            }, 5000);
    				}else{
    					$('.view-affiliate-modal').html('<h3>Error: Please try again later!</h3>');
			            setTimeout(function(){
			                document.getElementById('paymentForm').submit();
			            }, 10000);
    				}
    			}
    		});
    	}

    	function pickCard(){
			MonnifySDK.initialize({
			    amount: {{ $paymentInfo->amount }},
			    currency: "NGN",
			    reference: '{{ $paymentInfo->transactionId }}',
			    customerFullName: "{{ $paymentInfo->user->first_name.' '.$paymentInfo->user->last_name }}",
			    customerEmail: "{{ $paymentInfo->user->email }}",
			    customerMobileNumber: "{{ $paymentInfo->user->phone }}",
			    apiKey: "{{ env('MONNIFY_API_KEY') }}",
			    contractCode: "{{ env('MONNIFY_CONTRACT') }}",
			    paymentDescription: "{{ $paymentInfo->description }}",
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