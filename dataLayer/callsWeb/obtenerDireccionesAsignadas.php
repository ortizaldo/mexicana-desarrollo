<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
session_start();
$agency=strtoupper($_SESSION["nickname"]);
$IDAgency=getIdAgency($agency);
if ($IDAgency != "") {
    //consultamos el catalogo de vivienda
    $obtenerDirAssign = "SELECT id,idAgencia,idUser,idMunicipio,idColonia FROM siscomAssignMun where idAgencia=?;";
    if ($connDir = $conn->prepare($obtenerDirAssign)) {
        //devolvemos la respuesta
        $connDir->bind_param("i",$IDAgency);
        if ($connDir->execute()) {
            $connDir->store_result();
            $connDir->bind_result($id, $idAgencia, $idUser, $idMunicipio, $idColonia);
            $cont=0;
            while ($connDir->fetch()) {
                $requests[$cont]["idDirAssign"] = $id;
                $requests[$cont]["idAgencia"] = $idAgencia;
                $requests[$cont]["nickNameAgency"] = $agency;
                $requests[$cont]["idUser"] = $idUser;
                $requests[$cont]["userName"] = getUserName($idUser);
                $requests[$cont]["idMunicipio"] = $idMunicipio;
                $requests[$cont]["nombreMunicipio"] = getMunicipio($idMunicipio);
                $requests[$cont]["idColonia"] = $idColonia;
                $requests[$cont]["nombreColonia"] = getColonia($idColonia);
                $cont++;
            }
            $info["respuesta"] = $requests;
            $requests = null;
            $response["status"] = "OK";
            $response["code"] = "200";
            $response["response"] = $info;
        }
    }
    echo json_encode($response);
    $conn->close();
}
function getIdAgency($nickname)
{
    if ($nickname != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $test_string = '%' . $nickname . '%';
        $stmtAgency = "SELECT 
                                  a.id
                              FROM
                                  user a,
                                  agency b
                              WHERE
                                  a.id = b.idUser 
                                  AND UPPER(a.nickname) LIKE ? ;";
        $id_agencia="";
        if ($connDir = $conn->prepare($stmtAgency)) {
            $connDir->bind_param("s",$test_string);
            //devolvemos la respuesta
            if ($connDir->execute()) {
                $connDir->store_result();
                $connDir->bind_result($idAgencia);
                $cont=0;
                while ($connDir->fetch()) {
                    $id_agencia = $idAgencia;
                    $cont++;
                }
            }
        }
        return $id_agencia; 
    }
}

function getUserName($idUser)
{
    if ($idUser != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $test_string = '%' . $nickname . '%';
        $stmtUser = "SELECT 
                          nickname
                      FROM
                          user
                      WHERE
                          id = ? ;";
        $userName="";
        if ($connDir = $conn->prepare($stmtUser)) {
            $connDir->bind_param("i",$idUser);
            //devolvemos la respuesta
            if ($connDir->execute()) {
                $connDir->store_result();
                $connDir->bind_result($nickname);
                $cont=0;
                while ($connDir->fetch()) {
                    $userName = $nickname;
                    $cont++;
                }
            }
        }
        return $userName; 
    }
}

function getColonia($idCol)
{
    if ($idCol != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $stmtCol = "SELECT 
                            nombre
                        FROM
                            siscomColAgencia
                        WHERE
                            coloniaId = ?;";
        $nombreColonia="";
        if ($connDir = $conn->prepare($stmtCol)) {
            $connDir->bind_param("i",$idCol);
            //devolvemos la respuesta
            if ($connDir->execute()) {
                $connDir->store_result();
                $connDir->bind_result($nombreCol);
                $cont=0;
                while ($connDir->fetch()) {
                    $nombreColonia = $nombreCol;
                    $cont++;
                }
            }
        }
        return $nombreColonia; 
    }
}

function getMunicipio($idMun)
{
    if ($idMun != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        require_once('../libs/nusoap_lib/nusoap.php');

        $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";

        $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
        $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
        $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
        $nuSoapClientMexicana->decode_utf8 = false;
        $postData = array();
        $resultWsMunicipios = $nuSoapClientMexicana->call('ws_siscom_municipios', $postData);
        $nombreMunicipio="";
        if ($nuSoapClientMexicana->fault) {

        } else {
            $err = $nuSoapClientMexicana->getError();
        }
        if ($err) {
        } else {
            if (is_array($resultWsMunicipios) && array_key_exists('ot_municipios', $resultWsMunicipios)) {
                if (is_array($resultWsMunicipios['ot_municipios']) && array_key_exists('ot_municipiosRow', $resultWsMunicipios['ot_municipios'])) {
                    foreach ($resultWsMunicipios['ot_municipios'] as $key => $municipio) {
                        foreach ($municipio as $keyD => $value) {
                            if (intval($value["idMunicipio"] == intval($idMun))) {
                                $nombreMunicipio=$value["nombre"];
                            }
                            $contador++;
                        }
                        $contador++;
                    }
                }else{
                    error_log('message '.$p_agencia." no tiene Municipios asignadas");
                }
            }
            //var_dump($resultWsMunicipios, true);
        }
        return $nombreMunicipio; 
    }
}