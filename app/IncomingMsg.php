<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomingMsg extends Model
{
    //
    public $table = 'incoming';
    public $timestamps = false;
    protected $primaryKey = 'id';


    protected $fillable = [
    'response', 'source', 'seqnc_id', 'status', 'updated_at', 'created_at'
    ];

    
}
