<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repository\Packages\PackageRepositoryInterface;
use App\Repository\Payment\PaymentRepositoryInterface;
use App\Repository\Earnings\EarningRepositoryInterface;
use App\Repository\Wallet\WalletRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\LicenseWallet\LicenseWalletRepositoryInterface;
use App\Repository\Business\BusinessRepositoryInterface;
// use App\View\Components\AlertTypes;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class ViewController extends Controller
{
    /**
     * @var PackageRepositoryInterface
     */
    private PackageRepositoryInterface $packageRepository;
    /**
     * @var PaymentRepositoryInterface
     */
    private PaymentRepositoryInterface $paymentRepository;
    /**
     * @var EarningRepositoryInterface
     */
    private EarningRepositoryInterface $earningRepository;
    /**
     * @var WalletRepositoryInterface
     */
    private WalletRepositoryInterface $walletRepository;
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;
    /**
     * @var LicenseWalletRepositoryInterface
     */
    private LicenseWalletRepositoryInterface $licenseWalletRepository;
    /**
     * @var BusinessRepositoryInterface
     */
    private BusinessRepositoryInterface $businessRepository;

    /**
     * @param PackageRepositoryInterface $packageRepository
     * @param PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(PackageRepositoryInterface $packageRepository, PaymentRepositoryInterface $paymentRepository, EarningRepositoryInterface $earningRepository, WalletRepositoryInterface $walletRepository, UserRepositoryInterface $userRepository, LicenseWalletRepositoryInterface $licenseWalletRepository, BusinessRepositoryInterface $businessRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->paymentRepository = $paymentRepository;
        $this->earningRepository = $earningRepository;
        $this->walletRepository = $walletRepository;
        $this->userRepository = $userRepository;
        $this->licenseWalletRepository = $licenseWalletRepository;
        $this->businessRepository = $businessRepository;
    }

    /**
     * The showPage() will be used to display the dashboard
     * 
     * @param Request $request
     */
    public function showPage(Request $request)
    {
        $routeName = $request->route()->action['as'];

        $userId = \Auth::user()->id;
        $packages = $this->packageRepository->getSimulatedData();
        $totalRefers = $this->userRepository->getReferrals($userId);
        $totalEarningsCount = $this->earningRepository->getUserEarnings($userId)->count();
        $totalEarnings = $this->earningRepository->getUserEarnings($userId)->where('type','credit')->sum('amount');
        $earningsBalance = $this->earningRepository->getUserEarningsBalance($userId);
        $networkTotal = $this->userRepository->getUser($userId)->downlineTotal;
        $totalLicense = $this->licenseWalletRepository->balance();
        $promonieBalance = $this->walletRepository->getUserWalletsBalanceByKind($userId, 'promonie-wallet');
        $totalBusinesses = $this->businessRepository->myBusinessTotal();

        return view('dashboard.index', compact('routeName','packages','totalEarnings','totalEarningsCount','earningsBalance','totalRefers','networkTotal','totalLicense','promonieBalance','totalBusinesses'));
    }
}
