<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{   

    protected $table = 'code_states';

    public function setOrigin($originId) {
        $this->attributes['state_origin'] = $originId;
    }   

    public function getOrigin() {
        return $this->attributes['state_origin'];
    }

    public function setDestiny($destinyId) {
        $this->attributes['state_destiny'] = $destinyId;
    }

    public function getDestiny() {
        return $this->attributes['state_destiny'];
    }

    public function setCode($code) {
        $this->attributes['code'] = $code;
    }

    public function getCode() {
        return $this->attributes['code'];
    }

    public function findAll() {
        return $this::all()->toArray();
    }
    
    public function existsItCombination($originId, $destinyId) {

        $count = $this::where('state_origin', $originId)->where('state_destiny', $destinyId)->count();
        
        if ($count == 0) {
            return false;
        }

        return true;
    }

    public function getCodesNumber($originId, $destinyId) {

        $codes = $this::where('state_origin', $originId)->where('state_destiny', $destinyId)->where('state_origin', $originId)->first();

        if (!$codes) {
            return false;
        }

        $numberCode = $codes->toArray()['code'];
        if ( strlen($numberCode) > 1) {
            $numberCode = substr($numberCode, -1);
        }

        return $numberCode;
    }
}
