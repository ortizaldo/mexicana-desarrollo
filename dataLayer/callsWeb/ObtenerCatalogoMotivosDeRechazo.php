<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (true) {

    $DB = new DAO();
    $conn = $DB->getConnect();

    $response = ObtenerCatalogoMotivosDeRechazo($conn);

} else {

}


function obtenerCatalogoMotivosDeRechazo($conn)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $returnMotivosDeRechazo = array();
    $id="";
    $reason = "";
    $created_at = "";
    $updated_at = "";


    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtObtenerCatalogoMotivosDeRechazo = $conn->prepare("call spObtenerCatalogoMotivosDeRechazo();");

    if ($stmtObtenerCatalogoMotivosDeRechazo->execute()) {
        $stmtObtenerCatalogoMotivosDeRechazo->store_result();
        $stmtObtenerCatalogoMotivosDeRechazo->bind_result($id, $reason, $created_at, $updated_at);
        while ($stmtObtenerCatalogoMotivosDeRechazo->fetch()) {
            $response["id"] = $id;
            $response["reason"] = $reason;
            $response["created_at"] = $created_at;
            $response["updated_at"] = $updated_at;
            $returnMotivosDeRechazo[] = $response;
        }
        echo json_encode($returnMotivosDeRechazo);
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
