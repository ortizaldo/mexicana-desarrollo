<?php include_once "../DAO.php";
include_once "../libs/utils.php";

$DB = new DAO();
$conn = $DB->getConnect();

$employee; $reports = []; $result = [];

if (isset($_POST['param']) && isset($_POST['employee'])) {
    //$userID;
    $param = $_POST['param'];
    $employee = $_POST['employee'];
    $employee = intval($employee);
    $profileToAssign = $_POST['profile'];
    $profileToAssign = intval($profileToAssign);
    //$reports = json_decode($_POST['reports']);
    $report = $_POST['reports'];
    $result = "";

    //Flujo numero 1
    $idWorkflow = 1;
    //En Proceso
    $idStatus = 4; $profileID=0;
    //$typeSell = 2;

    //$getEmployeeID = $conn->prepare("SELECT `user`.`id`, `user`.`nickname`, `rol`.`type` FROM `user` INNER JOIN `user_rol` ON `user`.`id` = `user_rol`.`idUser` INNER JOIN `rol` ON `user_rol`.`idRol` = `rol`.`id` AND `user_rol`.`idUser` = `user`.`id` WHERE `nickname` = ?;");
    $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
    $getEmployeeData->bind_param('i', $employee);
    if ($getEmployeeData->execute()) {
        $getEmployeeData->store_result();
        $getEmployeeData->bind_result($id, $employee, $profileId, $profileName);
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

    // 1 Plomería    2 Segunda Venta
    if($profileToAssign == 1) {
        $report = intval($report);

        $searchReports = $conn->prepare("SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned FROM report AS RP WHERE RP.`id` = ?;");
        $searchReports->bind_param("i", $report);
        if ($searchReports->execute()) {
            $searchReports->store_result();
            $searchReports->bind_result($idReport, $employee, $creator, $reportType, $employeeAssigned);
            $searchReports->fetch();

            if ( $profileID == 3 || $profileID == 6 || $profileID == 7 || $profileID == 8 && $reportType == 2 && $employeeAssigned != "" ) {
                //Asignación de Plomería
                $plumber = 3;
                $employeesAssigned = "";
                //Agregar inner join con formulario de plomería para seleccionar el usuario que lo creó
                /*$reportEmployeeForm = $conn->prepare("SELECT `idEmployee` FROM `report_employee_form` AS RPEMP INNER JOIN `form_plumber` AS FRMPLUM ON RPEMP.`idForm` = FRMPLUM.`id` WHERE `idReport` = ?");
                $reportEmployeeForm->bind_param("i", $key);
                if ($reportEmployeeForm->execute()) {
                    $reportEmployeeForm->store_result();
                    $reportEmployeeForm->bind_result($idEmployee);
                    if ($reportEmployeeForm->fetch()) {
                        $idEmployee;

                        $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                        $reassingReport->bind_param("iisi", $idEmployee, $plumber, $employeesAssigned, $key);
                        $reassingReport->execute();
                    }
                }*/
                //Agregar inner join con formulario de venta para seleccionar el usuario que lo creó
                $reportEmployeeForm = $conn->prepare("SELECT `idEmployee` FROM `report_employee_form` AS RPEMP INNER JOIN `form_sells` AS FRMSELL ON RPEMP.`idForm` = FRMSELL.`id` WHERE `idReport` = ?");
                $reportEmployeeForm->bind_param("i", $report);
                if ($reportEmployeeForm->execute()) {
                    $reportEmployeeForm->store_result();
                    $reportEmployeeForm->bind_result($idEmployee);
                    if ($reportEmployeeForm->fetch()) {
                        $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                        $reassingReport->bind_param("iisi", $idEmployee, $plumber, $employeesAssigned, $report);
                        $reassingReport->execute();
                    }
                }
                //} else if ( $profileID == 2 || $profileID == 5 || $profileID == 6 || $profileID == 7 || $profileID == 8 && $reportType == 3 && $employeeAssigned != ""  ) {
            } else {
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = "No se puede asignar la venta, se encuentra en proceso de validacion";
            }
            $updateReportStatus = $conn->prepare("UPDATE `workflow_status_report` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
            $updateReportStatus->bind_param('ii', $idStatus, $report);

            if($updateReportStatus->execute()) {
                $updateReportAssignedStatus = $conn->prepare("UPDATE `report_AssignedStatus` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
                $updateReportAssignedStatus->bind_param('ii', $idStatus, $report);

                if($updateReportAssignedStatus->execute()) {
                    //Valida la información del empleado al cual se asignará la venta en caso que este tenga
                    //perfil de plomeria pasa a tipo de reporte de plomeria y solo puede ser realizado por estos mismos

                    //UPDATE report type to 5 (second sell) assing to user and create second sell
                    $updateReport = $conn->prepare("UPDATE `report` SET idEmployee = ?, idEmployee = ? WHERE id = ?;");
                    $updateReport->bind_param('iii', $employee, $employee, $report);

                    if($updateReport->execute()) {

                        $idStatusPH = 30;
                        $updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
                        $updateEstatusContrato->bind_param("iii", $idStatusPH, $employee, $reportID);
                        $updateEstatusContrato->execute();

                        $result["status"] = "OK";
                        $result["code"] = "200";
                        $result["result"] = "Reportes Asignados Exitosamente";
                    } else {
                        echo $updateReport->error;
                    }
                } else {
                    echo $updateReportAssignedStatus->error;
                }
            } else {
                echo $updateReportStatus->error;
            }
        }
    } else if ($profileToAssign == 2) {
        $secondSell = 5; $idStatus = 4; $report = intval($report);
        //if ($profileId == 2 || $profileId == 5 || $profileId == 6 || $profileId == 7 || $profileId == 8) {
            //Asignación de Segnda Venta
            $employeesAssigned = "";
            //Agregar inner join con formulario de venta para seleccionar el usuario que lo creó
            $reportEmployeeForm = $conn->prepare("SELECT `idEmployee` FROM `report_employee_form` AS RPEMP INNER JOIN `form_sells` AS FRMSELL ON RPEMP.`idForm` = FRMSELL.`id` WHERE `idReport` = ?;");
            $reportEmployeeForm->bind_param("i", $report);
            if ($reportEmployeeForm->execute()) {
                $reportEmployeeForm->store_result();
                $reportEmployeeForm->bind_result($idEmployee);
                if ($reportEmployeeForm->fetch()) {
                    $searchIdEmployee = $conn->prepare("SELECT id FROM employee WHERE idUser = ?;");
                    $searchIdEmployee->bind_param("i", $idEmployee);
                    if ($searchIdEmployee->execute()) {
                        $searchIdEmployee->store_result();
                        $searchIdEmployee->bind_result($idEmployee);
                        if ($searchIdEmployee->fetch()) {
                            $reassingReport = $conn->prepare("UPDATE report AS RP SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.id = ?;");
                            $reassingReport->bind_param("iisi", $idEmployee, $secondSell, $employeesAssigned, $report);
                            if ($reassingReport->execute()) {
                                $updateReportStatus = $conn->prepare("UPDATE `workflow_status_report` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
                                $updateReportStatus->bind_param('ii', $idStatus, $report);
                                if ($updateReportStatus->execute()) {
                                    $updateReportAssignedStatus = $conn->prepare("UPDATE `report_AssignedStatus` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
                                    $updateReportAssignedStatus->bind_param('ii', $idStatus, $report);
                                    if ($updateReportAssignedStatus->execute()) {
                                        //Valida la información del empleado al cual se asignará la venta en caso que este tenga
                                        //perfil de plomeria pasa a tipo de reporte de plomeria y solo puede ser realizado por estos mismos
                                        //UPDATE report type to 5 (second sell) assing to user and create second sell
                                        $updateReport = $conn->prepare("UPDATE `report` SET idEmployee = ?, idReportType = ? WHERE id = ?;");
                                        $updateReport->bind_param('iii', $idEmployee, $secondSell, $report);
                                        if ($updateReport->execute()) {
                                            $statusSegundaVenta = 40; $asignacionWeb = 2;
                                            $estatusCrontratoReport = $conn->prepare("UPDATE tEstatusContrato SET asignacionSegundaVenta = ?, idEmpleadoSegundaVenta = ?, validacionSegundaVenta = ? WHERE idReporte = ?;");
                                            $estatusCrontratoReport->bind_param("siii", $asignacionWeb, $idEmployee, $statusSegundaVenta, $report);
                                            $estatusCrontratoReport->execute();

                                            $result["status"] = "OK";
                                            $result["code"] = "200";
                                            $result["result"] = "Reportes Asignados Exitosamente";
                                        } else {
                                            echo $updateReport->error;
                                        }
                                    } else {
                                        echo $updateReportAssignedStatus->error;
                                    }
                                } else {
                                    echo $updateReportStatus->error;
                                }
                            }
                        }
                    }
                }
            }
        /*} else {
            $result["status"] = "BAD";
            $result["code"] = "500";
            $result["result"] = "No se puede asignar la venta, No cuenta con el perfil de vendedor.";
        }*/
    }
    echo json_encode($result);
}