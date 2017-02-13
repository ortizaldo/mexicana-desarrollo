<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
if (isset($_POST['idDirAssign'])) {
    $idDirAssign=$_POST['idDirAssign'];
    $DB = new DAO();
    $conn = $DB->getConnect();
    $deleteCatalogoSQL="DELETE FROM siscomAssignMun where id=?";
    if ($deleteCatalogo = $conn->prepare($deleteCatalogoSQL)) {
        $deleteCatalogo->bind_param("i", $idDirAssign);
        if ($deleteCatalogo->execute()) {
            $result["status"] = "OK";
            $result["code"] = "200";
            $result["result"] = "El registro se elimino correctamente";
        }else{
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "Error de Base De Datos, ".$conn->error;
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "Error de Base De Datos, ".$conn->error;
    }
    echo json_encode($result);
}else{
    $result["status"] = "BAD";
    $result["code"] = "500";
    $result["result"] = "Hat algunos datos vacios";
    echo json_encode($result);
}
