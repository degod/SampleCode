<?php

namespace App\Http\Controllers\PartnerPackages;

use App\Http\Controllers\Controller;
use App\Repository\PartnerCode\PartnerCodeRepositoryInterface;
use App\Repository\Packages\PackageRepositoryInterface;
use App\Repository\Payment\PaymentRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\Earnings\EarningRepositoryInterface;
use App\Repository\Coupon\CouponRepositoryInterface;
// use App\View\Components\AlertTypes;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Utilities\PackageEarningUtility;
use App\Services\TryCatchErrorLogger;
use App\Models\User;
use App\Models\Payment;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class OnboardController extends Controller
{
    /**
     * @var PartnerCodeRepositoryInterface
     */
    private PartnerCodeRepositoryInterface $partnerCodeRepository;
    /**
     * @var PackageRepositoryInterface
     */
    private PackageRepositoryInterface $packageRepository;
    /**
     * @var PaymentRepositoryInterface
     */
    private PaymentRepositoryInterface $paymentRepository;
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;
    /**
     * @var EarningRepositoryInterface
     */
    private EarningRepositoryInterface $earningRepository;
    /**
     * @var CouponRepositoryInterface
     */
    private CouponRepositoryInterface $couponRepository;
    /**
     * @var PackageEarningUtility
     */
    private PackageEarningUtility $earningUtil;
    /**
     * @var TryCatchErrorLogger
     */
    private TryCatchErrorLogger $logger;

    public $tempHold = [];

    /**
     * @param PackageRepositoryInterface $packageRepository
     * @param PaymentRepositoryInterface $paymentRepository
     * @param UserRepositoryInterface $userRepository
     * @param EarningRepositoryInterface $earningRepository
     */
    public function __construct(PartnerCodeRepositoryInterface $partnerCodeRepository, PackageRepositoryInterface $packageRepository, PaymentRepositoryInterface $paymentRepository, UserRepositoryInterface $userRepository, EarningRepositoryInterface $earningRepository, CouponRepositoryInterface $couponRepository, PackageEarningUtility $earningUtil, TryCatchErrorLogger $logger)
    {
        $this->partnerCodeRepository = $partnerCodeRepository;
        $this->packageRepository = $packageRepository;
        $this->paymentRepository = $paymentRepository;
        $this->userRepository = $userRepository;
        $this->earningRepository = $earningRepository;
        $this->couponRepository = $couponRepository;
        $this->earningUtil = $earningUtil;
        $this->logger = $logger;
    }

    /**
     * The onboardPackage() will be used to display the package selection
     * 
     * @param Request $request
     */
    public function onboardPackage(Request $request)
    {
        $packages = $this->packageRepository->getSimulatedData();

        return view('partner.package', compact('packages'));
    }

    /**
     * The onboardPreview() will be used to preview the package payment
     * 
     * @param Request $request
     */
    public function onboardPreview(Request $request, String $package=null)
    {
        $packages = $this->packageRepository->getSimulatedData();
        $packagePrice = $packages->{$package}->amount;

        return view('partner.preview', compact('packages','package','packagePrice'));
    }

    /**
     * Completes registration of a new user after a payment.
     *
     * @param  array  $data
     * redirects to success page
     */
    protected function onboardComplete(Request $request)
    {
        try{
            \DB::beginTransaction();

            $packages = $this->packageRepository->getSimulatedData();
            $data = $request->all();
            $user = \Auth::user();
            $dt = \Carbon\Carbon::now();

            $data['amount'] = $packages->{$request['package']}->amount;

            // CHECK IF COUPON CODE WAS GIVEN
            if($request->coupon_code){
                $fetchCoupon = $this->couponRepository->getCouponByCode($data['coupon_code']);
                
                if(empty($fetchCoupon)){
                    return redirect()->back()->withErrors("Coupon not valid!")->withInput();
                }
                if($fetchCoupon->type == 'special'){
                    if(!empty($fetchCoupon->user_id)){
                        return redirect()->back()->withErrors("Coupon has already been used!")->withInput();
                    }
                }
                if($dt->greaterThan($fetchCoupon->expiry)){
                    $updateCoupon = $this->couponRepository->updateCoupon($fetchCoupon->id, [
                        'status'=>'expired',
                        'updated_at'=>$dt
                    ]);
                    \DB::commit();
                    
                    return redirect()->back()->withErrors("Coupon has EXPIRED!")->withInput();
                }

                $regData['coupon_percent'] = $fetchCoupon->coupon_percent;
                $discount = ($regData['coupon_percent']/100) * $data['amount'];
                $data['amount'] = $data['amount'] - $discount;
            }

            $data['user_id'] = $user->id;
            $data['status'] = 'initiated';
            $data['description'] = 'Subscription Payment for '.$packages->{$request['package']}->name.' Package';

            // INITIATE PAYMENT FOR USER SUBSCRIPTION PACKAGE
            $createPayment = $this->paymentRepository->createPayment($data);

            // UPDATE FOR USER SUBSCRIPTION PACKAGE
            $packageUpdate = $this->userRepository->updateUser($user->id, [
                'package'=>$request['package'],
                'updated_at'=>$dt
            ]);

            // CONFIRM PAYMENT
            $paymentInfo = $this->paymentRepository->getPaymentByTransId($request->transactionId);
            $verifyPayment = $this->paymentRepository->verifyPayment($request->transactionId);

            // CHECK IF COUPON CODE WAS GIVEN
            if($request->coupon_code){
                // UPDATE COUPON USER_ID
                $updateCoupon = $this->couponRepository->updateCoupon($fetchCoupon->id, [
                    'user_id'=>$user->id,
                    'status'=>'used',
                    'updated_at'=>$dt
                ]);

                // UPDATE FOR USER COUPON CODE
                $packageUpdate = $this->userRepository->updateUser($user->id, [
                    'coupon_code'=>$request->coupon_code,
                    'coupon_percent'=>$regData['coupon_percent'],
                    'updated_at'=>$dt
                ]);
            }

            // UPDATE PAYMENT FIRST
            $this->paymentRepository->updatePayment($paymentInfo->id, [
                'status'=>isset($verifyPayment['status_code']) ? $verifyPayment['status_code']: $paymentInfo->status,
                'requery_response'=>isset($verifyPayment['raw']) ? $verifyPayment['raw']: 'empty',
                'updated_at'=>\DB::raw('NOW()')
            ]);

            \DB::commit();

            if(isset($verifyPayment['status_code']) && $verifyPayment['status_code'] == 'success'){
                // PROCESS NETWORK COMMISSIONS ACROSS DOWNLINES
                $this->processCommission($paymentInfo, $paymentInfo->user);
            }else{
                return redirect()->route('partner.onboard.failed');
            }
        }catch(\Exception $e){
            $this->logger->errorLog($e);

            \DB::rollback();
            return redirect()->route('partner.onboard.failed');
        }

        return redirect()->route('partner.onboard.success');
    }

    /**
     * The onboardSuccess() will be used to display the success page
     * 
     * @param Request $request
     */
    public function onboardSuccess()
    {
        return view('partner.success');
    }

    /**
     * The onboardFailed() will be used to display the failed page
     * 
     * @param Request $request
     */
    public function onboardFailed()
    {
        return view('partner.failed');
    }

    /**
     * The showForm() will be used to display the registration form
     * 
     * @param Request $request
     */
    public function showForm(Request $request, $package=null)
    {
        $packages = $this->packageRepository->getSimulatedData();
        $referralCode = \Request::get('ref');
        $referrerName = null;
        if(!empty($referralCode)){
            $referrer = $this->userRepository->getUserByUsername($referralCode);
            if(!empty($referrer))
                $referrerName = $referrer->first_name.' '.$referrer->last_name.' ('.$referralCode.')';
        }
        $states = ["Abia","Adamawa","Akwa Ibom","Anambra","Bauchi","Bayelsa","Benue","Borno","Cross River","Delta","Ebonyi","Edo","Ekiti","Enugu","FCT - Abuja","Gombe","Imo","Jigawa","Kaduna","Kano","Katsina","Kebbi","Kogi","Kwara","Lagos","Nasarawa","Niger","Ogun","Ondo","Osun","Oyo","Plateau","Rivers","Sokoto","Taraba","Yobe","Zamfara"];
        $countries = json_decode(file_get_contents('assets/c-states.json'));
        $codes = json_decode(file_get_contents('assets/c-codes.json'));

        return view('partner.register', compact('package', 'packages', 'referralCode', 'referrerName', 'states', 'countries', 'codes'));
    }

    /**
     * The verifyCode() will be used to display the dashboard
     * 
     * @param Request $request
     */
    public function verifyCode(Request $request)
    {
        $data = \Request::all();
        $packages = $this->packageRepository->getSimulatedData();
        $resp = "CODE NOT VALID";
        
        $verify = $this->partnerCodeRepository->verifyCode($data['code'], $data['email']);
        if($verify){
            $package = $packages->{$verify->package};
            $settled = ($verify->settled!='no') ? ' (Commissions already settled)': '';
            $resp = $package->name.' package for ₦'.number_format($package->amount, 2).$settled;
        }

        return $resp;
    }

    /**
     * The couponVerify() will be used to verify coupon codes
     * 
     * @param Request $request
     */
    public function couponVerify(Request $request)
    {
        $data = \Request::all();
        $resp = ["status"=>false, "message"=>"Coupon not passed"];
        if($data['code']){
            $fetchCoupon = $this->couponRepository->getCouponByCode($data['code']);
            $dt = \Carbon\Carbon::now();
            
            if(empty($fetchCoupon)){
                return ["status"=>false, "message"=>"Coupon not valid"];
            }
            if($fetchCoupon->type == 'special'){
                if(!empty($fetchCoupon->user_id)){
                    return ["status"=>false, "message"=>"Coupon has already been used"];
                }
            }
            if($dt->greaterThan($fetchCoupon->expiry)){
                $updateCoupon = $this->couponRepository->updateCoupon($fetchCoupon->id, [
                    'status'=>'expired',
                    'updated_at'=>$dt
                ]);
                \DB::commit();
                
                return ["status"=>false, "message"=>"Coupon has EXPIRED"];
            }

            $discount = ($fetchCoupon->coupon_percent/100) * $data['amount'];
            $data['amount'] = $data['amount'] - $discount;
            
            $resp = [
                "status"=>true, 
                "message"=>"Coupon code applied Successfully!", 
                "discount"=>$discount,
                "amount"=>$data['amount'],
                "percent"=>$fetchCoupon->coupon_percent
            ];
        }

        return $resp;
    }

    /**
     * The showPaymentForm() will be used to display the registration form
     *
     * @param  String $transactionId
     * @return redirect to package.payment
     */
    protected function showPaymentForm(Request $request, String $transactionId)
    {
        $paymentInfo = $this->paymentRepository->getPaymentByTransId($transactionId);

        return view('partner.package-payment', compact('paymentInfo'));
    }

    /**
     * The completePayment() will be used to display the registration form
     *
     * @param  String $transactionId
     * @return redirect to package.payment
     */
    protected function completePaymentForm(Request $request)
    {
        $routeName = $request->route()->action['as'];
        $user = \Auth::user();

        if($user->id == 1){
            $this->userRepository->updateUser($user->id, [
                'payment_status'=>'PAID',
                'referrer_user_id'=>null,
                'updated_at'=>\DB::raw('NOW()')
            ]);
            return redirect()->route('partner.dashboard');
        }

        return view('partner.complete-payment', compact('user','routeName'));
    }

    /**
     * Registers a new user after a valid registration.
     *
     * @param  array  $data
     * redirects to dasboard
     */
    protected function completePayment(Request $request)
    {
        try{
            \DB::beginTransaction();

            $packages = $this->packageRepository->getSimulatedData();
            $data = $request->all();
            $user = \Auth::user();

            // CHECK IF PAYMENT MEDIUM IS OFFLINE OR ONLINE
            if($request->payment_medium == 'offline'){
                $verifyCode = $this->partnerCodeRepository->verifyCode($data['partner_code'], $data['email']);
                if(empty($verifyCode)){
                    return redirect()->back()->withErrors("Code not valid")->withInput();
                }

                $regData['package'] = $verifyCode->package;
                $request['package'] = $verifyCode->package;
            }else{
                $request['package'] = $user->package;
            }
            $data['amount'] = $packages->{$request['package']}->amount;


            // CHECK IF COUPON CODE WAS GIVEN
            if($user->coupon_percent){
                $discount = ($user->coupon_percent/100) * $data['amount'];
                $data['amount'] = $data['amount'] - $discount;
            }

            $data['user_id'] = $user->id;
            $data['status'] = 'initiated';
            $data['description'] = 'Subscription Payment for '.$packages->{$request['package']}->name.' Package';
            $data['transactionId'] = rand(10000,99999).rand(10000,99999).'-'.rand(100,999).'-'.time();
            
            if($request->payment_medium == 'offline'){
                $data['description'] = 'Offline Payment for '.$packages->{$request['package']}->name.' Package';
                $data['transactionId'] = $request['partner_code'];
            }

            // INITIATE PAYMENT FOR USER SUBSCRIPTION PACKAGE
            $createPayment = $this->paymentRepository->createPayment($data);

            \DB::commit();
        }catch(\Exception $e){
            $this->logger->errorLog($e);

            \DB::rollback();
        }
        if($request->payment_medium == 'offline'){
            return redirect()->route('partner.skip.payment', ['transactionId'=>$createPayment->transactionId]);
        }

        return redirect()->route('partner.package.payment', ['transactionId'=>$createPayment->transactionId]);
    }

    /**
     * The confirmPayment() will be used to complete users package
     * subscription payment process and redirect to dashboard
     *
     * @return redirect to package.payment
     */
    protected function confirmPayment(Request $request)
    {
        $paymentInfo = $this->paymentRepository->getPaymentByTransId($request->transactionId);
        $verifyPayment = $this->paymentRepository->verifyPayment($request->transactionId);

        try{
            \DB::beginTransaction();

            // UPDATE PAYMENT FIRST
            $this->paymentRepository->updatePayment($paymentInfo->id, [
                'status'=>isset($verifyPayment['status_code']) ? $verifyPayment['status_code']: $paymentInfo->status,
                'requery_response'=>isset($verifyPayment['raw']) ? $verifyPayment['raw']: 'empty',
                'updated_at'=>\DB::raw('NOW()')
            ]);

            if(isset($verifyPayment['status_code']) && $verifyPayment['status_code'] == 'success'){
                // PROCESS NETWORK COMMISSIONS ACROSS DOWNLINES
                $this->processCommission($paymentInfo, $paymentInfo->user);
            }

            \DB::commit();
        }catch(\Exception $e){
            $this->logger->errorLog($e);
            
            \DB::rollback();
        }

        return redirect()->route('partner.dashboard');
    }

    /**
     * The skipPayment() will be used to complete offline users
     * subscription processing and redirect to dashboard
     *
     * @return redirect to package.payment
     */
    protected function skipPayment(Request $request, String $transactionId)
    {
        $paymentInfo = $this->paymentRepository->getPaymentByTransId($transactionId);
        $partnerInfo = $this->partnerCodeRepository->getPartnerCodeByTransId($transactionId);
        $verifyCode = $this->partnerCodeRepository->getPartnerCodeByTransId($transactionId);

        try{
            \DB::beginTransaction();
            $this->userRepository->updateUser($paymentInfo->user->id, [
                'payment_status'=>'PAID',
                'updated_at'=>\DB::raw('NOW()')
            ]);

            // UPDATE PAYMENT FIRST
            $this->paymentRepository->updatePayment($paymentInfo->id, [
                'status'=>'success',
                'requery_response'=>'PAID OFFLINE',
                'updated_at'=>\DB::raw('NOW()')
            ]);

            // UPDATE PARTNER MANUAL CODE
            $this->partnerCodeRepository->updatePartnerCode($partnerInfo->id, [
                'status'=>'USED',
                'user_id'=>$paymentInfo->user->id,
                'updated_at'=>\DB::raw('NOW()')
            ]);

            if($verifyCode->settled == 'no'){
                // PROCESS NETWORK COMMISSIONS ACROSS DOWNLINES
                $this->processCommission($paymentInfo, $paymentInfo->user);
            }

            \DB::commit();
        }catch(\Exception $e){
            $this->logger->errorLog($e);
            
            \DB::rollback();
        }

        return redirect()->route('partner.dashboard');
    }


    /**
     * The processCommission() will be used to credit users earning
     * 
     * @param Payment $paymentInfo
     * @param User $user
     * @param int $generation
     *
     * @return redirect to package.payment
     */
    public function processCommission(Payment $paymentInfo, User $user)
    {
        $user = $paymentInfo->user;
        $this->userRepository->updateUser($user->id, [
            'payment_status'=>'PAID',
            'updated_at'=>\DB::raw('NOW()')
        ]);
        $usr = $user;

        for($i = 1; $i <= 4; $i++){
            $res = $this->creditEarning($paymentInfo, $usr, $i);
            if($res === null){
                break;
            }else{
                $usr = $res;
            }
        }
    }


    /**
     * The creditEarning() will be used to credit users earning
     * 
     * @param Payment $paymentInfo
     * @param User $user
     * @param int $generation
     *
     * @return redirect to package.payment
     */
    public function creditEarning(Payment $paymentInfo, User $user, int $generation): ?User
    {
        $res = null;
        
        // CHECK IF USER WAS REFERRED BY A PARTNER
        if(!empty($user->referrer_user_id)){
            $referrer = $user->referrer;
            
            if($referrer->payment_status == 'PAID'){
                $amt = $this->earningUtil->calculateEarning($paymentInfo->amount, $generation);
                $this->tempHold['Gen_'.$generation] = $amt;

                // DO REFERRAL EARNING REMMITTANCE FOR REFERRER
                $this->earningRepository->createEarning([
                    'user_id'=>$referrer->id,
                    'amount'=>$amt,
                    'transactionId'=>$paymentInfo->transactionId.'_'.$generation.'_'.$user->id,
                    'description'=>'Referral Earning from '.$user->first_name.' package subscription of N'.$paymentInfo->amount,
                    'type'=>'credit'
                ]);

                $res = $referrer;
            }
        }

        return $res;
    }
}
