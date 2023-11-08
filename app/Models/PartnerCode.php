<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerCode extends Model
{
    use HasFactory;

    protected $table = 'partners_manual_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','email','code','amount','package','status','settled'];

    // public function adminRole(){
    //     return $this->belongsTo('App\Models\AdminRole','role','role');
    // }
}
