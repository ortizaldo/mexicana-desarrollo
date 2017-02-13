<?php require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

if (isset($_POST['p_agencia'])) {
   $p_agencia = $_POST['p_agencia'];

    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana,true);
    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;

$nuSoapClientMexicana->timeout = 0;
$nuSoapClientMexicana->response_timeout = 70000;
    $postData = array(
        'p_agencia' => $p_agencia
    );
    
    $resultWsDirecciones = $nuSoapClientMexicana->call('ws_cmc_direcciones_agencia', $postData);

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