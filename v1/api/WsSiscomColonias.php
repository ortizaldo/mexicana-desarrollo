<?php

require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

if (isset($_POST['idMunicipio'])) {
    $idMunicipio = $_POST['idMunicipio'];
    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;

    /*$postData = array(
        $idMunicipio=>$idMunicipio
    );*/
    $postData = array(
        'idMunicipio' => $idMunicipio
    );
    $resultWsColonias = $nuSoapClientMexicana->call('ws_siscom_colonias', $postData);

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
        'descripcion_error' => 'No se ingreso correctamente el id de la colonia',
        'ot_colonias' => ''

    );
    echo json_encode($responseJson);
}