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

    public function getIdByOriginAndDestiny($originId, $destinyId) {

    }

    public function existsItCombination($originId, $destinyId) {

        $count = $this::where('state_origin', $originId)->where('state_destiny', $destinyId)->count();
        
        if ($count == 0) {
            return false;
        }

        return true;
    }
}
