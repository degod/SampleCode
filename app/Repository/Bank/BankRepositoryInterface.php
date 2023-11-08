<?php

namespace App\Repository\Bank;

use App\Models\BankDetails;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface BankRepositoryInterface
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
     * The getBankById() will get a single BankDetails object given
     * the bankId as passed in the below param
     * 
     * @param int $bankId
     *
     * @return BankDetails|null
     */
    public function getBankById(int $bankId): ?BankDetails;

    /**
     * The getUserBanks() will get a single BankDetails object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return BankDetails|null
     */
    public function getUserBanks(String $user_id): ?BankDetails;

    /**
     * The createBank() will create a BankDetails record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return BankDetails
     */
    public function createBank(array $data): BankDetails;

    /**
     * The updateBank() will update a given BankDetails record
     * given the bankId and data array passed in the below param
     * 
     * @param int $bankId
     * @param array $data
     *
     * @return BankDetails
     */
    public function updateBank(int $bankId, array $data): BankDetails;


    /**
     * The verifyAccount() will be used to verify Account Details
     * 
     * @param String $accountNumber
     * @param String $bankCode
     *
     * @return String|null
     */
    public function verifyAccount(String $accountNumber, String $bankCode): String;
}