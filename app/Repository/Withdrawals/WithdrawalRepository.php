<?php

namespace App\Repository\Withdrawals;

use App\Repository\Withdrawals\WithdrawalRepositoryInterface;
use App\Models\Withdrawal;
use Illuminate\Support\Collection;
use App\Utilities\MonnifyUtility;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class WithdrawalRepository implements WithdrawalRepositoryInterface 
{
    /**
     * @var Withdrawal
     */
    private Withdrawal $withdraw;
    /**
     * @var MonnifyUtility
     */
    private MonnifyUtility $monnifyUtil;

    public function __construct(Withdrawal $withdraw, MonnifyUtility $monnifyUtil)
    {
        $this->withdraw = $withdraw;
        $this->monnifyUtil = $monnifyUtil;
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
        return $this->withdraw
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
     * The getWithdrawalById() will get a single Withdrawal object given
     * the withdrawId as passed in the below param
     * 
     * @param int $withdrawId
     *
     * @return Withdrawal|null
     */
    public function getWithdrawalById(int $withdrawId): ?Withdrawal
    {
        $withdraw = $this->withdraw->find($withdrawId);

        return $withdraw;
    }

    /**
     * The getWithdrawalByTransId() will get a single Withdrawal object given
     * a transactionId as passed in the below param
     * 
     * @param String $transactionId
     *
     * @return Withdrawal|null
     */
    public function getWithdrawalByTransId(String $transactionId): ?Withdrawal
    {
        $withdraw = $this->withdraw->where('transactionId',$transactionId)->first();

        return $withdraw;
    }

    /**
     * The getUserWithdrawals() will get a single Withdrawal object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Withdrawal|null
     */
    public function getUserWithdrawals(String $user_id): ?Withdrawal
    {
        $withdraw = $this->withdraw->where('user_id',$user_id);

        return $withdraw;
    }

    /**
     * The createWithdrawal() will create a Withdrawal record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Withdrawal
     */
    public function createWithdrawal(array $data): Withdrawal
    {
        $withdraw = $this->withdraw->create($data);

        return $withdraw;
    }

    /**
     * The updateWithrawal() will update a given Withrawal record
     * given the withdrawId and data array passed in the below param
     * 
     * @param int $withdrawId
     * @param array $data
     *
     * @return Withdrawal
     */
    public function updateWithdrawal(int $withdrawId, array $data): Withdrawal
    {
        $withdraw = $this->withdraw->findOrFail($withdrawId);
        $withdraw->update($data);

        return $withdraw;
    }
}