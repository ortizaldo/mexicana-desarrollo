<?php

include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
ini_set('memory_limit', '-1');

function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$token = "";
$imgName = "";


error_log("-------------------------------------------------------------------         ENTRA EN EL ARCHIVO        -----------------------------------------");



$token = $_SERVER["HTTP_TOKEN"];
$nombre = $_SERVER["HTTP_NOMBRE"];

if (isset($token)) {
    $DB = new DAO();
    $conn = $DB->getConnect();
    error_log('token http');
    error_log($token);
    //$token = $_SERVER["HTTP_TOKEN"];
    $token = base64_decode($token);

    list($id, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
    $token = base64_encode($id . "&" . $nickname . "&" . $email . "&" . $profile . "&" . $nicknameAgency . "&" . $rol . "&" . $value . "&" . $idDevice);
    $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
    $searchToken->bind_param('i', $id);

    if ($searchToken->execute()) {
        $searchToken->store_result();
        $searchToken->bind_result($userToken);

        if ($searchToken->fetch()) {
            error_log('token http');
            error_log($token);
            error_log('token user');
            error_log($userToken);
            error_log('idUSer '.$id);
            if ($token == $userToken) {
                if (isset($_FILES["imagen"])) {
                    $dir = "../../uploads/";
                    
                    error_log(json_encode($_FILES));

                    $data = $_FILES["imagen"];
                    $fileNum = 1;
                    
                    $idFile = generateRandomString();
					$imageName = $nombre . "_File_" . $idFile . "_elem.png";
                    $urlPhoto = $dir . $imageName;
                    
                    $moveFile = move_uploaded_file($_FILES["imagen"]["tmp_name"], $urlPhoto);
                    
                    $imageSize = $_FILES["imagen"]["size"];

                    $insertMultimediaSQL = "INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());";
                    if ($insertMultimedia = $conn->prepare($insertMultimediaSQL)) {
                        $insertMultimedia->bind_param("sssss", $urlPhoto, $imageName, $typeExt, $MIMEtype, $imageSize);
                        error_log('moveFile '.$moveFile);
                        if ($insertMultimedia->execute() && $moveFile) {
                            $response["status"] = "OK";
                            $response["code"] = "200";
                            $response["response"] = "Imagen Almacenada Exitosamente";
                            $response["imageID"] = $insertMultimedia->insert_id;
                            echo json_encode($response);
                        } else {
                            $response["status"] = "ERROR";
                            $response["code"] = "500";
                            $response["response"] = "Error al Grabar Imagen: " . $insertMultimedia->error;
                            echo json_encode($response);
                        }
                    }else{
                        error_log('error '.$insertMultimedia->error);
                    }
                    $fileNum++;
                        
                } else {
                        error_log("entra en error en la imagen");
	                $response["status"] = "ERROR";
	                $response["code"] = "400";
	                $response["response"] = "Error al Recibir Imagen.";
	                echo json_encode($response);
	            }
            } else {
                error_log("entra en token no valido");
	            $response["status"] = "ERROR";
	            $response["code"] = "500";
	            $response["response"] = "Error en el token vuelven.";
	            echo json_encode($response);
	        }
        }
        else
        {
             error_log("fetch query token");
        }
    }
    else {
        error_log("entra en donde no puedo hacer query para retornar token");
    }
} else {
    
    error_log("entro recepcion token");
    $response["status"] = "ERROR";
    $response["code"] = "500";
    $response["response"] = "Error en la recepcion token.";
    echo json_encode($response);
}
