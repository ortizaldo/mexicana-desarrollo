<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
error_log(json_encode($_POST));
if (isset($_POST["idEmployee"]) && isset($_POST["idDevice"]) && isset($_POST["tokenDevice"])) {

    $idEmployee = $_POST["idEmployee"];
    $idDevice = $_POST["idDevice"];
    $tokenDevice = $_POST["tokenDevice"];
    $DB = new DAO();
    $conn = $DB->getConnect();
    registrarTokenDeviceDispositivoMovil($conn, $idEmployee, $idDevice, $tokenDevice);
} else {
    $response["CodigoRespuesta"] = "ERROR";
    $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
    echo json_encode($response);
}


function registrarTokenDeviceDispositivoMovil($conn, $idEmployee, $idDevice, $tokenDevice)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";
    error_log('message entramos a registrarTokenDeviceDispositivoMovil');
    $resGetIdDevice=getIDDevice($idDevice);
    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    #$getUserInfoSP = "call spRegistrarTokenDeviceDispositivoMovil(?,?,?);";
    //validamos que el dispositivo exista si no existe lo registramos

    /*if($getUserInfo = $conn->prepare($getUserInfoSP)){
    	mysqli_stmt_bind_param($getUserInfo, 'iis', $idEmployee, $idDevice, $tokenDevice);
        if ($getUserInfo->execute()) {
        	$getUserInfo->store_result();
        	$getUserInfo->bind_result($CodigoRespuesta, $MensajeRespuesta);
        	if ($getUserInfo->fetch()) {
            		$response["CodigoRespuesta"] = $CodigoRespuesta;
            		$response["MensajeRespuesta"] = $MensajeRespuesta;
            		echo json_encode($response);

        	} else {
            		$response["CodigoRespuesta"] = "ERROR";
            		$response["MensajeRespuesta"] = "NO SE LOGRO REGISTRAR EL DISPOSITIVO MOVIL";
            		echo json_encode($response);
        	}
    	}
    }else{
	   error_log('error '.$conn->error);
    }*/
    //$conn->free_result();
    $conn->close();
}
function getIDDevice($idDevice)
{
    //generamos una consulta para obtener id
    if ($idDevice != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdDeviceSQL = "SELECT id  FROM device WHERE idDevice = $idDevice";
        $result = $conn->query($getIdDeviceSQL);
        $res="";
        error_log('id num devices '.$result->num_rows);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}
