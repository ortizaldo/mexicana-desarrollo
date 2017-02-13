<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (isset($_POST["message"]) && isset($_POST["idUser"])) {

    $ESTATUS_NOTIFICACION_NUEVA = 1;
    $ESTATUS_NOTIFICACION_VISTA = 2;

    /**ESTE METODO UTILIZA LAS VARIABLES
     * message
     * idAgency
     * idEmployee
     * tokenDevice
     * status
     * paraAgencia**/

    $message = $_POST["message"];
    $idUser = $_POST["idUser"];

    $DB = new DAO();
    $conn = $DB->getConnect();
    /***VALIDAMOS QUE SI EL MENSAJE ES PARA LA AGENCIA ENTONCES NO ENTRAMOS A LA RUTINA DEL
     * STOREPROCEDURE BUSCARTOKENDEVICEDELEMPLEADO**/

    /***BUSCAMOS LOS TOKENS DE LOS DISPOSITIVOS QUE TIENE EL EMPLEADO ASIGNADO EN EL SISTEMA**/
    $responseJson = buscarPorIdUsuario($conn, $idUser);
    $idUsers = $responseJson['idUsers'];
    $tokens = $responseJson['tokens'];
    //echo json_encode($responseJson);
    /**REALIZAMOS LA INSERCION DE LAS NOTIFICACIONES PARA CADA UNO DE LOS TOKENS QUE TIENE REGISTRADO
     * EL EMPLEADO***/

    $sizeArrayTokens = sizeof($tokens);
    foreach ($tokens as $key => $oToken) {
        $idUser = $idUsers[$key];
        insertarNotificacion($conn, $message, $oToken, $idUser, $ESTATUS_NOTIFICACION_NUEVA, $key, $sizeArrayTokens);
    }


} else {
    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);
}


function buscarPorIdUsuario($conn, $idUser)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";
    $tokenDevice = "";
    $id = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtObtenerInfoPorIdUsuario = $conn->prepare("call spBuscarPorIdUsuario(?);");
    mysqli_stmt_bind_param($stmtObtenerInfoPorIdUsuario, 'i', $idUser);
    if ($stmtObtenerInfoPorIdUsuario->execute()) {
        $stmtObtenerInfoPorIdUsuario->store_result();
        $stmtObtenerInfoPorIdUsuario->bind_result($CodigoRespuesta, $MensajeRespuesta, $tokenDevice, $id);

        $arrayidUsers = array();
        $arrayTokens = array();
        while ($stmtObtenerInfoPorIdUsuario->fetch()) {
            $responseJson = array(
                'CodigoRespuesta' => $CodigoRespuesta,
                'MensajeRespuesta' => $MensajeRespuesta,
                'idUsers' => array(),
                'tokens' => array());
            $arrayidUsers[] = $id;
            $arrayTokens[] = $tokenDevice;
        }
        $responseJson['idUsers'] = $arrayidUsers;
        $responseJson['tokens'] = $arrayTokens;
    } else {
        $responseJson["CodigoRespuesta"] = 0;
        $responseJson["MensajeRespuesta"] = "NO SE OBTUVIERON LOS TOKENDEVICE";
    }

    /**CERRAMOS EL RESULT DE ObtenerInfoPorIdUsuario***/
    $stmtObtenerInfoPorIdUsuario->close();
    /***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
     * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
     * http://stackoverflow.com/a/10745472 ***/
    $conn->next_result();
    error_log(json_encode($responseJson));
    return $responseJson;
}

