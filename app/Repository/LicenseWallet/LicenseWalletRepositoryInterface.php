<?php

namespace App\Repository\LicenseWallet;

use App\Models\LicenseWallet;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface LicenseWalletRepositoryInterface
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
     * The balance() will display a list of filtered packages given the
     * below parameters for filtering and DB selection
     *
     * @return int|null
     */
    public function balance(): ?int;

    /**
     * The packageBalance() will display a list of filtered packages given the
     * below parameters for filtering and DB selection
     * 
     * @param int $packageId
     *
     * @return int|null
     */
    public function packageBalance(int $packageId): ?int;


    /**
     * The createLicenseWallet() will create a single LicenseWallet in the license_wallet
     * table given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return LicenseWallet|null
     */
    public function createLicenseWallet(array $data): LicenseWallet;


    /**
     * The fetchSubPacks() will fetch an the subscription packages
     * from the Prokip Main Application via API
     * 
     *
     * @return array|null
     */
    public function fetchSubPacks(): array;


    /**
     * The makePacksAssoc() will make the fetched packages
     * from the Prokip Main Application an Associative Array
     * 
     *
     * @return array|null
     */
    public function makePacksAssoc(array $arr): array;
}