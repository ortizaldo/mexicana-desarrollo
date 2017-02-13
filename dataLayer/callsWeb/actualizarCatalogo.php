<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
if (isset($_POST['description'])) {
    $description=$_POST['description'];
    $dia=$_POST['dia'];
    $tipoCatalogoReg=$_POST['tipoCatalogoReg'];
    switch (intval($tipoCatalogoReg)) {
        case 3:
            $insertCatalogoSQL="INSERT INTO catAnomalias(descAnomalia) VALUES(?)";
            break;
        case 4:
            $insertCatalogoSQL="INSERT INTO catCenso(nivelVivienda, created_at, updated_at) VALUES(?, NOW(), NOW())";
            break;
        case 5:
            $insertCatalogoSQL="INSERT INTO catInstalacion(materialnstalacion, created_at, updated_at) VALUES(?, NOW(), NOW())";
            break;
        case 6:
            $insertCatalogoSQL="INSERT INTO catMedidor(medidorDesc) VALUES(?)";
            break;
        case 7:
            $insertCatalogoSQL="INSERT INTO catMotivosDesinteres(motivo, created_at, updated_at) VALUES(?, NOW(), NOW())";
            break;
        case 8:
            $insertCatalogoSQL="INSERT INTO catPlomeria(tuberia, created_at, updated_at) VALUES(?, NOW(), NOW())";
            break;
        case 9:
            $insertCatalogoSQL="INSERT INTO diasNoLaborales(descripcion,dia) VALUES(?,?)";
            break;
    }
    $DB = new DAO();
    $conn = $DB->getConnect();
    if ($stmt = $conn->prepare($insertCatalogoSQL)) {
        if (intval($tipoCatalogoReg) == 9) {
            $stmt->bind_param("ss", $description, $dia);
        }else{
            $stmt->bind_param("s", $description);
        }
        
        if ($stmt->execute()) {
            $result["status"] = "OK";
            $result["code"] = "200";
            echo json_encode($result);
        }else{
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "Ocurrio un problema al actualizar el catalogo ".$conn->error;
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
    $result["result"] = "Hat algunos datos vacios";
    echo json_encode($result);
}
