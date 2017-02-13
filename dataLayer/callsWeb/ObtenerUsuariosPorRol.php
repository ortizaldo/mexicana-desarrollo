<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (
isset($_POST["rolUsuarioABuscar"])

) {

    $DB = new DAO();
    $conn = $DB->getConnect();
    $rolUsuarioABuscar = $_POST["rolUsuarioABuscar"];

    $response = obtenerUsuariosPorRol($conn, $rolUsuarioABuscar);

} else {

    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);

}


function obtenerUsuariosPorRol($conn, $rolUsuarioABuscar)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $returnUsuarios = array();
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";
    $id = "";
    $nickname = "";
    $name = "";
    $lastName = "";
    $lastNameOp = "";
    $email = "";
    $phoneNumber = "";
    $active = "";
    $updated_at = "";
    $type = "";
    $perfilEmpleado = "";
    $agenciaDelEmpleado = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtobtenerUsuariosPorRol = $conn->prepare("call spObtenerUsuariosPorRol(?);");
    $stmtobtenerUsuariosPorRol->bind_param("i", $rolUsuarioABuscar);

    if ($stmtobtenerUsuariosPorRol->execute()) {
        $stmtobtenerUsuariosPorRol->store_result();
        $stmtobtenerUsuariosPorRol->bind_result($CodigoRespuesta, $MensajeRespuesta, $id, $name, $nickname,$lastName, $lastNameOp, $email, $phoneNumber, $active,
            $updated_at, $type, $perfilEmpleado, $agenciaDelEmpleado);
        while ($stmtobtenerUsuariosPorRol->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;
            $response["id"] = $id;
            $response["name"] = $name;
            $response["nickname"] = $nickname;
            $response["lastName"] = $lastName;
            $response["lastNameOp"] = $lastNameOp;
            $response["email"] = $email;
            $response["phoneNumber"] = $phoneNumber;
            $response["active"] = $active;
            $response["updated_at"] = $updated_at;
            $response["type"] = $type;
            $response["perfilEmpleado"] = $perfilEmpleado;
            $response["agenciaDelEmpleado"] = $agenciaDelEmpleado;

            $returnUsuarios[] = $response;
        }
        echo json_encode($returnUsuarios);
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
