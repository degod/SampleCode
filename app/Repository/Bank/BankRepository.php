<?php

namespace App\Repository\Bank;

use App\Repository\Bank\BankRepositoryInterface;
use App\Models\BankDetails;
use Illuminate\Support\Collection;
use App\Utilities\MonnifyUtility;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class BankRepository implements BankRepositoryInterface 
{
    /**
     * @var BankDetails
     */
    private BankDetails $bank;

    public function __construct(BankDetails $bank)
    {
        $this->bank = $bank;
    }

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
    public function all($filter, array $filterBy, array $select = ['*']): object
    {
        return $this->bank
            ->when(!is_null($filter), function ($query) use ($filter, $filterBy) {
                return $query->where(function ($query) use ($filter, $filterBy) {
                    foreach ($filterBy as $item) {
                        if (isset($item[1]) && $item[1] == 'like') {
                            if (isset($item[0])) {
                                $query->orWhere($item[0], 'like', '%' . $filter . '%');
                            }
                        } else {
                            if (isset($item[0])) {
                                $query->orWhere($item[0], $filter);
                            }
                        }
                    }
                });
            })
            ->select($select)
            ->paginate(config('pagination.per_page'));
    }

    /**
     * The getBankById() will get a single BankDetails object given
     * the bankId as passed in the below param
     * 
     * @param int $bankId
     *
     * @return BankDetails|null
     */
    public function getBankById(int $bankId): ?BankDetails
    {
        $bank = $this->bank->find($bankId);

        return $bank;
    }

    /**
     * The getUserBanks() will get a single BankDetails object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return BankDetails|null
     */
    public function getUserBanks(String $user_id): ?BankDetails
    {
        $bank = $this->bank->where('user_id',$user_id)->first();

        return $bank;
    }

    /**
     * The createBank() will create a BankDetails record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return BankDetails
     */
    public function createBank(array $data): BankDetails
    {
        $bank = $this->bank->create($data);

        return $bank;
    }

    /**
     * The updateBank() will update a given BankDetails record
     * given the bankId and data array passed in the below param
     * 
     * @param int $bankId
     * @param array $data
     *
     * @return BankDetails
     */
    public function updateBank(int $bankId, array $data): BankDetails
    {
        $bank = $this->bank->findOrFail($bankId);
        $bank->update($data);

        return $bank;
    }


    /**
     * The verifyAccount() will be used to verify Account Details
     * 
     * @param String $accountNumber
     * @param String $bankCode
     *
     * @return String|null
     */
    public function verifyAccount(String $accountNumber, String $bankCode): String
    {
        $monnify = $this->monnifyUtil->verifyBankAccount($accountNumber, $bankCode);

        return $monnify;
    }
}