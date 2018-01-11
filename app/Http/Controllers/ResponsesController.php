<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\CepHandler;
use Ixudra\Curl\Facades\Curl;
use App\State;
use App\Code;
use \App\Send;

class ResponsesController extends Controller
{
    public function getResponse(Request $request) {

        // origem:96020360
        // destino:96065710
        // peso:5000
        // largura:30
        // altura:50
        // comprimento:15
        // valor:19.90
        // ar:true
        // mao:false
        //seguro:true
        
        $errors = $this->validateParams($request);

        if ($errors) {

            return response()->json([
                'error' => true,
                'messages' => $errors
            ]); 
        }

        $params = $request->all();

        $servicesAditional = [
            'ar' => $params['ar'],
            'seguro' => $params['seguro'],
            'mao' => $params['mao']
        ];

        $peso = $this->calculateDimension($params['comprimento'], $params['largura'], $params['altura'], $params['peso']);

        $state = new State();

        $idOrigin = $state->getIdStateByCep($params['origem']);
        $idDestiny = $state->getIdStateByCep($params['destino']);

        $code = new Code();
        $codeField = $code->getCodes($idOrigin, $idDestiny);

        $send = new Send();
        $resultSend['eco'] = $send->getPrice($codeField, $peso, $params['valor'], 'ECO', $servicesAditional);
        $resultSend['exp'] = $send->getPrice($codeField, $peso, $params['valor'], 'EXP', $servicesAditional);

        return response()->json($resultSend);   
    }

    private function validateParams($request) {

        $validator = Validator::make($request->all(), [
            'origem' => 'required|max:8',
            'destino' => 'required|max:8',
            'peso' => 'required',
            'altura' => 'required|min:1|max:3',
            'largura' => 'required|min:1|max:3',
            'comprimento' => 'required|min:1|max:3',
            'valor' => 'required',
            'ar' => 'required',
            'mao' => 'required',
            'seguro' => 'required',
            'valor' => 'required'
        ], [
            'required' => ' :attribute é obrigatório.',
            'origem.max'    => 'O CEP de :attribute deve conter  :max números e não conter caracteres.',
            'destino.max'    => 'O CEP de :attribute deve conter  :max números e não conter caracteres.',
            'ar.required' => ' :attribute é obrigatório, use FALSE ou TRUE',
            'seguro.required' => ' :attribute é obrigatório, use FALSE ou TRUE',
            'mao.required' => ' :attribute é obrigatório, use FALSE ou TRUE',
            'max' => ' :attribute deve conter no maximo :max digitos',
            'min' => ' :attribute deve conter no maximo :min digitos'
            ]
        );

        $errors = [];

        if($validator->fails()) {
            $errorsResults = $validator->errors()->getMessages();

           $errors = [];
            foreach ($errorsResults as $error) {
                $errors[] = end($error);
            }
        }   

        return $errors;
    }

    private function calculateDimension($com, $lar, $alt, $peso) {

        $cub = $com * $lar * $alt;

        $totalCub = $cub/6000;

        if ($totalCub < 10) {
            return ceil($peso);
        }

        if ($totalCub >= 10 && $totalCub > $peso) {
            return ceil($totalCub);
        }

        return ceil($peso);

    }    
}
