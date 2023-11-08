<?php

namespace App\Repository\PartnerCode;

use App\Repository\PartnerCode\PartnerCodeRepositoryInterface;
use App\Models\PartnerCode;
use Illuminate\Support\Collection;
use App\Utilities\MonnifyUtility;

/**
 * @author Godwin Uche <uche.godwin@prowebesolutions.com>
 */
class PartnerCodeRepository implements PartnerCodeRepositoryInterface 
{
    /**
     * @var PartnerCode
     */
    private PartnerCode $partnerCode;
    /**
     * @var MonnifyUtility
     */
    private MonnifyUtility $monnifyUtil;

    public function __construct(PartnerCode $partnerCode, MonnifyUtility $monnifyUtil)
    {
        $this->partnerCode = $partnerCode;
        $this->monnifyUtil = $monnifyUtil;
    }

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
    public function all($filter, array $filterBy, array $select = ['*']): object
    {
        return $this->partnerCode->all();
        // return $this->partnerCode
        //     ->when(!is_null($filter), function ($query) use ($filter, $filterBy) {
        //         return $query->where(function ($query) use ($filter, $filterBy) {
        //             foreach ($filterBy as $item) {
        //                 if (isset($item[1]) && $item[1] == 'like') {
        //                     if (isset($item[0])) {
        //                         $query->orWhere($item[0], 'like', '%' . $filter . '%');
        //                     }
        //                 } else {
        //                     if (isset($item[0])) {
        //                         $query->orWhere($item[0], $filter);
        //                     }
        //                 }
        //             }
        //         });
        //     })
        //     ->select($select)
        //     ->get();
            // ->paginate(config('pagination.per_page'));
    }

    /**
     * The getPartnerCodeById() will get a single Payment object given
     * the partnerCodeId as passed in the below param
     * 
     * @param int partnerCodeId
     *
     * @return PartnerCode|null
     */
    public function getPartnerCodeById(int $partnerCodeId): ?PartnerCode
    {
        $partnerCode = $this->partnerCode->find($partnerCodeId);

        return $partnerCode;
    }

    /**
     * The getPartnerCodeByTransId() will get a single PartnerCode object given
     * a code as passed in the below param
     * 
     * @param String $code
     *
     * @return PartnerCode|null
     */
    public function getPartnerCodeByTransId(String $code): ?PartnerCode
    {
        $partnerCode = $this->partnerCode->where('code',$code)->first();

        return $partnerCode;
    }

    /**
     * The getUserPartnerCodes() will get a single PartnerCode object given
     * a user's ID as passed in the below param
     * 
     * @param int $user_id
     *
     * @return PartnerCode|null
     */
    public function getUserPartnerCodes(String $user_id): ?PartnerCode
    {
        $partnerCode = $this->partnerCode->where('user_id',$user_id);

        return $partnerCode;
    }


    /**
     * The getCode() will be used to generate a unique PartnerCode
     * 
     *
     * @return String|null
     */
    public function getCode(): ?String
    {
    	$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        $charactersLength =strlen($letters);
        $length = 7;
        for ($i = 0; $i < $length; $i++) {
            $code .= $letters[random_int(0, $charactersLength - 1)];
        }
        $exist = $this->partnerCode->where('code', $code)->count();
        if($exist > 0){
        	$code = $this->getCode();
        }

        return $code;
    }

    /**
     * The createPartnerCode() will create a PartnerCode record
     * given the data array passed in the below param
     * 
     * @param array $data
     *
     * @return PartnerCode
     */
    public function createPartnerCode(array $data): PartnerCode
    {
        $partnerCode = $this->partnerCode->create($data);

        return $partnerCode;
    }

    /**
     * The updatePartnerCode() will update a given PartnerCode record
     * given the partnerCodeId and data array passed in the below param
     * 
     * @param int $partnerCode
     * @param array $data
     *
     * @return Payment
     */
    public function updatePartnerCode(int $partnerCodeId, array $data): PartnerCode
    {
        $partnerCode = $this->partnerCode->findOrFail($partnerCodeId);
        $partnerCode->update($data);

        return $partnerCode;
    }

    /**
     * The verifyCode() will update a given PartnerCode record
     * given the partnerCodeId and data array passed in the below param
     * 
     * @param String $code
     * @param String $email
     *
     * @return PartnerCode
     */
    public function verifyCode(String $code, String $email): ?PartnerCode
    {
        $partnerCode = $this->partnerCode->where(['code'=>$code, 'email'=>$email, 'status'=>'PENDING'])->first();

        return $partnerCode;
    }
}