<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Packages\PackageRepository;
use App\Repository\Packages\PackageRepositoryInterface;
use App\Repository\User\UserRepository;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\Payment\PaymentRepository;
use App\Repository\Payment\PaymentRepositoryInterface;
use App\Repository\Earnings\EarningRepository;
use App\Repository\Earnings\EarningRepositoryInterface;
use App\Repository\PartnerCode\PartnerCodeRepository;
use App\Repository\PartnerCode\PartnerCodeRepositoryInterface;
use App\Repository\Withdrawals\WithdrawalRepository;
use App\Repository\Withdrawals\WithdrawalRepositoryInterface;
use App\Repository\LicenseWallet\LicenseWalletRepository;
use App\Repository\LicenseWallet\LicenseWalletRepositoryInterface;
use App\Repository\Bank\BankRepository;
use App\Repository\Bank\BankRepositoryInterface;
use App\Repository\Wallet\WalletRepository;
use App\Repository\Wallet\WalletRepositoryInterface;
use App\Repository\Business\BusinessRepository;
use App\Repository\Business\BusinessRepositoryInterface;
use App\Repository\Coupon\CouponRepository;
use App\Repository\Coupon\CouponRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(EarningRepositoryInterface::class, EarningRepository::class);
        $this->app->bind(PartnerCodeRepositoryInterface::class, PartnerCodeRepository::class);
        $this->app->bind(WithdrawalRepositoryInterface::class, WithdrawalRepository::class);
        $this->app->bind(BankRepositoryInterface::class, BankRepository::class);
        $this->app->bind(LicenseWalletRepositoryInterface::class, LicenseWalletRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(BusinessRepositoryInterface::class, BusinessRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
