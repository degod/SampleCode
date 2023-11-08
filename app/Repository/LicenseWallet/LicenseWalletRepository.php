<?php

namespace App\Repository\LicenseWallet;

use App\Repository\LicenseWallet\LicenseWalletRepositoryInterface;
use App\Models\LicenseWallet;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class LicenseWalletRepository implements LicenseWalletRepositoryInterface 
{
    /**
     * @var LicenseWallet
     */
    private LicenseWallet $licenseWallet;

    public function __construct(LicenseWallet $licenseWallet)
    {
        $this->licenseWallet = $licenseWallet;

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
        return $this->licenseWallet->where('user_id',\Auth::user()->id)->get();
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
        return $this->licenseWallet->where(['user_id'=>\Auth::user()->id, 'business_package_id'=>$packageId])->get();
    }

    /**
     * The balance() will display a list of filtered packages given the
     * below parameters for filtering and DB selection
     *
     * @return int|null
     */
    public function balance(): ?int
    {
        $cr = $this->licenseWallet->where(['user_id'=>\Auth::user()->id, 'type'=>'credit'])->sum('quantity');
        $dr = $this->licenseWallet->where(['user_id'=>\Auth::user()->id, 'type'=>'debit'])->sum('quantity');
        
        return ($cr - $dr);
    }

    /**
     * The packageBalance() will display a list of filtered packages given the
     * below parameters for filtering and DB selection
     * 
     * @param int $packageId
     *
     * @return int|null
     */
    public function packageBalance(int $packageId): ?int
    {
        $cr = $this->licenseWallet->where(['user_id'=>\Auth::user()->id, 'type'=>'credit', 'business_package_id'=>$packageId])->sum('quantity');
        $dr = $this->licenseWallet->where(['user_id'=>\Auth::user()->id, 'type'=>'debit', 'business_package_id'=>$packageId])->sum('quantity');
        
        return ($cr - $dr);
    }


    /**
     * The createLicenseWallet() will create a single LicenseWallet in the license_wallet
     * table given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return LicenseWallet|null
     */
    public function createLicenseWallet(array $data): LicenseWallet
    {
        $package = $this->licenseWallet->create($data);

        return $package;
    }


    /**
     * The fetchSubPacks() will fetch an the subscription packages
     * from the Prokip Main Application via API
     * 
     *
     * @return array|null
     */
    public function fetchSubPacks(): array
    {
        $subPacksCall = file_get_contents($this->prokip_url.'/partners-packages');
        $subPacks = json_decode($subPacksCall, true);
        $subPacks = ($subPacks) ? $subPacks['data']: [];
        $allowedPacks = [1, 8, 2, 3];
        $newPacks = [];
        foreach($allowedPacks as $id){
            foreach($subPacks as $pack){
                if($pack['id'] == $id){
                    $newPacks[] = $pack;
                    continue;
                }
            }
        }

        return $newPacks;
    }


    /**
     * The makePacksAssoc() will make the fetched packages
     * from the Prokip Main Application an Associative Array
     * 
     *
     * @return array|null
     */
    public function makePacksAssoc(array $arr): array
    {
        $newPacks = [];
        foreach($arr as $k){
            $newPacks[$k['id']] = $k;
        }

        return $newPacks;
    }
}