<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
if (isset($_POST['idContrato'])) {
    $idContrato=$_POST['idContrato'];
    $DB = new DAO();
    $conn = $DB->getConnect();
    $deleteContratoSQL = "DELETE FROM catalogoTiposDeContrato WHERE idCatalogoTiposDeContrato=?;";
    if ($deleteContrato = $conn->prepare($deleteContratoSQL)) {
        $deleteContrato->bind_param("i", $idContrato);
        if ($deleteContrato->execute()) {
            $result["status"] = "OK";
            $result["code"] = "200";
            $result["result"] = "El Tipo de contrato se elimino correctamente";
        }else{
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "Error de Base De Datos, ".$deleteContrato->error;
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "Error de Base De Datos, ".$deleteContrato->error;
    }
    echo json_encode($result);
}else{
    $result["status"] = "BAD";
    $result["code"] = "500";
    $result["result"] = "Hat algunos datos vacios";
    echo json_encode($result);
}
