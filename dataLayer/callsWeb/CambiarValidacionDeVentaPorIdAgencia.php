<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (
    isset($_POST["idUsuario"])
    && isset($_POST["validacionEstatus"])
    && isset($_POST["idReporte"])
) {

    $DB = new DAO();
    $conn = $DB->getConnect();

    $idUsuario = $_POST['idUsuario'];
    $validacionEstatus = $_POST['validacionEstatus'];
    $idReporte = $_POST['idReporte'];

    $response = actualizarValidacionPrimeraVenta($conn, $idUsuario, $validacionEstatus, $idReporte);

} else {

    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);

}


function actualizarValidacionPrimeraVenta($conn, $idUsuario, $validacionEstatus, $idReporte)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $returnAgencias = array();
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtobtenerAgencias = $conn->prepare("call spActualizarValidacionPrimeraVenta(?,?,?);");
    mysqli_stmt_bind_param($stmtobtenerAgencias, 'iii', $idUsuario, $validacionEstatus, $idReporte);

    if ($stmtobtenerAgencias->execute()) {
        $stmtobtenerAgencias->store_result();
        $stmtobtenerAgencias->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtobtenerAgencias->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;
            echo json_encode($response);
        }
    } else {
        $response["CodigoRespuesta"] = 0;
        $response["MensajeRespuesta"] = "Ocurrio un problema al momento de obtener la información de la agencia";
        echo json_encode($response);
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
