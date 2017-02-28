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
    $idEmployee = getIDEmployee($txtTaskEmployeeReasign);
    $idEmployeeAnt = getIDEmployeeAnt($reportType, $rowIDReporte);
    $resGetIDReportReasign = getIDReportReasign($reportType, $rowIDReporte);
    //echo "count ".count($resGetIDReportReasign);
    //var_dump($resGetIDReportReasign);
    if (count($resGetIDReportReasign) == 1) {
        //generamos un update
        $stmtReportR = "UPDATE reportesReasignados SET idUserAnterior = ?, idRepReasign= ?, tipoReporte=?, activo=1, updated_at=NOW() WHERE id = ?;";
        $banderaUpCreate=1;
    }elseif (count($resGetIDReportReasign) == 0) {
        //genearamos un insert
        $stmtReportR = "INSERT reportesReasignados (idRepReasign, idUserAnterior, tipoReporte,activo, created_at, updated_at) VALUES (?,?,?,1,NOW(),NOW());";
        $banderaUpCreate=0;
    }
    if ($stmtReportAnt = $conn->prepare($stmtReportR)) {
        if ($banderaUpCreate == 1) {
            $idRepR = $resGetIDReportReasign[0]["idRepR"];
            $idUserAssigned = $resGetIDReportReasign[0]["idUserAssigned"];
            $stmtReportAnt->bind_param("iiii", $idUserAssigned, $rowIDReporte,$reportType, $idRepR);
        }elseif ($banderaUpCreate == 0) {
            $stmtReportAnt->bind_param("iii", $rowIDReporte, $idEmployeeAnt, $reportType);
        }
        if ($stmtReportAnt->execute()) {
            $stmtReport = "UPDATE report SET idEmployee = ?, idUserCreator = ? WHERE id = ?;";
            if ($stmtReport = $conn->prepare($stmtReport)) {
                $stmtReport->bind_param("iii", $idEmployee, $txtTaskEmployeeReasign, $rowIDReporte);
                if ($stmtReport->execute()) {
                    error_log('pasamos primer update');
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
        }else{
            echo "error ".$conn->error;
        }
    }else{
        echo "error ".$conn->error;
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

function getIDEmployeeAnt($reportType, $idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '' && $reportType != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdUserQL = "SELECT idUserAssigned FROM reportHistory WHERE idReport = $idReport and idReportType = $reportType;";
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

function getIDReportReasign($reportType, $idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '' && $reportType != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepRQL = "SELECT 
                            a.id, b.idUserAssigned
                        FROM
                            reportesReasignados a,
                            reportHistory b
                        WHERE 0=0
                        AND a.idRepReasign = b.idReport
                        AND b.idReport = $idReport
                        AND b.idReportType = $reportType;";
        $result = $conn->query($getIdRepRQL);
        $res=[];
        if ($result->num_rows > 0) {
            $cont=0;
            while($row = $result->fetch_array()) {
                $res[$cont]["idRepR"]=$row[0];
                $res[$cont]["idUserAssigned"]=$row[1];
                $cont++;
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