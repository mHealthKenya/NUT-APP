<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationalVoice extends Model
{
    //
    public $table = 'educational_voice_messages';
    public $timestamps = false;
    protected $primaryKey = 'voice_message_id';


    protected $fillable = [
    'voice_url', 'sequence', 'updated_at', 'created_at'
    ];
    public function outgoing_voice(){
        return $this->hasMany('App\OutgoingVoice', 'voice_message_id', 'educational_message_id');
    }
}
