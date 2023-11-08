<?php

namespace App\Repository\Withdrawals;

use App\Models\Withdrawal;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface WithdrawalRepositoryInterface
{
    /**
     * The all() will display a list of filtered withdrawals given the
     * below parameters for filtering and DB selection
     * 
     * @param $filter
     * @param array $filterBy
     * @param array $select
     *
     * @return object
     */
    public function all($filter, array $filterBy, array $select = ['*']): object;

    /**
     * The getWithdrawalById() will get a single Withdrawal object given
     * the withdrawId as passed in the below param
     * 
     * @param int $withdrawId
     *
     * @return Withdrawal|null
     */
    public function getWithdrawalById(int $withdrawId): ?Withdrawal;

    /**
     * The getWithdrawalByTransId() will get a single Withdrawal object given
     * a transactionId as passed in the below param
     * 
     * @param String $transactionId
     *
     * @return Withdrawal|null
     */
    public function getWithdrawalByTransId(String $transactionId): ?Withdrawal;

    /**
     * The getUserWithdrawals() will get a single Withdrawal object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Withdrawal|null
     */
    public function getUserWithdrawals(String $user_id): ?Withdrawal;

    /**
     * The createWithdrawal() will create a Withdrawal record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Withdrawal
     */
    public function createWithdrawal(array $data): Withdrawal;

    /**
     * The updateWithrawal() will update a given Withrawal record
     * given the withdrawId and data array passed in the below param
     * 
     * @param int $withdrawId
     * @param array $data
     *
     * @return Withdrawal
     */
    public function updateWithdrawal(int $withdrawId, array $data): Withdrawal;
}