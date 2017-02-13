<?php

require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

if (isset($_POST['p_agencia'])) {
    $p_agencia = $_POST['p_agencia'];
    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;


    $postData = array(
        'p_agencia' => $p_agencia
    );
    $resultWsColonias = $nuSoapClientMexicana->call('ws_siscom_colonias_agencia', $postData);

    if ($nuSoapClientMexicana->fault) {

    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        echo json_encode($err);
    } else {
        echo json_encode($resultWsColonias);
        //print_r ($resultWsColonias);
    }
} else {
    // "result":"0","p_clasificacion":"0","descripcion_error":"Exito"}
    $responseJson = array(
        'result' => 0,
        'descripcion_error' => 'No se ingreso correctamente el id de la agencia',
        'ot_colonias' => ''

    );
    echo json_encode($responseJson);
}