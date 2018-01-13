<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;
use App\Code;
use Illuminate\Support\Facades\Storage;

class StatesController extends Controller
{   
    public function get() { 

        $state = new State();
        $states = $state->findAll();

        return view('states', ['states' => $states]);
    }

    public function getCodes() {
        $code = new Code();
        $state = new State();

        $listStates = $state->findAllList(); 
        $codes = $code->findAll();

        $response = [];

        foreach ($codes as $index1 => $code) {
            $response[$listStates[$code['state_origin']]][$listStates[$code['state_destiny']]] = $code['code'];
        }

        return view('codes', ['codes' => $response]);
    }

    public function import() {

        $data = $this->readCsvStates();

        for ($x=0; $x<=26; $x++) {
            $state = new State();
            $state->saveIfNotDuplicated($data[$x]);
        }

        return 'Importação OK';
    }

    public function importRelationship() {

        $state = new State();
        $states = $state->findAllList();
        $matrix = $this->generateMatrix();

        foreach ($matrix as $index1 => $items) {
            foreach ($items as $index2 => $items2) {
                foreach ($items2 as $index3 => $item) {

                    if ($index3 == 0 || $index3 == 1) {
                        continue;
                    }

                    $code = new Code();

                    $code->setOrigin($index2);
                    $code->setDestiny($index3 - 1);
                    $code->setCode($item);

                    if(!$code->existsItCombination($code->getOrigin(), $code->getDestiny())){
                        $code->save();
                    }
                }
            }
        }

        return 'Importação OK';
    }

    private function readCsvStates() {

        if (!Storage::disk('local')->has('codes.csv')) {
            echo 'Arquivo "codes" não encontrado, entre em contato com o administrador do sistema';
            die;
        }

        $path = url('/') . Storage::get('codes.csv');
        $response = explode("\n", $path);
        $states = explode(';', $response[2]);

        unset($states[0]);
        unset($states[1]);

        sort($states);
        return $states;
    }

    private function readCsv() {

        if (!Storage::disk('local')->has('codes.csv')) {
            echo 'Arquivo "codes" não encontrado, entre em contato com o administrador do sistema';
            die;
        }
        
        $path = url('/') . Storage::get('codes.csv');
        $data = explode("\n", $path);
        $response = [];

        for ($x=3; $x<=29; $x++) {
            $response[] = explode(';', $data[$x]);
        }

        return $response;
    }

    private function generateMatrix() {

        $data = $this->readCsv();

        $response = [];

        foreach ($data as $id => $items) {

            $origin = $id + 1;
            for ($x=0; $x<=26; $x++) {
                
                $destiny = $data[$x];
                $response[$origin][$x + 1] = $destiny;
            }
        }

        return $response;
    }
}
