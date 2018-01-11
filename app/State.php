<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ixudra\Curl\Facades\Curl;

class State extends Model
{
    protected $table = 'states';

    public function setName($name) {
        $this->attributes['name'] = strtoupper($name);
    }

    public function getName() {
        return $this->atttributes['name'];
    }

    public function getIdByName($name) {
        $name =  $this::where('name', $name)->first();
        if (!$name) {
            return null;
        }

        return $name->toArray()['name'];
    }

    public function saveIfNotDuplicated(State $state) {
        if(!$this->existsState($state->getName())) {    
            $state->save();
        }
    }

    private function existsState($name) {
        $count = $this::where('name', $name)->count();
        
        if ($count == 0) {
            return false;
        }

        return true;
    }

    public function getIdByUf($name) {
        $state = $this::where('name', $name)->first()->toArray();

        if (!$state) {
            return null;
        }

        return $state['id'];
    }

    public function findAll() {
        return $this::all()->toArray();
    }

    public function findAllList() {
        $response = [];
        $data =  $this::findAll();

        foreach ($data as $item) {
            $response[$item['id']] = $item['name'];
        }

        return $response;
    }

    public function getIdStateByCep($cep) {
        $info = json_decode(Curl::to('https://location.melhorenvio.com.br/' . $cep)->get());
        $uf = $info->uf;

        return $this->getIdByUf($uf);
    }

    
}
