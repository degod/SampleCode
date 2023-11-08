<?php
namespace App\Utilities;

use Illuminate\Support\Facades\Http;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class LicenseEarningUtility
{
    private const _2ND_GENERATION = 5;
    private const _3RD_GENERATION = 3;
    private const _4TH_GENERATION = 2;

    private array $generations;

    public function __construct(){
        $this->generations = [
            '2'=>self::_2ND_GENERATION,
            '3'=>self::_3RD_GENERATION,
            '4'=>self::_4TH_GENERATION
        ];
    }

    public function calculateEarning(float $amount, int $generation, float $percent=null)
    {
        $percent = ($percent==null) ? $this->generations[$generation]: $percent;
        $calc = ($amount * ($percent / 100));

        return $calc;
    }
}