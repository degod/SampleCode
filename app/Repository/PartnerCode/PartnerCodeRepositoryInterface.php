<?php

namespace App\Repository\PartnerCode;

use App\Models\PartnerCode;
use Illuminate\Support\Collection;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
interface PartnerCodeRepositoryInterface 
{

    /**
     * The all() will display a list of filtered payment given the
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
     * The getPartnerCodeById() will get a single Payment object given
     * the partnerCodeId as passed in the below param
     * 
     * @param int partnerCodeId
     *
     * @return PartnerCode|null
     */
    public function getPartnerCodeById(int $partnerCodeId): ?PartnerCode;

    /**
     * The getPartnerCodeByTransId() will get a single PartnerCode object given
     * a code as passed in the below param
     * 
     * @param String $code
     *
     * @return PartnerCode|null
     */
    public function getPartnerCodeByTransId(String $code): ?PartnerCode;

    /**
     * The getUserPartnerCodes() will get a single PartnerCode object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return PartnerCode|null
     */
    public function getUserPartnerCodes(String $user_id): ?PartnerCode;


    /**
     * The getCode() will be used to generate a unique PartnerCode
     * 
     *
     * @return String|null
     */
    public function getCode(): ?String;

    /**
     * The createPartnerCode() will create a PartnerCode record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return PartnerCode
     */
    public function createPartnerCode(array $data): PartnerCode;

    /**
     * The updatePartnerCode() will update a given PartnerCode record
     * given the partnerCodeId and data array passed in the below param
     * 
     * @param int $partnerCode
     * @param array $data
     *
     * @return Payment
     */
    public function updatePartnerCode(int $paymentId, array $data): PartnerCode;

    /**
     * The verifyCode() will update a given PartnerCode record
     * given the partnerCodeId and data array passed in the below param
     * 
     * @param String $code
     * @param String $email
     *
     * @return PartnerCode
     */
    public function verifyCode(String $code, String $email): ?PartnerCode;
}