<?php

//echo getIdCity('SAN NICOLAS');


function getIdCity($city)
{
    require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;

    $postData = array();
    $resultWsMunicipios = $nuSoapClientMexicana->call('ws_siscom_municipios', $postData);

    if ($nuSoapClientMexicana->fault) {

    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        echo json_encode($err);

    } else {
        $municipios = $resultWsMunicipios ["ot_municipios"]["ot_municipiosRow"];
        foreach ($municipios as $municipio) {
            if ($municipio["nombre"] == $city) {
                return $municipio["idMunicipio"];
            }
        }
    }
}