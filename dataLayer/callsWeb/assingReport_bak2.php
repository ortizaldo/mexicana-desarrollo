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
        $getReportUserCreation = $conn->prepare("SELECT RP.id FROM report AS RP WHERE RP.idUserCreator = ?;");
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
            if ( ($profileID == 3 || $profileID == 6 || $profileID == 7 || $profileID == 8) && 
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
                                $updateEstatusContrato->execute();

                                $result["status"] = "OK";
                                $result["code"] = "200";
                                $result["result"] = "Reportes Asignados Exitosamente";
                            } else {
                                echo $updateReportAssignedStatus->error;
                            }
                        } else {
                            echo $updateReportStatus->error;
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
                if($profileToAssign == 3){
                    $statusSegundaVenta = 41;
                    $asignacionWeb = 2;
                    $stmtTEstatus="UPDATE tEstatusContrato SET validacionSegundaVenta = ?, idClienteGenerado = ? WHERE idReporte = ?;";
                    if($estatusCrontratoReport = $conn->prepare($stmtTEstatus)){
                        $estatusCrontratoReport->bind_param("isi",$statusSegundaVenta, $_POST['param'], $report);
                        $estatusCrontratoReport->execute();

                        $result["status"] = "OK";
                        $result["code"] = "200";
                        $result["result"] = "Reportes Asignados Exitosamente";
                    }
                }

        asignarInstalacion($report,$profileToAssign,$employee);

              /* $getEmployeeData = $conn->prepare("SELECT user.id, employee.id, profile.id , profile.name FROM user INNER JOIN employee ON user.id = employee.idUser INNER JOIN profile ON employee.idProfile = profile.id inner join report on report.idemployee=employee.id WHERE report.id = ?;");
                $getEmployeeData->bind_param('i', $report);
                $getEmployeeData->store_result();
                $getEmployeeData->bind_result($id, $idEmployee, $profileID, $profileName);
                if ($getEmployeeData->execute()) {
                    if ($getEmployeeData->fetch()) {
                        $instalation = 4;
                        // if ( ($profileID == 4 || $profileID == 8) && $profileToAssign == 4 ) {
                        if ( $profileToAssign == 4  ) {
                            $DB = new DAO();
                            $conn = $DB->getConnect();
                            //Asignación de instalacion directa

                            $employeesAssigned = $employee;
                            $reassingReportSQL="call spAsignarInstalacion(?,?,?,?,?);";
                            if ($reassingReport=$conn->prepare($reassingReportSQL)) {
                                mysqli_stmt_bind_param($reassingReport, 'iiisi', $_POST['agency'],$idEmployee,$instalation,$employeesAssigned,$report);
                                if ($reassingReport->execute()) {
                                    $response["status"] = "COMPLETO";
                                    $response["code"] = "100";
                                    $response["response"] = "Asignación Automática de Instalación Satisfactoria";

                                    $DB2 = new DAO();
                                    $conn2 = $DB2->getConnect();

                                    //insertamos a reportHistory
                                    $searchReportsSQL = "SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned,
                                                    RP.idFormulario,RP.idForm,RP.idSolicitudMovil
                                             FROM report AS RP WHERE RP.id = ?;";
                                    if($searchReports = $conn2->prepare($searchReportsSQL)){
                                        $searchReports->bind_param("i", $report);
                                        if ($searchReports->execute()) {
                                            $searchReports->store_result();
                                            $searchReports->bind_result($idReport, $employee, $creator, $reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
                                            $searchReports->fetch();
                                            $idEstatusReport = 4;

                                            $querySmt = "INSERT INTO reportHistory(idReport,idFormSell,idReportType,idUserAssigned,idStatusReport,idFormulario, idSolicitud, updated_at,created_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())";

                                            if ($stmt = $conn2->prepare($querySmt)) {
                                                $stmt->bind_param("iiiiiii", $idReport, $idFormulario, $instalation, $employee, $idEstatusReport,$idForm,$idSolicitudMovil);
                                                $stmt->execute();
                                            }else{
                                                $response["status"] = "BAD";
                                                $response["code"] = "500";
                                                $response["result"] = "Error de Base De Datos, Favor de comunicarse con el Administrador".$querySmt;
                                                //printf("Errormessage: %s\n", $conn->error);
                                            }
                                        }



                                    }
                                    else
                                    {
                                        echo $searchReports->error;
                                    }

                                    $conn2->close();
                                    //echo json_encode($response);
                                }else{
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] = 'Error en primer update asignacion inst / '.$conn->error;
                                    echo json_encode($response);
                                }
                            }else{
                                $response["status"] = "ERROR";
                                $response["code"] = "500";
                                $response["response"] = 'Error en primer update asignacion inst / '.$conn->error;
                                echo json_encode($response);
                            }
                            $conn->close();
                    } else {
                    $DB = new DAO();
                        $conn = $DB->getConnect();
                        $employeesAssigned = "";
                        //ASIGNAR A AGENCIA QUE PERTENEZCA AL MUNICIPIO DONDE FUE CREADO EL REPORTE
                        $getReportCity = $conn->prepare("SELECT DISTINCT RP.id, RP.idUserCreator, RP.idEmployee, RP.idCity,RP.idReportType, RP.employeesAssigned,
                                                    RP.idFormulario,RP.idForm,RP.idSolicitudMovil  FROM report AS RP WHERE RP.id = ?;");
                        $getReportCity->bind_param("i", $report);
                        if ($getReportCity->execute()) {
                            $getReportCity->store_result();
                            $getReportCity->bind_result($idReport, $idUserCreator, $idEmployee, $city,$reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
                            if ($getReportCity->fetch()) {

                                //SELECCIONAR AGENCIA QUE TENGA EL MUNICIPIO ASIGNADO
                                $idCity=getIdCity($city);
                                $getAgencyCitySQL = "SELECT DISTINCT ASCTY.idAgency FROM agency_cities AS ASCTY WHERE ASCTY.idCity = ? ORDER BY RAND() LIMIT 1;";
                                if ($getAgencyCity = $conn->prepare($getAgencyCitySQL)) {
                                    $getAgencyCity->bind_param("i", $idCity);
                                    if ($getAgencyCity->execute()) {
                                        $getAgencyCity->store_result();
                                        $getAgencyCity->bind_result($agency);
                                        if ($getAgencyCity->fetch()) {
                                            $reassingReport=$conn->prepare("call spAsignarInstalacion(?,?,?,?,?);");
                                            mysqli_stmt_bind_param($reassingReport, 'iiisi', $agency,$idEmployee,$instalation,$employeesAssigned,$report);
                                            if ($reassingReport->execute()) {
                                                $reassingReport->store_result();
                                                $reassingReport->bind_result($employeesAssigned);


                                                $querySmt = "INSERT INTO reportHistory(idReport,idFormSell,idReportType,idUserAssigned,idStatusReport,idFormulario, idSolicitud, updated_at,created_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())";
                                                $idEstatusReport = 4;
                                                $idEmp=intval($_POST["employee"]);
                                                if ($stmt = $conn->prepare($querySmt)) {
                                                    $stmt->bind_param("iiiiiii", $idReport, $idFormulario, $instalation, $employeesAssigned, $idEstatusReport,$idForm,$idSolicitudMovil);
                                                    $stmt->execute();
                                                }else{
                                                    $result["status"] = "BAD";
                                                    $result["code"] = "500";
                                                    $result["result"] = "Error de Base De Datos, Favor de comunicarse con el Administrador";
                                                    //printf("Errormessage: %s\n", $conn->error);
                                                }

                                                $response["status"] = "COMPLETO";
                                                $response["code"] = "100";
                                                $response["response"] = "Asignación Automática de Instalación Satisfactoria";
                                                //echo json_encode($response);
                                                 $statusSegundaVenta = 41;
                                                $asignacionWeb = 2;
                                                $stmtTEstatus="UPDATE tEstatusContrato SET validacionSegundaVenta = ?, idClienteGenerado = ? WHERE idReporte = ?;";
                                                if($estatusCrontratoReport = $conn->prepare($stmtTEstatus)){
                                                    $estatusCrontratoReport->bind_param("isi",$statusSegundaVenta, $_POST['param'], $report);
                                                    $estatusCrontratoReport->execute();
                                                    $response["status"] = "OK";
                                                    $response["code"] = "200";
                                                    $response["result"] = "Reportes Asignados Exitosamente";
                                                }
                                            }
                                        }else{
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] =$conn->error;
                                            echo json_encode($response);
                                        }
                                    }
                                }else{
                                    echo $getAgencyCity->error;
                                }
                            }
                        }
                        $conn->close();
                    }
                }
            } */
        }
   // }
}


