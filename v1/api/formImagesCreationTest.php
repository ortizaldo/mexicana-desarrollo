<?php
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

// tiempo de ejecucion 10 min por escript
ini_set('max_execution_time', 600);

// 60 Megas para el post
ini_set('post_max_size', 62428800);

// 50 Megas para los uploads
ini_set('upload_max_filesize', 52428800);

error_log("-------------- ENTRA EN EL ARCHIVO FORMIMAGECREATIONTEST -------------");

/*error_log(json_encode($_POST));
error_log(json_encode($_FILES));


error_log($_POST["token"]);
error_log($_POST["imagen"]);
error_log($_POST["name"]);
error_log(json_encode($_POST["size"]));
error_log($_POST["type"]);*/

error_log("-------------- ENTRA EN EL ARCHIVO FORMIMAGECREATIONTEST -------------");



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
function base64_to_img($base64_string, $imageName) 
{
    if(strcmp(substr($base64_string,  0, 1), '"') == 0)
    {
        $base64_string = substr($base64_string, 1);
    }
    
    if(strcmp(substr($base64_string, -1), '"') == 0)
    {
        $base64_string = substr($base64_string, 0,-1);
    }
    
    $data = str_replace('data:image/png;base64,', '', $base64_string);
    $data = str_replace(' ', '+', $data);
    
    $data = base64_decode($data); 
    $output_file = "../../uploads/" . $imageName;
    
    
    //error_log("----------------     NOMBRE DEL IMAGEN     ------------------------");
    //error_log($output_file);
    //error_log("----------------     NOMBRE DEL IMAGEN     ------------------------");
    
    
    
    $success = @file_put_contents($output_file, $data);
    //error_log(var_dump($success));
    return $success;
}

$token = "";
$imgName = "";

$token = $_POST["token"];

//error_log(json_encode($token));


if (isset($token)) {
    
    //error_log("------------ ENTRO IF DONDE SI VIENE TOKEN ---------------");
    
    
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
                
                //error_log("------------ ENTRO EN IF DONDE SE COMPARAN LOS TOKEN ---------------");
                
                if (isset($_POST["imagen"])) {
                    
                    
                    //error_log("------------ ENTRO EN IF POST ---------------");
                    
                    
                    $imagen64 = $_POST["imagen"];
                    $imageName = $_POST["name"];
                    $imageSize = $_POST["size"];
                    $typeExt = $_POST["type"];
                    $MIMEtype= NULL;
                    $urlPhoto = NULL;
                    
                    if(strcmp(substr($imageName,  0, 1), '"') == 0)
                    {
                        $imageName = substr($imageName, 1);
                    }

                    if(strcmp(substr($imageName, -1), '"') == 0)
                    {
                        $imageName = substr($imageName, 0,-1);
                    }

                    if(strcmp(substr($typeExt,  0, 1), '"') == 0)
                    {
                        $typeExt = substr($typeExt, 1);
                    }

                    if(strcmp(substr($typeExt, -1), '"') == 0)
                    {
                        $typeExt = substr($typeExt, 0,-1);
                    }
                    
                    $imageName = $imageName  . "_File_" . generateRandomString() . "." . $typeExt;
                    
                    if(base64_to_img($imagen64, $imageName)){
                        //error_log("------------ ENTRO EN IF DONDE SE CONVIRTIO EN IMAGEN LA BASE 64 ---------------");   
                        
                        $insertMultimedia = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                        $insertMultimedia->bind_param("sssss", $urlPhoto, $imageName, $typeExt, $MIMEtype, $imageSize);

                        if ($insertMultimedia->execute()) {
                            $response["status"] = "OK";
                            $response["code"] = "200";
                            $response["response"] = "Imagen Almacenada Exitosamente";
                            $response["imageID"] = $insertMultimedia->insert_id;
                            echo json_encode($response);
                        } else {
                            error_log($insertMultimedia->error);
                            $response["status"] = "ERROR";
                            $response["code"] = "500";
                            $response["response"] = "Error al Grabar Imagen: " . $insertMultimedia->error;
                            echo json_encode($response);
                        }
                         
                    }else{
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
                error_log("entra en token no valido1");
                $response["status"] = "ERROR";
	            $response["code"] = "500";
	            $response["response"] = "Error en el token vuelven."." token ".$userToken." userToken ".$userToken;
	            echo json_encode($response);
                error_log(json_encode($response));
	        }
        }else{
            $response["status"] = "ERROR";
            $response["code"] = "500";
            $response["response"] = "Error Buscar token.";
             error_log("fetch query token");
        }
    } else {
        $response["status"] = "ERROR";
        $response["code"] = "500";
        $response["response"] = "Error Buscar token.";
        error_log("entra en donde no puedo hacer query para retornar token");
    }
} else {
    
    error_log("entro recepcion token");
    $response["status"] = "ERROR";
    $response["code"] = "500";
    $response["response"] = "Error en la recepcion token.";
    echo json_encode($response);
}