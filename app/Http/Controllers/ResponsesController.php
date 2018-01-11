<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator ;

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

        $errors = $this->validateParams($request);

        if ($errors) {

            return response()->json([
                'error' => true,
                'messages' => $errors
            ]); 
        }

        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA'
        ]);   
    }

    private function validateParams($request) {

        $validator = Validator::make($request->all(), [
            'origem' => 'required|max:8',
            'destino' => 'required|max:8',
            'peso' => 'required|min:1|max:2',
            'altura' => 'required|min:1|max:3',
            'largura' => 'required|min:1|max:3',
            'comprimento' => 'required|min:1|max:3',
            'valor' => 'required',
            'ar' => 'required',
            'mao' => 'required'
        ], [
            'required' => ' :attribute é obrigatório.',
            'origem.max'    => 'O CEP de :attribute deve conter  :max números e não conter caracteres.',
            'destino.max'    => 'O CEP de :attribute deve conter  :max números e não conter caracteres.',
            'ar.required' => ' :attribute é obrigatório, use FALSE ou TRUE',
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
}
