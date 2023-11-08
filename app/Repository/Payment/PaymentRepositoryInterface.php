<?php

namespace App\Repository\Payment;

use App\Models\Payment;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface PaymentRepositoryInterface
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
     * The getPaymentById() will get a single Payment object given
     * the paymentId as passed in the below param
     * 
     * @param int $paymentId
     *
     * @return Payment|null
     */
    public function getPaymentById(int $paymentId): ?Payment;

    /**
     * The getPaymentByTransId() will get a single Payment object given
     * a transactionId as passed in the below param
     * 
     * @param String $transactionId
     *
     * @return Payment|null
     */
    public function getPaymentByTransId(String $transactionId): ?Payment;

    /**
     * The getUserPayments() will get a single Payment object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Payment|null
     */
    public function getUserPayments(String $user_id): ?Payment;


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
     * The createPayment() will create a Payment record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Payment
     */
    public function createPayment(array $data): Payment;

    /**
     * The updatePayment() will update a given Payment record
     * given the paymentId and data array passed in the below param
     * 
     * @param int $paymentId
     * @param array $data
     *
     * @return Payment
     */
    public function updatePayment(int $paymentId, array $data): Payment;
}