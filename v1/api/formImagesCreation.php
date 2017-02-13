<?php
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
ini_set('memory_limit', '-1');


error_log("-------------- ENTRA EN EL ARCHIVO QUE NO ES TEST -------------");
error_log(json_encode($_POST));
error_log(json_encode($_FILES));


/**
 * Metodo que genera un idetificador para la imagen
 * @param type $length
 * @return string
 */
function generateRandomString($length = 25) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) 
    {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


/**
 * METODO QUE SE ENCARGA DE CONVERTIR EL BASE64 DE LA IMAGEN A FILE
 * @param string $base64_string
 * @param string $output_file
 * @return type
 */
function base64_to_img($base64_string,$imageName,$typeExt, $output_file = '../../uploads/') 
{
    $data = str_replace('data:image/png;base64,', '', $base64_string);
    $data = str_replace(' ', '+', $data);
    
    $data = base64_decode($data); 
    
    $output_file = $output_file . $imageName  . "_File_" . generateRandomString() . "." . $typeExt;
    $success = @file_put_contents($output_file, $data);
    
    return $success;
}

$token = "";
$imgName = "";

$token = $_SERVER["HTTP_TOKEN"];
$nombre = $_SERVER["HTTP_NOMBRE"];

error_log(json_encode($token));
error_log(json_encode($nombre));


if (isset($token)) {
    $DB = new DAO();
    $conn = $DB->getConnect();

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
            if ($token == $userToken) {
                
                if (isset($_POST["imagen"])) {
                    $dir = "../../uploads/";
                    
                    error_log(json_encode($_FILES));
                    
                    $imagen64 = $_POST["imagen"];
                    $imageName = $_POST["name"];
                    $imageSize = $_POST["size"];
                    $typeExt = $_POST["type"];
                    $MIMEtype= NULL;
                    $urlPhoto = NULL;
                    
                    if(base64_to_img($imagen64, $imageName, $typeExt))
                    {
                        $insertMultimedia = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                        $insertMultimedia->bind_param("sssss", $urlPhoto, $imageName, $typeExt, $MIMEtype, $imageSize);

                        if ($insertMultimedia->execute()) {
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
                    }
                    else 
                    {
                        error_log("entra en error codificar la imagen");
	                $response["status"] = "ERROR";
	                $response["code"] = "400";
	                $response["response"] = "Error al codificar la imagen.";
	                echo json_encode($response);
                    }
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