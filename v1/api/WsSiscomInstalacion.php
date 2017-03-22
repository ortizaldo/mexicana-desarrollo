<?php

require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

if (isset($_POST['p_cia'])
    && isset($_POST['p_usr_id'])
    && isset($_POST['it_instalaciones'])
    && isset($_POST['jsonItSolicitud'])) {
    error_log('array instalaciones'.json_encode($_POST['it_instalaciones']));
    $ip_cia_id = $_POST['p_cia'];
    $ip_usr_id = $_POST['p_usr_id'];
    $ip_contrato = $_POST['ip_contrato'];

    $jsonItSolicitud = $_POST['it_instalaciones'];

    $arrayItSolicitud = json_decode($jsonItSolicitud);
    $it_solicitud = (array)$arrayItSolicitud;
    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
    $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;
    $postData = array(
        'p_cia' => $ip_cia_id,
        'p_usr_id' => $ip_usr_id,
        'it_instalaciones' => $it_solicitud,
    );
    $resultWsColonias = $nuSoapClientMexicana->call('ws_siscom_instala_medidor', $postData);
    //echo $nuSoapClientMexicana->request;
    error_log($nuSoapClientMexicana->request);
    if ($nuSoapClientMexicana->fault) {
        //print_r($resultWsColonias);
    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        echo json_encode($err);
        error_log(json_encode($err)); 
    } else {
        echo json_encode($resultWsColonias);
        error_log(json_encode($resultWsColonias)); 
    }
} else {
    // "result":"0","p_clasificacion":"0","descripcion_error":"Exito"}
    $responseJson = array(
        'result' => 0,
        'ip_contrato' => 0,
        'op_message' => 'No se ingreso correctamente los parametros de entrada'
    );
    echo json_encode($responseJson);
}