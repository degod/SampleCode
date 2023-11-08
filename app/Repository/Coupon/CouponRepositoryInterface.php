<?php

namespace App\Repository\Coupon;

use App\Models\Coupon;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface CouponRepositoryInterface
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
     * The getCouponById() will get a single Coupon object given
     * the couponId as passed in the below param
     * 
     * @param int $couponId
     *
     * @return Coupon|null
     */
    public function getCouponById(int $couponId): ?Coupon;

    /**
     * The getCouponByCode() will get a single Coupon object given
     * the coupon as passed in the below param
     * 
     * @param String $coupon
     *
     * @return Coupon|null
     */
    public function getCouponByCode(String $coupon): ?Coupon;

    /**
     * The getUserCoupon() will get a single Coupon object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Coupon|null
     */
    public function getUserCoupon(String $user_id): ?Coupon;

    /**
     * The createCoupon() will create a Coupon record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Coupon
     */
    public function createCoupon(array $data): Coupon;

    /**
     * The updateCoupon() will update a given Coupon record
     * given the couponId and data array passed in the below param
     * 
     * @param int $couponId
     * @param array $data
     *
     * @return Coupon
     */
    public function updateCoupon(int $couponId, array $data): Coupon;
}