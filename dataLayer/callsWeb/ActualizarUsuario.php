<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
 session_start();

if (
    isset($_POST["txtId"])
    && isset($_POST["txtUrlImagen"])
    && isset($_POST["txtRol"])
    //&& isset($_POST["txtProfile"])
    //&& isset($_POST["txtAdminsCompanyAlta"])
    && isset($_POST["txtNickname"])
    && isset($_POST["txtPassword"])
    && isset($_POST["txtName"])
    && isset($_POST["txtLastName"])
    && isset($_POST["txtLastNameOp"])
    && isset($_POST["txtEmail"])
    && isset($_POST["txtPhoneNumber"])
) {

    /**CAMPOS OBLIGATORIOS PARA TODOS LOS USUARIOS**/
    $txtId = $_POST["txtId"];
    $txtUrlImagen = $_POST["txtUrlImagen"];
    $txtRol = $_POST["txtRol"];
    $txtProfile = isset($_POST["txtProfile"]) ? $_POST["txtProfile"] : null;
    $txtAdminsCompanyAlta = isset($_POST["txtAdminsCompanyAlta"]) ? $_POST["txtAdminsCompanyAlta"] : null;
    $txtNickname = $_POST["txtNickname"];
    $txtName = $_POST["txtName"];
    $txtPassword = $_POST["txtPassword"];
    $txtLastName = $_POST["txtLastName"];
    $txtLastNameOp = $_POST["txtLastNameOp"];
    $txtEmail = $_POST["txtEmail"];
    $txtPhoneNumber = $_POST["txtPhoneNumber"];

    /**CAMPOS EXCLUSIVOS PARA AGENCIA*/
    $agenciaTipo = isset($_POST["agenciaTipo"]) ? $_POST["agenciaTipo"] : null;
    $agenciaPlazo = isset($_POST["agenciaPlazo"]) ? $_POST["agenciaPlazo"] : null;


    grabarLog(
        "txtId => " . $_POST["txtId"]
        . "\n" . "txtUrlImagen => " . $_POST["txtUrlImagen"]
        . "\n" . "txtRol => " . $_POST["txtRol"]
        . "\n" . "txtProfile => " . $txtProfile
        . "\n" . "txtAdminsCompanyAlta => " . $txtAdminsCompanyAlta
        . "\n" . "txtNickname => " . $_POST["txtNickname"]
        . "\n" . "txtPassword => " . $_POST["txtPassword"]
        . "\n" . "txtName => " . $_POST["txtName"]
        . "\n" . "txtLastName => " . $_POST["txtLastName"]
        . "\n" . "txtLastNameOp => " . $_POST["txtLastNameOp"]
        . "\n" . "txtEmail => " . $_POST["txtEmail"]
        . "\n" . "txtPhoneNumber => " . $_POST['txtPhoneNumber']
        . "\n" . "agenciaTipo => " . $agenciaTipo
        . "\n" . "agenciaPlazo => " . $agenciaPlazo
        , "actualizarUsuario_DatosCorrectos");

    $DB = new DAO();
    $conn = $DB->getConnect();

    /***VALIDAMOS QUE SI EL PROFILE VIENE VACIO ENTONCES PONEMOS EL TIPO DE PROFILE
     * POR DEFAULT*/

    if ($txtProfile == null) {
        $txtProfile = 'Web';
    }

    if ($txtAdminsCompanyAlta == null) {
        $txtAdminsCompanyAlta = 0;
    }

    grabarLog(
        "txtId => " . $txtId
        . "\n" . "txtUrlImagen => " . $txtUrlImagen
        . "\n" . "txtRol => " . $txtRol
        . "\n" . "txtProfile => " . $txtProfile
        . "\n" . "txtAdminsCompanyAlta => " . $txtAdminsCompanyAlta
        . "\n" . "txtNickname => " . $txtNickname
        . "\n" . "txtPassword => " . $txtPassword
        . "\n" . "txtName => " . $txtName
        . "\n" . "txtLastName => " . $txtLastName
        . "\n" . "txtLastNameOp => " . $txtLastNameOp
        . "\n" . "txtEmail => " . $txtEmail
        . "\n" . "txtPhoneNumber => " .$txtPhoneNumber
        . "\n" . "agenciaTipo => " .$agenciaTipo
        . "\n" . "agenciaPlazo => " .$agenciaPlazo
        , "actualizarUsuario_PrepararQuery");

    /**MANDAMOS A LLAMAR EL STORE PARA ALMACENAR EL USUARIO NUEVO**/
    $response = actualizarUsuario($conn,$txtId, $txtRol, $txtProfile, $txtAdminsCompanyAlta, $txtNickname, $txtPassword, $txtUrlImagen, $txtName, $txtLastName, $txtLastNameOp, $txtEmail, $txtPhoneNumber, $agenciaTipo, $agenciaPlazo);
    //$response["CodigoRespuesta"] = 0;
    //$response["MensajeRespuesta"] = "AUN NO ESTA DISPONIBLE LA ACTUALIZACION, FAVOR DE ESPERAR...";
    //echo json_encode($response);

} else {

    grabarLog(
        "txtId => " . $_POST["txtId"]
        . "\n" . "txtUrlImagen => " . $_POST["txtUrlImagen"]
        . "\n" . "txtRol => " . $_POST["txtRol"]
        . "\n" . "txtProfile => " . isset($_POST["txtProfile"]) ? $_POST["txtProfile"] : ""
        . "\n" . "txtAdminsCompanyAlta => " . isset($_POST["txtAdminsCompanyAlta"]) ? $_POST["txtAdminsCompanyAlta"] : ""
        . "\n" . "txtNickname => " . $_POST["txtNickname"]
        . "\n" . "txtPassword => " . $_POST["txtPassword"]
        . "\n" . "txtName => " . $_POST["txtName"]
        . "\n" . "txtLastName => " . $_POST["txtLastName"]
        . "\n" . "txtLastNameOp => " . $_POST["txtLastNameOp"]
        . "\n" . "txtEmail => " . $_POST["txtEmail"]
        . "\n" . "txtPhoneNumber => " . $_POST['txtPhoneNumber']
        . "\n" . "agenciaTipo => " . isset($_POST["agenciaTipo"]) ? $_POST["agenciaTipo"] : ""
        . "\n" . "agenciaPlazo => " . isset($_POST["agenciaPlazo"]) ? $_POST["agenciaPlazo"] : ""
        , "actualizarUsuario_DatosIngresadorsErronamente");
    
    
    if($_SESSION["rol"] == "Agency")
    {
        $url = "empleadosDeAgencia.php";
    }
    else 
    {
        $url = "admins.php";
    }

    $response["url"] = $url;

    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);
}


