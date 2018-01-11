<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\State;
use App\Code;

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
        return view('calculate');
    }

    public function calculateRequest(Request $request) {

        $responseOrigin = $this->getResponseApiEnvio($request->origin);
        $responseDestiny = $this->getResponseApiEnvio($request->destiny);

        $codeSend = $this->selectCode($responseOrigin, $responseDestiny);

        $state = new State();
        $idOrigin = $state->getIdByUf($responseOrigin->uf);
        $idDestiny = $state->getIdByUf($responseDestiny->uf);

        $code = new Code();
        $codes = $code->getCodes($idOrigin, $idDestiny);
  
        $codeId = $codeSend.$codes;

        if (strlen($codeId) > 2) {
            $codeId = $codes;
        }

        echo $codeId;
        die;
        
    }

    private function getResponseApiEnvio($cep){
        return json_decode(Curl::to('https://location.melhorenvio.com.br/' . $cep)->get());
    }

    private function selectCode($origin, $destiny) {

        if(in_array($origin->cidade, $this::CAPITAIS) && in_array($destiny->cidade, $this::CAPITAIS) && $origin->cidade != $destiny->cidade ) {
            return 'N';
        }

        if($origin->cidade == $destiny->cidade){
            return 'L';
        }

        if($origin->uf == $destiny->uf){
            return 'E';
        }

        return 'I';
       
    }

}

