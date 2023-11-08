<?php
namespace App\Utilities;

// use App\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class MonnifyUtility
{
    public function __construct()
    {
        $this->contract = env('MONNIFY_CONTRACT');
        $this->api_key = env('MONNIFY_API_KEY');
        $this->secret_key = env('MONNIFY_SECRET_KEY');
        $this->headers = array('Content-Type: application/json');
        $this->base_url = env('MONNIFY_URL');
        $this->verify_url = $this->base_url.'api/v1/merchant/transactions/query?paymentReference=';
    }


    public function tokenMonnify(){
        $ch = curl_init();
        $url = $this->base_url.'api/v1/auth/login/';
        $cred = $this->api_key.':'.$this->secret_key;
        $basic = base64_encode($cred);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            'Authorization: Basic '.$basic
        ));
        $tokenResponse = curl_exec($ch);
        curl_close($ch);

        if(!empty($tokenResponse)){
            $tokenResponse = json_decode($tokenResponse, true);
            return $tokenResponse['responseBody']['accessToken'];
        }
    }


    public function verifyMonnifyTraditionally($trxref){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->base_url."api/v1/merchant/transactions/query?paymentReference=".urlencode($trxref));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic ".base64_encode($this->api_key.':'.$this->secret_key)
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        $raveObj = [];

        $raveObj['raw'] = $requestX;
        if($err){
            $raveObj['status_code'] = 'unknown';
        }
        curl_close ($ch);
        $result = json_decode($requestX, true);

        if (!empty($result['responseBody'])) {
            $value = $result['responseBody'];
            $chargeResponse = (!empty($value['paymentStatus'])) ? $value['paymentStatus']: '';
        }else{
            $chargeResponse = '';
        }
        
        if((in_array($chargeResponse, ['PAID','OVERPAID']) && '0'==$result['responseCode'])){
            $raveObj['status_code'] = 'success';
        }elseif(in_array($chargeResponse, ['EXPIRED','FAILED','CANCELLED'])) {
            $raveObj['status_code'] = 'failed';
        }elseif(in_array($chargeResponse, ['PARTIALLY_PAID','PENDING'])) {
            $raveObj['status_code'] = 'pending';
        }else{
            $raveObj['status_code'] = 'pending';
        }

        return $raveObj;
    }


    public function verifyBankAccount($accountNumber, $bankCode){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->base_url."api/v1/disbursements/account/validate?accountNumber=".$accountNumber."&bankCode=".$bankCode);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic ".base64_encode($this->api_key.':'.$this->secret_key)
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);
        $raveObj = [
            "message"=>"Invalid Account Details",
            "data"=>$result
        ];

        if(!empty($result) && ($result->requestSuccessful && $result->responseMessage) && $result->requestSuccessful==true && $result->responseMessage=='success'){
            $raveObj = [
                "message"=>$result->responseBody->accountName,
                "data"=>$result
            ];
        }

        return $raveObj;
    }


    public function listBanks(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->base_url."api/v1/sdk/transactions/banks");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic ".base64_encode($this->api_key.':'.$this->secret_key)
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);
        $raveObj = [];

        if(!empty($result) && ($result->requestSuccessful && $result->responseMessage) && $result->requestSuccessful==true && $result->responseMessage=='success'){
            $raveObj = $result->responseBody;
        }

        return $raveObj;
    }


    public function transferFund($data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->base_url."api/v2/disbursements/single");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic ".base64_encode($this->api_key.':'.$this->secret_key),
            "Content-Type: application/json"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);
        $raveObj = $result;

        if(!empty($result) && ($result->requestSuccessful && $result->responseMessage) && $result->requestSuccessful==true && $result->responseMessage=='success'){
            $raveObj = $result->responseBody;
        }

        return $raveObj;
    }


    public function createInvoice($data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->base_url."api/v1/invoice/create");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic ".base64_encode($this->api_key.':'.$this->secret_key),
            "Content-Type: application/json"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);
        $raveObj = $result;

        if(!empty($result) && ($result->requestSuccessful && $result->responseMessage) && $result->requestSuccessful==true && $result->responseMessage=='success'){
            $raveObj = $result->responseBody;
        }

        return $raveObj;
    }


    public function calcCharge($customer, $amount=0){
        $perc = (($customer->fee_percentage/100) * $amount);
        $flat = $customer->fee_flat;
        $charge = $perc + $flat;

        return $charge;
    }

    
    public function monnifyCallback(Request $request){
        $req = \Request::all();
        \DB::table('monnify_callback')->insert([
            'raw'=>json_encode($req),
            'provider'=>'monnify'
        ]);
        $req = json_decode(json_encode($req), true);
        if(!isset($req['transactionReference']) && isset($req['eventData'])) $req = $req['eventData'];
        
        if(!empty($req['transactionReference'])){
            $ver = $this->verifyMonnify($req['transactionReference']);
            $acc = \App\Models\ReservedAccount::where('account_ref', $req['product']['reference'])->first();
            $user = $acc->user;
            
            $transactionId = $req['transactionReference'];
            $amount = $req['amountPaid'];
            
            if($ver['status_code'] == 'success'){
                // FUND WALLET ACCOUNT WITH COMPLETE TRANSFER AMOUNT
                $data = [
                    'amount'=>$amount,
                    'type'=>'credit',
                    'user_id'=>$user->id,
                    'description'=>'Transfer to Virtual Wallet Account',
                    'channel'=>'virtual-wallet-transfer',
                    'transactionId'=>$transactionId
                ];
                app('App\Http\Controllers\WalletController')->logWallet($data);

                // DEBIT WALLET FUNDING CHARGES
                $charge = $this->calcCharge($acc, $amount);
                $data = [
                    'amount'=>$charge,
                    'type'=>'debit',
                    'user_id'=>$user->id,
                    'description'=>'Bank Charges on Wallet Funding',
                    'channel'=>'virtual-wallet-transfer',
                    'transactionId'=>$transactionId.'BC'
                ];
                app('App\Http\Controllers\WalletController')->logWallet($data);

            }
        }
        return $req;
    }
    
    public function generateAccount($user_id, $name, $account_ref, $email, $fee_percentage, $fee_flat){
        $ch = curl_init();
        $url = $this->base_url."api/v2/bank-transfer/reserved-accounts";

        $data = [
            "accountName"       => $name,
            "accountReference"  => $account_ref,
            "currencyCode"      => "NGN",
            "contractCode"      => $this->contract,
            "customerName"      => $name,
            "customerEmail"     => trim($email),
            "getAllAvailableBanks"=> false,
            "preferredBanks"    => ["035"]
        ];
        $json = json_encode($data);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->tokenMonnify()
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response, true);
        
        if(!empty($res) && isset($res['responseBody']['accounts'][0]['accountNumber'])){
            \App\Models\ReservedAccount::create([
                "user_id"=>$user_id,
                "account_name"=>$name,
                "account_number"=>$res['responseBody']['accounts'][0]['accountNumber'],
                "account_reference"=>$account_ref,
                "bank"=>$res['responseBody']['accounts'][0]['bankName'],
                "flat_charge"=>$fee_flat,
                "percentage_charge"=>$fee_percentage,
                "cap_charge"=>100
            ]);

            return back();
        }else{
            return back();
        }
    }

    public function getMonnifyAccountRef(){
        return 'PRK-'.time().'-'.rand(1000,9999);
    }

    public function getMonnifyAccountNo(Request $request, $id=null){
        if(!empty($request->user_id))
            $user = \App\Models\User::find($request->user_id);
        elseif(!empty($id))
            $user = \App\Models\User::find($id);
        else{
            return back()->withErrors("Something went wrong! Please try again or Contact Support.");
        }
        
        $account_ref = $this->getMonnifyAccountRef();
        $name = trim($user->first_name).' '.trim($user->last_name);
        $fee_percentage = $request->fee_percentage ?? 0;
        $fee_flat = $request->fee_flat ?? 100;
        
        $res = $this->generateAccount($user->id, $name, $account_ref, $user->email, $fee_percentage, $fee_flat);
        
        if(!empty($res) && $res=='success'){
            \Session::flash('success', 'Auto wallet account successfully created!');

            if(!empty($id))
                return back();
        }else{
            \Session::flash('error', 'ERROR: Could not create your auto wallet account. Please try again later!');

            if(!empty($id))
                return back();
        }
        
        return back();
    }


    public function getBalance(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->base_url."api/v2/disbursements/wallet-balance?accountNumber=".env('MONNIFY_ACCOUNT'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic ".base64_encode($this->api_key.':'.$this->secret_key)
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);
        $raveObj = [];
        // dd($requestX);

        if(!empty($result) && ($result->requestSuccessful && $result->responseMessage) && $result->requestSuccessful==true && $result->responseMessage=='success'){
            $raveObj = $result->responseBody;
        }

        return $raveObj;
    }
}