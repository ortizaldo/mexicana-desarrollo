<?php

ini_set('default_socket_timeout', 120);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
date_default_timezone_set('America/Mexico_City');
//ini_set('display_errors', '1');
$starttime = microtime(true);
$horaIni = date("H:i:s",$starttime);
error_log('message time start: '.$horaIni);
/* do stuff here */
require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
$getDataAgencys = "SELECT 
                       a.nickname, b.id
                       FROM
                       user a, agency b, agency_profile d
                       WHERE 0=0
                       and a.id=b.idUser
                       and b.id=d.idAgency
                       and d.idProfile > 2
                       -- and a.nickname like '%mago%'";
$result = $conn->query($getDataAgencys);
$res=[];
    
if ($result->num_rows > 0) {
    while($row = $result->fetch_array()) {
        $res['agencia']= $row[0];
        $res['idAgencia']= $row[1];
        $res['direccionesAgencia']= getDatosCol($row[0], $row[1]);
    }
}
$response["status"] = "EXITO";
$response["code"] = "200";
$response["response"] = $res;
function getDatosCol($p_agencia, $IDAgencia)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    error_log('message '.$p_agencia);
    if (isset($p_agencia)) {
        $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
        $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
        $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
        $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
        $nuSoapClientMexicana->decode_utf8 = false;
        $postData = array(
            'p_agencia' => $p_agencia
        );
        $resultWsDirecciones = $nuSoapClientMexicana->call('ws_siscom_colonias_agencia', $postData);
        if ($nuSoapClientMexicana->fault) {
        } else {
            $err = $nuSoapClientMexicana->getError();
        }
        if ($err) {
            return $err;
        } else {
            //echo json_encode($resultWsDirecciones);
            $insert;
            $contador=0;
            if (is_array($resultWsDirecciones) && array_key_exists('ot_colonias', $resultWsDirecciones)) {
                if (is_array($resultWsDirecciones['ot_colonias']) && array_key_exists('ot_coloniasRow', $resultWsDirecciones['ot_colonias'])) {
                    foreach ($resultWsDirecciones['ot_colonias'] as $key => $colonia) {
                        foreach ($colonia as $keyD => $value) {
                            //if ($contador == 0) {
                                $insert.=generarInsert($IDAgencia, 
                                                  $value["clas_col"], 
                                                  $value["clasificacion"], 
                                                  $value["idcolonia"], 
                                                  $value["idmunicipio"],
                                                  $value["nombre"]);
                            //}
                            $contador++;
                        }
                        $contador++;
                    }
                }else{
                    error_log('message '.$p_agencia." no tiene direcciones asignadas");
                }
            }
            insertarColonias($insert, $IDAgencia);
        }
    } else {
        $responseJson = array(
            'result' => 0,
            'descripcion_error' => 'No se ingreso correctamente la informacion de entrada',
            'ot_direcciones' => 'noData'
        );
        echo json_encode($responseJson);
    }
    $endtime = microtime(true);
    $timediff = date("H:i:s",$endtime-$starttime);
    error_log('message time elappsed: '.$timediff);
}

function insertarColonias($sql, $idAgency)
{
    if ((isset($sql) || $sql != "") &&
        (isset($idAgency) || $idAgency != "")) {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getDataAgencys = "SELECT 
                               count(*) as contadorDir
                               FROM
                               siscomColAgencia
                               WHERE 0=0
                               and idAgencia = $idAgency";
        $result = $conn->query($getDataAgencys);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                if (intval($row[0]) > 0) {
                    //borramos los datos de la tabla correspondientes al id de la agencia
                    $stmtDelFirst="DELETE FROM siscomColAgencia WHERE idAgencia=?";
                    if ($delDirAgency = $conn->prepare($stmtDelFirst)) {
                        $delDirAgency->bind_param("i",$idAgency);
                        if ($delDirAgency->execute()) {
                            error_log("registros eliminados");
                            if ($conn->multi_query($sql) === TRUE) {
                                error_log("New records created successfully");
                            } else {
                                error_log("Error: " . $sql . "<br>" . $conn->error);
                            }
                        }
                    }else{
                        error_log($conn->error);
                    }
                }elseif (intval($row[0]) == 0) {
                    if ($conn->multi_query($sql) === TRUE) {
                        error_log("New records created successfully");
                    } else {
                        error_log("Error: " . $sql . "<br>" . $conn->error);
                    }
                }
            }
        }
    } 
}

function generarInsert($IDAgencia, 
                      $clas_col, 
                      $clasificacion, 
                      $idcolonia, 
                      $idmunicipio,
                      $nombre)
{
    $stmtInsert;
    //validamos que la tabla no tenga datos de la agencia a la cual se le esta insertando
    if ((isset($IDAgencia) || $IDAgencia != "") &&
        (isset($idcolonia) || $idcolonia != "") &&
        (isset($idmunicipio) || $idmunicipio != "")) {
        $stmtInsert ="INSERT INTO siscomColAgencia(idAgencia,idMunicipio,coloniaId,nombre,clasificacion,clas_col,updated_at,created_at)
                            VALUES($IDAgencia,$idmunicipio, $idcolonia, '".$nombre."', '".$clasificacion."', '".$clas_col."', NOW(),NOW());";
        return $stmtInsert;
    }
}
    