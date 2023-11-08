<?php
namespace App\Utilities;

use Illuminate\Support\Facades\Http;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class PackageEarningUtility
{
    private const _1ST_GENERATION = 30;
    private const _2ND_GENERATION = 5;
    private const _3RD_GENERATION = 3;
    private const _4TH_GENERATION = 2;
    public const WITHDRAWAL_CHARGE = 50;

    private array $generations;

    public function __construct(){
        $this->generations = [
            '1'=>self::_1ST_GENERATION,
            '2'=>self::_2ND_GENERATION,
            '3'=>self::_3RD_GENERATION,
            '4'=>self::_4TH_GENERATION
        ];
    }

    public function calculateEarning(float $amount, int $generation)
    {
        $calc = ($amount * ($this->generations[$generation] / 100));

        return $calc;
    }
}