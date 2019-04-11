<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCounty extends Model
{
    //
    public $table = 'tbl_county';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'code', 'region_id', 'status', 'date_added', 'time_stamp'
        ];

        public function county(){
            return $this->belongsTo('App\County', 'county_id', 'id');
        }
}
