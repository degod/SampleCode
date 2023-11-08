<?php

namespace App\Http\Controllers\Networks;

use App\Http\Controllers\Controller;
use App\Repository\Packages\PackageRepositoryInterface;
use App\Repository\Payment\PaymentRepositoryInterface;
use App\Repository\PartnerCode\PartnerCodeRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
// use App\View\Components\AlertTypes;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DataTables;

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
     * @var PartnerCodeRepositoryInterface
     */
    private PartnerCodeRepositoryInterface $partnerCodeRepository;
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
     * The showPage() will be used to display the dashboard
     * 
     * @param int $pid
     * @param Request $request
     */
    public function showPage(Request $request, $pid=null)
    {
        $routeName = $request->route()->action['as'];

        $user = \Auth::user();
        $userId = $user->id;
        $packages = $this->packageRepository->getSimulatedData();
        $totalRefers = $this->userRepository->getReferrals($userId);
        $networks = null;
        $userFullName = null;
        if(empty($pid) || $pid=='null'){
            $userFullName = $user->first_name.' '.$user->last_name;
        }else{
            $user = $this->userRepository->getUser($pid);
            $userFullName = $user->first_name.' '.$user->last_name;
        }


        if ($request->ajax()) {
            if(empty($pid) || $pid=='null'){
                $networks = $this->userRepository->getUserNetworks($userId);
            }else{
                $networks = $this->userRepository->getUserNetworks($pid);
            }

            return Datatables::of($networks)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $downs = $this->userRepository->getUser($row->id)->downlineTotal;
                $btn = '<button class="more" ><img src="'.asset('assets/img/icons/item.svg').'" alt="">
                    <div class="table-sub-menu-wrap">
                        <div class="topbar-sub-menu">
                            <a href="'.route('partner.networks', ['pid'=>$row->id]).'" class="sub-menu-link">
                                View Downlines ('.$downs.')
                            </a>
                            <a href="'.route('partner.networks', ['pid'=>$row->id]).'" class="sub-menu-link">
                                <span>View Transactions</span>
                            </a>
                        </div>
                    </div>
                </button>';
                return $btn;
            })
            ->editColumn('package', function ($row) use($packages) {
                if(!empty($row->package))
                    return $packages->{$row->package}->name;
                return "Not Subscribed";
            })->editColumn('created_at', function ($row) {
                return date("F jS, Y", strtotime($row->created_at));
            })->escapeColumns(['action'])->make(true);
        }

        return view('networks.index', compact('routeName','packages','networks','totalRefers','pid','userFullName'));
    }

    /**
     * The generatedCodes() will be used to display the dashboard
     * 
     * @param Request $request
     */
    public function generatedCodes(Request $request)
    {
        $routeName = $request->route()->action['as'];

        $userId = \Auth::user()->id;
        $packages = $this->packageRepository->getSimulatedData();
        $totalRefers = $this->userRepository->getReferrals($userId);
        $codes = null;

        if ($request->ajax()) {
            $codes = $this->partnerCodeRepository->all($request->get('search'), [
                    ['user_id','='],
                    ['code','='],
                    ['email','like'],
                    ['amount','='],
                ]
            );

            return Datatables::of($codes)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="'.route('partner.code.edit',['id'=>$row->id]).'" class="primary-button">
                            <span>EDIT</span>
                        </a>';
                return $btn;
            })
            ->editColumn('code', function ($row) use($packages) {
                $txt = '<code><b>'.$row->code.'</b></code><br>';
                if($row->settled == 'no')
                    $txt .= '<small>Will Settle Referrers</small>';
                else
                    $txt .= '<small>No Commissions for this</small>';

                return $txt;
            })
            ->editColumn('package', function ($row) use($packages) {
                return $packages->{$row->package}->name;
            })
            ->editColumn('amount', function ($row) use($packages) {
                return '₦'.number_format($packages->{$row->package}->amount);
            })
            ->editColumn('created_at', function ($row) {
                return date("F jS, Y", strtotime($row->created_at));
            })
            ->editColumn('updated_at', function ($row) {
                return ($row->status=='PENDING') ? 'Unused': date("F jS, Y", strtotime($row->updated_at));
            })->escapeColumns(['action'])->make(true);
        }

        return view('networks.partner-codes', compact('routeName','packages','totalRefers','codes'));
    }

    /**
     * The generatedCodes() will be used to display the dashboard
     * 
     * @param Request $request
     * @param int $id
     */
    public function editCode(Request $request, int $id)
    {
        $routeName = $request->route()->action['as'];

        $userId = \Auth::user()->id;
        $packages = $this->packageRepository->getSimulatedData();
        $totalRefers = $this->userRepository->getReferrals($userId);
        $code = $this->partnerCodeRepository->getPartnerCodeById($id);

        return view('networks.partner-code-edit', compact('routeName','packages','totalRefers','code'));
    }
}
