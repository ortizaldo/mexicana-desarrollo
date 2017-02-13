<?php
/**
 * Created by PhpStorm.
 * User: softm
 * Date: 23/06/2016
 * Time: 05:40 PM
 */

require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

if (isset($_POST['p_id_municipio']) && isset($_POST['p_colonia_id'])) {
    $p_id_municipio = $_POST['p_id_municipio'];
    $p_colonia_id = $_POST['p_colonia_id'];

    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;

    $postData = array(
        'p_id_municipio' => $p_id_municipio,
        'p_colonia_id' => $p_colonia_id
    );
    $resultWsDirecciones = $nuSoapClientMexicana->call('ws_cmc_direcciones', $postData);

    if ($nuSoapClientMexicana->fault) {

    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        echo json_encode($err);

    } else {
        echo json_encode($resultWsDirecciones);
    }
} else {
    //{"result":"0","descripcion_error":"Exito","ot_direcciones":"\n"}
    $responseJson = array(
        'result' => 0,
        'descripcion_error' => 'No se ingreso correctamente la informacion de entrada',
        'ot_direcciones' => 'noData'
    );
    echo json_encode($responseJson);
}