<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
    if (isset($_POST["token"]) ){
        $DB = new DAO();
        $conn = $DB->getConnect();
        $token = $_POST["token"];
        $token = base64_decode($token);
        list($id, $username, $password, $rol, $value, $idDevice) = explode("&", $token);
        $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
        $searchToken->bind_param('i', $id);

        if ($searchToken->execute()) {
            $searchToken->store_result();
            $searchToken->bind_result($userToken);
            if ($searchToken->fetch()) {
                if ($_POST["token"] == $userToken) {
                    //consultamos el catalogo de vivienda
                    $catContratosSQL = "SELECT idContrato,tipoDeContrato,precio,plazo,pagos
                                       FROM catalogoTiposDeContrato;";
                    if ($catContratos = $conn->prepare($catContratosSQL)) {
                        //devolvemos la respuesta
                        if ($catContratos->execute()) {
                            $catContratos->store_result();
                            $catContratos->bind_result($idContrato,$tipoDeContrato,$precio,$plazo,$pagos);
                            $cont=0;
                            while ($catContratos->fetch()) {
                                $requests[$cont]['idArticulo']= $idContrato;
                                $requests[$cont]['desc']= $tipoDeContrato;
                                $requests[$cont]['precio']= $precio;
                                $requests[$cont]['plazo']= $plazo;
                                $requests[$cont]['pagos']= $pagos;
                                $cont++;
                            }
                        }
                    }
                    $info["contratos"] = $requests;
                    $requests = null;
                    $response["status"] = "OK";
                    $response["code"] = "200";
                    $response["response"] = $info;
                    echo json_encode($response);
                }else{
                    $response["status"] = "ERROR";
                    $response["code"] = "404";
                    $response["response"] = "Error en el token";
                    echo json_encode($response);
                }
            }
        }
        $conn->close();
    }