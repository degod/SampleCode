<?php

namespace App\Repository\Earnings;

use App\Models\Earning;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface EarningRepositoryInterface
{

    /**
     * The all() will display a list of filtered payment given the
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
     * The all() will display a list of filtered payment given the
     * below parameters for filtering and DB selection
     * 
     * @param $filter
     * @param array $filterBy
     * @param array $select
     *
     * @return object
     */
    public function allData($filter, array $filterBy, array $select = ['*']): object;

    /**
     * The getEarningById() will get a single Earning object given
     * the earningId as passed in the below param
     * 
     * @param int $earningId
     *
     * @return Earning|null
     */
    public function getEarningById(int $earningId): ?Earning;

    /**
     * The getEarningByTransId() will get a single Earning object given
     * a transactionId as passed in the below param
     * 
     * @param String $transactionId
     *
     * @return Earning|null
     */
    public function getEarningByTransId(String $transactionId): ?Earning;

    /**
     * The getUserEarnings() will get user Earnings object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Earning|null
     */
    public function getUserEarnings(String $user_id): ?Builder;

    /**
     * The getUserEarningsBalance() will get user Earnings balance given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return float
     */
    public function getUserEarningsBalance(String $user_id): ?float;


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
     * The createEarning() will create a Earning record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Earning
     */
    public function createEarning(array $data): Earning;

    /**
     * The updateEarning() will update a given Earning record
     * given the earningId and data array passed in the below param
     * 
     * @param int $earningId
     * @param array $data
     *
     * @return Earning
     */
    public function updateEarning(int $earningId, array $data): Earning;

}