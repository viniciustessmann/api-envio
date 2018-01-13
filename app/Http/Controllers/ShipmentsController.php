<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\State;
use App\Code;
use App\Send;

/**
 * Controller to manager shipments
 * @author Vinícius Schlee Tessmann
 * @version 1.0
 */
class ShipmentsController extends Controller
{   

    public function calculateForm() {
        return view('calculate');
    }

    /**
     * Function to calculate request from shipment
     * 
     * @param Request request
     * @return array response
     */
    public function calculateRequest(Request $request) {

        $params = $request->all();
        $errors = [];

        $responseOrigin = $this->getResponseApiEnvio($params['origin']);
        if (isset($responseOrigin['error'])) {
            $errors[] = $responseOrigin['message'];
        }

        $responseDestiny = $this->getResponseApiEnvio($params['destiny']);
        if (isset($responseDestiny['error'])) {
            $errors[] = $responseDestiny['message'];
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . '</br>'; 
            }
            die;
        }

        $state = new State();

        $code = $state->getCodeShipment($params['origin'], $params['destiny']);
      
        $send = new Send();
        $prices = [
            'eco' => $send->getPriceSample($code, $params['peso'], 'ECO'),
            'exp' => $send->getPriceSample($code, $params['peso'], 'EXP')
        ];

        return view('result', [
            'origin' => $responseOrigin['cidade'] . ' / CEP: ' . $params['origin'],
            'destiny' => $responseDestiny['cidade'] . ' CEP: ' . $params['destiny'],
            'peso' => $params['peso'] . 'kg',
            'code' => $code,
            'priceEco' => $this->setCoinToView($prices['eco']),
            'priceExp' => $this->setCoinToView($prices['exp'])
        ]);
        
    }

    /**
     * @param float coin
     * @return string coin
     */
    private function setCoinToView($item) {
        if ($item == 0) {
            return 'Não oferecemos esse pacote';
        }
        return  'R$' . number_format($item, 2, ',', '.');
    }


    /**
     * Function to get code of shipment
     * 
     * @param array origin
     * @param array destiny
     * @return string code
     */
    private function getCodeShipment($origin, $destiny) {

        $codeSend = $this->selectCode($origin, $destiny);

        $state = new State();
        $idOrigin = $state->getIdByUf($origin['uf']);
        $idDestiny = $state->getIdByUf($destiny['uf']);

        $code = new Code();
        $codes = $code->getCodes($idOrigin, $idDestiny);
  
        $codeId = $codeSend.$codes;

        if (strlen($codeId) > 2) {
            $codeId = $codes;
        }

        return $codeId;
    }

    /**
     * Function to get the response from API melhor transpotadora
     * 
     * @param string cep
     * @return array response
     */
    private function getResponseApiEnvio($cep){

        $cep = str_replace('-', '', $cep);

        $info =  json_decode(Curl::to('https://location.melhorenvio.com.br/' . $cep)->get());
        
        if (isset($info->error)) {
            return [
                'error' => true,
                'message' => $info->error . '. CEP:' . $cep
            ];
        }

        return [
            'cep' => $info->cep,
            'uf' => $info->uf,
            'cidade' => $info->cidade
        ];
    }
}
