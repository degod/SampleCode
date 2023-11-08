<?php

namespace App\Repository\Coupon;

use App\Repository\Coupon\CouponRepositoryInterface;
use App\Models\Coupon;
use Illuminate\Support\Collection;
use App\Utilities\MonnifyUtility;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class CouponRepository implements CouponRepositoryInterface 
{
    /**
     * @var Coupon
     */
    private Coupon $coupon;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
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
        return $this->coupon
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
     * The getCouponById() will get a single Coupon object given
     * the couponId as passed in the below param
     * 
     * @param int $couponId
     *
     * @return Coupon|null
     */
    public function getCouponById(int $couponId): ?Coupon
    {
        $coupon = $this->coupon->find($couponId);

        return $coupon;
    }

    /**
     * The getCouponByCode() will get a single Coupon object given
     * the coupon as passed in the below param
     * 
     * @param String $coupon
     *
     * @return Coupon|null
     */
    public function getCouponByCode(String $coupon): ?Coupon
    {
        $coupon = $this->coupon->whereCode($coupon)->first();

        return $coupon;
    }

    /**
     * The getUserCoupon() will get a single Coupon object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return Coupon|null
     */
    public function getUserCoupon(String $user_id): ?Coupon
    {
        $coupon = $this->coupon->where('user_id',$user_id)->first();

        return $coupon;
    }

    /**
     * The createCoupon() will create a Coupon record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Coupon
     */
    public function createCoupon(array $data): Coupon
    {
        $coupon = $this->coupon->create($data);

        return $coupon;
    }

    /**
     * The updateCoupon() will update a given Coupon record
     * given the couponId and data array passed in the below param
     * 
     * @param int $couponId
     * @param array $data
     *
     * @return Coupon
     */
    public function updateCoupon(int $couponId, array $data): Coupon
    {
        $coupon = $this->coupon->findOrFail($couponId);
        $coupon->update($data);

        return $coupon;
    }
}