<?php
session_start();
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/classes/reporteTiemposVentas.php";



$response = obtenerReporteTiempos();
echo json_encode($response);


function obtenerReporteTiempos()
{
    $reporteTiemposVenta = new ReporteTiemposVentas();
    $data = & $reporteTiemposVenta->geTiemposReporte();
    
    $response["CodigoRespuesta"] = 1;
    $response["data"] = $data;
        
    
    
    
    return $response;
}

function grabarLog($logInfo, $nombreArchivo ="getReporteTiempos")
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = $nombreArchivo . ".txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);
}

