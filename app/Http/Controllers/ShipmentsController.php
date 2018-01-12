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
    const CAPITAIS = [
        'SÃO PAULO',
        'RIO DE JANEIRO',
        'SALVADOR',
        'FORTALEZA',
        'BELO HORIZONTE',
        'CURITIBA',
        'MANAUS',
        'RECIFE',
        'PORTO ALEGRE',
        'BELÉM',
        'GOIÂNIA',
        'SÃO LUÍS',
        'MACEIÓ',
        'TERESINHA',
        'NATAL',
        'CAMPO GRANDE',
        'JOAÃO PESSOA',
        'CUIABÁ',
        'ARACAJU',
        'FLORIANÓPOLIS',
        'PORTO VELHO',
        'MACAPÁ',
        'RIO BRANCO',
        'VITÓRIA',
        'BOA VISTA',
        'PALMAS'
    ];

    public function calculateForm() {
        //TODO make a calculater after here.
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

        $code = $this->getCodeShipment($responseOrigin, $responseDestiny);
        
        $send = new Send();
        $price = $send->getPriceSample($code, $params['peso']);

        if (is_null($price)) {
            echo 'Preço não encontrado no sistema';
            die;
        }

        return view('result', [
            'origin' => $responseOrigin['cidade'] . ' / CEP: ' . $params['origin'],
            'destiny' => $responseDestiny['cidade'] . ' CEP: ' . $params['destiny'],
            'peso' => $params['peso'] . 'kg',
            'price' => 'R$' . number_format($price, 2, ',', '.')
        ]);
        
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

    /**
     * Function to get the Code of shipment Ex.: N, L, E or I
     * 
     * @param string origin
     * @param string destiny
     */
    private function selectCode($origin, $destiny) {

        if(in_array($origin['cidade'], $this::CAPITAIS) && in_array($destiny['cidade'], $this::CAPITAIS) && $origin-['cidade'] != $destiny-['cidade'] ) {
            return 'N';
        }

        if($origin['cidade'] == $destiny['cidade']){
            return 'L';
        }

        if($origin['uf'] == $destiny['uf']){
            return 'E';
        }

        return 'I';
       
    }

}
