<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['first_name','last_name','email','phone','username','password','address','passport_photo','identity_photo','means_of_identity','date_of_birth','state','country','referrer_user_id','payment_status','status','package','coupon_percent','coupon_code'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function referrer(){
        return $this->belongsTo('App\Models\User','referrer_user_id','id');
    }

    public function networks(){
        // return $this->hasMany('App\Models\User','referrer_user_id','id');
        return $this->hasMany('App\Models\User','referrer_user_id','id')->select(
            'id', 
            \DB::raw("CONCAT(first_name,' ',last_name) AS full_name"), 
            'username', 
            'email', 
            'phone', 
            'referrer_user_id',
            'package',
            'payment_status',
            'created_at'
        );
    }

    public function getDownlineTotalAttribute(){
        $total = 0;

        $networks_1 = $this->networks;
        $total += count($networks_1);

        foreach($networks_1 as $net1){
            $networks_2 = $net1->networks;
            $total += count($networks_2);

            foreach($networks_2 as $net2){
                $networks_3 = $net2->networks;
                $total += count($networks_3);

                foreach($networks_3 as $net3){
                    $networks_4 = $net3->networks;
                    $total += count($networks_4);
                }
            }
        }

        return $total;
    }

    public function bank(){
        return $this->hasOne('App\Models\BankDetails','user_id','id');
    }

    public function coupon(){
        return $this->hasOne('App\Models\Coupon','user_id','id');
    }

    public function couponCode(){
        return $this->belongsTo('App\Models\User','coupon_code','code');
    }

    public function account(){
        return $this->hasOne('App\Models\ReservedAccount','user_id','id');
    }

    public function partnerPackage(){
        return $this->hasOne('App\Models\Package','code','package');
    }
}
