<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (true) {

    $DB = new DAO();
    $conn = $DB->getConnect();

    $response = obtenerCatalogoTiposDeContrato($conn);

} else {

    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);

}


function obtenerCatalogoTiposDeContrato($conn)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $returnCatalogoTiposDeContrato = array();
    $CodigoRespuesta="";
    $MensajeRespuesta="";
    $idCatalogoTiposDeContrato = "";
    $tipoDeContrato = "";
    $precio = "";
    $plazo = "";
    $pagos = "";
    $fechaAlta = "";
    $fechaMod = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtobtenerCatalogoTiposDeContrato = $conn->prepare("call spObtenerCatalogoTiposDeContrato();");

    if ($stmtobtenerCatalogoTiposDeContrato->execute()) {
        $stmtobtenerCatalogoTiposDeContrato->store_result();
        $stmtobtenerCatalogoTiposDeContrato->bind_result($CodigoRespuesta,$MensajeRespuesta,
                                                         $idCatalogoTiposDeContrato, $idContrato,
                                                         $claveArticuloContrato,$tipoDeContrato,
                                                         $precio,$plazo,$pagos,$fechaAlta,$fechaMod,
                                                         $financiamiento,$clasif);
        while ($stmtobtenerCatalogoTiposDeContrato->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;
            $response["idCatalogoTiposDeContrato"] = $idCatalogoTiposDeContrato;
            $response['idContrato']=$idContrato;
            $response['claveArticuloContrato']=$claveArticuloContrato;
            $response['tipoDeContrato']=$tipoDeContrato;
            $response['precio']=$precio;
            $response['plazo']=$plazo;
            $response['pagos']=$pagos;
            $response['fechaAlta']=$fechaAlta;
            $response['fechaMod']=$fechaMod;
            $response['financiamiento']=$financiamiento;
            $response['clasif']=$clasif;
            $returnCatalogoTiposDeContrato[] = $response;
        }
        echo json_encode($returnCatalogoTiposDeContrato);
    } else {
        $response["CodigoRespuesta"] = 0;
        $response["MensajeRespuesta"] = "Ocurrio un problema al momento de obtener la información de los catalogos de tipo de contrato";
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
