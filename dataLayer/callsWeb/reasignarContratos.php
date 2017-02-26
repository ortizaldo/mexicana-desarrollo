<?php include_once "../DAO.php";
include_once "../libs/utils.php";

$DB = new DAO();
$conn = $DB->getConnect();

$DB = new DAO();
$conn = $DB->getConnect();

$rowIDReporte = $_POST["rowIDReporte"];
$rowTRTipoReporte = $_POST["rowTRTipoReporte"];
$txtTaskEmployeeReasign = $_POST["txtTaskEmployeeReasign"];
$agencia = $_POST["agencia"];

if ($txtTaskEmployeeReasign != "" && $rowIDReporte != "" && $rowTRTipoReporte != "" && $agencia != "") {
    $idEmployee = getIDEmployee($txtTaskEmployeeReasign);
    $stmtReport = "UPDATE report SET idEmployee = ?, idUserCreator = ? WHERE id = ?;";
    if ($stmtReport = $conn->prepare($stmtReport)) {
        $stmtReport->bind_param("iii", $idEmployee, $txtTaskEmployeeReasign, $rowIDReporte);
        if ($stmtReport->execute()) {
            error_log('pasamos primer update');
            switch ($rowTRTipoReporte) {
                case 'Venta':
                    $reportType = 2;
                    break;
                case 'Plomero':
                    $reportType = 3;
                    break;
                case 'Instalacion':
                    $reportType = 4;
                    break;
                case 'Segunda Venta':
                    $reportType = 5;
                    break;
            }
            if ($reportType != "") {
                $idReportH = getIdHist($reportType, $rowIDReporte);
                if (intval($idReportH) > 0) {
                    error_log('segundo update');
                    $stmtReportH = "UPDATE reportHistory SET idUserAssigned = ?,reasignado = 1, 
                                                             updated_at = NOW() 
                                                             WHERE idReportHistory = ?;";
                    if ($stmtRH = $conn->prepare($stmtReportH)) {
                        $stmtRH->bind_param("ii",$txtTaskEmployeeReasign, $idReportH);
                        if ($stmtRH->execute()) {
                            error_log('pasamos segundo update');
                            $reportTVTAID = getReportTVTAID($rowIDReporte);
                            $result["status"] = "EXITO";
                            $result["code"] = "200";
                            $result["idEmpleado"] = $txtTaskEmployeeReasign;
                            $result["result"] = "La instalcion se asigno correctamente";
                            /*if (intval($reportTVTAID) > 0) {
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
                            }*/
                        }else{
                            $result["status"] = "BAD";
                            $result["code"] = "500";
                            $result["result"] = "No se puede asignar la instalacion";
                        }
                    }
                }else{
                    $result["status"] = "BAD";
                    $result["code"] = "500";
                    $result["result"] = "error en history";
                }
            }else{
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = "No se encontro el tipo de reporte";
            }
        }
    }else{
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = "error mysql ".$conn->error;
    }
    echo json_encode($result);
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