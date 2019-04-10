<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    public $table = 'clients';
    public $timestamps = false;
    protected $primaryKey = 'client_id';


    protected $fillable = [
    'phone_number', 'user_id', 'is_sms', 'date_recruitment', 'updated_at', 'created_at'
    ];
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
    public function outgoing(){
        return $this->hasMany('App\OutgoingMsg', 'client_id', 'client_id');
    }
}