function getIdCity($city)
{
    require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

    $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
    $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
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
        $municipios = $resultWsMunicipios ["ot_municipios"]["ot_municipiosRow"];
        foreach ($municipios as $municipio) {
            if ($municipio["nombre"] == $city) {
                return $municipio["idMunicipio"];
            }
        }
    }
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

function asignarInstalacion($report,$profileToAssign,$employeeToAssign){

    $DB2 = new DAO();
    $conn2 = $DB2->getConnect();

    $idReport=0; $idUserCreator=0; $idEmployee=0; $city=0;$reportType=0; $employeeAssigned=""; $idFormulario=0; $idForm=0;$idSolicitudMovil=0;
    $instalation=4;$employeesAssigned="";$agency=0;$id=0;$profileID=0;$profileName="";

    $getEmployeeData = $conn2->prepare("SELECT user.id, employee.id, profile.id , profile.name FROM user INNER JOIN employee ON user.id = employee.idUser INNER JOIN profile ON employee.idProfile = profile.id inner join report on report.idemployee=employee.id WHERE report.id = ?;");
    $getEmployeeData->bind_param('i', $report);
    $getEmployeeData->store_result();
    $getEmployeeData->bind_result($id, $idEmployee, $profileID, $profileName);
    if ($getEmployeeData->execute()) {
        if ($getEmployeeData->fetch()) {
            $instalation = 4;
			  error_log("Si busco el empleado pendiente. ProfileToAssing: ".$profileToAssign);
            // if ( ($profileID == 4 || $profileID == 8) && $profileToAssign == 4 ) {
            if ( $profileID == 4 || $profileID == 8 ) {
                $DB3 = new DAO();
                $conn3 = $DB3->getConnect();
                //Asignación de instalacio

                //insertamos a reportHistory
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
                        if ($profileToAssign == 4)
                        {
                            $employeesAssigned = $employeeToAssign;
                        }
                        else {
                            $employeesAssigned = $creator;
                        }
						
						$DB4 = new DAO();
						$conn4 = $DB4->getConnect();
				
                        $reassingReportSQL="call spAsignarInstalacion(0,?,?,?,?,?,?,?);";
                        error_log("Ejecutar: call spAsignarInstalacion(0,".$idEmployee.",".$instalation.",".$employeesAssigned.",".$report.",".$idFormulario.",".$idForm.",".$idSolicitudMovil.");", 0);
                        if ($reassingReport=$conn4->prepare($reassingReportSQL)) {
                            mysqli_stmt_bind_param($reassingReport, 'iisiiii',$idEmployee,$instalation,$employeesAssigned,$report,$idFormulario,$idForm,$idSolicitudMovil);
                            if ($reassingReport->execute()) {
                                $response["status"] = "COMPLETO";
                                $response["code"] = "100";
                                $response["response"] = "Asignación Automática de Instalación Satisfactoria";

								 echo json_encode($response);
                                //$DB2 = new DAO();
                                //$conn2 = $DB2->getConnect();

                                //echo json_encode($response);
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

                    }

                }
                else
                {
                    echo $searchReports->error;
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
                        $getAgencyCitySQL = "SELECT DISTINCT ASCTY.idAgency FROM agency_cities AS ASCTY WHERE ASCTY.idCity = ? ORDER BY RAND() LIMIT 1;";
                        if ($getAgencyCity = $conn2->prepare($getAgencyCitySQL)) {
                            $getAgencyCity->bind_param("i", $idCity);
                            if ($getAgencyCity->execute()) {
                                $getAgencyCity->store_result();
                                $getAgencyCity->bind_result($agency);
                                if ($getAgencyCity->fetch()) {
                                    $reassingReport=$conn2->prepare("call spAsignarInstalacion(?,?,?,?,?,?,?,?);");
                                    mysqli_stmt_bind_param($reassingReport, 'iiisiiii', $agency,$idEmployee,$instalation,$employeesAssigned,$report,$idFormulario,$idForm,$idSolicitudMovil);
                                    if ($reassingReport->execute()) {
                                        $reassingReport->store_result();
                                        $reassingReport->bind_result($employee);
                                        error_log("Ejecutar: call spAsignarInstalacion(".$agency.",".$idEmployee.",".$instalation.",".$employeesAssigned.",".$report.",".$idFormulario.",".$idForm.",".$idSolicitudMovil.");", 0);

                                        /*	$querySmt = "INSERT INTO reportHistory(idReport,idFormSell,idReportType,idUserAssigned,idStatusReport,idFormulario, idSolicitud, updated_at,created_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())";
                                            $idEstatusReport = 4;
                                            if ($stmt = $conn2->prepare($querySmt)) {
                                                $stmt->bind_param("iiiiiii", $idReport, $idFormulario, $instalation, $employee, $idEstatusReport,$idForm,$idSolicitudMovil);
                                                $stmt->execute();

                                                $conn2->close();
                                            }else{
                                                $result["status"] = "BAD";
                                                $result["code"] = "500";
                                                $result["result"] = "Error de Base De Datos, Favor de comunicarse con el Administrador";
                                                //printf("Errormessage: %s\n", $conn->error);
                                            }*/

                                        $response["status"] = "COMPLETO";
                                        $response["code"] = "100";
                                        $response["response"] = "Asignación Automática de Instalación Satisfactoria";
                                        //echo json_encode($response);

                                    }
                                }else{
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] =$conn2->error;
                                    echo json_encode($response);
                                }
                            }
                        }else{
                            echo $getAgencyCity->error;
                        }
                    }
                }

            }
        }
    }
	
	else{
		$response["status"] = "ERROR";
		$response["code"] = "500";
		$response["response"] ="NO encontro el empleado asignado";
		echo json_encode($response);
	}

}
