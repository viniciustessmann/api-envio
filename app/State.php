<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ixudra\Curl\Facades\Curl;

class State extends Model
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
        'JOÃO PESSOA',
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

    protected $table = 'states';

    public function setName($name) {
        $this->attributes['name'] = strtoupper($name);
    }

    public function getName() {
        return $this->atttributes['name'];
    }

    public function getIdByName($name) {
        $name =  $this::where('name', $name)->first();
        if (!$name) {
            return null;
        }

        return $name->toArray()['name'];
    }

    public function saveIfNotDuplicated($nameState) {
        
        if(!$this->existsState($nameState)) {  
            $this->setName($nameState);
            $this->save();
        }
    }

    private function existsState($name) {

        $count = $this::where('name', $name)->count();

        if ($count === 0) {
            return false;
        }

        return true;
    }

    public function getIdByUf($name) {
        
        $state = $this::where('name', $name)->first();

        if (!$state) {
            return null;
        }

        return $state->toArray()['id'];
    }

    public function findAll() {
        return $this::all()->toArray();
    }

    public function findAllList() {
        $response = [];
        $data =  $this::findAll();

        foreach ($data as $item) {
            $response[$item['id']] = $item['name'];
        }

        return $response;
    }

    public function getUfByCep($cep) {
        $info = json_decode(Curl::to('https://location.melhorenvio.com.br/' . $cep)->get());

        if (isset($info->error)) {
            return [
                'error' => true,
                'message' => $info->error . '. CEP:' . $cep
            ];
        }

        return $info->uf;
    }

    public function getInfoByCep($cep) {
        $info = json_decode(Curl::to('https://location.melhorenvio.com.br/' . $cep)->get());

        if (isset($info->error)) {
            return [
                'error' => true,
                'message' => $info->error . '. CEP:' . $cep
            ];
        }

        return $info;
    }

    public function getIdStateByCep($cep) {
        $info = json_decode(Curl::to('https://location.melhorenvio.com.br/' . $cep)->get());

        if (isset($info->error)) {
            return [
                'error' => true,
                'message' => $info->error . '. CEP:' . $cep
            ];
        }

        $uf = $info->uf;

        return $this->getIdByUf($uf);
    }

    /**
     * Function to get the Code of shipment Ex.: N, L, E or I
     * 
     * @param string origin
     * @param string destiny
     */
    public function selectCode($origin, $destiny) {

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

    /**
     * Function to get code of shipment
     * 
     * @param string cepOrigin
     * @param string cepDestiny
     * @return string code
     */
    public  function getCodeShipment($CepOrigin, $CepDestiny) {

        $origin = $this->getInfoByCep($CepOrigin);
        $destiny = $this->getInfoByCep($CepDestiny);

        $codeSend = $this->selectCode($origin, $destiny);

        $state = new State();
        $idOrigin = $state->getIdByUf($origin->uf);
        $idDestiny = $state->getIdByUf($destiny->uf);

        $code = new Code();
        $codes = $code->getCodes($idOrigin, $idDestiny);
  
        $codeId = $codeSend.$codes;

        if (strlen($codeId) > 2) {
            $codeId = $codes;
        }

        return $codeId;
    }
    
}
