<?php

namespace App\Repository\Earnings;

use App\Repository\Earnings\EarningRepositoryInterface;
use App\Models\Earning;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class EarningRepository implements EarningRepositoryInterface 
{
    /**
     * @var Earning
     */
    private Earning $earning;


    public function __construct(Earning $earning)
    {
        $this->earning = $earning;
    }

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
    public function all($filter, array $filterBy, array $select = ['*']): object
    {
        return $this->earning->where(['kind'=>'earnings','user_id'=>\Auth::user()->id])->get();
    }

    /**
     * The allData() will display a list of filtered payment given the
     * below parameters for filtering and DB selection
     * 
     * @param $filter
     * @param array $filterBy
     * @param array $select
     *
     * @return object
     */
    public function allData($filter, array $filterBy, array $select = ['*']): object
    {
        return $this->earning->where(['kind'=>'earnings','user_id'=>\Auth::user()->id])
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
            ->select($select)->get();
    }

    /**
     * The getEarningById() will get a single Earning object given
     * the earningId as passed in the below param
     * 
     * @param int $earningId
     *
     * @return Earning|null
     */
    public function getEarningById(int $earningId): ?Earning
    {
        $earning = $this->earning->find($earningId);

        return $earning;
    }

    /**
     * The getEarningByTransId() will get a single Earning object given
     * a transactionId as passed in the below param
     * 
     * @param String $transactionId
     *
     * @return Earning|null
     */
    public function getEarningByTransId(String $transactionId): ?Earning
    {
        $earning = $this->earning->where('transactionId',$transactionId)->first();

        return $earning;
    }

    /**
     * The getUserEarnings() will get user Earnings object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Earning|null
     */
    public function getUserEarnings(String $user_id): ?Builder
    {
        $earning = $this->earning->where(['kind'=>'earnings','user_id'=>$user_id]);

        return $earning;
    }

    /**
     * The getUserEarningsBalance() will get user Earnings balance given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return float
     */
    public function getUserEarningsBalance(String $user_id): ?float
    {
        $cr = $this->earning->where(['kind'=>'earnings','user_id'=>$user_id,'type'=>'credit'])->sum('amount');
        $dr = $this->earning->where(['kind'=>'earnings','user_id'=>$user_id,'type'=>'debit'])->sum('amount');
        $earning = $cr - $dr;

        return $earning;
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
     * The createEarning() will create a Earning record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Earning
     */
    public function createEarning(array $data): Earning
    {
        $earning = $this->earning->create($data);

        return $earning;
    }

    /**
     * The updateEarning() will update a given Earning record
     * given the earningId and data array passed in the below param
     * 
     * @param int $earningId
     * @param array $data
     *
     * @return Earning
     */
    public function updateEarning(int $earningId, array $data): Earning
    {
        $earning = $this->earning->findOrFail($earningId);
        $earning->update($data);

        return $earning;
    }
}