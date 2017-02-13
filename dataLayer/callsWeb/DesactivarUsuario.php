<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

 session_start();

if (
    isset($_POST["idUsuario"])
) {

    /**CAMPOS OBLIGATORIOS PARA TODOS LOS USUARIOS**/
    $idUsuario = $_POST["idUsuario"];

    grabarLog($idUsuario,"DesactivarUsuario");

    $DB = new DAO();
    $conn = $DB->getConnect();

    /**MANDAMOS A LLAMAR EL STORE PARA ALMACENAR EL USUARIO NUEVO**/
    $response = desactivarUsuario($conn, $idUsuario);
    
 

} else {

    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    if($_SESSION["rol"] == "Agency")
        {
            $url = "empleadosDeAgencia.php";
        }
        else 
        {
            $url = "admins.php";
        }
        $response["url"] = $url;
    
    
    echo json_encode($response);
    grabarLog(json_encode($response),"DesactivarUsuario");

}


function desactivarUsuario($conn, $idUsuario)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtDesactivarUsuario = $conn->prepare("call spDesactivarUsuario(?);");
    mysqli_stmt_bind_param($stmtDesactivarUsuario, 'i', $idUsuario);

    if ($stmtDesactivarUsuario->execute()) {
        $stmtDesactivarUsuario->store_result();
        $stmtDesactivarUsuario->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtDesactivarUsuario->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;

        } else {
            $response["CodigoRespuesta"] = 0;
            $response["MensajeRespuesta"] = "NO SE LOGRO DESACTIVAR EL USUARIO";

        }
        // grabarLog(json_encode($response));
        
        if($_SESSION["rol"] == "Agency")
        {
            $url = "empleadosDeAgencia.php";
        }
        else 
        {
            $url = "admins.php";
        }
        $response["url"] = $url;

        echo json_encode($response);
        $conn->close();
    }
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
