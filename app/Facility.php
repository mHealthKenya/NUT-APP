<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    //
    public $table = 'tbl_master_facility';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'code', 'name', 'keph_level', 'facility_type', 'regulatory_body', 'county_id', 'Sub_County_ID', 'updated_by', 'created_at', 'updated_at', 'created_at',
        'lat', 'lng'
        ];

    
    public function subcounty(){
        return $this->belongsTo('App\SubCounty', 'Sub_County_ID', 'id');
    }
    public function user(){
        return $this->belongsTo('App\User', 'id', 'facility_id');
    }
}
