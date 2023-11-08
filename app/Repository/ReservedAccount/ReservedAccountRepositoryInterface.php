<?php

namespace App\Repository\ReservedAccount;

use App\Models\ReservedAccount;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface ReservedAccountRepositoryInterface
{

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
    public function all(): Collection;

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
    public function allData(): Collection;

    /**
     * The getWalletById() will get a single Wallet object given
     * the walletId as passed in the below param
     * 
     * @param int $walletId
     *
     * @return Wallet|null
     */
    public function getWalletById(int $walletId): ?Wallet;

    /**
     * The getWalletByTransId() will get a single Wallet object given
     * a transactionId as passed in the below param
     * 
     * @param String $transactionId
     *
     * @return Wallet|null
     */
    public function getWalletByTransId(String $transactionId): ?Wallet;

    /**
     * The getUserWallets() will get user Wallets object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Wallet|null
     */
    public function getUserWallets(String $user_id): ?Builder;

    /**
     * The getUserWalletsBalance() will get user Wallets balance given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return float
     */
    public function getUserWalletsBalance(String $user_id): ?float;


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
    public function verifyPayment(String $transactionId): ?array;

    /**
     * The createWallet() will create a Wallet record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Wallet
     */
    public function createWallet(array $data): Wallet;

    /**
     * The updateWallet() will update a given Wallet record
     * given the walletId and data array passed in the below param
     * 
     * @param int $walletId
     * @param array $data
     *
     * @return Wallet
     */
    public function updateWallet(int $walletId, array $data): Wallet;

}