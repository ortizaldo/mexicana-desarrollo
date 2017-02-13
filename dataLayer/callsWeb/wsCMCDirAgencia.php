<?php

/**
 * Created by PhpStorm.
 * User: softm
 * Date: 23/06/2016
 * Time: 05:40 PM
 */
ini_set('default_socket_timeout', 120);
ini_set('max_execution_time', 0);
//ini_set('display_errors', '1');

require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

if (isset($_POST['p_agencia'])) {
    $p_agencia = $_POST['p_agencia'];

    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";

    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

    //$nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    //$nuSoapClientMexicana->decode_utf8 = false;
    /*$nuSoapClientMexicana->timeout=120;
    $nuSoapClientMexicana->response_timeout=120;*/
    $postData = array(
        'p_agencia' => $p_agencia
    );


    //echo json_encode($nuSoapClientMexicana);
    $resultWsDirecciones = $nuSoapClientMexicana->call('ws_cmc_direcciones_agencia', $postData);
    error_log('message resultWsDirecciones 1 '.json_encode($resultWsDirecciones));
    if ($nuSoapClientMexicana->fault) {
        //echo "entro en err";
    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        //echo "entro en err";
        echo json_encode($err);
    } else {
        //echo "entro en resultWsDirecciones";
        error_log('message resultWsDirecciones '.json_encode($resultWsDirecciones));
        print_r($resultWsDirecciones);
    }
    //echo $nuSoapClientMexicana->getDebug();
} else {
    //{"result":"0","descripcion_error":"Exito","ot_direcciones":"\n"}
    $responseJson = array(
        'result' => 0,
        'descripcion_error' => 'No se ingreso correctamente la informacion de entrada',
        'ot_direcciones' => 'noData'
    );
    echo json_encode($responseJson);
}