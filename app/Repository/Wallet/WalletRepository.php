<?php

namespace App\Repository\Wallet;

use App\Repository\Wallet\WalletRepositoryInterface;
use App\Models\Wallet;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class WalletRepository implements WalletRepositoryInterface 
{
    /**
     * @var Wallet
     */
    private Wallet $wallet;


    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * The all() will display a list of filtered payment given the
     * below parameters for filtering and DB selection
     * 
     * @param $filter
     * @param array $filterBy
     * @param array $select
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->wallet->where('user_id',\Auth::user()->id)->get();
    }

    /**
     * The allData() will display a list of filtered payment given the
     * below parameters for filtering and DB selection
     * 
     * @param $filter
     * @param array $filterBy
     * @param array $select
     *
     * @return Collection
     */
    public function allData(): Collection
    {
        return $this->wallet->where('user_id', \Auth::user()->id)->get();
    }

    /**
     * The allData() will display a list of filtered payment given the
     * below parameters for filtering and DB selection
     * 
     * @param $filter
     * @param array $filterBy
     * @param array $select
     *
     * @return Collection
     */
    public function allByKind(String $kind): Collection
    {
        return $this->wallet->where(['kind'=>$kind,'user_id'=>\Auth::user()->id])->get();
    }

    /**
     * The getWalletById() will get a single Wallet object given
     * the walletId as passed in the below param
     * 
     * @param int $walletId
     *
     * @return Wallet|null
     */
    public function getWalletById(int $walletId): ?Wallet
    {
        $wallet = $this->wallet->find($walletId);

        return $wallet;
    }

    /**
     * The getWalletByTransId() will get a single Wallet object given
     * a transactionId as passed in the below param
     * 
     * @param String $transactionId
     *
     * @return Wallet|null
     */
    public function getWalletByTransId(String $transactionId): ?Wallet
    {
        $wallet = $this->wallet->where('transactionId',$transactionId)->first();

        return $wallet;
    }

    /**
     * The getUserWallets() will get user Wallets object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Wallet|null
     */
    public function getUserWallets(String $user_id): ?Builder
    {
        $wallet = $this->wallet->where('user_id',$user_id);

        return $wallet;
    }

    /**
     * The getUserWalletsBalance() will get user Wallets balance given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return float
     */
    public function getUserWalletsBalance(String $user_id): ?float
    {
        $cr = $this->wallet->where(['user_id'=>$user_id, 'type'=>'credit'])->sum('amount');
        $dr = $this->wallet->where(['user_id'=>$user_id, 'type'=>'debit'])->sum('amount');
        $wallet = $cr - $dr;

        return $wallet;
    }

    /**
     * The getUserWalletsBalanceByKind() will get user Wallets balance given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     * @param String $kind
     *
     * @return float
     */
    public function getUserWalletsBalanceByKind(String $user_id, String $kind): ?float
    {
        $cr = $this->wallet->where(['kind'=>$kind, 'user_id'=>$user_id, 'type'=>'credit'])->sum('amount');
        $dr = $this->wallet->where(['kind'=>$kind, 'user_id'=>$user_id, 'type'=>'debit'])->sum('amount');
        $wallet = $cr - $dr;

        return $wallet;
    }


    /**
     * The verifyPayment() will be used to verify Payment
     * from the admins table using both the "DF5F" and "TERM_PROFILE_ID"
     * as passed in the below params
     * 
     * @param String $transactionId
     * @param float $amount
     *
     * @return array|null
     */
    public function verifyPayment(String $transactionId): ?array
    {
        $monnify = $this->monnifyUtil->verifyMonnifyTraditionally($transactionId);

        return $monnify;
    }

    /**
     * The createWallet() will create a Wallet record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Wallet
     */
    public function createWallet(array $data): Wallet
    {
        $wallet = $this->wallet->create($data);

        return $wallet;
    }

    /**
     * The updateWallet() will update a given Wallet record
     * given the WalletId and data array passed in the below param
     * 
     * @param int $walletId
     * @param array $data
     *
     * @return Wallet
     */
    public function updateWallet(int $walletId, array $data): Wallet
    {
        $wallet = $this->wallet->findOrFail($walletId);
        $wallet->update($data);

        return $wallet;
    }
}