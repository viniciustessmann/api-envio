<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\State;
use App\Code;

class ShipmentsController extends Controller
{
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

        if($origin->cidade == $destiny->cidade){
            return 'L';
        }

        if($origin->uf == $destiny->uf){
            return 'E';
        }

        return 'I';
       
    }
}
