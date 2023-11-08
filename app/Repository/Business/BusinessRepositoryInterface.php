<?php

namespace App\Repository\Business;

use App\Models\Business;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface BusinessRepositoryInterface
{
    /**
     * The all() will display a list of filtered packages given the
     * below parameters for filtering and DB selection
     *
     * @return Collection|null
     */
    public function all(): ?Collection;

    /**
     * The allPackage() will display a list of filtered packages given the
     * below parameters for filtering and DB selection
     * 
     * @param int $packageId
     *
     * @return Collection|null
     */
    public function allPackage(int $packageId): ?Collection;

    /**
     * The createBusiness() will create a single Business in the license_wallet
     * table given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Business|null
     */
    public function createBusiness(array $data): Business;

    /**
     * The createBusiness() will create a single Business in the license_wallet
     * table given the data array passed in the below param
     * 
     *
     * @return int
     */
    public function myBusinessTotal(): int;


    /**
     * The signupBusiness() will be used to register a business on Prokip
     *
     * @param  array $data
     * @return void
     */
    public function signupBusiness(array $data);


    /**
     * The verifyUsername() will be used to check for business username on Prokip
     *
     * @param  string $username
     * @return object
     */
    public function verifyUsername($username);


    /**
     * The pullBusiness() will be used to pull businesses from Prokip
     *
     * @param  string $userId
     * @return object
     */
    public function pullBusiness($userId);


    /**
     * The pullInactive() will be used to check for business username on Prokip
     *
     * @param  string $userId
     * @return object
     */
    public function pullInactive($userId);


    /**
     * The pullTopBusiness() will be used to check for business username on Prokip
     *
     * @param  string $userId
     * @return object
     */
    public function pullTopBusiness($userId);


    /**
     * The pullRenewalBusiness() will be used to check for business username on Prokip
     *
     * @param  string $userId
     * @return object
     */
    public function pullRenewalBusiness($userId);


    /**
     * The pullSingleBusiness() will be used to pull business details from Prokip
     *
     * @param  int $business_id
     * @param  int $partner_id
     * @return object
     */
    public function pullSingleBusiness($business_id, $partner_id);
}