<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\ExcelServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SendController extends Controller
{   
    public function get() {
        $data = DB::select('SELECT * FROM sends');

        echo '<pre>';
        
        foreach ($data as $item) {
            var_dump($item->id);
        }

        die;
    }

    public function import() {

        DB::delete('delete from sends');

        set_time_limit(4000);
        ini_set('memory_limit', '2048M');

        $info = $this->readCsv();

        foreach ($info as $item) {

            DB::insert(
                'insert into sends (
                    min, 
                    max, 
                    type,
                    l1,
                    l2,
                    l3,
                    l4,
                    e1,
                    e2,
                    e3,
                    e4,
                    n1,
                    n2,
                    n3,
                    n4,
                    n5,
                    n6,
                    i1,
                    i2,
                    i3,
                    i4,
                    i5,
                    i6
                ) values (
                    ?, 
                    ?, 
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )', 
                [
                    floatval($item['min']), 
                    floatval($item['max']), 
                    $item['type'],
                    (isset($item['L1'])) ? $item['L1'] : null,
                    (isset($item['L2'])) ? $item['L2'] : null,
                    (isset($item['L3'])) ? $item['L3'] : null,
                    (isset($item['L4'])) ? $item['L4'] : null,
                    (isset($item['E1'])) ? $item['E1'] : null,
                    (isset($item['E2'])) ? $item['E2'] : null,
                    (isset($item['E3'])) ? $item['E3'] : null,
                    (isset($item['E4'])) ? $item['E4'] : null,
                    (isset($item['N1'])) ? $item['N1'] : null,
                    (isset($item['N2'])) ? $item['N2'] : null,
                    (isset($item['N3'])) ? $item['N3'] : null,
                    (isset($item['N4'])) ? $item['N4'] : null,
                    (isset($item['N5'])) ? $item['N5'] : null,
                    (isset($item['N6'])) ? $item['N6'] : null,
                    (isset($item['I1'])) ? $item['I1'] : null,
                    (isset($item['I2'])) ? $item['I2'] : null,
                    (isset($item['I3'])) ? $item['I3'] : null,
                    (isset($item['I4'])) ? $item['I4'] : null,
                    (isset($item['I5'])) ? $item['I5'] : null,
                    (isset($item['I6'])) ? $item['I6'] : null
                ]
            );
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

        for ($x=9; $x<=20; $x++) {
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
            $obj['min'] = $peso[0];
            $obj['max'] = end($peso);
        }

        return $obj;
    }

    private function converterFloat($info) {

        return floatval(str_replace(',', '.', rtrim($info)));
    }

    // private function readSheet() {

    //     $excel = new \Excel();
    //     $address = Storage::disk('local')->url('ECONÔMICO-Tabela1.csv');


    //     $path = Storage::get('teste.csv');

    //     echo '<pre>';
    //     var_dump($path);
    //     die;


    //     $fileHandle = fopen($path, "r");
        
    //     //Loop through the CSV rows.
    //     while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
    //         //Dump out the row for the sake of clarity.
    //         var_dump($row);
    //     }

    //     die;
    //     $response = null;

    //     $excel::load($address, function($reader) {
    //         $response = $reader->all()->toArray();
            
    //         dd($response);
    //     });

        
    // }
}
