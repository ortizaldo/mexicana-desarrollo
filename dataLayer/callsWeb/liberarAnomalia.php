<?php 
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
/*
$ESTATUS_VENTA_DEPURADO = 11;
$ESTATUS_PH_DEPURADO = 35;
$ESTATUS_SEGUNDA_VENTA_DEPURADO = 45;
$ESTATUS_INSTALACION_DEPURADO = 55;
$ESTATUS_REPORTE_DEPURADO = 65;
*/
session_start();
$rowIDReporte = intval($_POST["rowIDReporte"]);
$idFormInstall = $_POST["idFormInstall"];
$commentsLibAnom = $_POST["commentsLibAnom"];
$NicknameUsuarioLogeado = $_SESSION["nickname"];
if (((isset($_POST['rowIDReporte'])) && intval($_POST['rowIDReporte']) > 0) &&
    ((isset($_POST['idFormInstall'])) && intval($_POST['idFormInstall']) > 0)) {
    $DB = new DAO();
    $conn = $DB->getConnect();
    $updateTEstatusContratoSQL="UPDATE tEstatusContrato SET estatusAsignacionInstalacion=50
                        where idReporte=?";
    if ($updateTEstatusContrato = $conn->prepare($updateTEstatusContratoSQL)) {
        $updateTEstatusContrato->bind_param("i", $rowIDReporte);
        if ($updateTEstatusContrato->execute()) {
            //eliminamos los datos del formulario anterior
            $deleteSQLForm_installation_multimedia="DELETE FROM form_installation_multimedia where idFormInstallation=?";
            $deleteSQLForm_installation_details="DELETE FROM form_installation_details where idFormInstallation=?";
            $deleteSQLForm_installation="DELETE FROM form_installation where id=?";
            $banderaMuFI = 0;
            $banderaDetailsFI = 0;
            if ($deleteMuFI = $conn->prepare($deleteSQLForm_installation_multimedia)) {
                $deleteMuFI->bind_param("i", $idFormInstallation);
                if ($deleteMuFI->execute()) {
                    error_log('message eliminamos las fotos');
                    if ($deleteDetailsFI = $conn->prepare($deleteSQLForm_installation_details)) {
                        $deleteDetailsFI->bind_param("i", $idFormInstallation);
                        if ($deleteDetailsFI->execute()) {
                            $banderaDetailsFI = 1;
                            error_log('message eliminamos el detalle');
                            if ($deleteFI = $conn->prepare($deleteSQLForm_installation)) {
                                $deleteFI->bind_param("i", $idFormInstallation);
                                if ($deleteFI->execute()) {
                                    error_log('message eliminamos el formulario');
                                    //actualizamos el history y la tabla de tiempos
                                    $updateReportHistorySQL="UPDATE reportHistory SET idStatusReport=4, liberadoAnomalia=1 where idReport=? and idReportType = 4";
                                    if ($updateReportHistory = $conn->prepare($updateReportHistorySQL)) {
                                        $updateReportHistory->bind_param("i", $rowIDReporte);
                                        if ($updateReportHistory->execute()) {
                                            error_log('message actualizamos history');
                                            $updateReportVTASQL="UPDATE reportTiempoVentas SET fechaFinAnomInst=NOW() where idReporte=?";
                                            if ($updateReportVTA = $conn->prepare($updateReportVTASQL)) {
                                                $updateReportVTA->bind_param("i", $rowIDReporte);
                                                if ($updateReportVTA->execute()) {
                                                    $updateReportSQL="UPDATE report SET motivoLiberacion=?, quienLibAnomalia=? where id=?";
                                                    if ($updateReport = $conn->prepare($updateReportSQL)) {
                                                        $updateReport->bind_param("ssi", $commentsLibAnom,$NicknameUsuarioLogeado, $rowIDReporte);
                                                        if ($updateReport->execute()) {
                                                            $result["status"] = "OK";
                                                            $result["code"] = "200";
                                                            $result["result"] = "La anomalia se libero correctamente";
                                                        }
                                                    }
                                                }
                                            }else{
                                                error_log('message actualizamos tvta '.$conn->error);
                                            }
                                        }else{
                                            error_log('message actualizamos history '.$conn->error);
                                        }
                                    }else{
                                        error_log('message actualizamos history '.$conn->error);
                                    }
                                }else{
                                    error_log('error eliminamos el formulario '.$conn->error);
                                }
                            }
                        }else{
                            error_log('error eliminamos el detalle '.$conn->error);
                        }
                    }
                }else{
                    error_log('error eliminamos las fotos '.$conn->error);
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