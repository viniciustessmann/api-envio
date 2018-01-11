<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\ExcelServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use App\Send;

class SendController extends Controller
{   

    public function get() {

        $send = new Send();
        $data = $send->findAll();

        $response = [];
        foreach ($data as $item) {
            
            $response[$item['type']][$item['id']] = [
                'min' => $item['min'],
                'max' => $item['max'],
                'l1' => $item['l1'],
                'l2' => $item['l2'],
                'l3' => $item['l3'],
                'l4' => $item['l4'],
                'e1' => $item['e1'],
                'e2' => $item['e2'],
                'e3' => $item['e3'],
                'e4' => $item['e4'],
                'n1' => $item['n1'],
                'n2' => $item['n2'],
                'n3' => $item['n3'],
                'n4' => $item['n4'],
                'n5' => $item['n5'],
                'n6' => $item['n6'],
                'i1' => $item['i1'],
                'i2' => $item['i2'],
                'i3' => $item['i3'],
                'i4' => $item['i4'],
                'i5' => $item['i5'],
                'i6' => $item['i6'],
            ];
        }

        return view('values', ['data' => $response]);
    }

    public function import() {
        
        //TODO - Rever metodo de save, ver se já existe o registro.
        //Erase all data before insert new import.
        DB::delete('delete from sends');

        $info = $this->readCsv();

        foreach ($info as $item) {

            $send = new Send();

            $send->setType($item['type']);
            $send->setMin($this->converterFloat($item['min']));
            $send->setMax($this->converterFloat($item['max']));

            $send->setL1((isset($item['L1'])) ? $item['L1'] : null);
            $send->setL2((isset($item['L2'])) ? $item['L2'] : null);
            $send->setL3((isset($item['L3'])) ? $item['L3'] : null);
            $send->setL4((isset($item['L4'])) ? $item['L4'] : null);

            $send->setE1((isset($item['E1'])) ? $item['E1'] : null);
            $send->setE2((isset($item['E2'])) ? $item['E2'] : null);
            $send->setE3((isset($item['E3'])) ? $item['E3'] : null);
            $send->setE4((isset($item['E4'])) ? $item['E4'] : null);

            $send->setN1((isset($item['N1'])) ? $item['N1'] : null);
            $send->setN2((isset($item['N2'])) ? $item['N2'] : null);
            $send->setN3((isset($item['N3'])) ? $item['N3'] : null);
            $send->setN4((isset($item['N4'])) ? $item['N4'] : null);
            $send->setN5((isset($item['N5'])) ? $item['N5'] : null);
            $send->setN6((isset($item['N6'])) ? $item['N6'] : null);

            $send->setI1((isset($item['I1'])) ? $item['I1'] : null);
            $send->setI2((isset($item['I2'])) ? $item['I2'] : null);
            $send->setI3((isset($item['I3'])) ? $item['I3'] : null);
            $send->setI4((isset($item['I4'])) ? $item['I4'] : null);
            $send->setI5((isset($item['I5'])) ? $item['I5'] : null);
            $send->setI6((isset($item['I6'])) ? $item['I6'] : null);

            $send->save();

        }

        die;
        
    }

    private function readCsv() {

        $data = [];

        //Economico
        $path = url('/') . Storage::get('teste.csv');
        $response = explode("\n", $path);

        for ($x=9; $x<=20; $x++) {
            $data[] = $this->extractRow($response[$x], $response[8], 'ECO');
        }

        //Expresso
        $path = url('/') . Storage::get('teste2.csv');
        $response = explode("\n", $path);

        for ($x=9; $x<=21; $x++) {
            $data[] = $this->extractRow($response[$x], $response[8], 'EXP');
        }

        return $data;
    }

    private function extractRow($row, $head, $type){

        $head = explode(';', $head);
        $response = explode(';', $row);
        $obj = [];

        for ($x=1; $x < (count($response) - 1); $x++) {

            $peso = explode(' ', $response[0]);

            $obj[$head[$x]] = $this->converterFloat($response[$x]);
            $obj['type'] = $type;
            $obj['min'] = $this->converterPeso($peso[0]);
            $obj['max'] = $this->converterPeso(end($peso));

            if ($peso[0] == "Kg") {
                $obj['min'] = -1;
                $obj['max'] = -1;
            }
        }

        return $obj;
    }

    private function converterFloat($info) {
        return floatval(str_replace(',', '.', rtrim($info)));
    }

    private function converterPeso($info) {
        return floatval(str_replace('.', '', rtrim($info)));
    }

}
