<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\PartnerCode\PartnerCodeRepositoryInterface;
use App\Repository\Payment\PaymentRepositoryInterface;
use App\Repository\Packages\PackageRepositoryInterface;
use App\Repository\Coupon\CouponRepositoryInterface;
use App\Services\TryCatchErrorLogger;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;
    /**
     * @var PaymentRepositoryInterface
     */
    private PaymentRepositoryInterface $paymentRepository;
    /**
     * @var PartnerCodeRepositoryInterface
     */
    private PartnerCodeRepositoryInterface $partnerCodeRepository;
    /**
     * @var PackageRepositoryInterface
     */
    private PackageRepositoryInterface $packageRepository;
    /**
     * @var CouponRepositoryInterface
     */
    private CouponRepositoryInterface $couponRepository;
    /**
     * @var TryCatchErrorLogger
     */
    private TryCatchErrorLogger $logger;

    /**
     * @param TryCatchErrorLogger $logger
     * @param UserRepositoryInterface $userRepository
     * @param PaymentRepositoryInterface $paymentRepository
     * @param PackageRepositoryInterface $paymentRepository
     */
    public function __construct(TryCatchErrorLogger $logger, UserRepositoryInterface $userRepository, PaymentRepositoryInterface $paymentRepository, PartnerCodeRepositoryInterface $partnerCodeRepository, PackageRepositoryInterface $packageRepository, CouponRepositoryInterface $couponRepository)
    {
        $this->middleware('guest');
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->partnerCodeRepository = $partnerCodeRepository;
        $this->paymentRepository = $paymentRepository;
        $this->packageRepository = $packageRepository;
        $this->couponRepository = $couponRepository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone'=>'required|string|min:8|max:18',
            'country' => 'required|string',
            'username'=>'required|unique:users',
            'password' => 'required|string|min:6',
            'referral_code' => 'nullable|string|exists:users,username',
        ]);
    }

    /**
     * Registers a new user after a valid registration.
     *
     * @param  array  $data
     * redirects to dasboard
     */
    protected function register(Request $request)
    {
        date_default_timezone_set('Africa/Lagos');

        $createPayment = new \stdClass();
        $createPayment->transactionId = null;
        
    	try{
    		\DB::beginTransaction();

	    	$packages = $this->packageRepository->getSimulatedData();
	        $data = $request->all();

	    	$validator = $this->validator($request->all());
	        if ($validator->fails()) {
	            return redirect()->back()->withErrors($validator)->withInput();
	        }
	        $regData = $validator->valid();
            $regData['password'] = Hash::make($regData['password']);
	        
	        // CHECK IF USER WAS REFERRED
	        if(!empty($request->referral_code)){
	        	$referrer = $this->userRepository->getUserByUsername($request->referral_code);
	        	$regData['referrer_user_id'] = $referrer->id;
	        }else{
                $regData['referrer_user_id'] = 1;
            }

	        // CREATE USER ACCOUNT - PENDING PAYMENT CONFIRMATION
	        $createAccount = $this->userRepository->createUser($regData);

            // LOG USER IN AUTOMATICALLY
            \Auth::attempt($request->only('email','password'));

	        \DB::commit();
    	}catch(\Exception $e){
    		$this->logger->errorLog($e);

	        \DB::rollback();
            return redirect()->back()->withErrors("Oops! There was an issue. Not you, but from us :(")->withInput();
    	}

        return redirect()->route('partner.onboard.package');
    }
}
