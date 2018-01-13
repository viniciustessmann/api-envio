<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\State;
use App\Code;
use \App\Send;

/**
 * Controller to manager reponses of API
 * @author Vinícius Schlee Tessmann
 * @version 1.0
 */
class ResponsesController extends Controller
{   

    /** 
    * Get the request form API
    *
    * @param Request request
    * @return Json response
    */
    public function getResponse(Request $request) {
        
        $errors = $this->validateParams($request);

        if ($errors) {

            return response()->json([
                'error' => true,
                'messages' => $errors
            ]); 
        }

        $params = $request->all();

        //Remove "-" on CEPS
        $params['origem'] = str_replace('-', '', $params['origem']);
        $params['destino'] = str_replace('-', '', $params['destino']);

        $servicesAditional = [
            'ar' => $params['ar'],
            'seguro' => $params['seguro'],
            'mao' => $params['mao']
        ];

        $peso = $this->calculateDimension(
            $params['comprimento'], 
            $params['largura'], 
            $params['altura'], 
            $params['peso']
        );

        $state = new State();

        $idOrigin = $state->getIdStateByCep($params['origem']);
        if ($idOrigin['error']) {
            return $idOrigin;
        }

        $idDestiny = $state->getIdStateByCep($params['destino']);
        if ($idDestiny['error']) {
            return $idDestiny;
        }

        $code = new Code();
        $codeField = $code->getCodes($idOrigin, $idDestiny);

        if (!$codeField) {
            return response()->json([
                'error' => true,
                'message' => 'Não foram encontradas informações no banco de dados, entrar em contato com o administrado do sistema'
            ]);
        }

        $send = new Send();

        $resultSend = [
            'eco' => $send->getPrice($codeField, $peso, $params['valor'], 'ECO', $servicesAditional),
            'exp' => $send->getPrice($codeField, $peso, $params['valor'], 'EXP', $servicesAditional)
        ];

        return response()->json($resultSend);   
    }

    /**
    * Function to validate fields of request
    *
    * @param array inputs
    * @return array errors
    */
    private function validateParams($request) {

        $validator = Validator::make($request->all(), [
            'origem' => 'required|max:9',
            'destino' => 'required|max:9',
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

        $errorsCustom = $this->customValidate($request->all());
        return array_merge($errorsCustom, $errors);
    }

    /**
    * Function to validate dimension of package
    *
    * @param array inputs
    * @return array errors
    */
    private function customValidate($inputs) {

        $errors = [];

        if ($inputs['altura'] < 1 || $inputs['altura'] >= 106) {
            $errors[] = 'Altura deve ser mais que 1cm e menor que 106cm';
        }

        if ($inputs['largura'] < 10 || $inputs['largura'] >= 106) {
            $errors[] = 'Largura deve ser mais que 10cm e menor que 106cm';
        }

        if ($inputs['comprimento'] < 15 || $inputs['comprimento'] >= 106) {
            $errors[] = 'Comprimento deve ser mais que 16cm e menor que 106cm';
        }

        if ($inputs['peso'] < 1 || $inputs['peso'] > 30) {
            $errors[] = 'Peso deve ser mais que 1kg e menor que 30kg';
        }

        return $errors;
    }

     /**
      * Function to calcute o weight used on shipment 
      *
      * @param float com
      * @param float lar
      * @param float alt
      * @param float peso
      * @return float peso (Shipping Weight)
      */
    private function calculateDimension($com, $lar, $alt, $peso) {

        /**
         * Calculo cúbico: Soma do comprimento + largura + altura divido por 6000.
         * CUB = (COM X LAR X ALT) / 600
         * 
         * Se o peso cubico for maior que 10K cobrar valor aditional, e usar o peso maior entre o peso normal e o peso cubico
         */
  
        $pesoCubico = ($com * $lar * $alt)/6000;

        if ($pesoCubico >= 10 && $pesoCubico > $peso) {
            return ceil($pesoCubico);
        }

        return ceil($peso);

    }    
}
