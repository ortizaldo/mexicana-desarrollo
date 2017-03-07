<?php
session_start();
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
$response = obtenerReporteVentas($conn);
echo json_encode($response);


function obtenerReporteVentas($conn)
{
    $id = "";
    $agreementNumber = "";
    $idClienteGenerado = "";
    $estatus_ph = "";
    $estatus_venta = "";
    $estatus_instalacion = "";
    $idCity = "";
    $colonia = "";
    $street = "";
    $nombre_usuario = "";
    $agencia = "";
    $fecha = "";
    
    $estatusVenta = "";
    $phEstatus = "";
    $estatusAsignacionInstalacion = "";
    $data = array();
    $response = array();
    
    $fechaInicial = (isset($_POST['fechaInicial']) && $_POST['fechaInicial'] != "") ? $_POST['fechaInicial'] : '';
    $fechaFinal = (isset($_POST['fechaFinal']) && $_POST['fechaFinal'] != "") ? $_POST['fechaFinal'] : '';
    $nickName = (isset($_SESSION["nickname"]) && $_SESSION["nickname"] != "") ? $_SESSION["nickname"] : '';
    
    
    $stmt = $conn->prepare("call spReporteVentas(?,?,?);");
    mysqli_stmt_bind_param($stmt, 'sss', $fechaInicial, $fechaFinal, $nickName);
    
    if ($stmt->execute()) 
    {
        $stmt->store_result();
        $stmt->bind_result($id, $agreementNumber, $idClienteGenerado,$phEstatus, $estatus_ph, $estatusVenta,$estatus_venta, $estatusAsignacionInstalacion,$validacionSegundaVenta,$estatus_seg_venta, $estatusReporte, $estatus_instalacion, $idCity, $colonia, $street,$innerNumber,$outterNumber, $nombre_usuario, $agencia, $fecha);
        while ($stmt->fetch()) {
            $row = array();
            $row["id"] = $id;
            $row["agreementNumber"] = $agreementNumber;
            $row["idClienteGenerado"] = $idClienteGenerado;
            $row["phEstatus"] = $phEstatus;
            $row["estatus_ph"] = $estatus_ph;
            $row["estatusVenta"] = $estatusVenta;
            $row["estatus_venta"] = $estatus_venta;
            $row["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
            $row["validacionSegundaVenta"] = $validacionSegundaVenta;
            $row["estatus_seg_venta"] = $estatus_seg_venta;
            $row["estatusReporte"] = $estatusReporte;
            $row["estatus_instalacion"] = $estatus_instalacion;
            $row["idCity"] = $idCity;
            $row["colonia"] = $colonia;
            $row["street"] = $street;
            $row["innerNumber"] = $innerNumber;
            $row["outterNumber"] = $outterNumber;
            $row["nombre_usuario"] = $nombre_usuario;
            $row["agencia"] = $agencia;
            $row["fecha"] = $fecha;
            $data[] = $row;
        }
        
        $response["CodigoRespuesta"] = 1;
        $response["data"] = $data;
    }
    else
    {
        $response["CodigoRespuesta"] = 0;
        $response["MensajeRespuesta"] = "Ocurrio un problema al momento de obtener la información de las ventas";
    }
    
    return $response;
}

function grabarLog($logInfo, $nombreArchivo ="getReporteVentas")
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = $nombreArchivo . ".txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);
}