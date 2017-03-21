<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

$agencia = $_POST["agencia"];
$calle = $_POST["calle"];
$entre_calles = $_POST["entre_calles"];
$id_direccion = $_POST["id_direccion"];
$numero_exterior = $_POST["numero_exterior"];
$mun = $_POST["mun"];
$col = $_POST["col"];
$status = $_POST["status"];

$idSolicitud = $_POST['idSolicitud'];
$comentarios = $_POST['comentarios'];
$fechaSol = $_POST['fechaSol'];
$duracion = $_POST['duracion'];
$tipoSentencia = $_POST['tipoSentencia'];

$res = validaSiDirSolicitada($id_direccion);
if ($res == true) {
    $result["status"] = "OK";
    $result["code"] = "500";
    $result["result"] = "La direccion ya ha sido solicitada";
    echo json_encode($result);
}else{
    if ($tipoSentencia != "update") {
        $insertCatalogoSQL="INSERT INTO solicitudLibDir(agencia,calle,entreCalles,num,colonia,mun,idDireccion,fechaSol,estatusDir,estatus,modified_at,created_at) VALUES(?,?,?,?,?,?,?,NOW(),?,0, NOW(), NOW())";
    }elseif ($tipoSentencia == "update") {
        $insertCatalogoSQL="UPDATE solicitudLibDir SET estatus = 1, comentarios=?, tiempoLib=?, fechaLib=?,modified_at = NOW() WHERE idsolicitud=?";
    }
    $DB = new DAO();
    $conn = $DB->getConnect();
    if ($stmt = $conn->prepare($insertCatalogoSQL)) {
        if ($tipoSentencia != "update") {
            $stmt->bind_param("ssssssis", $agencia, $calle, $entre_calles,$numero_exterior, $col, $mun, $id_direccion, $status);
        }elseif ($tipoSentencia == "update") {
            $stmt->bind_param("sssi", $comentarios, $duracion,$fechaSol, $idSolicitud);
        }
        if ($stmt->execute()) {
            $result["status"] = "OK";
            $result["code"] = "200";
            if ($tipoSentencia != "update") {
                $result["result"] = "La direccion se solicito correctamente";
            }elseif ($tipoSentencia == "update") {
                $result["result"] = "La direccion se libero correctamente";
            }
            echo json_encode($result);
        }else{
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "Ocurrio un problema al actualizar el catalogo ".$stmt->error;
            echo json_encode($result);
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "Error de Base De Datos, Favor de comunicarse con el Administrador ".$conn->error;
        echo json_encode($result);
    }
}

function validaSiDirSolicitada($idDir)
{
    if (isset($idDir)) {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $res = false;
        $getCountMaterial = "SELECT 
                            count(*) as contadorDir
                            FROM
                            solicitudLibDir
                            WHERE
                            idDireccion = $idDir;";
        $contadorMaterial = 0;
        $result = $conn->query($getCountMaterial);
        while( $row = $result->fetch_array() ) {
            if (intval($row[0]) > 0) {
                $res = true;
            }
        }
    }
    return $res;
}
