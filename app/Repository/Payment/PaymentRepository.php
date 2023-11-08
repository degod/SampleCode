<?php

namespace App\Repository\Payment;

use App\Repository\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Support\Collection;
use App\Utilities\MonnifyUtility;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class PaymentRepository implements PaymentRepositoryInterface 
{
    /**
     * @var Payment
     */
    private Payment $payment;
    /**
     * @var MonnifyUtility
     */
    private MonnifyUtility $monnifyUtil;

    public function __construct(Payment $payment, MonnifyUtility $monnifyUtil)
    {
        $this->payment = $payment;
        $this->monnifyUtil = $monnifyUtil;
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
        return $this->payment
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
     * The getPaymentById() will get a single Payment object given
     * the paymentId as passed in the below param
     * 
     * @param int $paymentId
     *
     * @return Payment|null
     */
    public function getPaymentById(int $paymentId): ?Payment
    {
        $payment = $this->payment->find($paymentId);

        return $payment;
    }

    /**
     * The getPaymentByTransId() will get a single Payment object given
     * a transactionId as passed in the below param
     * 
     * @param String $transactionId
     *
     * @return Payment|null
     */
    public function getPaymentByTransId(String $transactionId): ?Payment
    {
        $payment = $this->payment->where('transactionId',$transactionId)->first();

        return $payment;
    }

    /**
     * The getUserPayments() will get a single Payment object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Payment|null
     */
    public function getUserPayments(String $user_id): ?Payment
    {
        $payment = $this->payment->where('user_id',$user_id);

        return $payment;
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
     * The createPayment() will create a Payment record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Payment
     */
    public function createPayment(array $data): Payment
    {
        $payment = $this->payment->create($data);

        return $payment;
    }

    /**
     * The updatePayment() will update a given Payment record
     * given the paymentId and data array passed in the below param
     * 
     * @param int $paymentId
     * @param array $data
     *
     * @return Payment
     */
    public function updatePayment(int $paymentId, array $data): Payment
    {
        $payment = $this->payment->findOrFail($paymentId);
        $payment->update($data);

        return $payment;
    }
}