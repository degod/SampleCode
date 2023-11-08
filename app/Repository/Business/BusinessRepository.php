<?php

namespace App\Repository\Business;

use App\Repository\Business\BusinessRepositoryInterface;
use App\Models\Business;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class BusinessRepository implements BusinessRepositoryInterface 
{
    /**
     * @var Business
     */
    private Business $business;

    public function __construct(Business $business)
    {
        $this->business = $business;

        $this->prokip_url = env('PROKIP_URL');
    }

    /**
     * The all() will display a list of filtered packages given the
     * below parameters for filtering and DB selection
     *
     * @return Collection|null
     */
    public function all(): ?Collection
    {
        return $this->business->where('user_id',\Auth::user()->id)->get();
    }

    /**
     * The allPackage() will display a list of filtered packages given the
     * below parameters for filtering and DB selection
     * 
     * @param int $packageId
     *
     * @return Collection|null
     */
    public function allPackage(int $packageId): ?Collection
    {
        return $this->business->where(['user_id'=>\Auth::user()->id, 'business_package_id'=>$packageId])->get();
    }

    /**
     * The createBusiness() will create a single Business in the license_wallet
     * table given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Business|null
     */
    public function createBusiness(array $data): Business
    {
        $package = $this->business->create($data);

        return $package;
    }

    /**
     * The myBusinessTotal() will create a single Business in the license_wallet
     * table given the data array passed in the below param
     * 
     *
     * @return int
     */
    public function myBusinessTotal(): int
    {
        $package = $this->business->where(['user_id'=>\Auth::user()->id])->count();

        return $package;
    }


    /**
     * The signupBusiness() will be used to register a business on Prokip
     *
     * @param  array $data
     * @return void
     */
    public function signupBusiness(array $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->prokip_url.'/partners-create-business');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);

        return $result;
    }


    /**
     * The verifyUsername() will be used to check for business username on Prokip
     *
     * @param  string $username
     * @return object
     */
    public function verifyUsername($username)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->prokip_url.'/verify-username');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['username'=>$username]));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);

        return $result;
    }


    /**
     * The pullBusiness() will be used to pull businesses from Prokip
     *
     * @param  string $userId
     * @return object
     */
    public function pullBusiness($userId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->prokip_url.'/partners-business-ids?partner_id='.$userId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);

        return $result;
    }


    /**
     * The pullInactive() will be used to check for business username on Prokip
     *
     * @param  string $userId
     * @return object
     */
    public function pullInactive($userId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->prokip_url.'/partners-inactive-business/'.$userId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);

        return $result;
    }


    /**
     * The pullTopBusiness() will be used to check for business username on Prokip
     *
     * @param  string $userId
     * @return object
     */
    public function pullTopBusiness($userId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->prokip_url.'/partners-topranking-business/'.$userId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);

        return $result;
    }


    /**
     * The pullRenewalBusiness() will be used to check for business username on Prokip
     *
     * @param  string $userId
     * @return object
     */
    public function pullRenewalBusiness($userId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->prokip_url.'/partners-renewal-business/'.$userId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);

        return $result;
    }


    /**
     * The pullSingleBusiness() will be used to pull business details from Prokip
     *
     * @param  int $business_id
     * @param  int $partner_id
     * @return object
     */
    public function pullSingleBusiness($business_id, $partner_id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_URL, $this->prokip_url.'/partners-business-ids?business_id='.$business_id.'&partner_id='.$partner_id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        $requestX = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $result = json_decode($requestX);

        return $result;
    }
}
