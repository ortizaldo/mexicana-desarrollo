<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
if (isset($_POST['idCatalog'])) {
    $idCatalogo=$_POST['idCatalog'];
    $tipoCatalogo=$_POST['tipoCatalogo'];
    $DB = new DAO();
    $conn = $DB->getConnect();
    switch (intval($tipoCatalogo)) {
        case 3:
            $deleteCatalogoSQL="DELETE FROM catAnomalias where idAnomalia=?";
            break;
        case 4:
            $deleteCatalogoSQL="DELETE FROM catCenso where idCatCenso=?";
            break;
        case 5:
            $deleteCatalogoSQL="DELETE FROM catInstalacion where idCatInst=?";
            break;
        case 6:
            $deleteCatalogoSQL="DELETE FROM catMedidor where idMedidor=?";
            break;
        case 7:
            $deleteCatalogoSQL="DELETE FROM catMotivosDesinteres where idMotivo=?";
            break;
        case 8:
            $deleteCatalogoSQL="DELETE FROM catPlomeria where idCatPlom=?";
            break;
        case 9:
            $deleteCatalogoSQL="DELETE FROM diasNoLaborales where id=?";
            break;
    }
    if ($deleteCatalogo = $conn->prepare($deleteCatalogoSQL)) {
        $deleteCatalogo->bind_param("i", $idCatalogo);
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
