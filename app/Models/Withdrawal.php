<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','transactionId','amount','description','status','raw_response','updated_at'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
