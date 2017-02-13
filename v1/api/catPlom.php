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
                $catPlomSQL = "SELECT idCatPlom,idMedidor, tuberia FROM catPlomeria;";
                if ($catPlom = $conn->prepare($catPlomSQL)) {
                    //devolvemos la respuesta
                    if ($catPlom->execute()) {
                        $catPlom->store_result();
                        $catPlom->bind_result($idCatPlom, $idMedidor, $tuberia);
                        $cont=0;
                        while ($catPlom->fetch()) {
                            $requests[$cont]["id"] = $idCatPlom;
                            $requests[$cont]["desc"] = $tuberia;
                            $cont++;
                        }
                    }
                }
                $info["plomeria"] = $requests;
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

function getMarcaMedidor($idMedidor)
{
    //generamos una consulta para obtener id
    if ($idMedidor != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getMarcaMedSQL = "SELECT medidorDesc  FROM catMedidor WHERE idMedidor = $idMedidor";
        $result = $conn->query($getMarcaMedSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                if ($row[0] != "") {
                    $res=$row[0];
                }
            }
        }
        $conn->close();
    }
    return $res;
}