<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (isset($_POST["inMotivoDeRechazo"])) {

    $DB = new DAO();
    $conn = $DB->getConnect();

    $inMotivoDeRechazo=$_POST["inMotivoDeRechazo"];
    $response = insertarCatalogoMotivosDeRechazo($conn,$inMotivoDeRechazo);

} else {

}


function insertarCatalogoMotivosDeRechazo($conn,$inMotivoDeRechazo)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $returnMotivosDeRechazo = array();
    $CodigoRespuesta="";
    $MensajeRespuesta = "";



    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtInsertarCatalogoMotivosDeRechazo = $conn->prepare("call spInsertarCatalogoMotivosDeRechazo(?);");
    mysqli_stmt_bind_param($stmtInsertarCatalogoMotivosDeRechazo, 's', $inMotivoDeRechazo);

    if ($stmtInsertarCatalogoMotivosDeRechazo->execute()) {
        $stmtInsertarCatalogoMotivosDeRechazo->store_result();
        $stmtInsertarCatalogoMotivosDeRechazo->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtInsertarCatalogoMotivosDeRechazo->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;
            echo json_encode($response);
        }

    } else {

    }
    // grabarLog(json_encode($response));

    $conn->close();

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
