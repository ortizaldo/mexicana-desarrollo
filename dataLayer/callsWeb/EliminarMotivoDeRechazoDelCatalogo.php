<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (isset($_POST["idMotivoRechazo"])) {

    $DB = new DAO();
    $conn = $DB->getConnect();
    $idMotivoRechazo = $_POST["idMotivoRechazo"];
    $response = eliminarMotivoDeRechazoDelCatalogo($conn, $idMotivoRechazo);

} else {

}


function eliminarMotivoDeRechazoDelCatalogo($conn, $idMotivoRechazo)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $returnMotivosDeRechazo = array();
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtEliminarMotivoDeRechazoDelCatalogo = $conn->prepare("call spEliminarMotivoDeRechazoDelCatalogo(?);");
    mysqli_stmt_bind_param($stmtEliminarMotivoDeRechazoDelCatalogo, 'i', $idMotivoRechazo);

    if ($stmtEliminarMotivoDeRechazoDelCatalogo->execute()) {
        $stmtEliminarMotivoDeRechazoDelCatalogo->store_result();
        $stmtEliminarMotivoDeRechazoDelCatalogo->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtEliminarMotivoDeRechazoDelCatalogo->fetch()) {
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
