<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
