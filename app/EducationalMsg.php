<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationalMsg extends Model
{
    //
    public $table = 'educational_messages';
    public $timestamps = false;
    protected $primaryKey = 'educational_message_id';


    protected $fillable = [
    'message', 'sequence', 'updated_at', 'created_at'
    ];
    public function outgoing_messages(){
        return $this->hasMany('App\OutgoingMsg', 'educational_message_id', 'educational_message_id');
    }
}
