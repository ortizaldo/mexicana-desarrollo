<?php require_once('../libs/nusoap_lib/nusoap.php');
$todos = $_POST["todos"];
$colonia = $_POST["colonia"];
$municipio = $_POST["municipio"];
//echo "count ".count($_POST['datosDir']);
//var_dump($_POST['datosDir']);
//die();
if ($todos == 1) {
    if (count($_POST['datosDir']) > 0) {
        $datosDir = $_POST['datosDir'];
        $cont = 0;
        foreach ($datosDir as $key => $value) {
            if (cont == 0) {
                $resStatusDir = getStatusDireccion($value["id_direccion"]);
                $requests[$cont]["id_direccion"] = $value["id_direccion"];
                $requests[$cont]["calle"] = $value["calle"];
                $requests[$cont]["numero_exterior"] = $value["numero_exterior"];
                $requests[$cont]["entre_calles"] = $value["entre_calles"];
                $requests[$cont]["statusDireccion"] = $resStatusDir;
                $requests[$cont]["colonia"] = $colonia;
                $requests[$cont]["municipio"] = $municipio;
            }
            $cont++;
        }
        echo json_encode($requests);
    } else {
        // "result":"0","p_clasificacion":"0","descripcion_error":"Exito"}
        $responseJson = array(
            'result' => 0,
            'descripcion_error' => 'No se ingreso correctamente el id del municipio o colonia',
            'ot_colonias' => ''

        );
        echo json_encode($responseJson);
    }
}elseif ($todos == 0) {
    if (count($_POST['datosDir']) > 0) {
        $datosDir = $_POST['datosDir'];
        $resStatusDir = getStatusDireccion($datosDir["id_direccion"]);
        $requests[0]["id_direccion"] = $datosDir["id_direccion"];
        $requests[0]["calle"] = $datosDir["calle"];
        $requests[0]["numero_exterior"] = $datosDir["numero_exterior"];
        $requests[0]["entre_calles"] = $datosDir["entre_calles"];
        $requests[0]["statusDireccion"] = $resStatusDir;
        echo json_encode($requests);
    } else {
        // "result":"0","p_clasificacion":"0","descripcion_error":"Exito"}
        $responseJson = array(
            'result' => 0,
            'descripcion_error' => 'No se ingreso correctamente el id del municipio o colonia',
            'ot_colonias' => ''

        );
        echo json_encode($responseJson);
    }
}
function getStatusDireccion($id_direccion)
{
    if (isset($id_direccion)) {
        //$p_codigo = 189635;
        $p_codigo = "";
        $clienteSoapMexicana = "http://111.111.111.3/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:produccion";
        $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
        $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.3/wsa/wsa1/";
        $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
        $nuSoapClientMexicana->decode_utf8 = false;
        $postData = array(
            'p_cia' => 1,
            'p_codigo' => $p_codigo,
            'p_nombre' => $id_direccion
        );
        $resultWsColonias = $nuSoapClientMexicana->call('ws_consulta_estatus_cliente', $postData);
        //echo $nuSoapClientMexicana->request;
        if ($nuSoapClientMexicana->fault) {

        } else {
            $err = $nuSoapClientMexicana->getError();
        }
        if ($err) {
            echo json_encode($err);
        } else {
            $res = "";
            $cont = 0;
            foreach ($resultWsColonias as $key => $value) {
                if ($key == "p_mensaje") {
                    $res = $value;
                }
            }
        }
    }
    return $res;
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