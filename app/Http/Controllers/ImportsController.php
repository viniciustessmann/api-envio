<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use App\Send;

class ImportsController extends Controller
{
    const codes = [
        'l1',
        'l2',
        'l3',
        'l4',
        'e1',
        'e2',
        'e3',
        'e4',
        'n1',
        'n2',
        'n3',
        'n4',
        'n5',
        'n6',
        'i1',
        'i2',
        'i3',
        'i4',
        'i5',
        'i6'
    ];

    public function get() {

        $send = new Send();
        $data = $send->findAll();

        $response = [];
        foreach ($data as $item) {
            
            $response[$item['type']][$item['id']] = [
                'min' => $item['min'] . 'g',
                'max' => $item['max'] . 'g',
                'l1' => $this->formatValue($item['l1']),
                'l2' => $this->formatValue($item['l2']),
                'l3' => $this->formatValue($item['l3']),
                'l4' => $this->formatValue($item['l4']),
                'e1' => $this->formatValue($item['e1']),
                'e2' => $this->formatValue($item['e2']),
                'e3' => $this->formatValue($item['e3']),
                'e4' => $this->formatValue($item['e4']),
                'n1' => $this->formatValue($item['n1']),
                'n2' => $this->formatValue($item['n2']),
                'n3' => $this->formatValue($item['n3']),
                'n4' => $this->formatValue($item['n4']),
                'n5' => $this->formatValue($item['n5']),
                'n6' => $this->formatValue($item['n6']),
                'i1' => $this->formatValue($item['i1']),
                'i2' => $this->formatValue($item['i2']),
                'i3' => $this->formatValue($item['i3']),
                'i4' => $this->formatValue($item['i4']),
                'i5' => $this->formatValue($item['i5']),
                'i6' => $this->formatValue($item['i6']),
            ];
        }

        return view('values', [
            'data' => $response,
            'count' => count($data)
        ]);
    }

    /**
     * Converter float to coin value
     * 
     * @param float value
     * @return string coin
     */
    private function formatValue($value) {

        if (is_null($value)) {
            return '';
        }
        return 'R$' . number_format($value, 2, ',', '.');
    }

    public function import() {

        // DB::delete('delete from sends');

        $send = new Send();
        $all = $send->findAll();
        
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

            $res = $this->existThisRowInDbAndNeedUpdate($item, $all);

            if ($res['update']) {
                $send->updateRow($res);
                continue;
            }

            if (is_null($res)) {
                $send->save();
                continue;
            }


            if ($res == false) {
                continue;

            } 
        }

        return 'Importação OK';
        
    }

    private function existThisRowInDbAndNeedUpdate($row, $all) {

        if (empty($all)) {
            return null;
        }

        foreach ($all as $index1 => $items1) {

            if ($items1['type'] != $row['type'] || $items1['min'] != $row['min'] || $items1['max'] != $row['max']) {
                continue;
            }

            $response = false;
            foreach ($this::codes as $code) {

                $codeUp = strtoupper($code);

                if (!isset($items1[$code])) {
                    continue;
                }

                if ($items1[$code] != $row[$codeUp]) {
                    return [
                        'update' => true,
                        'id' => $items1['id'], 
                        'field' => $code,
                        'value' => $row[$codeUp]
                    ];
                }
            }
        }

        return $response;
    }

    private function readCsv() {

        $data = [];

        //Economico
        $path = url('/') . Storage::get('economico.csv');
        $response = explode("\n", $path);

        for ($x=9; $x<=20; $x++) {
            $data[] = $this->extractRow($response[$x], $response[8], 'ECO');
        }

        //Expresso
        $path = url('/') . Storage::get('expresso.csv');
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
