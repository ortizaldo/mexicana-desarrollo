<?php
/**
 * Created by PhpStorm.
 * User: softm
 * Date: 23/06/2016
 * Time: 05:40 PM
 */

require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

if (isset($_POST['p_colonia'])) {
    $p_colonia = $_POST['p_colonia'];
    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;

    $postData = array('p_colonia' => $p_colonia);
    $resultWsExclusividad = $nuSoapClientMexicana->call('ws_cmc_exclusividad', $postData);

    if ($nuSoapClientMexicana->fault) {

    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        echo json_encode($err);

    } else {
        echo json_encode($resultWsExclusividad);
    }
} else {
    // "result":"0","p_exclusivo":"0","descripcion_error":"Exito"}
    $responseJson = array(
        'result' => 0,
        'p_exclusivo' => 0,
        'descripcion_error' => 'No se ingreso correctamente el id de la colonia'
    );
    echo json_encode($responseJson);
}