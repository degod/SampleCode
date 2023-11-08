<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name','code','amount','short_description','benefits','free_license','license_commission_percent','free_promonie_terminal','promonie_transaction_charge_percent','updated_at'];

    // public function adminRole(){
    //     return $this->belongsTo('App\Models\AdminRole','role','role');
    // }
}
