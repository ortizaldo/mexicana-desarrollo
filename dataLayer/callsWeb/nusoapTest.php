<?php
require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');


$clienteSoapMexicana = "http://111.111.111.3/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
$nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
//$nuSoapClientMexicana->forceEndpoint = "http://111.111.111.3/wsa/wsa1/";
$nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
$nuSoapClientMexicana->soap_defencoding = 'UTF-8';
$nuSoapClientMexicana->decode_utf8 = false;
$starttime = microtime(true);
/* do stuff here */
error_log('message time starttime: '.date("H:i:s",$starttime));
$postData = array(
    'p_agencia' => "LUMBRERA"
);
$resultWsDirecciones = $nuSoapClientMexicana->call('ws_cmc_direcciones_agencia', $postData);
if ($nuSoapClientMexicana->fault) {
} else {
    $err = $nuSoapClientMexicana->getError();
    error_log($err);
}
if ($err) {
    return $err;
} else {
    error_log(json_encode($resultWsDirecciones));
    //insertarDirecciones($insert);
    $endtime = microtime(true);
    $timediff = date("H:i:s",$endtime-$starttime);
    error_log('message time elappsed: '.$timediff);
}