<?php include_once "../DAO.php";
include_once "../libs/utils.php";

$DB = new DAO();
$conn = $DB->getConnect();

$employee; $reports = []; $result = [];


if (isset($_POST["arrSolicitudes"])) {
    $result = array();
    foreach ($_POST["arrSolicitudes"] as $key => $value) {
        if (intval($value["perfil"]) == 3) {
            # plomerias
            $resultado = asignarPlomeria($value["agencia"],$value["empleado"],$value["idReporte"]);
        }elseif (intval($value["perfil"]) == 4) {
            # instalaciones
            $resultado = asignarInstalacion($value["agencia"],$value["empleado"],$value["idReporte"]);
        }
        array_push($result, $resultado);
    }
    echo json_encode($result);
}

function asignarInstalacion($agencia,$empleado,$idReporte){
    $DB = new DAO();
    $conn = $DB->getConnect();
    $idEmployee = getIDEmployee($empleado);
    $stmtReport = "UPDATE report SET idEmployee = ?, idUserCreator = ? WHERE id = ?;";
    if ($stmtReport = $conn->prepare($stmtReport)) {
        $stmtReport->bind_param("iii", $idEmployee, $empleado, $idReporte);
        if ($stmtReport->execute()) {
            error_log('primer update');
            $stmtTEstatus = "UPDATE tEstatusContrato SET estatusAsignacionInstalacion = 50, 
                                                         idEmpleadoInstalacion = ? 
                                                         WHERE idReporte = ?;";
            if ($estatusCrontratoReport = $conn->prepare($stmtTEstatus)) {
                $estatusCrontratoReport->bind_param("ii",$idEmployee, $idReporte);
                if ($estatusCrontratoReport->execute()) {
                    error_log('pasamos primer update');
                    $reportType = 4;
                    $idReportH = getIdHist($reportType, $idReporte);
                    if (intval($idReportH) > 0) {
                        error_log('segundo update');
                        $stmtReportH = "UPDATE reportHistory SET idUserAssigned = ?, 
                                                                 updated_at = NOW() 
                                                                 WHERE idReportHistory = ?;";
                        if ($stmtRH = $conn->prepare($stmtReportH)) {
                            $stmtRH->bind_param("ii",$empleado, $idReportH);
                            if ($stmtRH->execute()) {
                                error_log('pasamos segundo update');
                                $reportTVTAID = getReportTVTAID($idReporte);
                                if (intval($reportTVTAID) > 0) {
                                    error_log('tercer update');
                                    $stmtReportH = "UPDATE reportTiempoVentas SET fechaFinAsigInst=now(), fechaInicioRealInst=now()
                                                    WHERE idReporte = ?;";
                                    if ($stmtRH = $conn->prepare($stmtReportH)) {
                                        $stmtRH->bind_param("i",$idReporte);
                                        if ($stmtRH->execute()) {
                                            $result["status"] = "EXITO";
                                            $result["code"] = "200";
                                            $result["idEmpleado"] = $empleado;
                                            $result["result"] = "La instalcion se asigno correctamente";
                                        }else{
                                            $result["status"] = "BAD";
                                            $result["code"] = "500";
                                            $result["result"] = "No se puede asignar la instalacion";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "error mysql ".$conn->error;
    }
    return $result;
}

function asignarPlomeria($agencia,$empleado,$idReporte){
    error_log('message plomeria');
    $DB = new DAO();
    $conn = $DB->getConnect();
    $idEmployee = getIDEmployee($empleado);
    $stmtReport = "UPDATE report SET idEmployee = ?, idUserCreator = ? WHERE id = ?;";
    if ($stmtReport = $conn->prepare($stmtReport)) {
        $stmtReport->bind_param("iii", $idEmployee, $empleado, $idReporte);
        if ($stmtReport->execute()) {
            error_log('primer update');
            $stmtTEstatus = "UPDATE tEstatusContrato SET phEstatus = 30, 
                                                         idEmpleadoPhAsignado = ?,
                                                         fechaMod = NOW() 
                                                         WHERE idReporte = ?;";
            if ($estatusCrontratoReport = $conn->prepare($stmtTEstatus)) {
                $estatusCrontratoReport->bind_param("ii",$idEmployee, $idReporte);
                if ($estatusCrontratoReport->execute()) {
                    error_log('pasamos primer update');
                    $reportType = 4;
                    //$idReportH = getIdHist($reportType, $idReporte);
                    //if (intval($idReportH) > 0) {
                        error_log('segundo update');
                        $searchReportsSQL = "SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned,
                                                RP.idFormulario,RP.idForm,RP.idSolicitudMovil 
                                         FROM report AS RP WHERE RP.id = ?;";
                        if ($searchReports = $conn->prepare($searchReportsSQL)) {
                            $searchReports->bind_param("i", $idReporte);
                            if ($searchReports->execute()) {
                                $searchReports->store_result();
                                $searchReports->bind_result($idReport, $employee, $creator, $reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
                                $searchReports->fetch();
                                $plumber = 3;
                                $idEstatusReport = 4;

                                $querySmt = "INSERT INTO reportHistory(idReport,idFormSell,idReportType,idUserAssigned,idStatusReport,idFormulario, idSolicitud, updated_at,created_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())";
                
                                if ($stmtRH = $conn->prepare($querySmt)) {
                                    $stmtRH->bind_param("iiiiiii", $idReport, $idFormulario, $plumber, $empleado, $idEstatusReport,$idForm,$idSolicitudMovil);
                                    if ($stmtRH->execute()) {
                                        error_log('pasamos segundo update');
                                        $reportTVTAID = getReportTVTAID($idReporte);
                                        if (intval($reportTVTAID) > 0) {
                                            error_log('tercer update');
                                            $stmtReportH = "UPDATE reportTiempoVentas SET fechaFinAsigPH=NOW(),fechaInicioRealizoPH=NOW()
                                                            WHERE idReporte = ?;";
                                            if ($stmtRH = $conn->prepare($stmtReportH)) {
                                                $stmtRH->bind_param("i",$idReporte);
                                                if ($stmtRH->execute()) {
                                                    $result["status"] = "EXITO";
                                                    $result["code"] = "200";
                                                    $result["idEmpleado"] = $empleado;
                                                    $result["result"] = "La Plomeria se asigno correctamente";
                                                }else{
                                                    $result["status"] = "BAD";
                                                    $result["code"] = "500";
                                                    $result["result"] = "No se puede asignar la instalacion";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } 
                    //}
                }
            }
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "error mysql ".$conn->error;
    }
    return $result;
}


function getIdHist($reportType, $idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($reportType != '' && $idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepHSQL = "SELECT idReportHistory FROM reportHistory WHERE idReport = $idReport and idReportType=$reportType;";
        $result = $conn->query($getIdRepHSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}

function getIDEmployee($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdUserQL = "SELECT id FROM employee WHERE idUser = $idUser;";
        $result = $conn->query($getIdUserQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}

function getReportTVTAID($idReport)
{
    //generamos una consulta para obtener id
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepHSQL = "SELECT id FROM reportTiempoVentas WHERE idReporte = $idReport";
        $result = $conn->query($getIdRepHSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}