function buscarTokenDeviceDelEmpleado($conn, $idEmployee)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $idUser = "";
    $tokenDevice = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtObtenerTokenDevice = $conn->prepare("call spBuscarTokenDevicePorIdEmployee(?);");
    mysqli_stmt_bind_param($stmtObtenerTokenDevice, 'i', $idEmployee);
    if ($stmtObtenerTokenDevice->execute()) {
        $stmtObtenerTokenDevice->store_result();
        $stmtObtenerTokenDevice->bind_result($tokenDevice, $idUser);

        $responseJson = array(
            'CodigoRespuesta' => '1',
            'MensajeRespuesta' => 'Se obtuvieron correctamente los tokens',
            'idUsers' => array(),
            'tokens' => array());
        $arrayidUsers = array();
        $arrayTokens = array();
        while ($stmtObtenerTokenDevice->fetch()) {
            $arrayidUsers[] = $idUser;
            $arrayTokens[] = $tokenDevice;
        }
        $responseJson['idUsers'] = $arrayidUsers;
        $responseJson['tokens'] = $arrayTokens;
    } else {
        $responseJson["CodigoRespuesta"] = 0;
        $responseJson["MensajeRespuesta"] = "NO SE OBTUVIERON LOS TOKENDEVICE";
    }

    /**CERRAMOS EL RESULT DE OBTENERTOKENDEVICE***/
    $stmtObtenerTokenDevice->close();
    /***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
     * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
     * http://stackoverflow.com/a/10745472 ***/
    $conn->next_result();
    return $responseJson;
}

function insertarNotificacion($conn, $message, $tokenDevice, $idUser, $status, $key, $sizeArrayTokens)
{

    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtInsertarNotificacion = $conn->prepare("call spInsertarNotificacion(?,?,?,?);");
    mysqli_stmt_bind_param($stmtInsertarNotificacion, 'ssii', $message, $tokenDevice, $idUser, $status);

    if ($stmtInsertarNotificacion->execute()) {
        $stmtInsertarNotificacion->store_result();
        $stmtInsertarNotificacion->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtInsertarNotificacion->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;

            /**VERIFICAMOS QUE SI NO TIENE TOKEN DEVICE ENTONCES ES UNA AGENCIA
             * PERO SI TIENE ES UN USUARIO POR LO CUAL NECESITAMOS REDIRECCIONAR A LAS PAGINAS CORRESPONDIENTES
             * DE CADA UNO
             * admins.php => PARA UN ADMINISTRADOR QUE ESTE MANDANDO A UN EMPLEADO, TIENE TOKEN
             * agencies.php => PARA UN ADMINISTRADOR QUE ESTE MANDANDO A UNA AGENCIA EN ESPECIFICO, NO TIENE TOKEN***/
            if ($tokenDevice == 'NO_TOKEN') {
                $response["url"] = 'agencies.php';
            } else {
                $response["url"] = 'admins.php';
            }
            grabarLog("el log del spInsertarNotificacion ".json_encode($response));
            enviarMensajePorGCMAppceleratorCloudApi($tokenDevice, $message);

        } else {
            $response["CodigoRespuesta"] = 0;
            $response["MensajeRespuesta"] = "NO SE LOGRO REGISTRAR EL DISPOSITIVO MOVIL";

        }

        /**GENERAMOS ESTA CONDICION PARA QUE HASTA QUE TERMINE EL RECORRIDO DEL FOREACH
         * ENTONCES SE CIERRE LA CONEXION A LA BASE DE DATOS
         * MIENTRAS NO SE TERMINE SEGUIMOS LLAMANDO AL SIGUIENTE STOREPROCEDURE***/
        $stmtInsertarNotificacion->close();
        if ($key < $sizeArrayTokens - 1) {
            $conn->next_result();
        } else {
            echo json_encode($response);
            //grabarLog($response);
            $conn->close();
        }
    }
}

function enviarMensajePorGCMAppceleratorCloudApi($tokenDevice, $message)
{
    $channelCMG = "CMG";
    $title = "Nuevo mensaje";
    $postData = array(
        'to_tokens' => $tokenDevice,
        'channel' => $channelCMG,
        'title' => $title,
        'message' => $message
    );
    $ch = curl_init();
    //$servidorActual = $_SERVER['SERVER_NAME'];
    $servidorActual='http://siscomcmg.com';
    $directorioServicioApiAppceleratorCloud = "/v1/api/AppceleratorCloud/ApiToTokens.php";
    //echo $servidorActual;
    curl_setopt($ch, CURLOPT_URL, $servidorActual . $directorioServicioApiAppceleratorCloud);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    echo $output;

}

function grabarLog($logInfo)
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = "InsertarNotificacion.txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);

}

