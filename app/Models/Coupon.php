<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','code','coupon_percent','type','status','expiry'];

    protected $table = 'coupon_codes';

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function users(){
        return $this->hasMany('App\Models\User','coupon_code','code');
    }
}
