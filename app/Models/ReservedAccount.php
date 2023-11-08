<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservedAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','account_name','account_number','account_reference','bank','flat_charge','percentage_charge','cap_charge','status','updated_at'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
