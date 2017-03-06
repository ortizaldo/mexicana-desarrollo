<?php 
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
session_start();
/*
$ESTATUS_VENTA_DEPURADO = 11;
$ESTATUS_PH_DEPURADO = 35;
$ESTATUS_SEGUNDA_VENTA_DEPURADO = 45;
$ESTATUS_INSTALACION_DEPURADO = 55;
$ESTATUS_REPORTE_DEPURADO = 65;

$ESTATUS_VENTA_ELIMINADO = 12;
$ESTATUS_PH_ELIMINADO = 36;
$ESTATUS_SEGUNDA_VENTA_ELIMINADO = 46;
$ESTATUS_INSTALACION_ELIMINADO = 57;
$ESTATUS_REPORTE_ELIMINADO = 66;
*/
$rowIDReporte = intval($_POST["rowIDReporte"]);
$opcionDep = $_POST["opcionDep"];
$motivoCancel = $_POST["motivoCancel"];
$NicknameUsuarioLogeado = $_SESSION["nickname"];
if ((isset($_POST['rowIDReporte'])) && intval($_POST['rowIDReporte']) > 0) {
    $DB = new DAO();
    $conn = $DB->getConnect();
    if (intval($opcionDep) == 1) {
        $updateTEstatusContratoSQL="UPDATE tEstatusContrato SET estatusReporte=65, estatusVenta=11, validacionSegundaVenta=45,estatusAsignacionInstalacion=55,phEstatus=35
                        where idReporte=?";
    }elseif (intval($opcionDep) == 2) {
        $updateTEstatusContratoSQL="UPDATE tEstatusContrato SET estatusReporte=66 where idReporte=?";
    }
    if ($updateTEstatusContrato = $conn->prepare($updateTEstatusContratoSQL)) {
        $updateTEstatusContrato->bind_param("i", $rowIDReporte);
        if ($updateTEstatusContrato->execute()) {
            $updateReportSQL="UPDATE report SET motivoCancelado=?, quienCancela=? where id=?";
            if ($updateReport = $conn->prepare($updateReportSQL)) {
                $updateReport->bind_param("ssi", $motivoCancel,$NicknameUsuarioLogeado, $rowIDReporte);
                if ($updateReport->execute()) {
                    $updateReportHistorySQL="UPDATE reportHistory SET idStatusReport=8 where idReport=?";
                    if ($updateReportHistory = $conn->prepare($updateReportHistorySQL)) {
                        $updateReportHistory->bind_param("i", $rowIDReporte);
                        if ($updateReportHistory->execute()) {
                            $result["status"] = "OK";
                            $result["code"] = "200";
                            $result["result"] = "El registro se elimino correctamente";
                        }
                    }
                }
            }
        }else{
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "Error de Base De Datos, ".$conn->error;
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "Error de Base De Datos, ".$conn->error;
    }
    echo json_encode($result);
}else{
    $result["status"] = "BAD";
    $result["code"] = "500";
    $result["result"] = "Hat algunos datos vacios";
    echo json_encode($result);
}