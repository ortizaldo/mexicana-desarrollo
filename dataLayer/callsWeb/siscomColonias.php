<?php

require_once('../libs/nusoap_lib/nusoap.php');

if (isset($_POST['city'])) {
    $idMunicipio = $_POST['city'];
    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
    $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;
    /*$postData = array(
        $idMunicipio=>$idMunicipio
    );*/
    $postData = array(
        'idMunicipio' => $idMunicipio
    );
    $resultWsColonias = $nuSoapClientMexicana->call('ws_siscom_colonias', $postData);
    //$resSan=utf8_converter($resultWsColonias);
    //echo json_encode($resSan);
    //printf(array($resultWsColonias['ot_colonias']['ot_coloniasRow']));
    if ($nuSoapClientMexicana->fault) {

    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        echo json_encode($err);
    } else {
        //error_log('municipios '.json_encode($resultWsColonias));
        echo json_encode($resultWsColonias);
    }
} else {
    // "result":"0","p_clasificacion":"0","descripcion_error":"Exito"}
    $responseJson = array(
        'result' => 0,
        'descripcion_error' => 'No se ingreso correctamente el id de la colonia',
        'ot_colonias' => ''

    );
    echo json_encode($responseJson);
}

function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });
 
    return $array;
}