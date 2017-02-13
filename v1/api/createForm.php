<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/classes/users.php";
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/classes/employees.php";
ini_set('memory_limit', '-1');

if (isset($_POST["token"])) {
    $DB = new DAO();
    $conn = $DB->getConnect();

    $token = $_POST["token"];
    $token = base64_decode($token);


    list($id, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
    $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
    $searchToken->bind_param('i', $id);

    if ($searchToken->execute()) {
        $searchToken->store_result();
        $searchToken->bind_result($userToken);


        if ($searchToken->fetch()) {
            if ($_POST["token"] == $userToken) {
                if (isset($_POST["solicitud"])) {
                    $data = $_POST["solicitud"];
                    $data = base64_decode($data);
                    $data = (array)json_decode($data);

                    //$employeesObj = new employees();

                    $usersObj = new Users();
                    $usersAdmins = $usersObj->getAdmins();
                    //var_dump($usersAdmins);

                    //print_r($data);
                    //exit;
                    $dir = "../../uploads/";

                                      $typeExt = ".png";
                    $MIMEtype = "image/png";

                    $idReportCreated = $data["idAsignacion"];
                    $assigned = $data["fecha_asignacion"];
                    $agendaDate = $data["fecha_agendada"];
                    $historical = $data["historico"];
                    $status = $data["estatus"];
                    $statusComplete = $data["estatus_concluido"];
                    //var_dump($statusComplete);
                    //$agreement = $data["num_contrato"];
                    $clientName = $data["nombre_cliente"];
                    $directions = $data["dirección"];
                    $observations = $data["observaciones"];
                    $profileType = $data["tipo_perfil"];
                    $statusType = $data["tipo_estatus"];
                    $formType = $data["idTypeForm"];
                    $idFormulario = $data["idFormulario"];
                    $dataFormulario = (array)$data["formulario"];
                    $dataCallejero = (array)$data["callejero"];

                    $idMunicipio = "";
                                      // print_r($dataFormulario);
                    // print_r($dataCallejero);
                    // exit;

                    if (isset($dataCallejero) && $dataCallejero != null) {
                        $idMunicipio = $dataCallejero["municipio"];
                        $idSolicitud = $dataCallejero["idSolicitud"];
                        $colonia = $dataCallejero["colonia"];
                        $street = $dataCallejero["calle"];
                        $betweenStreets = $dataCallejero["entrecalles"];
                        $innerNumber = $dataCallejero["numero"];
                        if ($innerNumber == NULL) {
                            $innerNumber = 0;
                        }
                        $nse = $dataCallejero["nse"];
                        $newStreet = $dataCallejero["direccionNueva"];
                        $streets = $dataCallejero["entrecallesNueva"];
                        $coloniaType = $dataCallejero["exclusividad"];
                        $marketType = $dataCallejero["tipoMercado"];
                        $latitude = (double)$dataCallejero["latitud"];
                        if (!is_numeric($latitude)) {
                            $latitude = 0.00;
                        }
                        $longitude = (double)$dataCallejero["longitud"];
                        if (!is_numeric($longitude)) {
                            $longitude = 0.00;
                        }

                    }

                    /*var_dump($street);
                    var_dump($innerNumber);
                    var_dump($colonia);
                    var_dump($observations);
                    var_dump($clientName);
                    var_dump($agreement);
                    var_dump($id);
                    var_dump($latitude);
                    var_dump($longitude);**/
                    $latitude = $data["latitud"];
                    $longitude = $data["longitud"];

                    $insertTask = $conn->prepare("INSERT INTO `task`(`street`, `number`, `colonia`, `annotations`, `clientName`, `agreementNumber`, `idUserCreator`, `idUserAssigned`, `idCity`, `created_at`, `updated_at`, `dot_latitude`, `dot_longitude`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW(), ?, ?);");
                    $insertTask->bind_param("sssssiiidd", $street, $innerNumber, $colonia, $observations, $clientName, intval($agreement), intval($id), intval($id), $latitude, $longitude);

                    if ($insertTask->execute()) {
                        $taskID = $insertTask->insert_id;

                        $idCountry = 1;
                        $idState = 1;
                        //$idCity = 1;
                        $statusTaskAssigned = 4;
                        //$idEmployee = $id;
                        //$idReportType = 1;
                        $cp = "-";

                        $insertTask = $conn->prepare("INSERT INTO `task_AssignedStatus`(`idTask`, `idStatus`, `notes`, `created_at`, `updated_at`) VALUES(?, ?, ?, CURDATE(), NOW());");
                        $insertTask->bind_param("iis", $taskID, $statusTaskAssigned, $observations);
                        $insertTask->execute();

                        if (intval($formType) == 0) {//Cesus Venta Plomero Instalacion Segunda Venta
                            $reportType = 1;
                        } else if (intval($formType) == 1) {
                            $reportType = 2;
                        } else if (intval($formType) == 2) {
                            $reportType = 3;
                        } else if (intval($formType) == 3) {
                            $reportType = 4;
                        } else if (intval($formType) == 4) {
                            $reportType = 5;
                        }


                        //Tomar Id de Report Type, iDState & iDCity. Así como iDEmployee.
                        $searchIdEmployee = $conn->prepare("SELECT id FROM employee WHERE idUser = ?;");
                        $searchIdEmployee->bind_param("i", $id);


                        if ($searchIdEmployee->execute()) {
                            $searchIdEmployee->store_result();
                            $searchIdEmployee->bind_result($idEmployee);
                            if ($searchIdEmployee->fetch()) {
                                $idReportCreated = intval($idReportCreated);
                                /*var_dump("Reporte Creado");
                                var_dump($idReportCreated);
                                var_dump($formType);*/

                                //Validar si el reporte existe para actualizarlo según el id traido desde la App
                                //Si el reporte es diferente de vacío significa que existe

                                if ($idReportCreated != 0 || $idReportCreated != "" || $idReportCreated != null) {
                                    if (intval($formType) == 0 && $statusComplete == "Pendiente Reagendado") {
                                        $createReport = $conn->prepare("UPDATE report SET `nse` = ?, `marketType` = ?, `idEmployee` = ?, `idReportType` = ?, `idUserCreator` = ?, `idFormulario` = ?, `dot_latitude` = ?, `dot_longitude` = ?, `updated_at` = NOW() WHERE `id` = ?;");
                                        $createReport->bind_param("ssiiiiddi", $nse, $marketType, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude, $idReportCreated);
                                        if ($createReport->execute()) {
                                            $idStatus = 7;
                                            //Actualizar workflow status para reagendada
                                            $updateStatus = $conn->prepare("UPDATE workflow_status_report SET idStatus = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
                                            $updateStatus->bind_param("ii", $idStatus, $idReportCreated);
                                            if ($updateStatus->execute()) {
                                                $response["status"] = "OK";
                                                $response["code"] = "200";
                                                $response["response"] = $idReportCreated;
                                                echo json_encode($response);
                                                exit;
                                            }
                                        }

                                    } else if (intval($formType) == 1 && $statusComplete == "Pendiente Reagendado") {
                                        $agreement = $dataFormulario["num_solicitud"];
                                        $clientName = $data["nombre_cliente"];
                                        $clientLastName1 = $data["apellidop_cliente"];
                                        $clientLastName2 = $data["apellidom_cliente"];
                                        //if (intval($formType) == 2) {
                                        $createReport = $conn->prepare("UPDATE report SET `agreementNumber` = ?, `clientName` = ?, `clientLastName1` = ?, `clientLastName2` = ?, `nse` = ?, `marketType` = ?, `idEmployee` = ?, `idReportType` = ?, `idUserCreator` = ?, `idFormulario` = ?, `dot_latitude` = ?, `dot_longitude` = ?, `updated_at` = NOW() WHERE `id` = ?;");
                                        $createReport->bind_param("ssssssiiiiddi", $agreement, $dataFormulario["nombre"], $dataFormulario["apellido_paterno"], $dataFormulario["apellido_materno"], $nse, $marketType, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude, $idReportCreated);
                                        if ($createReport->execute()) {
                                            $idStatus = 7;
                                            //Actualizar workflow status para reagendada
                                            $updateStatus = $conn->prepare("UPDATE workflow_status_report SET idStatus = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
                                            $updateStatus->bind_param("ii", $idStatus, $idReportCreated);
                                            if ($updateStatus->execute()) {
                                                $response["status"] = "OK";
                                                $response["code"] = "200";
                                                $response["response"] = $idReportCreated;
                                                echo json_encode($response);
                                                exit;
                                            }
                                        }


                                    } else if (intval($formType) == 1) {
                                        $agreement = $dataFormulario["num_solicitud"];
                                        $clientName = $data["nombre_cliente"];
                                        $clientLastName1 = $data["apellidop_cliente"];
                                        $clientLastName2 = $data["apellidom_cliente"];
                                        $createReport = $conn->prepare("UPDATE report SET `agreementNumber` = ?, `clientName` = ?, `clientLastName1` = ?, `clientLastName2` = ?, `nse` = ?, `marketType` = ?, `idEmployee` = ?, `idReportType` = ?, `idUserCreator` = ?, `idFormulario` = ?, `dot_latitude` = ?, `dot_longitude` = ?, `updated_at` = NOW() WHERE `id` = ?;");
                                        $createReport->bind_param("ssssssiiiiddi", $agreement, $dataFormulario["nombre"], $dataFormulario["apellido_paterno"], $dataFormulario["apellido_materno"], $nse, $marketType, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude, $idReportCreated);
                                        if ($createReport->execute()) {
                                            $reportID = $idReportCreated;
                                            $idReport = $idReportCreated;
                                        }
                                    } else {
                                        $createReport = $conn->prepare("UPDATE report SET `nse` = ?, `marketType` = ?, `idEmployee` = ?, `idReportType` = ?, `idFormulario` = ?, `dot_latitude` = ?, `dot_longitude` = ?, `updated_at` = NOW() WHERE `id` = ?;");
                                        $createReport->bind_param("ssiiiddi", $nse, $marketType, $idEmployee, $reportType, $idFormulario, $latitude, $longitude, $idReportCreated);
                                        if ($createReport->execute()) {
                                            $reportID = $idReportCreated;
                                            $idReport = $idReportCreated;
                                        } else {
                                            //Error en la creacion de reporte
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Fallo en la actualizacion de Callejero: " . $createReport->error;
                                            echo json_encode($response);
                                        }
                                    }
                                } else {
                                    if (intval($formType) == 0 && $statusComplete == "Completado") {

                                        $formType = intval($formType);

                                        $getEstatusReport = $conn->prepare("SELECT id, idFormulario FROM report WHERE idUserCreator = ? AND idReportType = ?;");
                                        $getEstatusReport->bind_param("ii", $id, $formType);
                                        if ($getEstatusReport->execute()) {
                                            $getEstatusReport->store_result();
                                            $getEstatusReport->bind_result($idReport, $idFormulario);
                                            if ($getEstatusReport->fetch()) {
                                                $createReport = $conn->prepare("UPDATE report SET `nse` = ?, `marketType` = ?, `idEmployee` = ?, `idReportType` = ?, `idUserCreator` = ?, `idFormulario` = ?, `dot_latitude` = ?, `dot_longitude` = ?, `updated_at` = NOW() WHERE `idFormulario` = ?;");
                                                $createReport->bind_param("ssiiiiddi", $nse, $marketType, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude, $idFormulario);
                                                if ($createReport->execute()) {
                                                    $idStatus = 3;
                                                    //Actualizar workflow status para reagendada
                                                    $updateStatus = $conn->prepare("UPDATE workflow_status_report SET idStatus = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
                                                    $updateStatus->bind_param("ii", $idStatus, $idReport);
                                                    $updateStatus->execute();
                                                }
                                            } else {
                                                if ($agreement == null) {
                                                    $agreement = "-";
                                                }
                                                //var_dump($innerNumber);
                                                //Cambiar estatus a reporte y asignar como reagendado -- ID 9 de los estatus
                                                $createReport = $conn->prepare("INSERT INTO report(`colonia`, `street`, `betweenStreets`, `innerNumber`, `outterNumber`, `nse`, `newStreet`, `streets`, `coloniaType`, `marketType`, `idCountry`, `idState`, `idCity`, `cp`, `idEmployee`, `idReportType`, `idUserCreator`, `idFormulario`, `dot_latitude`, `dot_longitude`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                $createReport->bind_param("ssssssssssiisiiiiidd",  $colonia, $street, $betweenStreets, $innerNumber, $innerNumber, $nse, $newStreet, $streets, $coloniaType, $marketType, $idCountry, $idState, $idMunicipio, $cp, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude);
                                                if ($createReport->execute()) {
                                                    $reportID = $createReport->insert_id;
                                                    $idReport = $createReport->insert_id;

                                                    $idWorkflow = 1;
                                                    $idStatus = 4;
                                                    //Insert workflow status para reagendada
                                                    $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                                    $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $idReport);
                                                    $insertStatusReport->execute();
                                                } else {
                                                    //Error en la creacion de reporte
                                                    $response["status"] = "ERROR";
                                                    $response["code"] = "500";
                                                    $response["response"] = "Fallo en Creacion de Callejero:" . $createReport->error;
                                                    echo json_encode($response);
                                                }
                                            }
                                        }
                                    } else if (intval($formType) == 1 && $statusComplete == "Pendiente Reagendado") {
                                        /*$agreement = $dataFormulario["num_solicitud"];
                                        $clientName = $data["nombre_cliente"];
                                        $clientLastName1 = $data["apellidop_cliente"];
                                        $clientLastName2 = $data["apellidom_cliente"];
                                        var_dump($agreement);
                                        var_dump($clientName);
                                        var_dump($clientLastName1);
                                        var_dump($clientLastName2);*/

                                        $agreement = $data["num_contrato"];
                                        $clientName = $dataFormulario["nombre"];
                                        $clientLastName1 = $dataFormulario["apellido_paterno"];
                                        $clientLastName2 = $dataFormulario["apellido_materno"];

                                        /*var_dump($agreement);
                                        var_dump($clientName);
                                        var_dump($clientLastName1);
                                        var_dump($clientLastName2);*/

                                        if ($agreement == null) { $agreement = "-"; }
                                        //Cambiar estatus a reporte y asignar como reagendado -- ID 9 de los estatus
                                        $createReport = $conn->prepare("INSERT INTO report(`agreementNumber`, `clientName`, `clientLastName1`, `clientLastName2`, `colonia`, `street`, `betweenStreets`, `innerNumber`, `outterNumber`, `nse`, `newStreet`, `streets`, `coloniaType`, `marketType`, `idCountry`, `idState`, `idCity`, `cp`, `idEmployee`, `idReportType`, `idUserCreator`, `idFormulario`, `dot_latitude`, `dot_longitude`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                        $createReport->bind_param("ssssssssssssssiisiiiiidd", $agreement, $clientName, $clientLastName1, $clientLastName2, $colonia, $street, $betweenStreets, $innerNumber, $innerNumber, $nse, $newStreet, $streets, $coloniaType, $marketType, $idCountry, $idState, $idMunicipio, $cp, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude);
                                        if ($createReport->execute()) {
                                            $reportID = $createReport->insert_id;
                                            $idReport = $createReport->insert_id;
                                            //var_dump($idReport);
                                            //exit;
                                            $idWorkflow = 1;
                                            $idStatus = 7;
                                            //Insert workflow status para reagendada
                                            $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                            $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $idReport);
                                            if ($insertStatusReport->execute()) {
                                                $statusReport = 60;
                                                //var_dump($reportType);
                                                if ($reportType == 1) {
                                                    $statusCensus = 70;
                                                    $statusSells = 0;
                                                    $setStatusNoCensus = 0;
                                                    $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`,
                                                      `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`,
                                                      `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`,
                                                      `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                    $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $reportID, $statusReport, $statusCensus, $statusSells, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus);
                                                    $insertInEstatusContrato->execute();
                                                } else if ($reportType == 2) {
                                                    $statusCensus = 0;
                                                    $statusSells = 4;
                                                    $setStatusNoSell = 0;
                                                    $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`, `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`, `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`, `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                    $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $reportID, $statusReport, $statusCensus, $statusSells, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell);
                                                    $insertInEstatusContrato->execute();
                                                }
                                            }
                                        } else {
                                            //Error en la creacion de reporte
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Fallo en Creacion de Callejero   if (intval(formType) == 1 && statusComplete == Pendiente Reagendado) { : " . $createReport->error;
                                            echo json_encode($response);
                                        }
                                    } else if ($statusComplete == "Pendiente Reagendado") {
                                        $createReport = $conn->prepare("INSERT INTO report(`colonia`, `street`, `betweenStreets`, `innerNumber`, `outterNumber`, `nse`, `newStreet`, `streets`, `coloniaType`, `marketType`, `idCountry`, `idState`, `idCity`, `cp`, `idEmployee`, `idReportType`, `idUserCreator`, `idFormulario`, `dot_latitude`, `dot_longitude`, `created_at`, `updated_at`) VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                        $createReport->bind_param("ssssssssssiisiiiiidd", $colonia, $street, $betweenStreets, $innerNumber, $innerNumber, $nse, $newStreet, $streets, $coloniaType, $marketType, $idCountry, $idState, $idMunicipio, $cp, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude);
                                        if ($createReport->execute()) {
                                            $idWorkflow = 1;
                                            $idReport = $createReport->insert_id;
                                            $idStatus = 7;
                                            //Insert workflow status para reagendada
                                            $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                            $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $idReport);
                                            if ($insertStatusReport->execute()) {
                                                $statusReport = 60;
                                                //var_dump($reportType);
                                                if ($reportType == 1) {
                                                    $statusCensus = 70;
                                                    $statusSells = 0;
                                                    $setStatusNoCensus = 0;
                                                    $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`,
                                                          `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`,
                                                          `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`,
                                                          `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                    $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $idReport, $statusReport, $statusCensus, $statusSells, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus);
                                                    $insertInEstatusContrato->execute();
                                                } else if ($reportType == 2) {
                                                    $statusCensus = 0;
                                                    $statusSells = 4;
                                                    $setStatusNoSell = 0;
                                                    $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`, `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`, `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`, `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                    $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $idReport, $statusReport, $statusCensus, $statusSells, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell);
                                                    $insertInEstatusContrato->execute();
                                                }
                                                $response["status"] = "OK";
                                                $response["code"] = "200";
                                                $response["response"] = $idReport;
                                                echo json_encode($response);
                                                exit;
                                            }
                                        } else {
                                            //Error en la creacion de reporte
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Fallo en Creacion de Callejero: else if (statusComplete ==Pendiente Reagendado) " . $createReport->error;
                                            echo json_encode($response);
                                        }
                                    } else if (intval($formType) == 1) {

                                        $agreement = $data["num_contrato"];
                                        $clientName = $dataFormulario["nombre"];
                                        $clientLastName1 = $dataFormulario["apellido_paterno"];
                                        $clientLastName2 = $dataFormulario["apellido_materno"];

                                        /*var_dump($agreement);
                                        var_dump($clientName);
                                        var_dump($clientLastName1);
                                        var_dump($clientLastName2);

                                        var_dump("Creacion de contrato");*/

                                        if ($agreement == null) { $agreement = "-"; }
                                        //Cambiar estatus a reporte y asignar como reagendado -- ID 9 de los estatus
                                        $createReport = $conn->prepare("INSERT INTO report(`agreementNumber`, `clientName`, `clientLastName1`, `clientLastName2`, `colonia`, `street`, `betweenStreets`, `innerNumber`, `outterNumber`, `nse`, `newStreet`, `streets`, `coloniaType`, `marketType`, `idCountry`, `idState`, `idCity`, `cp`, `idEmployee`, `idReportType`, `idUserCreator`, `idFormulario`, `dot_latitude`, `dot_longitude`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                        $createReport->bind_param("ssssssssssssssiisiiiiidd", $agreement, $clientName, $clientLastName1, $clientLastName2, $colonia, $street, $betweenStreets, $innerNumber, $innerNumber, $nse, $newStreet, $streets, $coloniaType, $marketType, $idCountry, $idState, $idMunicipio, $cp, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude);

                                        if ($createReport->execute()) {
                                            $reportID = $createReport->insert_id; $idReport = $createReport->insert_id; $idWorkflow = 1; $idStatus = 4;

                                            /*var_dump($reportID);
                                            var_dump($idReport);
                                            var_dump($idWorkflow);
                                            var_dump($idStatus);*/

                                            //Insert workflow status para reagendada
                                            $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                            $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $idReport);

                                            if ($insertStatusReport->execute()) {
                                                $statusReport = 60;
                                               // var_dump($reportType);

                                              //  var_dump("Creacion de tEstatusContrato");
                                                    $statusCensus = 0; $statusSells = 4; $setStatusNoCensus = 0;

                                                //var_dump($statusCensus); var_dump($statusSells); var_dump($setStatusNoCensus);

                                                    $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`,
                                                      `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`,
                                                      `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`,
                                                      `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                    $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $reportID, $statusReport, $statusCensus, $statusSells, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus);

                                                    $insertInEstatusContrato->execute();
                                                /*else if ($reportType == 2) {
                                                    $statusCensus = 0;
                                                    $statusSells = 4;
                                                    $setStatusNoSell = 0;
                                                    $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`, `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`, `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`, `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                    $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $reportID, $statusReport, $statusCensus, $statusSells, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell);
                                                    $insertInEstatusContrato->execute();
                                                }*/

                                            }
                                        } else {
                                            //Error en la creacion de reporte
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Fallo en Creacion de Callejero:  if (createReport->execute())  " . $createReport->error;
                                            echo json_encode($response);
                                        }

                                    } else {

                                        if ($agreement == null) {
                                            $agreement = "-";
                                        }
                                        //var_dump($innerNumber);
                                        //Cambiar estatus a reporte y asignar como reagendado -- ID 9 de los estatus
                                        $createReport = $conn->prepare("INSERT INTO report(`agreementNumber`, `clientName`, `clientLastName1`, `clientLastName2`, `colonia`, `street`, `betweenStreets`, `innerNumber`, `outterNumber`, `nse`, `newStreet`, `streets`, `coloniaType`, `marketType`, `idCountry`, `idState`, `idCity`, `cp`, `idEmployee`, `idReportType`, `idUserCreator`, `idFormulario`, `dot_latitude`, `dot_longitude`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                        $createReport->bind_param("ssssssssssssssiisiiiiidd", $agreement, $dataFormulario["nombre"], $dataFormulario["apellido_paterno"], $dataFormulario["apellido_materno"], $colonia, $street, $betweenStreets, $innerNumber, $innerNumber, $nse, $newStreet, $streets, $coloniaType, $marketType, $idCountry, $idState, $idMunicipio, $cp, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude);
                                        if ($createReport->execute()) {
                                            $reportID = $createReport->insert_id;
                                            $idReport = $createReport->insert_id;

                                            $idWorkflow = 1;
                                            $idStatus = 4;
                                            //Insert workflow status para reagendada
                                            $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                            $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $idReport);
                                            $insertStatusReport->execute();
                                        } else {
                                            //Error en la creacion de reporte
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Fallo en Creacion de Callejero:   if (createReport->execute()) {" . $createReport->error;
                                            echo json_encode($response);
                                        }
                                    }
                                }

                                //Consultar estatus para censo, venta, PH e instalacion
                                if ($statusType == "Completo") {
                                    $idStatus = 3;
                                } else if ($statusType == "Prendiente Reagendado") {
                                    $idStatus = 7;
                                } else {
                                    $idStatus = 4;
                                }

                               /* var_dump("idStatus");
                                var_dump("-------");
                                var_dump($idStatus);

                                var_dump("reportID");
                                var_dump("-------");
                                var_dump($reportID);*/

                                //Cuando creas censo estableces el status de censo en 70 y a los demás 0 y cuando es venta pones venta a 4 y censo a 0
                                $estatusContratoID = 0;
                                $getEstatusReport = $conn->prepare("SELECT idReporte FROM tEstatusContrato WHERE idReporte = ?;");
                                $getEstatusReport->bind_param("i", $reportID);
                                if ($getEstatusReport->execute()) {
                                    $getEstatusReport->store_result();
                                    $getEstatusReport->bind_result($idReport);
                                    /*if ($getEstatusReport->fetch()) {
                                        $updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `estatusReporte` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
                                        $updateEstatusContrato->bind_param("ii", $statusComplete, $idReport);
                                        if ($updateEstatusContrato->execute()) {
                                            $estatusContratoID = $idReport;
                                        }
                                    } else {*/

                                    if (!$getEstatusReport->fetch()) {
                                        $statusReport = 60;
                                        //var_dump($reportType);
                                        if ($reportType == 1) {
                                            $statusCensus = 70;
                                            $statusSells = 0;
                                            $setStatusNoCensus = 0;
                                            $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`,
                                                  `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`,
                                                  `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`,
                                                  `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                            $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $reportID, $statusReport, $statusCensus, $statusSells, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus);
                                            $insertInEstatusContrato->execute();
                                        } else if ($reportType == 2) {
                                            $statusCensus = 0;
                                            $statusSells = 4;
                                            $setStatusNoSell = 0;
                                            $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`dReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`, `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`, `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`, `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                            $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $reportID, $statusReport, $statusCensus, $statusSells, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell, $setStatusNoSell);
                                            $insertInEstatusContrato->execute();
                                        }
                                    }else{

                                    }

                                }
                                /*var_dump(intval($formType));
                                var_dump("statusComplete");
                                var_dump($statusComplete);*/

                               // var_dump($estatusContratoID);

                                //if (intval($formType) == 0 && $reportId==null) {//Cesus
                                if (intval($formType) == 0) {//Cesus
                                    //var_dump($idReport);
                                    //exit;
                                    //$dataFormulario = (array) $data["idFormulario"];
                                    //print_r($dataFormulario);
                                    $lote_type = $dataFormulario["tipo_lote"];
                                    $requestNum = $dataFormulario["id"];
                                    $homeLevel = $dataFormulario["nivel_vivienda"];
                                    $socialLevel = $dataFormulario["nivel_socioeconomico"];
                                    $area = $dataFormulario["giro"];
                                    $acometida = $dataFormulario["acometida"];

                                    /*$acometidaPhotos = $dataFormulario["imagenes_acometida"];
                                    $acometidaPhotosArray = explode("&", $acometidaPhotos);*/

                                    $photosArray = $dataFormulario["fotos"];

                                    $comments = $dataFormulario["observaciones"];
                                    $color = $dataFormulario["color_tapon"];
                                    $measurer = $dataFormulario["medidor"];
                                    $measurerType = $dataFormulario["tipo_medidor"];
                                    $measurerSerialNumber = $dataFormulario["num_serie"];
                                    $measurerBrand = $dataFormulario["marca"];
                                    $niple = $dataFormulario["niple_decorte"];


                                    //var_dump($lote_type);
                                    //var_dump($requestNum);
                                    //var_dump($homeLevel);

                                    //var_dump($acometida);
                                    if ($acometida == true) {
                                        $acometida = 1;
                                    } else {
                                        $acometida = 0;
                                    }

                                    //var_dump($photosArray);

                                    //var_dump($comments);
                                    //var_dump($color);
                                    if ($color == true) {
                                        $color = 1;
                                    } else {
                                        $color = 0;
                                    }
                                    //var_dump($measurer);
                                    if ($measurer == true) {
                                        $measurer = 1;
                                    } else {
                                        $measurer = 0;
                                    }
                                    //var_dump($measurerType);
                                    //var_dump($measurerSerialNumber);
                                    //var_dump($measurerBrand);
                                    //var_dump($niple);
                                    if ($niple == true) {
                                        $niple = 1;
                                    } else {
                                        $niple = 0;
                                    }
                                    //$measurerPhoto = $dataFormulario["foto_medidor"];
                                    //$acometidaPhoto = $dataFormulario["foto_acometida"];


                                    /**INSERTAMOS EL CENSO EN LA TABLA DE REPORTE Y EN LA TABLA TESTATUSCONTRATO*****/
                                    /*$createReport = $conn->prepare("INSERT INTO report(`agreementNumber`, `clientName`, `clientLastName1`, `clientLastName2`, `colonia`, `street`, `betweenStreets`, `innerNumber`, `outterNumber`, `nse`, `newStreet`, `streets`, `coloniaType`, `marketType`, `idCountry`, `idState`, `idCity`, `cp`, `idEmployee`, `idReportType`, `idUserCreator`, `idFormulario`, `dot_latitude`, `dot_longitude`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                    $createReport->bind_param("ssssssssssssssiisiiiiidd", $agreement, $dataFormulario["nombre"], $dataFormulario["apellido_paterno"], $dataFormulario["apellido_materno"], $colonia, $street, $betweenStreets, $innerNumber, $innerNumber, $nse, $newStreet, $streets, $coloniaType, $marketType, $idCountry, $idState, $idMunicipio, $cp, $idEmployee, $reportType, $id, $idFormulario, $latitude, $longitude);
                                    if ($createReport->execute()) {
                                        $reportID = $createReport->insert_id;
                                    }*/
                                    $setStatusNoCensus=0; $statusReport=60; $statusCensus=70; $statusSells=0;
                                    $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`,
                                                  `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`,
                                                  `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`,
                                                  `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                    $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $reportID, $statusReport, $statusCensus, $statusSells, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus);
                                    $insertInEstatusContrato->execute();

                                    $createCensus = $conn->prepare("INSERT INTO `form_census`(`lote`, `houseStatus`, `nivel`, `giro`, `acometida`, `observacion`, `tapon`, `medidor`, `marca`, `tipo`, `NoSerie`, `niple`, `latitude`, `longitude`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1);");
                                    $createCensus->bind_param("ssssisiisssidd", $lote_type, $homeLevel, $socialLevel, $area, $acometida, $comments, $color, $measurer, $measurerBrand, $measurerType, $measurerSerialNumber, $niple, $latitude, $longitude);

                                    if ($createCensus->execute()) {
                                        $idCenso = $createCensus->insert_id;
                                        //var_dump($idCenso);
                                        //print_r($photosArray);

                                        foreach ($photosArray as $images) {
                                            $createCensusMultimedia = $conn->prepare("INSERT INTO `form_census_multimedia`(`idFormCensus`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                            $createCensusMultimedia->bind_param("ii", $idCenso, $images);
                                            if (!$createCensusMultimedia->execute()) {
                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Falla en el enlace para la imagen de censo: " . $createCensusMultimedia->error;
                                                echo json_encode($response);
                                            }
                                        }
                                        $censoMultimediaSave = true;
                                        if ($censoMultimediaSave) {
                                            $idWorkflow = 1;
                                            //TODO revisar workflows
                                            if ($statusType == "Completo") {
                                                $idStatus = 3;
                                            } else if ($statusType == "Pendiente Reagendado") {
                                                $idStatus = 7;
                                            } else {
                                                $idStatus = 4;
                                            }


                                            /*$idStatus = 7;
                                            //Actualizar workflow status para reagendada
                                            $updateStatus = $conn->prepare("UPDATE workflow_status_report SET idStatus = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
                                            $updateStatus->bind_param("ii", $idStatus, $idReportCreated);
                                            if ($updateStatus->execute()) {
                                                $response["status"] = "OK";
                                                $response["code"] = "200";
                                                $response["response"] = $idReportCreated;
                                                echo json_encode($response);
                                                exit;
                                            }*/

                                            $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                            $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $reportID);

                                            if ($insertStatusReport->execute()) {

                                                $reportEmployeeForm = $conn->prepare("INSERT INTO `report_employee_form`(`idReport`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                                $reportEmployeeForm->bind_param("iii", $reportID, $id, $idCenso);

                                                if ($reportEmployeeForm->execute()) {
                                                    $response["status"] = "OK";
                                                    $response["code"] = "200";
                                                    $response["response"] = "Reporte de Censo Creado";
                                                    $response["reportId"] = $idCenso;
                                                    echo json_encode($response);
                                                }
                                            } else {
                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Error al Crear Estatus para el Formulario de censo xx: " . $insertStatusReport->error;
                                                echo json_encode($response);
                                            }
                                        } else {
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Error al Crear Relacion de imagen de acometida/medidor con el formulario para censo: " . $createCensusMultimedia->error;
                                            echo json_encode($response);
                                        }
                                    } else {
                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] = "Falla en Creación para Reporte de Censo: " . $createCensus->error;
                                        echo json_encode($response);
                                    }
                                } else if (intval($formType) == 1) { //Venta
                                    //search mexicana admins, convert to json and update report attribute RP.`employeesAssigned` = json_encode(mexicana admins)
                                    $rol = 2;
                                    $getAdmins = $conn->prepare("SELECT US.id, US.nickname, RL.type FROM user AS US INNER JOIN user_rol AS USRL ON US.id = USRL.idUser INNER JOIN rol AS RL ON USRL.idRol = RL.id WHERE RL.id = ?;");
                                    $getAdmins->bind_param("i", $rol);

                                    if ($getAdmins->execute()) {
                                        $getAdmins->store_result();
                                        $getAdmins->bind_result($idUserAdmin, $nickname, $rol);
                                        while ($getAdmins->fetch()) {
                                            $admin["idUserAdmin"] = $idUserAdmin;
                                            $admin["nickname"] = $nickname;
                                            $admin["rol"] = $rol;
                                            $admins[] = $admin;
                                        }
                                        $admins = json_encode($admins);

                                        $getFinancialAgencyEmployees = $conn->prepare("UPDATE report SET employeesAssigned = ? WHERE id = ?;");
                                        $getFinancialAgencyEmployees->bind_param("si", $admins, $reportID);
                                        $getFinancialAgencyEmployees->execute();

                                    }

                                    $prospect = $dataFormulario["esta_interesado"];
                                    //var_dump($prospect);
                                    if ($prospect == null) {
                                        $prospect = 0;
                                    }
                                    if ($prospect == true) {
                                        $prospect = 1;
                                    } else if ($prospect == false) {
                                        $prospect = 0;
                                    }
                                    //var_dump($prospect);
                                    $uninteresting = $dataFormulario["motivo_desinteres"];
                                    //var_dump($uninteresting);
                                    $comments = $dataFormulario["comentarios"];
                                    //var_dump($comments);
                                    $owner = $dataFormulario["titular_encontrado"];
                                    //var_dump($owner);
                                    if ($owner == null) {
                                        $owner = 0;
                                    }
                                    if ($owner == true) {
                                        $owner = 1;
                                    } else if ($name == false) {
                                        $owner = 0;
                                    }
                                    //var_dump($owner);
                                    //$consecutive = $dataFormulario["consecutivo"];
                                    $name = $dataFormulario["nombre"];
                                    $lastName = $dataFormulario["apellido_paterno"];
                                    $lastNameOp = $dataFormulario["apellido_materno"];
                                    $payment = $dataFormulario["forma_pago"];
                                    //var_dump($payment);
                                    if ($payment == null) {
                                        $payment = 0;
                                    }
                                    if ($payment == true) {
                                        $payment = 1;
                                    } else if ($payment == false) {
                                        $payment = 0;
                                    }
                                    //var_dump($payment);
                                    $financialService = $dataFormulario["financiera"];
                                    //var_dump($financialService);
                                    if ($financialService == null) {
                                        $financialService = 0;
                                    }
                                    if ($financialService == true) {
                                        $financialService = 1;
                                        $asignadoMexicana = 1;
                                        $asignadoAyopsa = 1;
                                    } else if ($financialService == false) {
                                        $financialService = 0;
                                        $asignadoMexicana = 2;
                                        $asignadoAyopsa = 2;
                                    }
                                    //var_dump($financialService);
                                    $requestNumber = $dataFormulario["num_solicitud"];
                                    //var_dump($requestNumber);
                                    $imagesArray = (array)$dataFormulario["fotos"];

                                    /*var_dump($prospect);
                                    var_dump($uninteresting);
                                    var_dump($comments);
                                    var_dump($owner);
                                    var_dump($name);
                                    var_dump($lastName);
                                    var_dump($lastNameOp);
                                    var_dump($payment);
                                    var_dump($financialService);
                                    var_dump($requestNumber);
                                    var_dump($idFormulario);
                                    var_dump($latitude);
                                    var_dump($longitude);*/

                                    $createSell = $conn->prepare("INSERT INTO `form_sells`(`prospect`, `uninteresting`, `comments`, `owner`, `name`, `lastName`, `lastNameOp`, `payment`, `financialService`, `requestNumber`, `meeting`,`idFormulario`, `latitude`, `longitude`, `created_at`, `updated_at`, `active`)  VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, NOW(), NOW(), 1);");
                                    $createSell->bind_param("ississsiisidd", $prospect, $uninteresting, $comments, $owner, $name, $lastName, $lastNameOp, $payment, $financialService, $requestNumber, $idFormulario, $latitude, $longitude);

                                    if ($createSell->execute()) {
                                        $idSell = $createSell->insert_id;
                                        /*var_dump("idSell");
                                        var_dump($idSell);
                                        var_dump($idReport);*/
                                        foreach ($imagesArray as $images) {
                                            $createAgreementMultimedia = $conn->prepare("INSERT INTO `form_sells_multimedia`(`idSell`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                            $createAgreementMultimedia->bind_param("ii", $idSell, $images);

                                            if (!$createAgreementMultimedia->execute()) {
                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Falla en el enlace para la imagen de venta: " . $createAgreementMultimedia->error;
                                                echo json_encode($response);
                                            }
                                        }

                                        $reportEmployeeForm = $conn->prepare("INSERT INTO `report_employee_form`(`idReport`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                        $reportEmployeeForm->bind_param("iii", $idReport, $id, $idSell);
                                        $reportEmployeeForm->execute();

                                        $idWorkflow = 1; $idStatus = 4; $segundaVenta = 40;
                                        /*if ($statusType == "Completo")  {
                                            $idStatus = 3;
                                        } else if ($statusType == "Prendiente Reagendado")  {
                                            $idStatus = 7;
                                        } else {
                                            $idStatus = 4;
                                        }*/

                                        $updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `estatusVenta` = ?, `idEmpleadoParaVenta` = ?, `asignadoMexicana` = ?, `asignadoAyopsa` = ?, validacionSegundaVenta = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
                                        $updateEstatusContrato->bind_param("iiiiii", $idStatus, $id, $asignadoMexicana, $asignadoAyopsa, $segundaVenta, $reportID);
                                        $updateEstatusContrato->execute();

                                        //$insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                        //$insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $idReport);

                                        //if ($reportEmployeeForm->execute()) {
                                            //if ($insertStatusReport->execute()) {
                                                /*$updateReport = $conn->prepare("UPDATE `report` SET idSolicitud = ?, `updated_at` = NOW() WHERE `id` = ? ");
                                                $updateReport->bind_param("ii", $idSell, $idReport);
                                                if (!$updateReport->execute()) {
                                                    $response["status"] = "ERROR";
                                                    $response["code"] = "500";
                                                    $response["response"] = "Falla en la Asignación de solicitud " . $insertStatusReport->error;
                                                    echo json_encode($response);
                                                }*/

                                                //ASIGNAR A ADMINISTRADORES DE MEXICANA
                                                $rol = 2;
                                                $getAdmins = $conn->prepare("SELECT US.id, US.nickname, RL.type FROM user AS US INNER JOIN user_rol AS USRL ON US.id = USRL.idUser INNER JOIN rol AS RL ON USRL.idRol = RL.id WHERE RL.id = ?;");
                                                $getAdmins->bind_param("i", $rol);
                                                if ($getAdmins->execute()) {
                                                    $getAdmins->store_result();
                                                    $getAdmins->bind_result($idUserAdmin, $nickname, $rol);
                                                    $admin = array();
                                                    $admins=array();
                                                   //print_r($getAdmins);
                                                    while ($getAdmins->fetch()) {
                                                        $admin["idUserAdmin"] = $idUserAdmin;
                                                        $admin["nickname"] = $nickname;
                                                        $admin["rol"] = $rol;
                                                        $admins = $admin;
                                                    }

                                                    $admins = json_encode($admins);
                                                    //var_dump($admins);
                                                    /*$getFinancialAgencyEmployees = $conn->prepare("UPDATE report SET employeesAssigned = ? WHERE id = ?;");
                                                    $getFinancialAgencyEmployees->bind_param("si", $admins, $reportID);
                                                    $getFinancialAgencyEmployees->execute();*/

                                                    $updateReport = $conn->prepare("UPDATE report SET idSolicitud = ?, employeesAssigned = ?, updated_at = NOW() WHERE id = ? ");
                                                    $updateReport->bind_param("isi", $idSell, $admins, $idReport);
                                                    if (!$updateReport->execute()) {
                                                        $response["status"] = "ERROR";
                                                        $response["code"] = "500";
                                                        $response["response"] = "Falla en la Asignación de solicitud " . $insertStatusReport->error;
                                                        echo json_encode($response);
                                                    }
                                                }

                                                //$admins = $usersObj->getAdmins();
                                                //put admins into report class and then set as employeesAssigned
                                                //var_dump(intval($id));


                                                //En lugar de hacer el procedimiento de abajo, y mandar llamar getEmployeeByUser($userID) de la clase employees
                                                //$employeesObj = new employees();
                                                //$usersObj = new users();

                                                //$employees = $employeesObj->getEmployeeByUser($id);

                                                $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
                                                $getEmployeeData->bind_param('i', intval($id));
                                                if ($getEmployeeData->execute()) {
                                                    $getEmployeeData->store_result();
                                                    $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
                                                    if ($getEmployeeData->fetch()) {

                                                        //$profileID = $employee["profileID"];
                                                        //Asignación de Plomerí

                                                        //$employeesID = "";
                                                        $plumber = 3;
                                                        if ($profileID == 3 || $profileID == 6 || $profileID == 7 || $profileID == 8) {
                                                            //Asignación de Plomería
                                                            //Actualizar tipo de reporte a plomeria

                                                            //assingReport($employeesID, $employee, $plumber, $idReport);

                                                            $plumber = 4;

                                                            $reassingReport = $conn->prepare("UPDATE report AS RP SET RP.idEmployee = ?, RP.idReportType = ? WHERE RP.id = ?;");
                                                            $reassingReport->bind_param("iii", $employee, $plumber, $idReport);
                                                            if ($reassingReport->execute()) {
                                                                $idStatusPH = 30;
                                                                $updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
                                                                $updateEstatusContrato->bind_param("iii", $idStatusPH, $employee, $idReport);
                                                                $updateEstatusContrato->execute();

                                                                $reportEmployeeForm = $conn->prepare("INSERT INTO `report_AssignedStatus`(`idReport`, `idStatus`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                                                $reportEmployeeForm->bind_param("ii", $idReport, $idStatus);
                                                                $reportEmployeeForm->execute();

                                                                $response["status"] = "OK";
                                                                $response["code"] = "200";
                                                                $response["response"] = "Report sell created";
                                                                echo json_encode($response);
                                                            }
                                                            $plumber = 3;
                                                        } else {

                                                            $plumber = 4;
                                                            //En caso de que el empleado no tenga perfil de plomeria en su rol de empleado
                                                            //asignar a la agencia de plomería la cuál se encargará de asignarlo a su empleado correspondiente
                                                            $profile1 = 3; $profile2 = 6; $profile3 = 7; $profile4 = 8;
                                                            $getAgencyID = $conn->prepare("SELECT DISTINCT AG.id FROM agency AS AG INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id WHERE PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ?;");
                                                            $getAgencyID->bind_param("iiii", $profile1, $profile2, $profile3, $profile4);
                                                            if ($getAgencyID->execute()) {
                                                                $getAgencyID->store_result();
                                                                $getAgencyID->bind_result($idAgencyToAssign);
                                                                if ($getAgencyID->fetch()) {
                                                                    //Asignación de Plomería
                                                                    //Actualizar tipo de reporte a plomeria
                                                                    $reassingReport = $conn->prepare("UPDATE report SET idEmployee = ?, idReportType = ? WHERE id = ?;");
                                                                    $reassingReport->bind_param("iii", $employee, $plumber, $idReport);
                                                                    if ($reassingReport->execute()) {
                                                                        $idStatusPH = 30;
                                                                        $updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
                                                                        $updateEstatusContrato->bind_param("iii", $idStatusPH, $employee, $idReport);
                                                                        $updateEstatusContrato->execute();

                                                                        $reportEmployeeForm = $conn->prepare("INSERT INTO `report_AssignedStatus`(`idReport`, `idStatus`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                                                        $reportEmployeeForm->bind_param("ii", $idReport, $idStatus);
                                                                        $reportEmployeeForm->execute();

                                                                        $response["status"] = "OK";
                                                                        $response["code"] = "200";
                                                                        $response["response"] = "Report sell created";
                                                                        echo json_encode($response);
                                                                    }
                                                                }
                                                            }
                                                            
                                                            $plumber = 3;
                                                        }
                                                    }
                                                }
                                           /* } else {
                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Falla en la Asignación de Estatus para el Formulario " . $insertStatusReport->error;
                                                echo json_encode($response);
                                            }*/
                                        /*} else {
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Falla en Creación de enlace Reporte de Venta con Empleado: " . $reportEmployeeForm->error;
                                            echo json_encode($response);
                                        }*/
                                    } else {
                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] = "Falla en Creación para Reporte de Venta: " . $createSell->error;
                                        echo json_encode($response);
                                    }
                                } else if (intval($formType) == 2) {//PH
                                    //print_r($dataFormulario);
                                    $name = $dataFormulario["nombre"];
                                    $lastName = $dataFormulario["apellido_paterno"];
                                    $request = $dataFormulario["num_solicitud"];
                                    $documentNumber = $dataFormulario["num_solicitud"];
                                    if ($ri == null || $ri == 0 || $ri == "") {
                                        $ri = 0;
                                    } else {
                                        $ri = $dataFormulario["ri"];
                                    }
                                    $tapon = $dataFormulario["color_tapon"];
                                    if ($tapon == "verde") {
                                        $tapon = 1;
                                    } else {
                                        $tapon = 0;
                                    }
                                    $dictamen = $dataFormulario["num_dictamen"];
                                    //print_r($dataFormulario["calculos"]);
                                    //$preassureFalls = (array) $dataFormulario["calculos"];
                                    $preassureFalls = $dataFormulario["calculos"];
                                    //$preassureFalls = (array) $preassureFalls["CALCULOS"];
                                    //print_r($preassureFalls);
                                    $diagrama = $dataFormulario["diagrama"];
                                    $comments = $dataFormulario["observaciones"];
                                    //var_dump($comments);
                                    $newPipe = $dataFormulario["serequiere_tuberia"];
                                    if ($newPipe == true) {
                                        $newPipe = 1;
                                    } else {
                                        $newPipe = 0;
                                    }
                                    $pipesCount = $dataFormulario["num_tomas"];
                                    //$pipesInstallation = $dataFormulario["foto_tuberia"];
                                    $ph = $dataFormulario["resultado_ph"];
                                    //$rightFootBrand = $dataFormulario["foto_piederecho"];
                                    $fotos = $dataFormulario["fotos"];
                                    $diagram = $dataFormulario["diagrama"];
                                    /*var_dump($ph);
                                    var_dump($diagram);
                                    print_r($fotos);*/
                                    $createPlumberForm = $conn->prepare("INSERT INTO `form_plumber`(`name`, `lastName`, `request`, `tapon`, `documentNumber`, `diagram`, `ri`, `observations`, `newPipe`, `ph`, `pipesCount`, `meeting`, `latitude`, `longitude`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, NOW(), NOW(), 1);");
                                    $createPlumberForm->bind_param("sssissisiiidd", $name, $lastName, $request, $tapon, $documentNumber, $diagram, $ri, $observations, $newPipe, $ph, $pipesCount, $latitude, $longitude);

                                    if ($createPlumberForm->execute()) {
                                        $idPlumberForm = $createPlumberForm->insert_id;

                                        // var_dump("idPlumberForm");
                                        // var_dump($idPlumberForm);

                                        foreach ($fotos as $imgID) {
                                            $insertPlumberPipesPhoto = $conn->prepare("INSERT INTO `form_plumber_multimedia`(`idFormPlumber`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                            $insertPlumberPipesPhoto->bind_param("ii", $idPlumberForm, $imgID);
                                            if (!$insertPlumberPipesPhoto->execute()) {
                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Falla en el enlace de la Imagen de Tuberias con el Reporte de Plomero: " . $insertPlumberPipesPhoto->error;
                                                echo json_encode($response);
                                            }
                                        }
                                        //var_dump($fallsMeasurement);
                                        //print_r($preassureFalls);
                                        foreach ($preassureFalls as $key) {
                                            $data = (array)$key;
                                            //print_r($data);

                                            $createPlumberDetails = $conn->prepare("INSERT INTO `form_plumber_details`(`path`, `distance`, `pipe`, `fall`, `idFormPlumber`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");

                                            /* var_dump($data["tramo"]);
                                              var_dump($data["distancia"]);
                                              var_dump($data["tuberia"]);
                                              var_dump($data["caida"]);
                                              var_dump($idPlumberReport); */

                                            $createPlumberDetails->bind_param("ssssi", $data["tramo"], $data["distancia"], $data["tuberia"], $data["caida"], $idPlumberForm);
                                            if (!$createPlumberDetails->execute()) {
                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Falla en el registro de material: " . $createPlumberDetails->error;
                                                echo json_encode($response);
                                            }
                                        }
                                        $reportEmployeeForm = $conn->prepare("INSERT INTO `report_employee_form`(`idReport`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                        $reportEmployeeForm->bind_param("iii", $reportID, $id, $idPlumberForm);
                                        $reportEmployeeForm->execute();

                                        $idWorkflow = 1;
                                        //TODO revisar workflows
                                        if ($statusType == "Completo") {
                                            $idStatus = 3;
                                        } else if ($statusType == "Prendiente Reagendado") {
                                            $idStatus = 7;
                                        } else {
                                            $idStatus = 4;
                                        }

                                        $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                        $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $reportID);
                                        $insertStatusReport->execute();
                                    }
                                    $response["status"] = "OK";
                                    $response["code"] = "200";
                                    $response["response"] = "Creacion de Reporte para Plomero de Manera Exitosa.";
                                    $response["reportId"] = $idPlumberForm;
                                    echo json_encode($response);
                                        //Solamente crear el PH y buscar si el empleado tiene perfil de venta, asignar la segunda venta.
                                } else if (intval($formType) == 3) { //Instalacion
                                    $clientName = $dataFormulario["nombre"];
                                    $clientlastName = $dataFormulario["apellido_materno"];
                                    $numRequest = $dataFormulario["num_solicitud"];
                                    //$acometidaPhoto = $dataFormulario["foto_estado_acometida"];
                                    $labelColor = $dataFormulario["color_etiqueta"];
                                    //if ($labelColor == "verde") { $labelColor = 1; } else { $labelColor = 0; }
                                    $hasAgency = $dataFormulario["tiene_num_agencia"];
                                    $AgencyNumber = $dataFormulario["num_agencia"];
                                    $continue = $dataFormulario["se_proecede"];
                                    $issues_catalog = $dataFormulario["catalogo_anomalias"];
                                    $measurerBrand = $dataFormulario["marca_medidor"];
                                    $measurerKind = $dataFormulario["tipo_medidor"];
                                    $measurerNum = $dataFormulario["num_medidor"];
                                    $measurerData = $dataFormulario["lectura_medidor"];

                                    //$measurerPhoto = $dataFormulario["foto_caratula"];
                                    //$measurerKindMeasurerPhoto = $dataFormulario["foto_cuadromedicion"];

                                    /* var_dump($clientName);
                                      var_dump($clientlastName);
                                      var_dump($numRequest);
                                      var_dump($labelColor);
                                      var_dump($hasAgency);
                                      var_dump($AgencyNumber);
                                      var_dump($continue);
                                      var_dump($issues_catalog);
                                      var_dump($measurerBrand);
                                      var_dump($measurerKind);
                                      var_dump($measurerNum);
                                      var_dump($measurerData); */

                                    $materials = (array)$dataFormulario["materiales"];
                                    $materials = $materials["MATERIAL"];

                                    $installationReport = $conn->prepare("INSERT INTO `form_installation`(`name`, `lastName`, `request`, `phLabel`, `agencyPh`, `agencyNumber`, `installation`, `abnormalities`, `brand`, `type`, `serialNuber`, `measurement`, `latitude`, `longitude`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1);");
                                    $installationReport->bind_param("sssiisisssssdd", $clientName, $clientlastName, $numRequest, $labelColor, $hasAgency, $AgencyNumber, $continue, $issues_catalog, $measurerBrand, $measurerKind, $measurerNum, $measurerData, $latitude, $longitude);

                                    if ($installationReport->execute()) {
                                        $idInstallationReport = $installationReport->insert_id;

                                        //var_dump($materials);
                                        foreach ($materials as $key) {
                                            $key = (array)$key;
                                            /* var_dump($key);
                                              var_dump($key["nombre"]);
                                              var_dump($key["cantidad"]); */

                                            $idMaterial = $conn->prepare("SELECT id FROM form_installation_material WHERE materialName = ?;");
                                            $idMaterial->bind_param("s", $key["nombre"]);

                                            if ($idMaterial->execute()) {
                                                $idMaterial->store_result();
                                                $idMaterial->bind_result($id);

                                                $insertInstallationMaterials = $conn->prepare("INSERT INTO `form_installation_details`(`qty`, `idFormInstallation`, `idInstallationMaterial`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                                $insertInstallationMaterials->bind_param("iii", $key["cantidad"], $idInstallationReport, $id);

                                                if (!$insertInstallationMaterials->execute()) {
                                                    $response["status"] = "ERROR";
                                                    $response["code"] = "500";
                                                    $response["response"] = "Falla en el almacenaje de Materiales para la Instalacion: " . $insertInstallationMaterials->error;
                                                    echo json_encode($response);
                                                }
                                            } else {
                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Falla en la Busqueda de Material: " . $idMaterial->error;
                                                echo json_encode($response);
                                            }
                                        }

                                        $reportEmployeeForm = $conn->prepare("INSERT INTO `report_employee_form`(`idReport`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                        $reportEmployeeForm->bind_param("iii", $idReport, $id, $idInstallationReport);

                                        $idWorkflow = 1;
                                        //TODO revisar workflows
                                        if ($statusType == "Completo") {
                                            $idStatus = 3;
                                        } else if ($statusType == "Prendiente Reagendado") {
                                            $idStatus = 7;
                                        } else {
                                            $idStatus = 4;
                                        }
                                        //$idReport = $createReport->insert_id;

                                        $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                        $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $idReport);

                                        if ($reportEmployeeForm->execute()) {
                                            if ($insertStatusReport->execute()) {

                                                $statusVenta = 5;
                                                $asignado = 1;
                                                $validadoMexicana = 0;
                                                $validadoAyopsa = 0;
                                                $PHCompleto = 31;

                                                $estatusCrontratoReport = $conn->prepare("UPDATE tEstatusContrato SET estatusVenta = ?, asignadoMexicana = ?, validadoMexicana = ?, asignadoAyopsa = ?, validadoAyopsa = ?, phEstatus = ? WHERE id = ?;");
                                                $estatusCrontratoReport->bind_param("iiiiiii", $statusVenta, $asignado, $validadoMexicana, $asignado, $validadoAyopsa, $PHCompleto, $reportID);
                                                $estatusCrontratoReport->execute();

                                                $response["status"] = "OK";
                                                $response["code"] = "200";
                                                $response["response"] = "Creacion de Reporte para Instalacion de Manera Exitosa.";
                                                $response["reportId"] = $idInstallationReport;
                                                echo json_encode($response);
                                            }
                                        }
                                    } else {
                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] = "Falla en la Creacion del Reporte de Instalacion: " . $installationReport->error;
                                        echo json_encode($response);
                                    }
                                } else if (intval($formType) == 4) {//Segunda Venta

                                    //print_r($dataFormulario);
                                    $prospect = $dataFormulario["num_solicitud"];
                                    $idAgency = $dataFormulario["agencia"];
                                    //var_dump($idAgency);
                                    //var_dump("Agency");
                                    $payment = $dataFormulario["pagare"];
                                    $agreement = $dataFormulario["contrato"];
                                    $requestDate = $dataFormulario["fecha_solicitud"];
                                    $clientName = $clientLastName2;
                                    $clientlastName = $clientName;
                                    $clientlastName2 = $clientLastName1;
                                    $clientRFC = $dataFormulario["rfc"];
                                    $clientCURP = $dataFormulario["curp"];
                                    $clientEmail = $dataFormulario["correo"];
                                    $clientRelationship = $dataFormulario["estado_civil"];
                                    $clientgender = $dataFormulario["sexo"];
                                    $clientIdNumber = $dataFormulario["num_identificacion"];
                                    $identificationType = $dataFormulario["tipo_identificacion"];
                                    $clientBirthDate = $dataFormulario["fecha_nacimiento"];
                                    $clientBirthCountry = $dataFormulario["pais_nacimiento"];
                                    $idState = $dataFormulario["estado"];
                                    $idCity = $dataFormulario["municipio"];
                                    $idColonia = $dataFormulario["colonia"];
                                    $street = $dataFormulario["calle"];
                                    $inHome = $dataFormulario["vive_encasa"];
                                    $homeTelephone = $dataFormulario["tel_casa"];
                                    $celullarTelephone = $dataFormulario["tel_celular"];
                                    $agreementType = $dataFormulario["tipo_contrato"];
                                    $price = $dataFormulario["precio"];
                                    $agreementExpires = $dataFormulario["plazo"];
                                    $agreementMonthlyPayment = $dataFormulario["mensualidad"];
                                    $agreementRi = $dataFormulario["ri"];
                                    $agreementRiDate = $dataFormulario["fecha_ri"];
                                    //var_dump("----fecha_ri----");
                                    //var_dump($agreementRiDate);
                                    $references = (array)$dataFormulario["referencias_array"];
                                    //print_r($references);
                                    $clientJobEnterprise = $dataFormulario["empresa"];
                                    $clientJobLocation = $dataFormulario["direccion"];
                                    $clientJobRange = $dataFormulario["puesto"];
                                    $clientJobActivity = $dataFormulario["area"];
                                    $clientJobTelephone = $dataFormulario["tel_empresa"];

                                    $getFormSell = $conn->prepare("SELECT DISTINCT FS.`id`, FS.`financialService`, RP.`employeesAssigned`, RP.`idUserCreator` FROM report_employee_form AS REF INNER JOIN form_sells AS FS ON REF.idForm = FS.id INNER JOIN report AS RP ON REF.idReport = RP.id WHERE RP.`id` = ?;");
                                    $getFormSell->bind_param("i", $reportID);
                                    if ($getFormSell->execute()) {
                                        $getFormSell->store_result();
                                        $getFormSell->bind_result($idForm, $financialService, $employeesAssigned, $idUserCreator);
                                        if ($getFormSell->fetch()) {
                                            //var_dump($idForm); var_dump($financialService); var_dump($employeesAssigned); var_dump($idUserCreator);
                                            //var_dump("employeesAssigned");
                                            $getFormSellValidation = $conn->prepare("SELECT id, validate FROM form_sells_validation WHERE idFormSell = ?;");
                                            $getFormSellValidation->bind_param("i", $idForm);
                                            if ($getFormSellValidation->execute()) {
                                                $getFormSellValidation->store_result();
                                                $getFormSellValidation->bind_result($idFormValidation, $validateStatus);
                                                if ($getFormSellValidation->fetch()) {
                                                    if ($validateStatus == 1) {
                                                        //var_dump($idFormValidation); var_dump($validateStatus);
                                                        //exit;
                                                        $getPlumberForm = $conn->prepare("SELECT DISTINCT FP.`id`, RP.`employeesAssigned`, RP.`idUserCreator` FROM report_employee_form AS REF INNER JOIN form_plumer AS FP ON REF.idForm = FP.id INNER JOIN report AS RP ON REF.idReport = RP.id WHERE RP.id = ?;");
                                                        $getPlumberForm->bind_param("i", $reportID);
                                                        if ($getPlumberForm->execute()) {
                                                            $getPlumberForm->store_result();
                                                            $getPlumberForm->bind_result($idForm, $employeesAssigned, $idUserCreator);
                                                            if ($getPlumberForm->fetch()) {
                                                                if ($employeesAssigned == "" && $idForm != 0) {

                                                                    //BUSQUEDA DE CONTRATO PARA LA SOLICITUD
                                                                    $getAgreementNumber = $conn->prepare("SELECT AGR.idAgency, AGR.payment, AGR.idReport, AGR.requestDate, AGR.clientlastName, AGR.clientlastName2, AGR.clientName, AGR.clientRFC, AGR.clientCURP, AGR.clientEmail, AGR.clientRelationship, AGR.clientgender, AGR.clientIdNumber, AGR.identificationType, AGR.clientBirthDate, AGR.clientBirthCountry, AGR.idState, AGR.idCity, AGR.idColonia, AGR.street, AGR.inHome, AGR.homeTelephone, AGR.celullarTelephone, AGR.agreementType, AGR.price, AGR.agreementExpires, AGR.agreementMonthlyPayment, AGR.agreementRi, AGR.agreementRiDate, AGR.clientJobEnterprise, AGR.clientJobLocation, AGR.clientJobRange, AGR.clientJobActivity, AGR.clientJobTelephone, AGR.latitude, AGR.longitude, AGR.created_at, AGR.updated_at FROM report AS RP INNER JOIN agreement_employee_report AS AGEMP ON RP.id = AGEMP.idReport INNER JOIN agreement AS AGR ON AGEMP.idAgreement = AGR.id WHERE RP.id = ?;");
                                                                    $getAgreementNumber->bind_param("i", $reportID);
                                                                    if ($getAgreementNumber->execute()) {
                                                                        $getAgreementNumber->store_result();
                                                                        $getAgreementNumber->bind_result($idAgency, $payment, $reportID, $requestDate,
                                                                            $clientlastName, $clientlastName2, $clientName, $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber,
                                                                            $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia, $street, $inHome, $homeTelephone, $celullarTelephone,
                                                                            $agreementType, $price, $agreementExpires, $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation, $clientJobRange,
                                                                            $clientJobActivity, $clientJobTelephone, $latitude, $longitude);
                                                                        if ($getAgreementNumber->fetch()) {
                                                                            $sell['tf_solcto_id'] = $reportID;
                                                                            $sell['tf_sucursal'] = null;
                                                                            $sell['tf_fecha_venta'] = null;
                                                                            $sell['tf_nombre'] = $clientName;
                                                                            $sell['tf_appaterno'] = $clientlastName;
                                                                            $sell['tf_apmaterno'] = $clientlastName2;
                                                                            $sell['tf_fnac'] = null;
                                                                            $sell['tf_rfc'] = null;
                                                                            $sell['tf_vendedor'] = null;
                                                                            $sell['tf_vivienda'] = null;
                                                                            $sell['tf_tiempo'] = null;
                                                                            $sell['tf_uni_tiempo'] = null;
                                                                            $sell['tf_nomemp'] = $clientJobEnterprise;
                                                                            $sell['tf_diremp'] = $clientJobLocation;
                                                                            $sell['tf_telemp'] = $clientJobTelephone;
                                                                            $sell['tf_cbtelemp'] = $clientJobActivity;
                                                                            $sell['tf_ptoemp'] = $clientJobRange;
                                                                            $sell['tf_actemp'] = null;
                                                                            $sell['tf_aparato'] = null;
                                                                            $sell['tf_causas'] = null;
                                                                            $sell['tf_dir_id'] = null;
                                                                            $sell['tf_ffirma'] = null;
                                                                            $sell['tf_fpago'] = null;
                                                                            $sell['tf_antsol'] = null;
                                                                            $sell['tf_mtsri'] = null;
                                                                            $sell['tf_tomasri'] = null;
                                                                            $sell['tf_fpbahr'] = null;
                                                                            $sell['tf_fredint'] = null;
                                                                            $sell['tf_email'] = null;
                                                                            $sell['tf_enviar_cfdi'] = null;
                                                                            $sell['tf_foliocto'] = null;
                                                                            $sell['tf_foliopag'] = null;
                                                                            $sell['tf_serie'] = null;
                                                                            $sell['tf_articulo'] = null;
                                                                            $sell['tf_financiar'] = null;
                                                                            $sell['tf_precio'] = null;
                                                                            $sell['tf_estufa'] = null;
                                                                            $sell['tf_boiler'] = null;
                                                                            $sell['tf_calentador'] = null;
                                                                            $sell['tf_tpocto'] = null;
                                                                            $sell['tf_tel1'] = $homeTelephone;
                                                                            $sell['tf_tel2'] = null;
                                                                            $sell['tf_tel3'] = null;
                                                                            $sell['tf_movil'] = $celullarTelephone;
                                                                            $sell['tf_ext2'] = null;
                                                                            $sell['tf_ext3'] = null;
                                                                            $sell['tf_nomrefer'] = null;
                                                                            $sell['tf_nomrefer2'] = null;
                                                                            $sell['tf_nomrefer3'] = null;
                                                                            $sell['tf_telTrab1'] = null;
                                                                            $sell['tf_telTrab2'] = null;
                                                                            $sell['tf_telTrab3'] = null;
                                                                            $sell['tf_cbTipoTelRef1'] = null;
                                                                            $sell['tf_cbTipoTelRef2'] = null;
                                                                            $sell['tf_cbTipoTelRef3'] = null;
                                                                            $sell['tf_telPart1'] = null;
                                                                            $sell['tf_telPart2'] = null;
                                                                            $sell['tf_telPart3'] = null;
                                                                            $sell['tf_cbTipoTelPartRef1'] = null;
                                                                            $sell['tf_cbTipoTelPartRef2'] = null;
                                                                            $sell['tf_cbTipoTelPartRef3'] = null;
                                                                            $sell['tf_pais'] = null;
                                                                            $sell['tf_docs'] = null;
                                                                            //$sellInfo[] = $sell;
                                                                            //print_r($sell);
                                                                            //exit;

                                                                            $it_solicitud = $sell;
                                                                            $ip_cia_id = 0;
                                                                            $ip_usr_id = $idUserCreator;
                                                                            $ip_contrato = $idAgreement;

                                                                            $clienteSoapMexicana = "http://111.111.111.16:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
                                                                            $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

                                                                            $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
                                                                            $nuSoapClientMexicana->decode_utf8 = false;

                                                                            $postData = array(
                                                                                'ip_cia_id' => $ip_cia_id,
                                                                                'ip_usr_id' => $ip_usr_id,
                                                                                'it_solicitud' => $it_solicitud,
                                                                                'ip_contrato' => $ip_contrato,
                                                                            );

                                                                            $AgreementResult = $nuSoapClientMexicana->call('ws_siscom_guarda_contrato', $postData);

                                                                            if ($nuSoapClientMexicana->fault) {

                                                                            } else {
                                                                                $err = $nuSoapClientMexicana->getError();
                                                                            }
                                                                            if ($err) {
                                                                                echo json_encode($err);
                                                                            } else {
                                                                                $instalation = 4;
                                                                                $employeesAssigned = "";
                                                                                $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
                                                                                $getEmployeeData->bind_param('i', $id);
                                                                                if ($getEmployeeData->execute()) {
                                                                                    $getEmployeeData->store_result();
                                                                                    $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
                                                                                    if ($getEmployeeData->fetch()) {
                                                                                        if ($profileID == 4 || $profileID == 8 && $reportType == 3) {
                                                                                            //Asignación de instalacion
                                                                                            $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                                                                                            $reassingReport->bind_param("iisi", $employee, $instalation, $employeesAssigned, $idReport);
                                                                                            $reassingReport->execute();

                                                                                            $statusInstalacion = 4;
                                                                                            $asignacionInstalacion = 1;

                                                                                            $statusContratoReport = $conn->prepare("UPDATE tEstatusContrato SET validacionInstalacion = ?, estatusAsignacionInstalacion = ?, idEmpleadoInstalacion = ? WHERE id = ?;");
                                                                                            $statusContratoReport->bind_param("iiii", $statusInstalacion, $asignacionInstalacion, $employee, $idReport);
                                                                                            $statusContratoReport->execute();

                                                                                        } else {
                                                                                            //ASIGNAR A AGENCIA QUE PERTENEZCA AL MUNICIPIO DONDE FUE CREADO EL REPORTE
                                                                                            $getReportCity = $conn->prepare("SELECT DISTINCT RP.`id`, RP.`idUserCreator`, RP.`idEmployee`, RP.`idCity` FROM report AS RP WHERE RP.id = ?;");
                                                                                            $getReportCity->bind_param("i", $idReport);
                                                                                            if ($getReportCity->execute()) {
                                                                                                $getReportCity->store_result();
                                                                                                $getReportCity->bind_result($idReport, $idUserCreator, $idEmployee, $city);
                                                                                                if ($getReportCity->fetch()) {
                                                                                                    //SELECCIONAR AGENCIA QUE TENGA EL MUNICIPIO ASIGNADO

                                                                                                    $getAgencyCity = $conn->prepare("SELECT DISTINCT ASCTY.id FROM agency_cities AS ASCTY WHERE ASCTY.idCity = ?;");
                                                                                                    $getAgencyCity->bind_param("i", $city);
                                                                                                    if ($getAgencyCity->execute()) {
                                                                                                        $getAgencyCity->store_result();
                                                                                                        $getAgencyCity->bind_result($agency);
                                                                                                        if ($getAgencyCity->fetch()) {
                                                                                                            $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                                                                                                            $reassingReport->bind_param("iisi", $agency, $instalation, $employeesAssigned, $idReport);
                                                                                                            $reassingReport->execute();

                                                                                                            $statusInstalacion = 4;
                                                                                                            $asignacionInstalacion = 1;

                                                                                                            $statusContratoReport = $conn->prepare("UPDATE tEstatusContrato SET validacionInstalacion = ?, estatusAsignacionInstalacion = ?, idEmpleadoInstalacion = ? WHERE id = ?;");
                                                                                                            $statusContratoReport->bind_param("iiii", $statusInstalacion, $asignacionInstalacion, $agency, $idReport);
                                                                                                            $statusContratoReport->execute();
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                $updateClientNumber = $conn->prepare("UPDATE report SET idAgreement = ? WHERE id = ?;");
                                                                                $updateClientNumber->bind_param("ii", intval($AgreementResult['ip_contrato']), $idReport);
                                                                                if ($updateClientNumber->execute()) {
                                                                                    $finish["status"] = "OK";
                                                                                    $finish["code"] = "200";
                                                                                    $finish["response"] = "Contrato Creado Exitosamente";
                                                                                    echo json_encode($finish);
                                                                                }
                                                                            }

                                                                        } else {
                                                                            $createSecondStepSell = $conn->prepare("INSERT INTO `agreement`(`idAgency`, `payment`, `idReport`, `requestDate`, `clientlastName`, `clientlastName2`, `clientName`,
                                                                                  `clientRFC`, `clientCURP`, `clientEmail`, `clientRelationship`, `clientgender`, `clientIdNumber`, `identificationType`, `clientBirthDate`, `clientBirthCountry`,
                                                                                  `idState`, `idCity`, `idColonia`, `street`, `inHome`, `homeTelephone`, `celullarTelephone`, `agreementType`, `price`, `agreementExpires`, `agreementMonthlyPayment`,
                                                                                  `agreementRi`, `agreementRiDate`, `clientJobEnterprise`, `clientJobLocation`, `clientJobRange`, `clientJobActivity`, `clientJobTelephone`, `latitude`, `longitude`,
                                                                                  `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                                            $createSecondStepSell->bind_param("idisssssssssssssiiisissssisdssssssdd", $idAgency, $payment, $reportID, $requestDate,
                                                                                $clientlastName, $clientlastName2, $clientName, $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber,
                                                                                $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia, $street, $inHome, $homeTelephone, $celullarTelephone,
                                                                                $agreementType, $price, $agreementExpires, $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation, $clientJobRange,
                                                                                $clientJobActivity, $clientJobTelephone, $latitude, $longitude);
                                                                            if ($createSecondStepSell->execute()) {
                                                                                $secondSell = $createSecondStepSell->insert_id;

                                                                                //Después de crear la segunda venta es necesario asignar el reporte a instalación en base a la lógica del procedimiento definida
                                                                                foreach ($references as $key) {
                                                                                    $data = (array)$key;

                                                                                    $createReference = $conn->prepare("INSERT INTO `agreement_reference`(`name`, `telephone`, `jobTelephone`, `ext`, `idAgreement`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                                                                                    $createReference->bind_param("ssssi", $data["nombre"], $data["telefono_particular"], $data["telefono_trabajo"], $data["extension"], $secondSell);

                                                                                    if (!$createReference->execute()) {
                                                                                        $response = null;

                                                                                        $response["status"] = "ERROR";
                                                                                        $response["code"] = "500";
                                                                                        $response["response"] = "Error en la creación de imagen de referencia" . $createReference->error;
                                                                                        echo json_encode($response);
                                                                                    }
                                                                                }

                                                                                $createAgreeReport = $conn->prepare("INSERT INTO `agreement_employee_report`(`idAgreement`, `idEmployee`, `idReport`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                                                                $createAgreeReport->bind_param("iii", $secondSell, $id, $reportID);
                                                                                if ($createAgreeReport->execute()) {

                                                                                    $idWorkflow = 1;

                                                                                    if ($statusType == "Completo") {
                                                                                        $idStatus = 3;
                                                                                    } else if ($statusType == "Prendiente Reagendado") {
                                                                                        $idStatus = 7;
                                                                                    } else {
                                                                                        $idStatus = 4;
                                                                                    }

                                                                                    $idAgreegment = $createSecondStepSell->insert_id;

                                                                                    $createAgreementStatus = $conn->prepare("INSERT INTO `workflow_status_agreement`(`idWorkflow`, `idStatus`, `idAgreement`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                                                                    $createAgreementStatus->bind_param("iii", $idWorkflow, $idStatus, $idAgreegment);
                                                                                    $response = null;

                                                                                    if ($createAgreementStatus->execute()) {
                                                                                        $sell['tf_solcto_id'] = $idReport;
                                                                                        $sell['tf_sucursal'] = null;
                                                                                        $sell['tf_fecha_venta'] = null;
                                                                                        $sell['tf_nombre'] = $clientName;
                                                                                        $sell['tf_appaterno'] = $clientlastName;
                                                                                        $sell['tf_apmaterno'] = $clientlastName2;
                                                                                        $sell['tf_fnac'] = null;
                                                                                        $sell['tf_rfc'] = null;
                                                                                        $sell['tf_vendedor'] = null;
                                                                                        $sell['tf_vivienda'] = null;
                                                                                        $sell['tf_tiempo'] = null;
                                                                                        $sell['tf_uni_tiempo'] = null;
                                                                                        $sell['tf_nomemp'] = $clientJobEnterprise;
                                                                                        $sell['tf_diremp'] = $clientJobLocation;
                                                                                        $sell['tf_telemp'] = $clientJobTelephone;
                                                                                        $sell['tf_cbtelemp'] = $clientJobActivity;
                                                                                        $sell['tf_ptoemp'] = $clientJobRange;
                                                                                        $sell['tf_actemp'] = null;
                                                                                        $sell['tf_aparato'] = null;
                                                                                        $sell['tf_causas'] = null;
                                                                                        $sell['tf_dir_id'] = null;
                                                                                        $sell['tf_ffirma'] = null;
                                                                                        $sell['tf_fpago'] = null;
                                                                                        $sell['tf_antsol'] = null;
                                                                                        $sell['tf_mtsri'] = null;
                                                                                        $sell['tf_tomasri'] = null;
                                                                                        $sell['tf_fpbahr'] = null;
                                                                                        $sell['tf_fredint'] = null;
                                                                                        $sell['tf_email'] = null;
                                                                                        $sell['tf_enviar_cfdi'] = null;
                                                                                        $sell['tf_foliocto'] = null;
                                                                                        $sell['tf_foliopag'] = null;
                                                                                        $sell['tf_serie'] = null;
                                                                                        $sell['tf_articulo'] = null;
                                                                                        $sell['tf_financiar'] = null;
                                                                                        $sell['tf_precio'] = null;
                                                                                        $sell['tf_estufa'] = null;
                                                                                        $sell['tf_boiler'] = null;
                                                                                        $sell['tf_calentador'] = null;
                                                                                        $sell['tf_tpocto'] = null;
                                                                                        $sell['tf_tel1'] = $homeTelephone;
                                                                                        $sell['tf_tel2'] = null;
                                                                                        $sell['tf_tel3'] = null;
                                                                                        $sell['tf_movil'] = $celullarTelephone;
                                                                                        $sell['tf_ext2'] = null;
                                                                                        $sell['tf_ext3'] = null;
                                                                                        $sell['tf_nomrefer'] = null;
                                                                                        $sell['tf_nomrefer2'] = null;
                                                                                        $sell['tf_nomrefer3'] = null;
                                                                                        $sell['tf_telTrab1'] = null;
                                                                                        $sell['tf_telTrab2'] = null;
                                                                                        $sell['tf_telTrab3'] = null;
                                                                                        $sell['tf_cbTipoTelRef1'] = null;
                                                                                        $sell['tf_cbTipoTelRef2'] = null;
                                                                                        $sell['tf_cbTipoTelRef3'] = null;
                                                                                        $sell['tf_telPart1'] = null;
                                                                                        $sell['tf_telPart2'] = null;
                                                                                        $sell['tf_telPart3'] = null;
                                                                                        $sell['tf_cbTipoTelPartRef1'] = null;
                                                                                        $sell['tf_cbTipoTelPartRef2'] = null;
                                                                                        $sell['tf_cbTipoTelPartRef3'] = null;
                                                                                        $sell['tf_pais'] = null;
                                                                                        $sell['tf_docs'] = null;
                                                                                        //$sellInfo[] = $sell;
                                                                                        //print_r($sell);
                                                                                        //exit;

                                                                                        $it_solicitud = $sell;
                                                                                        $ip_cia_id = 0;
                                                                                        $ip_usr_id = $idUserCreator;
                                                                                        $ip_contrato = $idAgreement;

                                                                                        $clienteSoapMexicana = "http://111.111.111.16:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
                                                                                        $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

                                                                                        $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
                                                                                        $nuSoapClientMexicana->decode_utf8 = false;

                                                                                        $postData = array(
                                                                                            'ip_cia_id' => $ip_cia_id,
                                                                                            'ip_usr_id' => $ip_usr_id,
                                                                                            'it_solicitud' => $it_solicitud,
                                                                                            'ip_contrato' => $ip_contrato,
                                                                                        );

                                                                                        $AgreementResult = $nuSoapClientMexicana->call('ws_siscom_guarda_contrato', $postData);

                                                                                        if ($nuSoapClientMexicana->fault) {

                                                                                        } else {
                                                                                            $err = $nuSoapClientMexicana->getError();
                                                                                        }
                                                                                        if ($err) {
                                                                                            echo json_encode($err);
                                                                                        } else {
                                                                                            $instalation = 4;
                                                                                            $employeesAssigned = "";
                                                                                            $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
                                                                                            $getEmployeeData->bind_param('i', $id);
                                                                                            if ($getEmployeeData->execute()) {
                                                                                                $getEmployeeData->store_result();
                                                                                                $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
                                                                                                if ($getEmployeeData->fetch()) {
                                                                                                    if ($profileID == 4 || $profileID == 8 && $reportType == 3) {
                                                                                                        //Asignación de instalacion
                                                                                                        $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                                                                                                        $reassingReport->bind_param("iisi", $employee, $instalation, $employeesAssigned, $idReport);
                                                                                                        $reassingReport->execute();
                                                                                                    } else {
                                                                                                        //ASIGNAR A AGENCIA QUE PERTENEZCA AL MUNICIPIO DONDE FUE CREADO EL REPORTE
                                                                                                        $getReportCity = $conn->prepare("SELECT DISTINCT RP.`id`, RP.`idUserCreator`, RP.`idEmployee`, RP.`idCity` FROM report AS RP WHERE RP.id = ?;");
                                                                                                        $getReportCity->bind_param("i", $idReport);
                                                                                                        if ($getReportCity->execute()) {
                                                                                                            $getReportCity->store_result();
                                                                                                            $getReportCity->bind_result($idReport, $idUserCreator, $idEmployee, $city);
                                                                                                            if ($getReportCity->fetch()) {
                                                                                                                //SELECCIONAR AGENCIA QUE TENGA EL MUNICIPIO ASIGNADO

                                                                                                                $getAgencyCity = $conn->prepare("SELECT DISTINCT ASCTY.id FROM agency_cities AS ASCTY WHERE ASCTY.idCity = ?;");
                                                                                                                $getAgencyCity->bind_param("i", $city);
                                                                                                                if ($getAgencyCity->execute()) {
                                                                                                                    $getAgencyCity->store_result();
                                                                                                                    $getAgencyCity->bind_result($agency);
                                                                                                                    if ($getAgencyCity->fetch()) {
                                                                                                                        $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                                                                                                                        $reassingReport->bind_param("iisi", $agency, $instalation, $employeesAssigned, $idReport);
                                                                                                                        $reassingReport->execute();
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                            $updateClientNumber = $conn->prepare("UPDATE report SET idAgreement = ? WHERE id = ?;");
                                                                                            $updateClientNumber->bind_param("ii", intval($AgreementResult['ip_contrato']), $idReport);
                                                                                            if ($updateClientNumber->execute()) {
                                                                                                $finish["status"] = "OK";
                                                                                                $finish["code"] = "200";
                                                                                                $finish["response"] = "Contrato Creado Exitosamente";
                                                                                                echo json_encode($finish);
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        $response["status"] = "ERROR";
                                                                                        $response["code"] = "500";
                                                                                        $response["response"] = "Error en la Creacion del status de Contrato: " . $createAgreeForm->error;
                                                                                        echo json_encode($response);
                                                                                    }
                                                                                } else {
                                                                                    $response["status"] = "ERROR";
                                                                                    $response["code"] = "500";
                                                                                    $response["response"] = "Error en la Creacion de Formulario Contrato: " . $createAgreeForm->error;
                                                                                    echo json_encode($response);
                                                                                }
                                                                            }
                                                                        }
                                                                    }

                                                                    /*$createSecondStepSell = $conn->prepare("INSERT INTO `agreement`(`idAgency`, `payment`, `idReport`, `requestDate`, `clientlastName`, `clientlastName2`, `clientName`,
                                                                      `clientRFC`, `clientCURP`, `clientEmail`, `clientRelationship`, `clientgender`, `clientIdNumber`, `identificationType`, `clientBirthDate`, `clientBirthCountry`,
                                                                      `idState`, `idCity`, `idColonia`, `street`, `inHome`, `homeTelephone`, `celullarTelephone`, `agreementType`, `price`, `agreementExpires`, `agreementMonthlyPayment`,
                                                                      `agreementRi`, `agreementRiDate`, `clientJobEnterprise`, `clientJobLocation`, `clientJobRange`, `clientJobActivity`, `clientJobTelephone`, `latitude`, `longitude`,
                                                                      `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                                    $createSecondStepSell->bind_param("idisssssssssssssiiisissssisdssssssdd", $idAgency, $payment, $reportID, $requestDate,
                                                                        $clientlastName, $clientlastName2, $clientName, $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber,
                                                                        $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia, $street, $inHome, $homeTelephone, $celullarTelephone,
                                                                        $agreementType, $price, $agreementExpires, $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation, $clientJobRange,
                                                                        $clientJobActivity, $clientJobTelephone, $latitude, $longitude);
                                                                    if ($createSecondStepSell->execute()) {
                                                                        $secondSell = $createSecondStepSell->insert_id;

                                                                        //Después de crear la segunda venta es necesario asignar el reporte a instalación en base a la lógica del procedimiento definida

                                                                        foreach ($references as $key) {
                                                                            $data = (array) $key;

                                                                            $createReference = $conn->prepare("INSERT INTO `agreement_reference`(`name`, `telephone`, `jobTelephone`, `ext`, `idAgreement`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                                                                            $createReference->bind_param("ssssi", $data["nombre"], $data["telefono_particular"], $data["telefono_trabajo"], $data["extension"], $secondSell);

                                                                            if (!$createReference->execute()) {
                                                                                $response = null;

                                                                                $response["status"] = "ERROR";
                                                                                $response["code"] = "500";
                                                                                $response["response"] = "Error en la creación de imagen de referencia" . $createReference->error;
                                                                                echo json_encode($response);
                                                                            }
                                                                        }

                                                                        $createAgreeReport = $conn->prepare("INSERT INTO `agreement_employee_report`(`idAgreement`, `idEmployee`, `idReport`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                                                        $createAgreeReport->bind_param("iii", $secondSell, $id, $reportID);
                                                                        if ($createAgreeReport->execute()) {

                                                                            $idWorkflow = 1;
                                                                            $idStatus = 3;
                                                                            $idAgreegment = $createSecondStepSell->insert_id;

                                                                            $createAgreementStatus = $conn->prepare("INSERT INTO `workflow_status_agreement`(`idWorkflow`, `idStatus`, `idAgreement`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                                                            $createAgreementStatus->bind_param("iii", $idWorkflow, $idStatus, $idAgreegment);
                                                                            $response = null;

                                                                            if ($createAgreementStatus->execute()) {
                                                                                $sell['tf_solcto_id'] = $idReport;
                                                                                $sell['tf_sucursal'] = "";
                                                                                $sell['tf_dir'] = $street;
                                                                                $sell['tf_fecha_venta'] = $requestDate;
                                                                                $sell['tf_nombre'] = $clientName;
                                                                                $sell['tf_appaterno'] = $clientlastName;
                                                                                $sell['tf_apmaterno'] = $clientlastName2;
                                                                                $sell['tf_fnac'] = $clientBirthDate;
                                                                                $sell['tf_rfc'] = $clientRFC;
                                                                                $sell['tf_vendedor'] = $creator;
                                                                                $sell['tf_vivienda'] = "";
                                                                                $sell['tf_tiempo'] = "";
                                                                                $sell['tf_uni_tiempo'] = "";
                                                                                $sell['tf_nomemp'] = $clientJobEnterprise;
                                                                                $sell['tf_diremp'] = $clientJobLocation;
                                                                                $sell['tf_telemp'] = $clientJobTelephone;
                                                                                $sell['tf_cbtelemp'] = $clientJobActivity;
                                                                                $sell['tf_ptoemp'] = $clientJobRange;
                                                                                $sell['tf_nomfam'] = "";
                                                                                $sell['tf_parentesco'] = "";
                                                                                $sell['tf_nomempfam'] = "";
                                                                                $sell['tf_dirempfam'] = "";
                                                                                $sell['tf_ptoempfam'] = "";
                                                                                $sell['tf_telempfam'] = "";
                                                                                $sell['tf_cbtelempfam'] = "";
                                                                                $sell['tf_teleref'] = "";
                                                                                $sell['tf_cbteleref'] = "";
                                                                                $sell['tf_telpref'] = "";
                                                                                $sell['tf_cbtelpref'] = "";
                                                                                $sell['tf_aparato'] = "";
                                                                                $sell['tf_causas'] = "";
                                                                                $sell['tf_dir_id'] = "";
                                                                                $sell['tf_ffirma'] = "";
                                                                                $sell['tf_fpago'] = $financialService;
                                                                                $sell['tf_antcto'] = "";
                                                                                $sell['tf_antsol'] = "";
                                                                                $sell['tf_mtsri'] = "";
                                                                                $sell['tf_tomasri'] = "";
                                                                                $sell['tf_fpbahr'] = "";
                                                                                $sell['tf_docs'] = "";
                                                                                $sell['tf_fredint'] = "";
                                                                                $sell['tf_actemp'] = "";
                                                                                $sell['tf_actempfam'] = "";
                                                                                $sell['tf_email'] = "";
                                                                                $sell['tf_enviar_cfdi'] = "";
                                                                                $sell['tf_foliocto'] = "";
                                                                                $sell['tf_foliopag'] = "";
                                                                                $sell['tf_serie'] = "";
                                                                                $sell['tf_articulo'] = "";
                                                                                $sell['tf_financiar'] = "";
                                                                                $sell['tf_precio'] = "";
                                                                                $sell['tf_estufa'] = "";
                                                                                $sell['tf_boiler'] = "";
                                                                                $sell['tf_calentador'] = "";
                                                                                $sell['tf_tpocto'] = "";
                                                                                $sell['tf_tel1'] = $homeTelephone;
                                                                                $sell['tf_tel2'] = "";
                                                                                $sell['tf_tel3'] = "";
                                                                                $sell['tf_movil'] = $celullarTelephone;
                                                                                $sell['tf_ext2'] = "";
                                                                                $sell['tf_ext3'] = "";
                                                                                $sell['tf_nomrefer'] = "";
                                                                                $sell['tf_nomrefer2'] = "";
                                                                                $sell['tf_nomrefer3'] = "";
                                                                                $sell['tf_telTrab1'] = "";
                                                                                $sell['tf_telTrab2'] = "";
                                                                                $sell['tf_telTrab3'] = "";
                                                                                $sell['tf_cbTipoTelRef1'] = "";
                                                                                $sell['tf_cbTipoTelRef2'] = "";
                                                                                $sell['tf_cbTipoTelRef3'] = "";
                                                                                $sell['tf_telPart1'] = "";
                                                                                $sell['tf_telPart2'] = "";
                                                                                $sell['tf_telPart3'] = "";
                                                                                $sell['tf_cbTipoTelPartRef1'] = "";
                                                                                $sell['tf_cbTipoTelPartRef2'] = "";
                                                                                $sell['tf_cbTipoTelPartRef3'] = "";
                                                                                $sell['tf_precio'] = $price;
                                                                                $sell['tf_pais'] = "";

                                                                                $returnData['jsonItSolicitud'] = $sell;
                                                                                $returnData = json_encode($returnData);

                                                                                $ip_cia_id = 0;
                                                                                $ip_usr_id = $idUserCreator;
                                                                                $curl = curl_init();

                                                                                mexicanaServerTest/v1/api/WsSiscomGuardaContrato.php");
                                                                                curl_setopt($curl, CURLOPT_POST, 1);
                                                                                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('ip_cia_id' => $ip_cia_id, 'ip_usr_id' => $ip_usr_id, 'it_solicitud' => $returnData, 'ip_contrato' => 0, 'jsonItSolicitud' => $returnData)));

                                                                                $response = curl_exec($curl);
                                                                                $err = curl_error($curl);

                                                                                curl_close($curl);

                                                                                $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
                                                                                $getEmployeeData->bind_param('i', $id);
                                                                                if ( $getEmployeeData->execute() ) {
                                                                                    $getEmployeeData->store_result();
                                                                                    $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
                                                                                    if ( $getEmployeeData->fetch() ) {
                                                                                        if ( $profileID == 4 || $profileID == 8 && $reportType == 3 ) {
                                                                                            //Asignación de instalacion
                                                                                            $instalation = 4;
                                                                                            $employeesAssigned = "";

                                                                                            $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                                                                                            $reassingReport->bind_param("iisi", $employee, $instalation, $employeesAssigned, $idReport);
                                                                                            $reassingReport->execute();
                                                                                        } else {
                                                                                            $instalation = 4;
                                                                                            $employeesAssigned = "";
                                                                                            //ASIGNAR A AGENCIA QUE PERTENEZCA AL MUNICIPIO DONDE FUE CREADO EL REPORTE
                                                                                            $getReportCity = $conn->prepare("SELECT DISTINCT RP.`id`, RP.`idUserCreator`, RP.`idEmployee`, RP.`idCity` FROM report AS RP WHERE RP.id = ?;");
                                                                                            $getReportCity->bind_param("i", $idReport);
                                                                                            if ($getReportCity->execute()) {
                                                                                                $getReportCity->store_result();
                                                                                                $getReportCity->bind_result($idReport, $idUserCreator, $idEmployee, $city);
                                                                                                if ($getReportCity->fetch()) {
                                                                                                    //SELECCIONAR AGENCIA QUE TENGA EL MUNICIPIO ASIGNADO

                                                                                                    $getAgencyCity = $conn->prepare("SELECT DISTINCT ASCTY.id FROM agency_cities AS ASCTY WHERE ASCTY.idCity = ?;");
                                                                                                    $getAgencyCity->bind_param("i", $city);
                                                                                                    if ($getAgencyCity->execute()) {
                                                                                                        $getAgencyCity->store_result();
                                                                                                        $getAgencyCity->bind_result($agency);
                                                                                                        if ($getAgencyCity->fetch()) {
                                                                                                            $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                                                                                                            $reassingReport->bind_param("iisi", $agency, $instalation, $employeesAssigned, $idReport);
                                                                                                            $reassingReport->execute();
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                if ($err) {
                                                                                    $finish["status"] = "BAD";
                                                                                    $finish["code"] = "500";
                                                                                    $finish["response"] = "cURL Error #:" . $err;
                                                                                    echo json_encode($finish);
                                                                                } else {
                                                                                    $finish["status"] = "OK";
                                                                                    $finish["code"] = "200";
                                                                                    $finish["response"] = "Contrato Creado Exitosamente!" . $response;
                                                                                    echo json_encode($finish);
                                                                                }
                                                                            } else {
                                                                                $response["status"] = "ERROR";
                                                                                $response["code"] = "500";
                                                                                $response["response"] = "Error en la Creacion del status de Contrato: " . $createAgreeForm->error;
                                                                                echo json_encode($response);
                                                                            }
                                                                        } else {
                                                                            $response["status"] = "ERROR";
                                                                            $response["code"] = "500";
                                                                            $response["response"] = "Error en la Creacion de Formulario Contrato: " . $createAgreeForm->error;
                                                                            echo json_encode($response);
                                                                        }
                                                                    } else {
                                                                        $response["status"] = "ERROR";
                                                                        $response["code"] = "500";
                                                                        $response["response"] = "Error en la Creacion de Contrato: " . $createSecondStepSell->error;
                                                                        echo json_encode($response);
                                                                    }*/
                                                                } else {
                                                                    $response["status"] = "ERROR";
                                                                    $response["code"] = "500";
                                                                    $response["response"] = "Para la creación de una Segunda Venta es necesario que se encuentre realizada la plomería";
                                                                    echo json_encode($response);
                                                                }
                                                            } else {
                                                                //Asignar a plomería
                                                                //$plumber = 3;

                                                                /*$reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ? WHERE RP.`id` = ?;");
                                                                $reassingReport->bind_param("iii", $employee, $plumber, $reportID);
                                                                $reassingReport->execute();*/

                                                                $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
                                                                $getEmployeeData->bind_param('i', intval($id));
                                                                if ($getEmployeeData->execute()) {
                                                                    $getEmployeeData->store_result();
                                                                    $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
                                                                    if ($getEmployeeData->fetch()) {
                                                                        //Asignación de Plomería
                                                                        $employeesID = "";
                                                                        $plumber = 3;
                                                                        if ($profileID == 3 || $profileID == 6 || $profileID == 7 || $profileID == 8) {
                                                                            //Asignación de Plomería
                                                                            //Actualizar tipo de reporte a plomeria
                                                                            $reassingReport = $conn->prepare("UPDATE report AS RP SET RP.employeesAssigned = ?, RP.idEmployee = ?, RP.idReportType = ? WHERE RP.id = ?;");
                                                                            $reassingReport->bind_param("siii", $employeesID, $employee, 4, $idReport);
                                                                            if ($reassingReport->execute()) {
                                                                                $response["status"] = "OK";
                                                                                $response["code"] = "200";
                                                                                $response["response"] = "Report sell created";
                                                                                echo json_encode($response);
                                                                            }
                                                                        } else {
                                                                            //En caso de que el empleado no tenga perfil de plomeria en su rol de empleado
                                                                            //asignar a la agencia de plomería la cuál se encargará de asignarlo a su empleado correspondiente
                                                                            $profile1 = 3;
                                                                            $profile2 = 6;
                                                                            $profile3 = 7;
                                                                            $profile4 = 8;
                                                                            $getAgencyID = $conn->prepare("SELECT DISTINCT AG.id FROM agency AS AG INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id WHERE PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ?;");
                                                                            $getAgencyID->bind_param("iiii", $profile1, $profile2, $profile3, $profile4);
                                                                            if ($getAgencyID->execute()) {
                                                                                $getAgencyID->store_result();
                                                                                $getAgencyID->bind_result($idAgencyToAssign);
                                                                                if ($getAgencyID->fetch()) {
                                                                                    //Asignación de Plomería
                                                                                    //Actualizar tipo de reporte a plomeria
                                                                                    $reassingReport = $conn->prepare("UPDATE report SET employeesAssigned = ?, idEmployee = ?, idReportType = ? WHERE id = ?;");
                                                                                    $reassingReport->bind_param("siii", $employeesID, $employee, $plumber, $idReport);
                                                                                    $reassingReport->execute();
                                                                                    if ($reassingReport->execute()) {
                                                                                        $response["status"] = "OK";
                                                                                        $response["code"] = "200";
                                                                                        $response["response"] = "Report sell created";
                                                                                        echo json_encode($response);
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        //Verificar si está con los administradores de mexicana o de parte de los administradores de AYOPSA
                                                        //ASIGNAR A ADMINISTRADORES DE MEXICANA
                                                        $rol = 2;
                                                        $getAdmins = $conn->prepare("SELECT US.id, US.nickname, RL.type FROM user AS US INNER JOIN user_rol AS USRL ON US.id = USRL.idUser INNER JOIN rol AS RL ON USRL.idRol = RL.id WHERE RL.id = ?;");
                                                        $getAdmins->bind_param("i", $rol);

                                                        if ($getAdmins->execute()) {
                                                            $getAdmins->store_result();
                                                            $getAdmins->bind_result($idUserAdmin, $nickname, $rol);
                                                            while ($getAdmins->fetch()) {
                                                                $admin["idUserAdmin"] = $idUserAdmin;
                                                                $admin["nickname"] = $nickname;
                                                                $admin["rol"] = $rol;
                                                                $admins[] = $admin;
                                                            }

                                                            $admins = json_encode($admins);

                                                            $getFinancialAgencyEmployees = $conn->prepare("UPDATE report SET employeesAssigned = ? WHERE id = ?;");
                                                            $getFinancialAgencyEmployees->bind_param("si", $admins, $reportID);
                                                            $getFinancialAgencyEmployees->execute();
                                                        }
                                                    }
                                                } else {
                                                    $createSecondStepSell = $conn->prepare("INSERT INTO `agreement`(`idAgency`, `payment`, `idReport`, `requestDate`, `clientlastName`, `clientlastName2`, `clientName`,
                                                                      `clientRFC`, `clientCURP`, `clientEmail`, `clientRelationship`, `clientgender`, `clientIdNumber`, `identificationType`, `clientBirthDate`, `clientBirthCountry`,
                                                                      `idState`, `idCity`, `idColonia`, `street`, `inHome`, `homeTelephone`, `celullarTelephone`, `agreementType`, `price`, `agreementExpires`, `agreementMonthlyPayment`,
                                                                      `agreementRi`, `agreementRiDate`, `clientJobEnterprise`, `clientJobLocation`, `clientJobRange`, `clientJobActivity`, `clientJobTelephone`, `latitude`, `longitude`,
                                                                      `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                                    $createSecondStepSell->bind_param("idisssssssssssssiiisissssisdssssssdd", $idAgency, $payment, $reportID, $requestDate,
                                                        $clientlastName, $clientlastName2, $clientName, $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber,
                                                        $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia, $street, $inHome, $homeTelephone, $celullarTelephone,
                                                        $agreementType, $price, $agreementExpires, $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation, $clientJobRange,
                                                        $clientJobActivity, $clientJobTelephone, $latitude, $longitude);
                                                    if ($createSecondStepSell->execute()) {
                                                        $secondSell = $createSecondStepSell->insert_id;

                                                        //Después de crear la segunda venta es necesario asignar el reporte a instalación en base a la lógica del procedimiento definida
                                                        foreach ($references as $key) {
                                                            $data = (array)$key;

                                                            $createReference = $conn->prepare("INSERT INTO `agreement_reference`(`name`, `telephone`, `jobTelephone`, `ext`, `idAgreement`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                                                            $createReference->bind_param("ssssi", $data["nombre"], $data["telefono_particular"], $data["telefono_trabajo"], $data["extension"], $secondSell);

                                                            if (!$createReference->execute()) {
                                                                $response = null;

                                                                $response["status"] = "ERROR";
                                                                $response["code"] = "500";
                                                                $response["response"] = "Error en la creación de imagen de referencia" . $createReference->error;
                                                                echo json_encode($response);
                                                            }
                                                        }

                                                        $createAgreeReport = $conn->prepare("INSERT INTO `agreement_employee_report`(`idAgreement`, `idEmployee`, `idReport`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                                        $createAgreeReport->bind_param("iii", $secondSell, $id, $reportID);
                                                        if ($createAgreeReport->execute()) {
                                                            $response = null;

                                                            $idWorkflow = 1;
                                                            if ($statusType == "Completo") {
                                                                $idStatus = 3;
                                                            } else if ($statusType == "Prendiente Reagendado") {
                                                                $idStatus = 7;
                                                            } else {
                                                                $idStatus = 4;
                                                            }

                                                            $idAgreegment = $createSecondStepSell->insert_id;

                                                            $createAgreementStatus = $conn->prepare("INSERT INTO `workflow_status_agreement`(`idWorkflow`, `idStatus`, `idAgreement`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                                            $createAgreementStatus->bind_param("iii", $idWorkflow, $idStatus, $secondSell);
                                                        }
                                                    }

                                                    //ASIGNAR ADMINISTRADORES DE MEXICANA
                                                    $rol = 2;
                                                    $getAdmins = $conn->prepare("SELECT US.id, US.nickname, RL.type FROM user AS US INNER JOIN user_rol AS USRL ON US.id = USRL.idUser INNER JOIN rol AS RL ON USRL.idRol = RL.id WHERE RL.id = ?;");
                                                    $getAdmins->bind_param("i", $rol);

                                                    if ($getAdmins->execute()) {
                                                        $getAdmins->store_result();
                                                        $getAdmins->bind_result($idUserAdmin, $nickname, $rol);
                                                        while ($getAdmins->fetch()) {
                                                            $admin["idUserAdmin"] = $idUserAdmin;
                                                            $admin["nickname"] = $nickname;
                                                            $admin["rol"] = $rol;
                                                            $admins[] = $admin;
                                                        }

                                                        $admins = json_encode($admins);

                                                        $getFinancialAgencyEmployees = $conn->prepare("UPDATE report SET employeesAssigned = ? WHERE id = ?;");
                                                        $getFinancialAgencyEmployees->bind_param("si", $admins, $reportID);
                                                        $getFinancialAgencyEmployees->execute();
                                                    }

                                                    $secondSell = 5;
                                                    $getFinancialAgencyEmployees = $conn->prepare("UPDATE report SET idReportType = ? WHERE id = ?;");
                                                    $getFinancialAgencyEmployees->bind_param("si", $secondSell, $reportID);
                                                    $getFinancialAgencyEmployees->execute();
                                                    $idStatus = 1;
                                                    $getFinancialAgencyEmployees = $conn->prepare("UPDATE workflow_status_report SET idStatus = ? WHERE idReport = ?;");
                                                    $getFinancialAgencyEmployees->bind_param("si", $idStatus, $reportID);
                                                    $getFinancialAgencyEmployees->execute();

                                                    $response["status"] = "OK";
                                                    $response["code"] = "200";
                                                    $response["response"] = "Contrato Almacenado Exitosamente, Pendiente Creacion Para Numero de Venta!";
                                                    echo json_encode($response);
                                                }
                                            }
                                        }
                                    }

                                    /*$prospect = $dataFormulario["num_solicitud"];
                                    $idAgency = $dataFormulario["agencia"];
                                    $payment = $dataFormulario["pagare"];
                                    $agreement = $dataFormulario["contrato"];
                                    $requestDate = $dataFormulario["fecha_solicitud"];
                                    $clientName = $clientLastName2;
                                    $clientlastName = $clientName;
                                    $clientlastName2 = $clientLastName1;
                                    $clientRFC = $dataFormulario["rfc"];
                                    $clientCURP = $dataFormulario["curp"];
                                    $clientEmail = $dataFormulario["correo"];
                                    $clientRelationship = $dataFormulario["estado_civil"];
                                    $clientgender = $dataFormulario["sexo"];
                                    $clientIdNumber = $dataFormulario["num_identificacion"];
                                    $identificationType = $dataFormulario["tipo_identificacion"];
                                    $clientBirthDate = $dataFormulario["fecha_nacimiento"];
                                    $clientBirthCountry = $dataFormulario["pais_nacimiento"];
                                    $idState = $dataFormulario["estado"];
                                    $idCity = $dataFormulario["municipio"];
                                    $idColonia = $dataFormulario["colonia"];
                                    $street = $dataFormulario["calle"];
                                    $inHome = $dataFormulario["vive_encasa"];
                                    $homeTelephone = $dataFormulario["tel_casa"];
                                    $celullarTelephone = $dataFormulario["tel_celular"];
                                    $agreementType = $dataFormulario["tipo_contrato"];
                                    $price = $dataFormulario["precio"];
                                    $agreementExpires = $dataFormulario["plazo"];
                                    $agreementMonthlyPayment = $dataFormulario["mensualidad"];
                                    $agreementRi = $dataFormulario["ri"];
                                    $agreementRiDate = $dataFormulario["fecha_ri"];
                                    $references = (array)$dataFormulario["referencias_array"];
                                    $clientJobEnterprise = $dataFormulario["empresa"];
                                    $clientJobLocation = $dataFormulario["direccion"];
                                    $clientJobRange = $dataFormulario["puesto"];
                                    $clientJobActivity = $dataFormulario["area"];
                                    $clientJobTelephone = $dataFormulario["tel_empresa"];*/

                                    /*$idState = 1;
                                    $idCity = 1;
                                    $idColonia = 1;

                                    $createSecondStepSell = $conn->prepare("INSERT INTO `agreement`(`idAgency`, `payment`, `idReport`, `requestDate`, `clientlastName`, `clientlastName2`, `clientName`,
                                          `clientRFC`, `clientCURP`, `clientEmail`, `clientRelationship`, `clientgender`, `clientIdNumber`, `identificationType`, `clientBirthDate`, `clientBirthCountry`,
                                          `idState`, `idCity`, `idColonia`, `street`, `inHome`, `homeTelephone`, `celullarTelephone`, `agreementType`, `price`, `agreementExpires`, `agreementMonthlyPayment`,
                                          `agreementRi`, `agreementRiDate`, `clientJobEnterprise`, `clientJobLocation`, `clientJobRange`, `clientJobActivity`, `clientJobTelephone`, `latitude`, `longitude`,
                                          `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                                    $createSecondStepSell->bind_param("idisssssssssssssiiisissssisdssssssdd", $idAgency, $payment, $reportID, $requestDate, $clientlastName, $clientlastName2, $clientName, $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber, $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia, $street, $inHome, $homeTelephone, $celullarTelephone, $agreementType, $price, $agreementExpires, $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation, $clientJobRange, $clientJobActivity, $clientJobTelephone, $latitude, $longitude);

                                    if ($createSecondStepSell->execute()) {
                                        $secondSell = $createSecondStepSell->insert_id;

                                        //$references = (array) $references;//json_decode($references);
                                        //print_r($references);
                                        //print_r($references["references"]);

                                        //foreach ($references["REFERENCIAS"] as $key) {
                                        foreach ($references as $key) {
                                            $data = (array)$key;
                                            //var_dump($data);

                                            $createReference = $conn->prepare("INSERT INTO `agreement_reference`(`name`, `telephone`, `jobTelephone`, `ext`, `idAgreement`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                                            $createReference->bind_param("ssssi", $data["nombre"], $data["telefono_particular"], $data["telefono_trabajo"], $data["extension"], $secondSell);

                                            if (!$createReference->execute()) {
                                                $response = null;

                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Error en la creación de imagen de referencia" . $createReference->error;
                                                echo json_encode($response);
                                            }
                                        }

                                        $createAgreeReport = $conn->prepare("INSERT INTO `agreement_employee_report`(`idAgreement`, `idEmployee`, `idReport`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                        $createAgreeReport->bind_param("iii", $secondSell, $id, $reportID);

                                        if ($createAgreeReport->execute()) {
                                            $response = null;

                                            $idWorkflow = 2;
                                            $idStatus = 3;

                                            $idAgreegment = $createSecondStepSell->insert_id;

                                            $createAgreementStatus = $conn->prepare("INSERT INTO `workflow_status_agreement`(`idWorkflow`, `idStatus`, `idAgreement`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                            $createAgreementStatus->bind_param("iii", $idWorkflow, $idStatus, $idAgreegment);
                                            $response = null;

                                            if ($createAgreementStatus->execute()) {
                                                $response["status"] = "OK";
                                                $response["code"] = "200";
                                                $response["response"] = "Contrato Creado Exitosamente!";
                                                echo json_encode($response);
                                            } else {
                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Error en la Creacion del status de Contrato: " . $createAgreeForm->error;
                                                echo json_encode($response);
                                            }
                                        } else {
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Error en la Creacion de Formulario Contrato: " . $createAgreeForm->error;
                                            echo json_encode($response);
                                        }
                                    } else {
                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] = "Error en la Creacion de Contrato: " . $createSecondStepSell->error;
                                        echo json_encode($response);
                                    }*/
                                } else {
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] = "Tipo de Reporte no Especificado";
                                    echo json_encode($response);
                                }
                            }
                        }

                    } else {
                        $response["status"] = "ERROR";
                        $response["code"] = "500";
                        $response["response"] = "Fallo en Creacion de Tarea: " . $insertTask->error;
                        echo json_encode($response);
                    }
                }
            } else {
                $response["status"] = "ERROR";
                $response["code"] = "500";
                $response["response"] = "Token introducido no valido";
                echo json_encode($response);
            }
        }
    }
} else {
    $response["status"] = "ERROR";
    $response["code"] = "500";
    $response["response"] = "Request sin token";
    echo json_encode($response);
}