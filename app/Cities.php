<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $primaryKey = 'city_id';
    protected $table ='cities';
    
    public function checkCity($data)
    {
        //DB::enableQueryLog();
        $city = $this->select('*');
        if (isset($data['city_name'])) {
            $city->where(DB::raw('LOWER(city_name)'), 'like', DB::raw('LOWER("%' . $data['city_name'] . '%")'));
        }
        if (isset($data['country_code'])) {
            $city->where('country_code', '=', $data['country_code']);
        }
        if (isset($data['state_code'])) {
            $city->where('state_code', '=', $data['state_code']);
        }
        //$city->first();
        //dd(DB::getQueryLog());
        return $result = $city->first();
    }
}
