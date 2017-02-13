<?php

session_start();
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
$response = obtenerReporte($conn);
echo json_encode($response);


function obtenerReporte($conn)
{
    $id = "";
    $agreementNumber = "";
    $agencia = "";
    $validadoPor = "";
    $reason = "";
    
    $data = array();
    $response = array();
    
    $fechaInicial = (isset($_GET['dateF']) && $_GET['dateF'] != "") ? $_GET['dateF'] : '';
    $fechaFinal = (isset($_GET['dateT']) && $_GET['dateT'] != "") ? $_GET['dateT'] : '';
    $nickName = (isset($_SESSION["nickname"]) && $_SESSION["nickname"] != "") ? $_SESSION["nickname"] : '';
    
    $stmt = $conn->prepare("call spReporteRechazos(?,?,?);");
    mysqli_stmt_bind_param($stmt, 'sss', $fechaInicial, $fechaFinal, $nickName);
    
    if ($stmt->execute()) 
    {
        $stmt->store_result();
        $stmt->bind_result($id, $agreementNumber, $agencia, $validadoPor, $reason);
        
        while ($stmt->fetch()) {
            $row = array();
            $row["id"] = $id;
            $row["agreementNumber"] = $agreementNumber;
            $row["agencia"] = $agencia;
            $row["validadoPor"] = $validadoPor;
            $row["reason"] = $reason;
            $data[] = $row;
        }
        
        $response["CodigoRespuesta"] = 1;
        $response["data"] = $data;
    }
    else
    {
        $response["CodigoRespuesta"] = 0;
        $response["MensajeRespuesta"] = "Ocurrio un problema al momento de obtener la información del reporte";
    }
    
    return $response;
}

function grabarLog($logInfo, $nombreArchivo ="getReporteRechazo")
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = $nombreArchivo . ".txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);
}