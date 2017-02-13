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
                    $catAnomaliasSQL = "SELECT idAnomalia, descAnomalia FROM catAnomalias;";
                    if ($catAnomalias = $conn->prepare($catAnomaliasSQL)) {
                        //devolvemos la respuesta
                        if ($catAnomalias->execute()) {
                            $catAnomalias->store_result();
                            $catAnomalias->bind_result($idAnomalia, $descAnomalia);
                            $cont=0;
                            while ($catAnomalias->fetch()) {
                                $requests[$cont]["id"] = $idAnomalia;
                                $requests[$cont]["desc"] = $descAnomalia;
                                $cont++;
                            }
                        }
                    }
                    $info["anomalias"] = $requests;
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