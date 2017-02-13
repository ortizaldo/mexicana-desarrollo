<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (
isset($_POST["idUsuario"])
) {

    /**CAMPOS OBLIGATORIOS PARA TODOS LOS USUARIOS**/
    $idUsuario = $_POST["idUsuario"];

    grabarLog($idUsuario, "ObtenerInformacionDeAgencia");

    $DB = new DAO();
    $conn = $DB->getConnect();

    /**MANDAMOS A LLAMAR EL STORE PARA ALMACENAR EL USUARIO NUEVO**/
    $response = obtenerInformacionDeAgencia($conn, $idUsuario);

} else {

    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);
    grabarLog(json_encode($response), "ObtenerInformacionDeAgencia");

}


function ObtenerInformacionDeAgencia($conn, $idUsuario)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";
    $nickname = "";
    $password = "";
    $name = "";
    $lastName = "";
    $lastNameOp = "";
    $email = "";
    $phoneNumber = "";
    $photoUrl = "";
    $tipo = "";
    $perfilAgencia="";
    $numeroReferenciaPh = "";
    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtObtenerInformacionDeAgencia = $conn->prepare("call spObtenerInformacionDeAgencia(?);");
    mysqli_stmt_bind_param($stmtObtenerInformacionDeAgencia, 'i', $idUsuario);

    if ($stmtObtenerInformacionDeAgencia->execute()) {
        $stmtObtenerInformacionDeAgencia->store_result();
        $stmtObtenerInformacionDeAgencia->bind_result($CodigoRespuesta, $MensajeRespuesta, $nickname, $password, $name, $lastName, $lastNameOp, $email, $phoneNumber, $photoUrl,
            $tipo, $perfilAgencia,$numeroReferenciaPh);
        if ($stmtObtenerInformacionDeAgencia->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;
            $response["nickname"] = $nickname;
            $response["password"] = $password;
            $response["name"] = $name;
            $response["lastName"] = $lastName;
            $response["lastNameOp"] = $lastNameOp;
            $response["email"] = $email;
            $response["phoneNumber"] = $phoneNumber;
            $response["photoUrl"] = $photoUrl;
            $response["tipo"] = $tipo;
            $response["perfilAgencia"] = $perfilAgencia;
            $response["numeroReferenciaPh"] = $numeroReferenciaPh;

        } else {
            $response["CodigoRespuesta"] = 0;
            $response["MensajeRespuesta"] = "Ocurrio un problema al momento de obtener la información de la agencia";

        }
        // grabarLog(json_encode($response));

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
