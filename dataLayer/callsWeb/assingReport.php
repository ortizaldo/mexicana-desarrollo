<?php include_once "../DAO.php";
include_once "../libs/utils.php";

$DB = new DAO();
$conn = $DB->getConnect();

$employee; $reports = []; $result = [];

if (isset($_POST['param']) && isset($_POST['employee'])) {
    //$userID;
    $param = $_POST['param'];
    $employee = $_POST['employee'];
    $employee = (int)$employee;
    $profileToAssign = $_POST['profile'];
    $profileToAssign = intval($profileToAssign);
    //var_dump($_POST);
    //$reports = json_decode($_POST['reports']);
    $report = $_POST['reports'];
    $report = intval($report);
      
    $result = "";

    //Flujo numero 1
    $idWorkflow = 1;
    //En Proceso
    $idStatus = 4; $profileID=0;
    //$typeSell = 2;

    //$getEmployeeID = $conn->prepare("SELECT user.id, user.nickname, rol.type FROM user INNER JOIN user_rol ON user.id = user_rol.idUser INNER JOIN rol ON user_rol.idRol = rol.id AND user_rol.idUser = user.id WHERE nickname = ?;");
    $getEmployeeData = $conn->prepare("SELECT user.id, employee.id, profile.id , profile.name FROM user INNER JOIN employee ON user.id = employee.idUser INNER JOIN profile ON employee.idProfile = profile.id WHERE user.id = ?;");
    $getEmployeeData->bind_param('i', $employee);
    if ($getEmployeeData->execute()) {
        $getEmployeeData->store_result();
        $getEmployeeData->bind_result($id, $employeeID, $profileId, $profileName);
        //$getEmployeeData->fetch();
        if ($getEmployeeData->fetch()) {
            $profileID = $profileId;
        }
    }

    if ($profileToAssign == 2) {
        $getReportUserCreation = $conn->prepare("SELECT RP.idReport FROM reportHistory AS RP WHERE RP.idUserAssigned = ? AND RP.idReportType=2;");
        $getReportUserCreation->bind_param('i', $id);
        if ($getReportUserCreation->execute()) {
            $getReportUserCreation->store_result();
            $getReportUserCreation->bind_result($idReportResult);
            if (!$getReportUserCreation->fetch()) {
                $result["status"] = "ERROR";
                $result["code"] = "200";
                $result["result"] = "Debes seleccionar el usuario de creacion";
                echo json_encode($result);
                exit;
            }
        }
    }

    
    $getEstatusContrato = $conn->prepare("select estatusReporte from tEstatusContrato where asignadoMexicana is null and idReporte = ?;");
    $getEstatusContrato->bind_param('i', $report);
    if ($getEstatusContrato->execute()) {
        $getEstatusContrato->store_result();
        $getEstatusContrato->bind_result($estatusReporte);
        if ($getEstatusContrato->fetch()) {
            $estatusReporte = intval($estatusReporte);
            if($estatusReporte==60){
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = "Es necesario completar primera venta para asignar segunda venta o plomeria ";
                 echo json_encode($result);
                exit;       
            }
        }
    }

    // 1 Plomería    2 Segunda Venta
    if($profileToAssign == 1) {
        $searchReports = $conn->prepare("SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned,
                                                RP.idFormulario,RP.idForm,RP.idSolicitudMovil 
                                         FROM report AS RP WHERE RP.id = ?;");
        $searchReports->bind_param("i", $report);
        if ($searchReports->execute()) {
            $searchReports->store_result();
            $searchReports->bind_result($idReport, $employee, $creator, $reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
            $searchReports->fetch();
            $employeAssigned='{venta:'+$employee+'}';
            if ( ($profileID == 3 || $profileID == 6 || $profileID == 7 || $profileID == 8 || $profileID == 9) && 
                 ($reportType == 2) ) {
                //Asignación de Plomería
                $plumber = 3;

                $idEstatusReport = 4;
                
                $querySmt = "INSERT INTO reportHistory(idReport,idFormSell,idReportType,idUserAssigned,idStatusReport,idFormulario, idSolicitud, updated_at,created_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())";
                
                $idEmp=intval($_POST["employee"]);
                if ($stmt = $conn->prepare($querySmt)) {
                    $stmt->bind_param("iiiiiii", $idReport, $idFormulario, $plumber, $idEmp, $idEstatusReport,$idForm,$idSolicitudMovil);
                    if ($stmt->execute()) {
                        //mandamos la notificacion al celular
                        $stmtTEstatusSQL="UPDATE report SET idEmployee = ?, idUserCreator = ? WHERE id = ?;";
                        if($estatusCrontratoReport = $conn->prepare($stmtTEstatusSQL)){
                            $emp=intval($_POST['employee']);
                            $estatusCrontratoReport->bind_param("iii", $employeeID, $emp, $report);
                            $estatusCrontratoReport->execute();
                        }
                        $updateReportStatus = $conn->prepare("UPDATE workflow_status_report SET idStatus = ?, updated_at = NOW() WHERE idReport = ?;");
                        $updateReportStatus->bind_param('ii', $idStatus, $report);

                        if($updateReportStatus->execute()) {
                            $updateReportAssignedStatus = $conn->prepare("UPDATE report_AssignedStatus SET idStatus = ?, updated_at = NOW() WHERE idReport = ?;");
                            $updateReportAssignedStatus->bind_param('ii', $idStatus, $report);

                            if($updateReportAssignedStatus->execute()) {
                                $idStatusPH = 30;
                                $updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET phEstatus = ?, idEmpleadoPhAsignado = ?, fechaMod = NOW() WHERE idReporte = ?;");
                                $updateEstatusContrato->bind_param("iii", $idStatusPH, $idEmp, $report);
                                if ($updateEstatusContrato->execute()) {
                                    //actualizamos la tabla tiempoVentas
                                    $getIdRpTVTA=getReportTVTAID($report);
                                    if ($getIdRpTVTA != "") {
                                        $updateReportTVTASQL = "UPDATE reportTiempoVentas SET fechaFinAsigPH=NOW(),fechaInicioRealizoPH=NOW() WHERE idReporte = ?;";
                                        if ($updateReportTVTA= $conn->prepare($updateReportTVTASQL)) {
                                            $updateReportTVTA->bind_param('i',$report);
                                            if($updateReportTVTA->execute()){
                                                $result["status"] = "OK";
                                                $result["code"] = "200";
                                                $result["result"] = "Reportes Asignados Exitosamente";
                                            }
                                        }
                                    }
                                }
                            } else {
                                $result["status"] = "BAD";
                                $result["code"] = "500";
                                $result["result"] = "No se asigno correctamente ".$updateReportAssignedStatus->error;
                            }
                        } else {
                            $result["status"] = "BAD";
                            $result["code"] = "500";
                            $result["result"] = "No se asigno correctamente ".$updateReportStatus->error;
                        }
                    }else{
                        $result["status"] = "BAD";
                        $result["code"] = "500";
                        $result["result"] = "No se asigno correctamente";
                    }
                }else{
                    $result["status"] = "BAD";
                    $result["code"] = "500";
                    $result["result"] = "Error de Base De Datos, Favor de comunicarse con el Administrador";
                    //printf("Errormessage: %s\n", $conn->error);
                }
            } else {
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = "No se puede asignar la venta, se encuentra en proceso de validacion";
            }
        }
        echo json_encode($result);
    }elseif($profileToAssign == 2) {
        $secondSell = 5; $idStatus = 4; $report = intval($report);
        if ($employee != "") {
            
            $searchReports = $conn->prepare("SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned,
                                                RP.idFormulario,RP.idForm,RP.idSolicitudMovil 
                                             FROM report AS RP WHERE RP.id = ?;");
            $searchReports->bind_param("i", $report);
            if ($searchReports->execute()) {
                $searchReports->store_result();
                $searchReports->bind_result($idReport, $employee, $creator, $reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
                $searchReports->fetch();
                //preguntamos si ya se registro en history
                $resGetIdHist=getIdHist($secondSell,$report);
                if($resGetIdHist == ""){
                    $querySmt = "INSERT INTO reportHistory(idReport,idFormSell,idReportType,idUserAssigned,idStatusReport,idFormulario, idSolicitud, updated_at,created_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())";
                
                    $idEmp=intval($_POST["employee"]);
                    $idEstatusReport = 4;
                    if ($stmt = $conn->prepare($querySmt)) {
                        $stmt->bind_param("iiiiiii", $idReport, $idFormulario, $secondSell, $idEmp, $idEstatusReport,$idForm,$idSolicitudMovil);
                        if ($stmt->execute()) {
                            $updateReportStatus = $conn->prepare("UPDATE workflow_status_report 
                                                                    SET idStatus = ?, updated_at = NOW() 
                                                                  WHERE idReport = ?;");
                            $updateReportStatus->bind_param('ii', $idStatus, $report);
                            if ($updateReportStatus->execute()) {
                                $updateReportAssignedStatus = $conn->prepare("UPDATE report_AssignedStatus 
                                                                        SET idStatus = ?, updated_at = NOW() 
                                                                      WHERE idReport = ?;");
                                $updateReportAssignedStatus->bind_param('ii', $idStatus, $report);
                                if ($updateReportAssignedStatus->execute()) {
                                    $statusSegundaVenta = 40;
                                    $asignacionWeb = 2;
                                    $stmtTEstatus="UPDATE tEstatusContrato SET asignacionSegundaVenta = ?, idEmpleadoSegundaVenta = ?, validacionSegundaVenta = ? WHERE idReporte = ?;";
                                    if($estatusCrontratoReport = $conn->prepare($stmtTEstatus)){
                                        $estatusCrontratoReport->bind_param("siii", $asignacionWeb, $employee, $statusSegundaVenta, $report);
                                        $estatusCrontratoReport->execute();

                                        $result["status"] = "OK";
                                        $result["code"] = "200";
                                        $result["result"] = "Reportes Asignados Exitosamente";
                                    }
                                } else {
                                    $result["status"] = "BAD";
                                    $result["code"] = "500";
                                    $result["result"] = $updateReportAssignedStatus->error;
                                }
                            } else {
                                $result["status"] = "BAD";
                                $result["code"] = "500";
                                $result["result"] = $updateReportStatus->error;

                            }
                        }
                    }
                }
            }
           
        }
        echo json_encode($result);
    }elseif ($profileToAssign == 3 || $profileToAssign == 4  ) {
        asignarInstalacion($report, $profileToAssign, $employee, $_POST['param']);

    }
}else{
    echo "error";
}

function asignarInstalacion($report,$profileToAssign,$employeeToAssign, $idClient){
    $DB2 = new DAO();
    $conn2 = $DB2->getConnect();

    $idReport=0; $idUserCreator=0; $idEmployee=0; $city=0;$reportType=0; $employeeAssigned=""; $idFormulario=0; $idForm=0;$idSolicitudMovil=0;
    $instalation=4;$employeesAssigned="";$agency=0;$id=0;$profileID=0;$profileName="";

    $strGetEmployeeData="SELECT user.id, employee.id, profile.id , profile.name,agency.id, agency.tipo FROM user INNER JOIN employee ON user.id = employee.idUser
    INNER JOIN profile ON employee.idProfile = profile.id inner join agency_employee on agency_employee.idemployee=employee.id
    inner join agency on agency.id=agency_employee.idAgency
    inner join report on report.idemployee=employee.id WHERE report.id =?";

    $getEmployeeData = $conn2->prepare($strGetEmployeeData);
    $getEmployeeData->bind_param('i', $report);
    $getEmployeeData->store_result();
    $getEmployeeData->bind_result($id, $idEmployee, $profileID, $profileName,$agency,$agencyType);
    if ($getEmployeeData->execute()) {
        if ($getEmployeeData->fetch()) {
            $instalation = 4;
            $existePlomeroInstalador = comprobarPlomeroInst($report);
            //echo var_dump($existePlomeroInstalador);
            //validamos si el usuario que realizo la plomeria es plomero/instalador para asignarle la instalacion
            if (intval($existePlomeroInstalador[0]["idProfile"]) == 9) {
                //asignamos el reporte al plomero instaladdor
                $resultadoAsignacion = asignarInstalacionPlomeroInst($existePlomeroInstalador[0]["idUsserAssigned"],$report, $idClient);
                echo json_encode($resultadoAsignacion);
            }else{
                if ( $agencyType == "Instalacion" || $agencyType == "Instalacion y Comercializadora" ) {
                    $DB3 = new DAO();
                    $conn3 = $DB3->getConnect();
                    //Asignación de instalacion

                    $searchReportsSQL = "SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned,
                                                        RP.idFormulario,RP.idForm,RP.idSolicitudMovil
                                                 FROM report AS RP WHERE RP.id = ?;";
                    if($searchReports = $conn3->prepare($searchReportsSQL)){
                        $searchReports->bind_param("i", $report);
                        if ($searchReports->execute()) {
                            $searchReports->store_result();
                            $searchReports->bind_result($idReport, $idEmployee, $creator, $reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
                            $searchReports->fetch();
                            error_log("Si busco el empleado asignado. ProfileToAssing,employeeToAssign: ".$profileToAssign.",".$employeeToAssign);

                            if ($profileToAssign == 4){
                                $employeesAssigned = $employeeToAssign;
                            }else {
                                $employeesAssigned = "";
                            }
                            
                            $DB4 = new DAO();
                            $conn4 = $DB4->getConnect();
                    
                            $reassingReportSQL="call spAsignarInstalacion(?,?,?,?,?,?,?,?);";
                            error_log("Ejecutar: call spAsignarInstalacion(".$agency.",".$idEmployee.",".$instalation.",".$employeesAssigned.",".$report.",".$idFormulario.",".$idForm.",".$idSolicitudMovil.");", 0);
                            if ($reassingReport=$conn4->prepare($reassingReportSQL)) {
                                mysqli_stmt_bind_param($reassingReport, 'iiisiiii',$agency,$idEmployee,$instalation,$employeesAssigned,$report,$idFormulario,$idForm,$idSolicitudMovil);
                                if ($reassingReport->execute()) {
                                    $response["status"] = "COMPLETO";
                                    $response["code"] = "100";
                                    $response["response"] = "Asignación Automática de Instalación Satisfactoria";
                                    if ($profileToAssign == 3) {
                                        $dbSegVta = new DAO();
                                        $connSegVta = $dbSegVta->getConnect();
                                        $statusSegundaVenta = 42;
                                        $asignacionWeb = 2;
                                        $stmtTEstatus = "UPDATE tEstatusContrato SET validacionSegundaVenta = ?, idClienteGenerado = ? WHERE idReporte = ?;";
                                        if ($estatusCrontratoReport = $connSegVta->prepare($stmtTEstatus)) {
                                            $estatusCrontratoReport->bind_param("isi", $statusSegundaVenta, $idClient, $report);
                                            if ($estatusCrontratoReport->execute()) {
                                                $selecteportTVTASQL = "SELECT id, fechaSegundaCaptura
                                                                       FROM reportTiempoVentas 
                                                                       WHERE idReporte = $report";
                                                $result = $connSegVta->query($selecteportTVTASQL);
                                                if ($result->num_rows > 0) {
                                                    while($row = $result->fetch_array()) {
                                                        if (!isset($row[1]) || empty($row[1]) ) {
                                                            error_log('message fechaSegundaCaptura vacio');
                                                            $updateReportTVTASQL = "UPDATE reportTiempoVentas SET fechaSegundaCaptura = NOW() WHERE idReporte = ?;";
                                                        }
                                                    }
                                                }
                                                if ($updateReportTVTA= $connSegVta->prepare($updateReportTVTASQL)) {
                                                    $updateReportTVTA->bind_param('i',$report);
                                                    if($updateReportTVTA->execute()){
                                                        error_log("Se actualizo la tabla reporte de tiempos");
                                                    }
                                                }
                                            }
                                        }else{
                                            error_log('maldito error 1 '.$connSegVta->error);
                                        }
                                        $connSegVta->close();
                                    }
                                    echo json_encode($response);
                                }else{
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] = 'Error en primer update asignacion inst / '.$conn2->error;
                                    echo json_encode($response);
                                }
                            }else{
                                $response["status"] = "ERROR";
                                $response["code"] = "500";
                                $response["response"] = 'Error en primer update asignacion inst / '.$conn2->error;
                                echo json_encode($response);
                            }
                        }else{
                            $response["status"] = "ERROR";
                            $response["code"] = "500";
                            $response["response"] ="NO encontro el empleado asignado ".$searchReports->error;
                            echo json_encode($response);
                        }
                    }else{
                        $response["status"] = "ERROR";
                        $response["code"] = "500";
                        $response["response"] ="NO encontro el empleado asignado ".$searchReports->error;
                        echo json_encode($response);
                    }
                } else {
                    $DB2 = new DAO();
                    $conn2 = $DB2->getConnect();
                    $employeesAssigned = "";
                    //ASIGNAR A AGENCIA QUE PERTENEZCA AL MUNICIPIO DONDE FUE CREADO EL REPORTE
                    $getReportCity = $conn2->prepare("SELECT DISTINCT RP.id, RP.idUserCreator, RP.idEmployee, RP.idCity,RP.idReportType, RP.employeesAssigned,
                                                        RP.idFormulario,RP.idForm,RP.idSolicitudMovil  FROM report AS RP WHERE RP.id = ?;");
                    $getReportCity->bind_param("i", $report);
                    if ($getReportCity->execute()) {
                        $getReportCity->store_result();
                        $getReportCity->bind_result($idReport, $idUserCreator, $idEmployee, $city,$reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
                        if ($getReportCity->fetch()) {
                            $employeesAssigned="";
                            //SELECCIONAR AGENCIA QUE TENGA EL MUNICIPIO ASIGNADO
                            $idCity=getIdCity($city);
                            error_log('message idCity '.$idCity);
                            //die();
                            $getAgencyCitySQL = "SELECT DISTINCT AC.idAgency
                                                 FROM agency_cities AC
                                                 WHERE 0=0 
                                                 AND AC.idAgency IN (
                                                     SELECT A.id
                                                     FROM agency A
                                                     WHERE A.tipo LIKE '%inst%'
                                                 )
                                                 AND AC.idCity=?
                                                 LIMIT 1;";
                            if ($getAgencyCity = $conn2->prepare($getAgencyCitySQL)) {
                                $getAgencyCity->bind_param("i", $idCity);
                                if ($getAgencyCity->execute()) {
                                    $getAgencyCity->store_result();
                                    $getAgencyCity->bind_result($agency);
                                    if ($getAgencyCity->fetch()) {
                                        error_log('message agency '.$agency);
                                        //die();
                                        $reassingReport=$conn2->prepare("call spAsignarInstalacion(?,?,?,?,?,?,?,?);");
                                        mysqli_stmt_bind_param($reassingReport, 'iiisiiii', $agency,$idEmployee,$instalation,$employeesAssigned,$report,$idFormulario,$idForm,$idSolicitudMovil);
                                        if ($reassingReport->execute()) {
                                            $reassingReport->store_result();
                                            $reassingReport->bind_result($employee);
                                            error_log("Ejecutar: call spAsignarInstalacion(".$agency.",".$idEmployee.",".$instalation.",".$employeesAssigned.",".$report.",".$idFormulario.",".$idForm.",".$idSolicitudMovil.");", 0);

                                            $response["status"] = "COMPLETO";
                                            $response["code"] = "100";
                                            $response["response"] = "Asignación Automática de Instalación Satisfactoria";
                                            if ($profileToAssign == 3) {
                                                $statusSegundaVenta = 42;
                                                $asignacionWeb = 2;
                                                $dbSegVta = new DAO();
                                                $connSegVta = $dbSegVta->getConnect();
                                                $stmtTEstatus = "UPDATE tEstatusContrato SET validacionSegundaVenta = ?, idClienteGenerado = ? WHERE idReporte = ?;";
                                                if ($estatusCrontratoReport = $connSegVta->prepare($stmtTEstatus)) {
                                                    $estatusCrontratoReport->bind_param("isi", $statusSegundaVenta, $idClient, $report);
                                                    if ($estatusCrontratoReport->execute()) {
                                                        $selecteportTVTASQL = "SELECT id, fechaSegundaCaptura
                                                                       FROM reportTiempoVentas 
                                                                       WHERE idReporte = $report";
                                                        $result = $connSegVta->query($selecteportTVTASQL);
                                                        if ($result->num_rows > 0) {
                                                            while($row = $result->fetch_array()) {
                                                                if (!isset($row[1]) || empty($row[1]) ) {
                                                                    error_log('message fechaSegundaCaptura vacio');
                                                                    $updateReportTVTASQL = "UPDATE reportTiempoVentas SET fechaSegundaCaptura = NOW() WHERE idReporte = ?;";
                                                                }
                                                            }
                                                        }
                                                        if ($updateReportTVTA= $connSegVta->prepare($updateReportTVTASQL)) {
                                                            $updateReportTVTA->bind_param('i',$report);
                                                            if($updateReportTVTA->execute()){
                                                                error_log("Se actualizo la tabla reporte de tiempos");
                                                            }
                                                        }
                                                    }
                                                }else{
                                                    error_log('maldito error '.$connSegVta->error);
                                                }
                                                $connSegVta->close();
                                            }
                                            echo json_encode($response);
                                        }else{
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] =$conn2->error;
                                            echo json_encode($response);
                                        }
                                    }else{
                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] =$conn2->error;
                                        echo json_encode($response);
                                    }
                                }else{
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] ="NO encontro el empleado asignado 4".$getAgencyCity->error;
                                    echo json_encode($response);
                                }
                            }else{
                                $response["status"] = "ERROR";
                                $response["code"] = "500";
                                $response["response"] ="NO encontro el empleado asignado 3".$getAgencyCity->error;
                                echo json_encode($response);
                            }
                        }else{
                            $response["status"] = "ERROR";
                            $response["code"] = "500";
                            $response["response"] ="NO encontro el empleado asignado 2";
                            echo json_encode($response);
                        }
                    }else{
                        $response["status"] = "ERROR";
                        $response["code"] = "500";
                        $response["response"] ="NO encontro el empleado asignado 1";
                        echo json_encode($response);
                    }
                }
            }
        }else{
            $response["status"] = "ERROR";
            $response["code"] = "500";
            $response["response"] ="NO encontro el empleado asignado";
            echo json_encode($response);
        }
    }else{
        $response["status"] = "ERROR";
        $response["code"] = "500";
        $response["response"] ="NO encontro el empleado asignado";
        echo json_encode($response);
    }

}

function asignarInstalacionPlomeroInst($empleado,$idReporte, $idClient){
    $DB = new DAO();
    $conn = $DB->getConnect();
    $idEmployee = getIDEmployee($empleado);
    $stmtReport = "UPDATE report SET idEmployee = ?, idUserCreator = ? WHERE id = ?;";
    if ($stmtReport = $conn->prepare($stmtReport)) {
        $stmtReport->bind_param("iii", $idEmployee, $empleado, $idReporte);
        if ($stmtReport->execute()) {
            $stmtTEstatus = "UPDATE tEstatusContrato SET estatusAsignacionInstalacion = 50, 
                                                         idEmpleadoInstalacion = ? 
                                                         WHERE idReporte = ?;";
            if ($estatusCrontratoReport = $conn->prepare($stmtTEstatus)) {
                $estatusCrontratoReport->bind_param("ii",$idEmployee, $idReporte);
                if ($estatusCrontratoReport->execute()) {
                    error_log('pasamos primer update');
                    $reportType = 4;
                    $idReportH = getIdHist($reportType, $idReporte);
                    if (intval($idReportH) == 0) {
                        error_log('insert');
                        $resGetValuesRH = getValuesRH($idReporte);
                        $stmtReportH = "INSERT reportHistory (idReport,idFormSell,idReportType, idUserAssigned, idStatusReport, idSolicitud, idFormulario,updated_at, created_at) values(?,?,4,?,4,?,?,NOW(),NOW())";
                        if ($stmtRH = $conn->prepare($stmtReportH)) {
                            $stmtRH->bind_param("iiiii",$idReporte, $resGetValuesRH[0]["idFormSell"], $empleado, $resGetValuesRH[0]["idSolicitud"], $resGetValuesRH[0]["idFormulario"]);
                            if ($stmtRH->execute()) {
                                $idReportH = $stmtRH->insert_id;
                                error_log('pasamos segundo update');
                                $reportTVTAID = getReportTVTAID($idReporte);
                                if (intval($reportTVTAID) > 0) {
                                    error_log('tercer update');
                                    $stmtReportH = "UPDATE reportTiempoVentas SET fechaFinAsigInst=now(), fechaInicioRealInst=now()
                                                    WHERE idReporte = ?;";
                                    if ($stmtRH = $conn->prepare($stmtReportH)) {
                                        $stmtRH->bind_param("i",$idReporte);
                                        if ($stmtRH->execute()) {
                                            error_log('cuarto update');
                                            $statusSegundaVenta = 42;
                                            $asignacionWeb = 2;
                                            $dbSegVta = new DAO();
                                            $connSegVta = $dbSegVta->getConnect();
                                            $stmtTEstatus = "UPDATE tEstatusContrato SET validacionSegundaVenta = ?, idClienteGenerado = ? WHERE idReporte = ?;";
                                            if ($estatusCrontratoReport = $connSegVta->prepare($stmtTEstatus)) {
                                                $estatusCrontratoReport->bind_param("isi", $statusSegundaVenta, $idClient, $idReporte);
                                                if ($estatusCrontratoReport->execute()) {
                                                    error_log('5to update');
                                                    $updateReportTVTASQL = "UPDATE reportTiempoVentas SET fechaSegundaCaptura = NOW() WHERE idReporte = ?;";
                                                    if ($updateReportTVTA= $connSegVta->prepare($updateReportTVTASQL)) {
                                                        $updateReportTVTA->bind_param('i',$idReporte);
                                                        if($updateReportTVTA->execute()){
                                                            error_log('message terminamos');
                                                            $result["status"] = "EXITO";
                                                            $result["code"] = "200";
                                                            $result["idEmpleado"] = $empleado;
                                                            $result["result"] = "La instalcion se asigno correctamente";
                                                        }
                                                    }
                                                }
                                            }
                                            $connSegVta->close();
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

function getValuesRH($idReport)
{
    error_log('message getValuesRH'.$idReport);
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdUserQL = "SELECT idReport, idFormSell, idSolicitud, idFormulario FROM reportHistory WHERE idReportType = 2 and idReport=$idReport;";
        error_log("query ".$getIdUserQL);
        $result = $conn->query($getIdUserQL);
        $res=[];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res["idReport"]=$row[0];
                $res["idFormSell"]=$row[1];
                $res["idSolicitud"]=$row[2];
                $res["idFormulario"]=$row[3];
                $returnData[]=$res;
            }
        }
        $conn->close();
    }
    return $returnData;
}

function comprobarPlomeroInst($idReport)
{
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepHSQL = "SELECT 
                            c.idProfile,
                            b.id
                        FROM
                            reportHistory a,
                            user b,
                            employee c
                        WHERE
                        0 = 0 
                        AND a.idUserAssigned = b.id
                        AND c.idUser = b.id
                        AND a.idReport = $idReport
                        AND a.idReportType = 3;";
        $result = $conn->query($getIdRepHSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res["idProfile"]=$row[0];
                $res["idUsserAssigned"]=$row[1];
                $returnData[]=$res;
            }
        }
        $conn->close();
    }
    return $returnData;
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

function getIdCity($city)
{
    require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

    //$clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $clienteSoapMexicana = "http://111.111.111.3/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
    //$nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
    $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.3/wsa/wsa1/";
    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
    $nuSoapClientMexicana->decode_utf8 = false;

    $postData = array();
    $resultWsMunicipios = $nuSoapClientMexicana->call('ws_siscom_municipios', $postData);

    if ($nuSoapClientMexicana->fault) {

    } else {
        $err = $nuSoapClientMexicana->getError();
    }
    if ($err) {
        echo json_encode($err);
    } else {
        $ciudad="";
        $municipios = $resultWsMunicipios ["ot_municipios"]["ot_municipiosRow"];
        foreach ($municipios as $municipio) {
            if ($municipio["nombre"] == $city) {
                $ciudad = $municipio["idMunicipio"];
            }
        }
    }
    return $ciudad;
}