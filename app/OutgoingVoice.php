<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutgoingVoice extends Model
{
    //
    public $table = 'outgoing_voice_messages';
    public $timestamps = false;
    protected $primaryKey = 'outgoing_message_id';


    protected $fillable = [
    'message', 'destination', 'send_date', 'status', 'client_id', 'educational_message_id', 'updated_at', 'created_at'
    ];

    public function educational_message(){
        return $this->belongsTo('App\EducationalMsg', 'educational_message_id', 'voice_message_id');
    }

    public function client(){
        return $this->belongsTo('App\Client', 'client_id', 'client_id');
    }
}
