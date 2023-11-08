<?php

namespace App\Http\Controllers\PartnerPackages;

use App\Http\Controllers\Controller;
use App\Repository\Packages\PackageRepositoryInterface;
// use App\View\Components\AlertTypes;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class ListController extends Controller
{
    /**
     * @var PackageRepositoryInterface
     */
    // private PackageRepositoryInterface $packageRepository;

    /**
     * @param PackageRepositoryInterface $packageRepository
     */
    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    /**
     * The showPage() will be used to display the package selection
     * 
     * @param Request $request
     */
    public function showPage(Request $request)
    {
        $packages = $this->packageRepository->getSimulatedData();

        return view('layouts.home', compact('packages'));
    }
}
