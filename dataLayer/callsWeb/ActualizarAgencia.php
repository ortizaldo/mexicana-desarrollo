<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (
    isset($_POST["txtId"])
    && isset($_POST["txtUrlImagen"])
    && isset($_POST["txtNickname"])
    && isset($_POST["txtPassword"])
    && isset($_POST["txtName"])
    && isset($_POST["txtLastName"])
    && isset($_POST["txtLastNameOp"])
    && isset($_POST["txtEmail"])
    && isset($_POST["txtPhoneNumber"])
    && isset($_POST["txtTipoAgencia"])
    && isset($_POST["txtPerfilAgencia"])
    && isset($_POST["txtPhAgencia"])
) {

    /**CAMPOS OBLIGATORIOS PARA TODOS LOS USUARIOS**/
    $txtId = $_POST["txtId"];
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
        "txtId => " . $_POST["txtId"]
        . "\n" . "txtUrlImagen => " . $_POST["txtUrlImagen"]
        . "\n" . "txtNickname => " . $_POST["txtNickname"]
        . "\n" . "txtPassword => " . $_POST["txtPassword"]
        . "\n" . "txtName => " . $_POST["txtName"]
        . "\n" . "txtLastName => " . $_POST["txtLastName"]
        . "\n" . "txtLastNameOp => " . $_POST["txtLastNameOp"]
        . "\n" . "txtEmail => " . $_POST["txtEmail"]
        . "\n" . "txtPhoneNumber => " . $_POST["txtPhoneNumber"]
        . "\n" . "txtTipoAgencia => " . $_POST["txtTipoAgencia"]
        . "\n" . "txtPhAgencia => " . isset($_POST["txtPhAgencia"]) ? $_POST["txtPhAgencia"] : "" 
        , "actualizarAgencia_DatosCorrectos");

    $DB = new DAO();
    $conn = $DB->getConnect();

    /**MANDAMOS A LLAMAR EL STORE PARA ALMACENAR EL USUARIO NUEVO**/
    $response = actualizarAgencia($conn, $txtId, $txtUrlImagen, $txtNickname, $txtName, $txtLastName,$txtLastNameOp,$txtPassword, $txtEmail,$txtPhoneNumber,$txtTipoAgencia,$txtPerfilAgencia, $txtPhAgencia);

} else {
    grabarLog(
        "txtId => " . $_POST["txtId"]
        . "\n" . "txtUrlImagen => " . $_POST["txtUrlImagen"]
        . "\n" . "txtNickname => " . $_POST["txtNickname"]
        . "\n" . "txtPassword => " . $_POST["txtPassword"]
        . "\n" . "txtName => " . $_POST["txtName"]
        . "\n" . "txtLastName => " . $_POST["txtLastName"]
        . "\n" . "txtLastNameOp => " . $_POST["txtLastNameOp"]
        . "\n" . "txtEmail => " . $_POST["txtEmail"]
        . "\n" . "txtPhoneNumber => " . $_POST["txtPhoneNumber"]
        . "\n" . "txtTipoAgencia => " . $_POST["txtTipoAgencia"]
        . "\n" . "txtPhAgencia => " . isset($_POST["txtPhAgencia"]) ? $_POST["txtPhAgencia"] : "" 
        , "actualizarAgencia_DatosInCorrectos");
    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);
}

function actualizarAgencia($conn, $txtId, $txtUrlImagen, $txtNickname, $txtName,$txtLastName,$txtLastNameOp, $txtPassword, $txtEmail,$txtPhoneNumber,$txtTipoAgencia,$txtPerfilAgencia,$txtPhAgencia)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtactualizarAgencia = $conn->prepare("call spActualizarAgencia(?,?,?,?,?,?,?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmtactualizarAgencia, 'isssssssssis', $txtId, $txtUrlImagen, $txtNickname, $txtName, $txtLastName,$txtLastNameOp,$txtPassword,$txtEmail,$txtPhoneNumber, $txtTipoAgencia, $txtPerfilAgencia,$txtPhAgencia);

    if ($stmtactualizarAgencia->execute()) {
        $stmtactualizarAgencia->store_result();
        $stmtactualizarAgencia->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtactualizarAgencia->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;

        } else {
            $response["CodigoRespuesta"] = 0;
            $response["MensajeRespuesta"] = "NO SE LOGRO ACTUALIZAR CORRECTAMENTE LA AGENCIA";

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
