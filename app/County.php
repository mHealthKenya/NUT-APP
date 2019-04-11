<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    //
    public $table = 'tbl_county';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'code', 'region_id', 'status', 'date_added', 'time_stamp'
        ];
}
