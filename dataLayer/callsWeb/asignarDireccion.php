<?php 
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
session_start();
if (isset($_POST['empleadoSis'])) {
    $municipioSis= $_POST["municipioSis"];
    $coloniaSis= $_POST["coloniaSis"];
    $empleadoSis= $_POST["empleadoSis"];
    $agency=strtoupper($_SESSION["nickname"]);
    $IDAgency=getIdAgency($agency);
    $contador=validateMunicipio($coloniaSis, $municipioSis, $empleadoSis);
    if(intval($contador) == 0 && $IDAgency != ""){
        $insertCatalogoSQL="INSERT INTO siscomAssignMun(idAgencia,idUser,idMunicipio,idColonia, created_at, updated_at) VALUES(?,?,?,?, NOW(), NOW())";
        $DB = new DAO();
        $conn = $DB->getConnect();
        if ($stmt = $conn->prepare($insertCatalogoSQL)) {
            $stmt->bind_param("iiii", $IDAgency, $empleadoSis, $municipioSis, $coloniaSis);
            if ($stmt->execute()) {
                $result["status"] = "OK";
                $result["code"] = "200";
                echo json_encode($result);
            }else{
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = "Ocurrio un problema al asignar la direccion ".$conn->error;
                echo json_encode($result);
            }
        }else{
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "Error de Base De Datos, Favor de comunicarse con el Administrador ".$conn->error;
            //printf("Errormessage: %s\n", $conn->error);
            echo json_encode($result);
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "Esta colonia ya se encuentra asignada al Empleado que esta seleccionando.. ".$contador;
        //printf("Errormessage: %s\n", $conn->error);
        echo json_encode($result);
    }
}else{
    $result["status"] = "BAD";
    $result["code"] = "500";
    $result["result"] = "Hat algunos datos vacios";
    echo json_encode($result);
}
function validateMunicipio($colonia, $idMunicipio, $empleadoSis)
{
    if ($colonia != "" && $idMunicipio != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $stmtValidate = "SELECT 
                                  count(*) as contadorColonia
                              FROM
                                  siscomAssignMun
                              WHERE
                                  idColonia=? and 
                                  idMunicipio=? and
                                  idUser=?;";
        $contador=0;
        if ($connDir = $conn->prepare($stmtValidate)) {
            $connDir->bind_param("iii",$colonia, $idMunicipio, $empleadoSis);
            //devolvemos la respuesta
            if ($connDir->execute()) {
                $connDir->store_result();
                $connDir->bind_result($contadorColonia);
                
                $cont=0;
                while ($connDir->fetch()) {
                    $contador = intval($contadorColonia);
                    $cont++;
                }
            }else{
                echo "error ". $conn->error;
            }
        }else{
            echo "error ". $conn->error;
        }
        return $contador;
    }
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