function actualizarUsuario($conn, $txtId,$txtRol, $txtProfile, $txtAdminsCompanyAlta, $txtNickname, $txtPassword, $txtUrlImagen, $txtName, $txtLastName, $txtLastNameOp, $txtEmail, $txtPhoneNumber, $agenciaTipo, $agenciaPlazo)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtActualizarUsuario = $conn->prepare("call spActualizarUsuario(?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmtActualizarUsuario, 'iiiissssssssss', $txtId,$txtRol, $txtProfile, $txtAdminsCompanyAlta, $txtNickname, $txtPassword, $txtUrlImagen, $txtName, $txtLastName, $txtLastNameOp, $txtEmail, $txtPhoneNumber,
        $agenciaTipo, $agenciaPlazo);

    if ($stmtActualizarUsuario->execute()) {
        $stmtActualizarUsuario->store_result();
        $stmtActualizarUsuario->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtActualizarUsuario->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;

        } else {
            $response["CodigoRespuesta"] = 0;
            $response["MensajeRespuesta"] = "NO SE LOGRO INSERTAR EL USUARIO CORRECTAMENTE";

        }
        // grabarLog(json_encode($response));

        if($_SESSION["rol"] == "Agency")
        {
            $url = "empleadosDeAgencia.php";
        }
        else 
        {
            $url = "admins.php";
        }
        
        $response["url"] = $url;
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
