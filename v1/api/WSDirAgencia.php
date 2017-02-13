<?php 
include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
    if (isset($_POST["token"]) ){
        $DB = new DAO();
        $conn = $DB->getConnect();
        $token = $_POST["token"];
        $token = base64_decode($token);
        list($id, $username, $password, $rol, $value, $idDevice) = explode("&", $token);
        $searchToken = $conn->prepare("SELECT `user`.`token`, `user`.`id` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
        $searchToken->bind_param('i', $id);

        if ($searchToken->execute()) {
            $searchToken->store_result();
            $searchToken->bind_result($userToken, $userID);
            if ($searchToken->fetch()) {
                if ($_POST["token"] == $userToken) {
                    //consultamos el catalogo de vivienda
                    $nicknameAgencia = getUserAgency($userID);
                    $getReportStatus = "SELECT 
                                        a.idUser,
                                        c.idAgencia,
                                        c.idDireccion,
                                        c.idMunicipio,
                                        c.idColonia,
                                        c.calle,
                                        c.entreCalles,
                                        c.numero
                                        FROM
                                        siscomAssignMun a, 
                                        direccionesAgenciaCSV c
                                        WHERE
                                        0 = 0 
                                        AND a.idColonia = c.idColonia
                                        AND a.idUser = $userID
                                        AND c.nombreAgencia = '".$nicknameAgencia."';";
                    $result = $conn->query($getReportStatus);
                    $cont=0;
                    while( $row = $result->fetch_array() ) {
                        $requests[$cont]["id_direccion"] = $row[2];
                        $requests[$cont]["id_municipio"] = $row[3];
                        $requests[$cont]["colonia_id"] = $row[4];
                        $requests[$cont]["calle"] = $row[5];
                        $requests[$cont]["entre_calles"] = $row[6];
                        $requests[$cont]["numero_exterior"] = $row[7];
                        $cont++;
                    }

                    $info["ot_direccionesRow"] = $requests;
                    $requests = null;
                    $response["descripcion_error"] = "Exito";
                    $response["code"] = "200";
                    $response["ot_direcciones"] = $info;
                    echo json_encode($response);
                }else{
                    $response["descripcion_error"] = "Error";
                    $response["code"] = "404";
                    $response["ot_direcciones"] = "Error en el token";
                    echo json_encode($response);
                }
            }
        }
        $conn->close();
    }
    function getUserName($idAgencia)
    {
        if ($idAgencia != "") {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $stmtUser = "SELECT 
                              a.nickname
                          FROM
                              user a,
                              agency b
                          WHERE 0=0
                          AND a.id=b.idUser
                          AND b.id= ? ;";
            $userName="";
            if ($connDir = $conn->prepare($stmtUser)) {
                $connDir->bind_param("i",$idAgencia);
                //devolvemos la respuesta
                if ($connDir->execute()) {
                    $connDir->store_result();
                    $connDir->bind_result($nickname);
                    $cont=0;
                    while ($connDir->fetch()) {
                        $userName = $nickname;
                        $cont++;
                    }
                }
            }
            return $userName; 
        }
    }
    function getUserAgency($IDUser)
    {
        if ($IDUser != "") {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $stmtUser = "SELECT 
                          (select f.nickname from user f, agency g where f.id=g.idUser and g.id=c.id) as nicknameAgencia
                        FROM
                          employee a,
                          agency_employee b,
                          agency c,
                          user d
                        WHERE 0=0
                        and a.id=b.idemployee
                        and b.idAgency=c.id
                        and d.id=a.idUser
                        AND d.id=? ;";
            $userName="";
            if ($connDir = $conn->prepare($stmtUser)) {
                $connDir->bind_param("i",$IDUser);
                //devolvemos la respuesta
                if ($connDir->execute()) {
                    $connDir->store_result();
                    $connDir->bind_result($nickname);
                    $cont=0;
                    while ($connDir->fetch()) {
                        $userName = $nickname;
                        $cont++;
                    }
                }
            }
            return $userName; 
        }
    }