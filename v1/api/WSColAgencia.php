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
                    $dirColSQL = "SELECT 
                                            a.idUser,b.idAgencia,a.idMunicipio,b.coloniaId,b.nombre,b.clasificacion,b.clas_col
                                        FROM
                                            siscomAssignMun a,
                                            siscomColAgencia b
                                        WHERE
                                            0 = 0 
                                            AND a.idColonia = b.coloniaId
                                            AND a.idUser =?";
                    if ($connDirAg = $conn->prepare($dirColSQL)) {
                        $connDirAg->bind_param("i",$userID);
                        //devolvemos la respuesta
                        if ($connDirAg->execute()) {
                            $connDirAg->store_result();
                            $connDirAg->bind_result($idUser,$idAgencia,$idMunicipio,$coloniaId,$nombre,$clasificacion,$clas_col);
                            $cont=0;
                            while ($connDirAg->fetch()) {
                                $requests[$cont]["idmunicipio"] = $idMunicipio;
                                $requests[$cont]["idcolonia"] = $coloniaId;
                                $requests[$cont]["nombre"] = $nombre;
                                $requests[$cont]["clasificacion"] = $clasificacion;
                                $requests[$cont]["clas_col"] = $clas_col;
                                $cont++;
                            }
                        }
                    }
                    $info["ot_coloniasRow"] = $requests;
                    $requests = null;
                    $response["descripcion_error"] = "Exito";
                    $response["code"] = "200";
                    $response["ot_colonias"] = $info;
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
    function getUserNameEmp($IDUser)
    {
        if ($IDUser != "") {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $stmtUser = "SELECT 
                              nickname
                          FROM
                              user
                          WHERE 0=0
                          AND id= ? ;";
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