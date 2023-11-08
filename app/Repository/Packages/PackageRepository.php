<?php

namespace App\Repository\Packages;

use App\Repository\Packages\PackageRepositoryInterface;
use App\Models\Package;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class PackageRepository implements PackageRepositoryInterface 
{
    /**
     * @var Package
     */
    private Package $package;

    public function __construct(Package $package)
    {
        $this->package = $package;
    }

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
    public function all($filter, array $filterBy, array $select = ['*']): object
    {
        return $this->package
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
     * The createPackage() will create a single Package in the packages table
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return Package|null
     */
    public function createPackage(array $data): Package
    {
        $package = $this->package->create($data);

        return $package;
    }


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
    public function getSimulatedData(): object
    {
        $packs = $this->package->all();
        $packages = [];
        foreach($packs as $pack){
            $packages[$pack->code] = [
                'name'=>strtoupper($pack->name),
                'amount'=>$pack->amount,
                'short_description'=>$pack->short_description,
                'benefits'=>explode(',', $pack->benefits)
            ];
        }

        $ret = json_decode(json_encode($packages));;

        return $ret;
    }


    public function getSimulatedDataFormer(): object
    {
        $packages = [
            'pro'=>[
                'name'=>'PRO',
                'amount'=>50000,
                'short_description'=>'For users who are just starting off',
                'benefits'=>[
                    'Per Lifetime free',
                    '1 FREE Prokip license included',
                    '30% commission per License Sold',
                    'Access to Promonie POS terminals',
                    '0.05% on Promonie Transaction charges',
                    'Access to Support and Mentorship',
                    'Sales Training & Marketing Resources'
                ]
            ],
            'elite'=>[
                'name'=>'ELITE',
                'amount'=>200000,
                'short_description'=>'For users who want more',
                'benefits'=>[
                    'Per Lifetime free',
                    '3 FREE Prokip license included',
                    '40% commission per License Sold',
                    'Access to Promonie POS terminals',
                    '1 FREE Promonie Terminal include',
                    '0.05% on Promonie Transaction charges',
                    'Access to Support and Mentorship',
                    'Sales Training & Marketing Resources'
                ]
            ],
            'investor'=>[
                'name'=>'INVESTOR',
                'amount'=>500000,
                'short_description'=>'For INVESTORS only',
                'benefits'=>[
                    'Per Lifetime free',
                    '10 FREE Prokip license included',
                    '40% commission per License Sold',
                    'Access to Promonie POS terminals',
                    '5 FREE Promonie Terminal include',
                    '0.05% on Promonie Transaction charges',
                    'Access to Support and Mentorship',
                    'Sales Training & Marketing Resources'
                ]
            ]
        ];

        $ret = json_decode(json_encode($packages));;

        return $ret;
    }
}