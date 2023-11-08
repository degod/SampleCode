<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','first_name','name','phone','business_type','business_id','updated_at'];

	protected $table = 'business';
    
    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function license(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
