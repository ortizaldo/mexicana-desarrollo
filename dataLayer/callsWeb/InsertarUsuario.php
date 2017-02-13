<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

session_start();
error_log(json_encode($_POST));
if (isset($_POST["txtUrlImagen"])
    && isset($_POST["txtRol"])
    && isset($_POST["txtNickname"])
    && isset($_POST["txtPassword"])
    && isset($_POST["txtName"])
    && isset($_POST["txtLastName"])
    && isset($_POST["txtLastNameOp"])
    && isset($_POST["txtEmail"])
    && isset($_POST["txtPhoneNumber"])
) {
    /**CAMPOS OBLIGATORIOS PARA TODOS LOS USUARIOS**/
    $txtUrlImagen = $_POST["txtUrlImagen"];
    $txtRol = $_POST["txtRol"];
    $txtProfile = isset($_POST["txtProfile"]) ? $_POST["txtProfile"] : null;
    $txtAdminsCompanyAlta = isset($_POST["txtAdminsCompanyAlta"]) ? $_POST["txtAdminsCompanyAlta"] : "";
    $txtNickname = $_POST["txtNickname"];
    $txtName = $_POST["txtName"];
    $txtPassword = $_POST["txtPassword"];
    $txtLastName = $_POST["txtLastName"];
    $txtLastNameOp = $_POST["txtLastNameOp"];
    $txtEmail = $_POST["txtEmail"];
    $txtPhoneNumber = $_POST["txtPhoneNumber"];

    /**CAMPOS EXCLUSIVOS PARA AGENCIA*/
    $txtAgenciaTipo = isset($_POST["txtAgenciaTipo"]) ? $_POST["txtAgenciaTipo"] : null;
    $agenciaPlazo = isset($_POST["agenciaPlazo"]) ? $_POST["agenciaPlazo"] : null;

    grabarLog(
        "txtUrlImagen => " . $_POST["txtUrlImagen"]
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
        . "\n" . "txtAgenciaTipo => " . $txtAgenciaTipo
        . "\n" . "agenciaPlazo => " . $agenciaPlazo
        , "InsertarUsuario_DatosCorrectos");

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

    if ($txtAgenciaTipo == null) {
        $txtAgenciaTipo = 1;
    }

    if ($agenciaPlazo == null) {
        $agenciaPlazo = 0;
    }
    //validamos que no sea un nickname existente dentro de las agencias
    $resNickNameUser=getNickNameUser($txtAdminsCompanyAlta, $txtNickname, $txtEmail);
    if ($resNickNameUser == "") {
        $response = insertarUsuario($conn, $txtRol, $txtProfile, $txtAdminsCompanyAlta, $txtNickname, $txtPassword, $txtUrlImagen, $txtName, $txtLastName, $txtLastNameOp, $txtEmail, $txtPhoneNumber, $txtAgenciaTipo, $agenciaPlazo);
    }else{
        $response["CodigoRespuesta"] = 0;
        $response["MensajeRespuesta"] = "El usuario ya existe.";
        echo json_encode($response);
    }

} else {
    grabarLog(
        "txtUrlImagen => " . $_POST["txtUrlImagen"]
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
        . "\n" . "txtAgenciaTipo => " . isset($_POST["txtAgenciaTipo"]) ? $_POST["txtAgenciaTipo"] : ""
        . "\n" . "agenciaPlazo => " . isset($_POST["agenciaPlazo"]) ? $_POST["agenciaPlazo"] : ""
        , "InsertarUsuario_DatosIngresadorsErronamente");
    $url = "";
    if($_SESSION["rol"] == "Agency")
    {
        $url = "empleadosDeAgencia.php";
    }else{
        $url = "admins.php";
    }
    $response["url"] = $url;
    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);
}

function insertarUsuario($conn, $txtRol, $txtProfile, $txtAdminsCompanyAlta, $txtNickname, $txtPassword, $txtUrlImagen, $txtName, $txtLastName, $txtLastNameOp, $txtEmail, $txtPhoneNumber, $txtAgenciaTipo, $agenciaPlazo)
{
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    $stmtInsertarUsuario = $conn->prepare("call spInsertarUsuario(?,?,?,?,?,?,?,?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmtInsertarUsuario, 'iiissssssssss', $txtRol, $txtProfile, $txtAdminsCompanyAlta, $txtNickname, $txtPassword, $txtUrlImagen, $txtName, $txtLastName, $txtLastNameOp, $txtEmail, $txtPhoneNumber,
        $txtAgenciaTipo, $agenciaPlazo);

    if ($stmtInsertarUsuario->execute()) {
        $stmtInsertarUsuario->store_result();
        $stmtInsertarUsuario->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtInsertarUsuario->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;

        } else {
            $response["CodigoRespuesta"] = 0;
            $response["MensajeRespuesta"] = "NO SE LOGRO INSERTAR EL USUARIO CORRECTAMENTE";

        }
        // grabarLog(json_encode($response));
        
        $url = "";
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

function getNickNameUser($txtAdminsCompanyAlta, $txtNickname, $inTxtEmail)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($txtAdminsCompanyAlta != '' && $txtNickname != "") {
        $getNickName = "SELECT a.nickname
                        FROM user a, agency_employee b, employee c
                        WHERE 0=0 
                        AND b.idemployee=c.id 
                        AND c.idUser=a.id 
                        AND b.idAgency=$txtAdminsCompanyAlta 
                        AND a.nickname='".$txtNickname."';";
    }elseif ($$inTxtEmail != "" || $txtNickname != "") {
        $getNickName = "SELECT id
                        FROM user
                        WHERE 0=0
                        AND (nickname='".$txtNickname."' OR email='".$inTxtEmail."');";
    }
    $DB = new DAO();
    $conn = $DB->getConnect();
    $result = $conn->query($getNickName);
    $res="";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $res=$row[0];
        }
    }
    $conn->close();
    return $res;
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
