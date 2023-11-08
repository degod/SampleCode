<?php

namespace App\Repository\Packages;

use App\Models\Package;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface PackageRepositoryInterface
{
    /**
     * The all() will display a list of filtered packages given the
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
     * The createPackage() will create a single Package in the packages table
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Package|null
     */
    public function createPackage(array $data): Package;


    /**
     * The getConfigByProfileId() will get a single collection of Admin class
     * from the admins table using both the "DF5F" and "TERM_PROFILE_ID"
     * as passed in the below params
     * 
     * @param String $brandId
     * @param int $profileId
     *
     * @return Admin|null
     */
    public function getSimulatedData(): object;


    public function getSimulatedDataFormer(): object;
}