<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoiceLog extends Model
{
    //
    public $table = 'voice_log';
    public $timestamps = false;
    protected $primaryKey = 'voice_log_id';


    protected $fillable = [
        'phoneNumber', 'status', 'sessionId', 'errorMessage', 'updated_at', 'created_at'
    ];
}




