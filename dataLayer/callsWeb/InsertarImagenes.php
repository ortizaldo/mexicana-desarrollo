<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
require_once '../classes/estructuraCarpetas.php';

 session_start();

if (
isset($_FILES["inAvatarImg"])
) {
    /***INSERTAMOS LA FOTOGRAFIA EN EL SERVDOR EN LA CARPETA UPLOADS**/
    $rutaImagenAlmacenada = insertarImagenes();
    
} else {
    grabarLog(
        "inAvatarImgUrl => " . $_FILES["inAvatarImg"]["name"]
        , "InsertarUsuario_DatosIngresadorsErronamente");
    
    $url = "";
    if($_SESSION["rol"] == "Agency")
    {
        $url = "empleadosDeAgencia.php";
    }
    else 
    {
        $url = "admins.php";
    }

    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    $response["url"] = $url;
    echo json_encode($response);
}


function insertarImagenes()
{
    error_log("------------------------       ENTRA EN INSERTAR IMAGEN           -----------------------");
    $dir = "../../uploads/";
    $fileName = $_FILES["inAvatarImg"]["name"];
    $ext = $_FILES["inAvatarImg"]["type"];
    $error = $_FILES["inAvatarImg"]["error"];
    $upload = $_FILES["inAvatarImg"]["tmp_name"];
    $size = $_FILES["inAvatarImg"]["size"];

//    $rutaArchivo = $dir . $fileName;
    
    $oEstructuraCarpeta = new EstructuraCarpetas();
    $oEstructuraCarpeta->setPerfil((int) $_POST["txtProfile"]);
    $oEstructuraCarpeta->crearCarpetaEmpleado();
    $rutaArchivo = $oEstructuraCarpeta->getDirEmpleado();

    $responseUpload = move_uploaded_file($upload, $rutaArchivo . $fileName);

    error_log($response);

    error_log($fileName);
    error_log($rutaArchivo . $fileName);

    error_log("InsetarImagenes_insertarImagenes");
    if ($responseUpload == 1) {
        $response["CodigoRespuesta"] = 1;
        $response["MensajeRespuesta"] = "Se ingreso correctamente la imagen";
        $response["urlImagen"] = $fileName;
    } else {
        $response["CodigoRespuesta"] = 0;
        $response["MensajeRespuesta"] = "No se ingreso la imagen correctamente";
        $response["urlImagen"] = '';
    }
    
    $url = "";
    if($_SESSION["rol"] == "Agency")
    {
        $url = "empleadosDeAgencia.php";
    }
    else 
    {
        $url = "admins.php";
    }
    $response["url"] = $url;


error_log("------------------------       FIN EN INSERTAR IMAGEN           -----------------------");
    $dir = "../../uploads/";
    echo json_encode($response);
}


function grabarLog($logInfo, $nombreArchivo)
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = $nombreArchivo . ".txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);

}

