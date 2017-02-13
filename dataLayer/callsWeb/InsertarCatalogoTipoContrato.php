<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
if (isset($_POST['articulo']) &&
    isset($_POST['description']) &&
    isset($_POST['Precio']) &&
    isset($_POST['plazos']) &&
    isset($_POST['pagos'])) {
    $idArticulo=$_POST['idArticulo'];
    $articulo=$_POST['articulo'];
    $financ=$_POST['financ'];
    $description=$_POST['description'];
    $Precio=$_POST['Precio'];
    $plazos=$_POST['plazos'];
    $pagos=$_POST['pagos'];
    $DB = new DAO();
    $conn = $DB->getConnect();
    $searchMaxContratoSQL = "SELECT idContrato FROM catalogoTiposDeContrato WHERE idContrato=?;";
    if ($searchMaxContrato = $conn->prepare($searchMaxContratoSQL)) {
        $searchMaxContrato->bind_param("i", $idArticulo);
        if ($searchMaxContrato->execute()) {
            $searchMaxContrato->store_result();
            $searchMaxContrato->bind_result($maxContrato);
            $searchMaxContrato->fetch();
            if(intval($maxContrato) > 0){
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = "Ya existe un contrato con el mismo ID articulo";
            }else{
                $querySmt = "INSERT INTO catalogoTiposDeContrato(idContrato,claveArticuloContrato,financiamiento,tipoDeContrato,precio,plazo,
                    pagos,fechaAlta,fechaMod)
                    VALUES(?,?,?,?,?,?,?,NOW(),NOW())";
                if ($stmt = $conn->prepare($querySmt)) {
                    $stmt->bind_param("isssdss", $idArticulo, $articulo, $financ,$description, $Precio, $plazos,$pagos);
                    if ($stmt->execute()) {
                        $result["status"] = "OK";
                        $result["code"] = "200";
                        $result["result"] = "Tipo de contrato guardado correctamente";
                    }else{
                        $result["status"] = "BAD";
                        $result["code"] = "500";
                        $result["result"] = "Ocurrio un problema al guardar el Tipo de Contrato";
                    }
                }else{
                    $result["status"] = "BAD";
                    $result["code"] = "500";
                    $result["result"] = "Error de Base De Datos, Favor de comunicarse con el Administrador, ".$conn->error;
                    //printf("Errormessage: %s\n", $conn->error);
                }
            }
        }else{
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "Error de Base De Datos, ".$searchMaxContrato->error;
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "Error de Base De Datos, ".$searchMaxContrato->error;
    }
    echo json_encode($result);
}else{
    $result["status"] = "BAD";
    $result["code"] = "500";
    $result["result"] = "Hat algunos datos vacios";
    echo json_encode($result);
}
