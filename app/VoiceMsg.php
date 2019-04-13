<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoiceMsg extends Model
{
    //
    public $table = 'voice_messages';
    public $timestamps = false;
    protected $primaryKey = 'voice_message_id';


    protected $fillable = [
        'voice_url', 'sequence', 'updated_at', 'created_at'
    ];
}
