<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
if (isset($_POST['tipoSentencia'])) {
    $tipoSentencia=$_POST['tipoSentencia'];
    if ($tipoSentencia == 'insert') {
        $idFI=$_POST['idFI'];
        $qty=$_POST['qty'];
        $material=$_POST['material'];
        $resCountMaterial = validaMaterialExistente($idFI, $material);
        if (intval($resCountMaterial) > 0) {
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "El material ya ha sido capturado..";
            //printf("Errormessage: %s\n", $conn->error);
            echo json_encode($result);
        }else{
            switch ($tipoSentencia) {
                case 'update':
                    $idFI=$_POST['idFI'];
                    $serialNumber=$_POST['serialNumber'];
                    $measurement=$_POST['measurement'];
                    $insertCatalogoSQL="UPDATE form_installation SET serialNumber=?, measurement=? where id=?";
                    break;
                case 'insert':
                    $insertCatalogoSQL="INSERT INTO form_installation_details(qty,idFormInstallation,material, created_at, updated_at) VALUES(?,?,?, NOW(), NOW())";
                    break;
                case 'delete':
                    $idFI=$_POST['idFI'];
                    $insertCatalogoSQL="DELETE FROM form_installation_details where id=?";
                    break;
            }
            $DB = new DAO();
            $conn = $DB->getConnect();
            if ($stmt = $conn->prepare($insertCatalogoSQL)) {
                if ($tipoSentencia == 'update') {
                    $stmt->bind_param("ssi", $serialNumber, $measurement, $idFI);
                }elseif ($tipoSentencia == 'insert') {
                    $stmt->bind_param("sis", $qty, $idFI, $material);
                }elseif ($tipoSentencia == 'delete') {
                    $stmt->bind_param("i", $idFI);
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
        }
    }else{
        switch ($tipoSentencia) {
            case 'update':
                $idFI=$_POST['idFI'];
                $serialNumber=$_POST['serialNumber'];
                $measurement=$_POST['measurement'];
                $insertCatalogoSQL="UPDATE form_installation SET serialNumber=?, measurement=? where id=?";
                break;
            case 'insert':
                $insertCatalogoSQL="INSERT INTO form_installation_details(qty,idFormInstallation,material, created_at, updated_at) VALUES(?,?,?, NOW(), NOW())";
                break;
            case 'delete':
                $idFI=$_POST['idFI'];
                $insertCatalogoSQL="DELETE FROM form_installation_details where id=?";
                break;
        }
        $DB = new DAO();
        $conn = $DB->getConnect();
        if ($stmt = $conn->prepare($insertCatalogoSQL)) {
            if ($tipoSentencia == 'update') {
                $stmt->bind_param("ssi", $serialNumber, $measurement, $idFI);
            }elseif ($tipoSentencia == 'insert') {
                $stmt->bind_param("sis", $qty, $idFI, $material);
            }elseif ($tipoSentencia == 'delete') {
                $stmt->bind_param("i", $idFI);
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
    }
}else{
    $result["status"] = "BAD";
    $result["code"] = "500";
    $result["result"] = "Hat algunos datos vacios";
    echo json_encode($result);
}

function validaMaterialExistente($idFI, $material)
{
    if (isset($idFI) && isset($material)) {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getCountMaterial = "SELECT 
                            count(*) as contadorMaterial
                            FROM
                            form_installation_details
                            WHERE
                            idFormInstallation = $idFI
                            AND material LIKE '%".$material."%';";
        $contadorMaterial = 0;
        $result = $conn->query($getCountMaterial);
        while( $row = $result->fetch_array() ) {
            $contadorMaterial = $row[0];
        }
    }
    return $contadorMaterial;
}
