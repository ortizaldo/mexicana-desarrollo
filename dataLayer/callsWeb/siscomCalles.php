<?php require_once('../libs/nusoap_lib/nusoap.php');

if (isset($_POST['city']) && isset($_POST['colonia'])) {
    $idMunicipio = $_POST['city'];
    $idColonia = $_POST['colonia'];
    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
    $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;
    $postData = array(
        'p_id_municipio' => $idMunicipio,
        'p_colonia_id' => $idColonia
    );

    $resultWsColonias = $nuSoapClientMexicana->call('ws_cmc_direcciones', $postData);
    //$resSan=utf8_converter($resultWsColonias);
    if ($nuSoapClientMexicana->fault) {

    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        echo json_encode($err);
    } else {
        echo json_encode($resultWsColonias);
    }
} else {
    // "result":"0","p_clasificacion":"0","descripcion_error":"Exito"}
    $responseJson = array(
        'result' => 0,
        'descripcion_error' => 'No se ingreso correctamente el id del municipio o colonia',
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