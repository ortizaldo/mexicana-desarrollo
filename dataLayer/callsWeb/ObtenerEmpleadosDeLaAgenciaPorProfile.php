<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (true) {

    $DB = new DAO();
    $conn = $DB->getConnect();

    $idAgencia=$_POST["idAgencia"];
    $profile=$_POST["profile"];
    $response = obtenerEmpleadosDeLaAgenciasPorProfile($conn,$profile,$idAgencia);

} else {

    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);

}


function obtenerEmpleadosDeLaAgenciasPorProfile($conn,$profile,$idAgencia)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $returnEmpleados = array();
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";
    $id = "";
    $nickname = "";
    $name = "";
    $lastName = "";
    $lastNameOp = "";
    $email = "";
    $idRol = "";
    $updated_at = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtobtenerAgencias = $conn->prepare("call spObtenerEmpleadosDeLaAgenciaPorProfile(?,?);");
    mysqli_stmt_bind_param($stmtobtenerAgencias, 'si',$profile,$idAgencia);
    if ($stmtobtenerAgencias->execute()) {
        $stmtobtenerAgencias->store_result();
        $stmtobtenerAgencias->bind_result($CodigoRespuesta,$MensajeRespuesta,$id, $nickname, $name, $lastName,$lastNameOp,$email,$idRol,
            $updated_at);
        while ($stmtobtenerAgencias->fetch()) {
            $response['CodigoRespuesta'] = $CodigoRespuesta;
            $response['MensajeRespuesta'] = $MensajeRespuesta;
            $response['id'] = $id;
            $response['nickname'] =$nickname;
            $response['name'] = $name;
            $response['lastName'] = $lastName;
            $response['lastNameOp'] =$lastNameOp;
            $response['email'] = $email;
            $response['idRol'] = $idRol;
            $response['updated_at'] = $updated_at;
            $returnEmpleados[] = $response;
        }
        echo json_encode($returnEmpleados);
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
