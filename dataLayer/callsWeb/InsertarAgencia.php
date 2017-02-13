<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (
    isset($_POST["txtUrlImagen"])
    && isset($_POST["txtNickname"])
    && isset($_POST["txtPassword"])
    && isset($_POST["txtName"])
    && isset($_POST["txtLastName"])
    && isset($_POST["txtLastNameOp"])
    && isset($_POST["txtEmail"])
    && isset($_POST["txtPhoneNumber"])
    && isset($_POST["txtTipoAgencia"])
    && isset($_POST["txtPerfilAgencia"])

) {

    /**CAMPOS OBLIGATORIOS PARA TODOS LOS USUARIOS**/
    $txtUrlImagen = $_POST["txtUrlImagen"];
    $txtNickname = $_POST["txtNickname"];
    $txtName = $_POST["txtName"];
    $txtLastName = $_POST["txtLastName"];
    $txtLastNameOp = $_POST["txtLastNameOp"];
    $txtPassword = $_POST["txtPassword"];
    $txtEmail = $_POST["txtEmail"];
    $txtPhoneNumber = $_POST["txtPhoneNumber"];
    $txtPassword = $_POST["txtPassword"];
    $txtTipoAgencia = $_POST["txtTipoAgencia"];
    $txtPerfilAgencia = $_POST["txtPerfilAgencia"];
    $txtPhAgencia = isset($_POST["txtPhAgencia"]) ? $_POST["txtPhAgencia"] : null;

    grabarLog(
        "txtUrlImagen => " . $_POST["txtUrlImagen"]
        . "\n" . "txtNickname => " . $_POST["txtNickname"]
        . "\n" . "txtPassword => " . $_POST["txtPassword"]
        . "\n" . "txtName => " . $_POST["txtName"]
        . "\n" . "txtLastName => " . $_POST["txtLastName"]
        . "\n" . "txtLastNameOp => " . $_POST["txtLastNameOp"]
        . "\n" . "txtEmail => " . $_POST["txtEmail"]
        . "\n" . "txtPhoneNumber => " . $_POST["txtPhoneNumber"]
        . "\n" . "txtTipoAgencia => " . $_POST["txtTipoAgencia"]
        . "\n" . "txtPerfilAgencia => " . $_POST["txtPerfilAgencia"]
        . "\n" . "txtPhAgencia => " . isset($_POST["txtPhAgencia"]) ? $_POST["txtPhAgencia"] : "" 
        , "insertarAgencia_DatosCorrectos");

    if ($txtPhAgencia == null || $txtPhAgencia = "") {
        $txtPhAgencia = "";
    }
    $DB = new DAO();
    $conn = $DB->getConnect();

    /**MANDAMOS A LLAMAR EL STORE PARA ALMACENAR EL USUARIO NUEVO**/
    $response = insertarAgencia($conn, $txtUrlImagen, $txtNickname, $txtName, $txtLastName, $txtLastNameOp, $txtPassword, $txtEmail, $txtPhoneNumber, $txtTipoAgencia, $txtPerfilAgencia, $txtPhAgencia);

} else {
    grabarLog(
        "txtUrlImagen => " . $_POST["txtUrlImagen"]
        . "\n" . "txtNickname => " . $_POST["txtNickname"]
        . "\n" . "txtPassword => " . $_POST["txtPassword"]
        . "\n" . "txtName => " . $_POST["txtName"]
        . "\n" . "txtLastName => " . $_POST["txtLastName"]
        . "\n" . "txtLastNameOp => " . $_POST["txtLastNameOp"]
        . "\n" . "txtEmail => " . $_POST["txtEmail"]
        . "\n" . "txtPhoneNumber => " . $_POST["txtPhoneNumber"]
        . "\n" . "txtTipoAgencia => " . $_POST["txtTipoAgencia"]
        . "\n" . "txtPhAgencia => " . isset($_POST["txtPhAgencia"]) ? $_POST["txtPhAgencia"] : "" 
        , "insertarAgencia_DatosInCorrectos");
    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);
}

function insertarAgencia($conn, $txtUrlImagen, $txtNickname, $txtName, $txtLastName, $txtLastNameOp, $txtPassword, $txtEmail, $txtPhoneNumber, $txtTipoAgencia, $txtPerfilAgencia,$txtPhAgencia)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtinsertarAgencia = $conn->prepare("call spInsertarAgencia(?,?,?,?,?,?,?,?,?,?,?);");
    error_log("Ejecutar: call spInsertarAgencia(".$txtUrlImagen.",".$txtNickname.",".$txtName.",".$txtLastName.",".$txtLastNameOp.",".$txtPassword.",".$txtEmail.",".$txtPhoneNumber.",".$txtTipoAgencia.",".$txtPerfilAgencia.",".$txtPhAgencia.");", 0);
    mysqli_stmt_bind_param($stmtinsertarAgencia, 'sssssssssis', $txtUrlImagen, $txtNickname, $txtName, $txtLastName, $txtLastNameOp, $txtPassword, $txtEmail, $txtPhoneNumber, $txtTipoAgencia,$txtPerfilAgencia, $txtPhAgencia);

    if ($stmtinsertarAgencia->execute()) {
        $stmtinsertarAgencia->store_result();
        $stmtinsertarAgencia->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtinsertarAgencia->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;

        } else {
            $response["CodigoRespuesta"] = 0;
            $response["MensajeRespuesta"] = "NO SE LOGRO INSERTAR CORRECTAMENTE LA AGENCIA";

        }

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
