<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseWallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','quantity','transactionId','description','type','business_package_id'];

    protected $table = 'license_wallet';

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
