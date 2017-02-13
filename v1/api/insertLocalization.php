<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
  //error_log('post '.json_encode($_POST));
  if ( isset($_POST["token"]) ) {
    $DB = new DAO();
    $conn = $DB->getConnect();

    $token = $_POST["token"];
    $token = base64_decode($token);

    list($id, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
    //$token = base64_encode($id . "&" . $nickname . "&" . $email . "&" . $profile . "&" . $nicknameAgency . "&" . $rol . "&" . $value . "&" . $idDevice);

    $searchToken = $conn->prepare("SELECT token FROM user WHERE id = ? AND active = 1;");
    $searchToken->bind_param('i', $id);
    if ($searchToken->execute()) {
        $searchToken->store_result();
        $searchToken->bind_result($userToken);

        if ($searchToken->fetch()) {
            if ( $_POST["token"] == $userToken ) {
                if ( isset($_POST["latitud"]) && isset($_POST["longitud"]) ) {
                    //validamos la latitud y longitud
                    $latitude = $_POST["latitud"];
                    $longitude = $_POST["longitud"];
                    $track = "SELECT start_latitude, start_longitude
                              FROM track 
                              WHERE 0=0 
                              AND idEmployee = $id 
                              AND start_latitude='".$latitude."'
                              AND start_longitude='".$longitude."';";
                    $result = $conn->query($track);
                    $res="";
                    //echo "query ".$track;
                    //echo "num_rows ".$result->num_rows;
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_array()) {
                            $start_latitude = $row[0];
                            $start_longitude = $row[1];
                            if ($start_latitude != $latitude && $start_longitude != $longitude) {
                                $sqlInsertTrack= "INSERT INTO track(`idEmployee`, `idDot`, `idPlace_from`, 
                                                                    `idPlace_to`, `start_latitude`,
                                                                    `start_longitude`, `created_at`, 
                                                                    `updated_at`, `active`)
                                                  VALUES(?, 0, 0, 0, ?, ?, NOW(), NOW(), 1);";
                                $resInsert=insertTrack($conn, $sqlInsertTrack,$id,$latitude, $longitude);
                                if ($resInsert) {
                                    $response["status"] = "OK";
                                    $response["code"] = "200";
                                    $response["response"] = "Localizacion Insertada Exitosamente";
                                    echo json_encode($response);
                                }
                            }else{
                                $response["status"] = "ERROR";
                                $response["code"] = "500";
                                $response["response"] = "Misma ubicacion.";
                                echo json_encode($response);
                            }
                        }
                    }else{
                       $sqlInsertTrack= "INSERT INTO track(`idEmployee`, `idDot`, `idPlace_from`, 
                                                                    `idPlace_to`, `start_latitude`,
                                                                    `start_longitude`, `created_at`, 
                                                                    `updated_at`, `active`)
                                                  VALUES(?, 0, 0, 0, ?, ?, NOW(), NOW(), 1);";
                        $resInsert=insertTrack($conn, $sqlInsertTrack,$id,$latitude, $longitude);
                        if ($resInsert) {
                            $response["status"] = "OK";
                            $response["code"] = "200";
                            $response["response"] = "Localizacion Insertada Exitosamente";
                            echo json_encode($response);
                        }
                    }
                }
            } else {
                $response["status"] = "ERROR";
                $response["code"] = "500";
                $response["response"] = "Error de permisos.";
                echo json_encode($response);
            }
        }
    }
 }  else {
    $response["status"] = "ERROR";
    $response["code"] = "500";
    $response["response"] = "Error al recibir token.";
    echo json_encode($response);
 }
 //error_log('response '.json_encode($response));
 function insertTrack($conn, $sql,$id,$latitude, $longitude){
    $res=false;
    if (isset($conn) && isset($sql)) {
        if ($insertLocalization = $conn->prepare($sql)) {
            $insertLocalization->bind_param("idd", $id, $latitude, $longitude);
            if($insertLocalization->execute()){
                $res=true;
            }
        }else{
            echo "error ".$conn->error;
        }
    }
    return $res;
 }