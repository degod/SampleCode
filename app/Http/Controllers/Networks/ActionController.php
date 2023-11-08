<?php

namespace App\Http\Controllers\Networks;

use App\Http\Controllers\Controller;
use App\Repository\Packages\PackageRepositoryInterface;
use App\Repository\Payment\PaymentRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\PartnerCode\PartnerCodeRepositoryInterface;
// use App\View\Components\AlertTypes;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class ActionController extends Controller
{
    /**
     * @var PackageRepositoryInterface
     */
    private PackageRepositoryInterface $packageRepository;
    /**
     * @var PartnerCodeRepositoryInterface
     */
    private PartnerCodeRepositoryInterface $partnerCodeRepository;
    /**
     * @var PaymentRepositoryInterface
     */
    private PaymentRepositoryInterface $paymentRepository;
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @param PackageRepositoryInterface $packageRepository
     * @param PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(PackageRepositoryInterface $packageRepository, PaymentRepositoryInterface $paymentRepository, PartnerCodeRepositoryInterface $partnerCodeRepository, UserRepositoryInterface $userRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->paymentRepository = $paymentRepository;
        $this->partnerCodeRepository = $partnerCodeRepository;
        $this->userRepository = $userRepository;
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
            'email' => 'required|string|email|max:255|unique:partners_manual_codes',
            'package' => 'required|string'
        ]);
    }

    /**
     * The showPage() will be used to display the dashboard
     * 
     * @param Request $request
     */
    public function generateCode(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // CHECK THAT THE USER IS NOT PAID YET
        $validateUser = $this->userRepository->getUserByEmail($request->email);
        if ($validateUser && $validateUser->payment_status == 'PAID') {
            return redirect()->back()->withErrors('User already exists and package PAID as well')->withInput();
        }
        $request['code'] = $this->partnerCodeRepository->getCode();
        $request['amount'] = $this->packageRepository->getSimulatedData()->{$request['package']}->amount;

        $created = $this->partnerCodeRepository->createPartnerCode($request->all());
        if ($created){
            return redirect()->route('partner.code.generated');
        } else {
            return redirect()->back()->withErrors('Unable to create Partner Code.');
        }
    }

    /**
     * The showPage() will be used to display the dashboard
     * 
     * @param Request $request
     */
    public function updateCode(Request $request, $id)
    {
        // CHECK THAT THE USER IS NOT PAID YET
        $code = $this->partnerCodeRepository->getPartnerCodeById($id);

        if ($code->status != 'PENDING') {
            return redirect()->route('partner.code.generated')->withErrors('CODE has already been used!')->withInput();
        }
        $request['amount'] = $this->packageRepository->getSimulatedData()->{$request['package']}->amount;

        $updated = $this->partnerCodeRepository->updatePartnerCode($id, $request->except('_token'));
        if ($updated){
            return redirect()->route('partner.code.generated');
        } else {
            return redirect()->back()->withErrors('Unable to update Partner Code.');
        }
    }
}
