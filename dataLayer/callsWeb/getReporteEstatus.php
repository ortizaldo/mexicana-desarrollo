<?php
session_start();
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
$response = obtenerReporteEstatus($conn);
echo json_encode($response);


function obtenerReporteEstatus($conn)
{
    $agency = "";
    $revisionVentas ="";
    $revisionFinanciera = "";
    $rechazadoVentas = "";
    $rechazadoFinanciera = "";
    $segundaCaptura = "";

    $phPorAsignar = "";
    $phEnProceso = "";
    $phPendiente = "";
    $phCompleto = "";

    $insPorAsignar = "";
    $insEnProceso = "";
    $insPendiente = "";
    $insCompleto = "";

    $fechaInicial = "";
    $fechaFinal = "";
    
    $revisionSegundaCaptura = "";

    $data = array();
    $response = array();
    
    $fechaInicial = (isset($_GET['dateF']) && $_GET['dateF'] != "") ? $_GET['dateF'] : '';
    $fechaFinal = (isset($_GET['dateT']) && $_GET['dateT'] != "") ? $_GET['dateT'] : '';
    $nickName = (isset($_SESSION["nickname"]) && $_SESSION["nickname"] != "") ? $_SESSION["nickname"] : '';
    
    
    $stmt = $conn->prepare("call spReporteEstatus(?,?,?);");
    mysqli_stmt_bind_param($stmt, 'sss', $fechaInicial, $fechaFinal, $nickName);
    
    if ($stmt->execute()) 
    {
        $stmt->store_result();
        $stmt->bind_result($agency, $revisionVentas, $revisionFinanciera,$rechazadoVentas, $rechazadoFinanciera, $segundaCaptura, $revisionSegundaCaptura,$phPorAsignar, $phEnProceso, $phCompleto, $insPorAsignar, $insEnProceso, $insCompleto);
        
        while ($stmt->fetch()) {
            $row = array();
            $row["agency"] = $agency;
            $row["revisionVentas"] = $revisionVentas;
            $row["revisionFinanciera"] = $revisionFinanciera;
            $row["rechazadoVentas"] = $rechazadoVentas;
            $row["rechazadoFinanciera"] = $rechazadoFinanciera;
            $row["segundaCaptura"] = $segundaCaptura;
            $row["revisionSegundaCaptura"] = $revisionSegundaCaptura;
            $row["phPorAsignar"] = $phPorAsignar;
            $row["phEnProceso"] = $phEnProceso;
            $row["phCompleto"] = $phCompleto;
            $row["insPorAsignar"] = $insPorAsignar;
            $row["insEnProceso"] = $insEnProceso;
            $row["insCompleto"] = $insCompleto;
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

function grabarLog($logInfo, $nombreArchivo ="getReporteEstatus")
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = $nombreArchivo . ".txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);
}
