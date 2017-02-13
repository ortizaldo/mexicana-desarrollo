<?php include_once "../DAO.php";
require_once('../libs/nusoap_lib/nusoap.php');

$DB = new DAO();
$conn = $DB->getConnect();


if (isset($_POST['Form'])) {
    $formNumber = $_POST['Form'];
 $formNumber = intval($formNumber); 
 $userNickname = $_POST['id'];
    $reason = json_decode($_POST['reasons']);

    $trustedHome = $_POST['trustedHome']; $requestImage = $_POST['requestImage'];
    $privacyAdvice = $_POST['privacyAdvice']; $identificationImage = $_POST['identificationImage'];
    $payerImage = $_POST['payerImage']; $agreegmentImage = $_POST['agreegmentImage'];
    $returnData = []; $employees = []; $financialService;

    /*var_dump($formNumber);
    var_dump($userNickname);
    var_dump($reason);
    var_dump($trustedHome);
    var_dump($requestImage);
    var_dump($privacyAdvice);
    var_dump($identificationImage);
    var_dump($payerImage);
    var_dump($agreegmentImage);*/

    $searchIdEmployee = $conn->prepare("SELECT id FROM user WHERE nickname = ?;");
    $searchIdEmployee->bind_param("s", $userNickname);
    if ($searchIdEmployee->execute()) {
        $searchIdEmployee->store_result();
        $searchIdEmployee->bind_result($ip_usr_id);
        if ($searchIdEmployee->fetch()) {
            $ip_usr_id;
        }
    }
    //var_dump($ip_usr_id);
    //Validar el proceso de validaciÃ³n de la venta
    $getFormSell = $conn->prepare("SELECT DISTINCT FS.`id`, FS.`financialService`, RP.`employeesAssigned`, RP.`idUserCreator`, RP.`id` FROM report_employee_form AS REF INNER JOIN form_sells AS FS ON REF.idForm = FS.id INNER JOIN report AS RP ON REF.idReport = RP.id WHERE FS.id = ?;");
    $getFormSell->bind_param("i", $formNumber);
    if ($getFormSell->execute()) {
        $getFormSell->store_result();
        $getFormSell->bind_result($idForm, $financialService, $employeesAssigned, $idUserCreator, $idReport);
        if ($getFormSell->fetch()) {
            //var_dump("Entrando a la comprobacion para la existencia de la verificacion para la forma de venta");

            /*var_dump($idForm);
            var_dump($financialService);
            var_dump($employeesAssigned);
            var_dump($idUserCreator);
            var_dump($idReport);*/

            if ($financialService == 1 && $employeesAssigned != "") {
                $idAYOPSA = 0;
                $agencyName = "AYOPSA";
                $getFinancialAgencyEmployees = $conn->prepare("SELECT DISTINCT AG.id FROM user AS US INNER JOIN agency AS AG ON US.id = AG.idUser WHERE nickname = ? LIMIT 1;");
                $getFinancialAgencyEmployees->bind_param("s", $agencyName);
                if ($getFinancialAgencyEmployees->execute()) {
                    $getFinancialAgencyEmployees->store_result();
                    $getFinancialAgencyEmployees->bind_result($agencyId);
                    if ($getFinancialAgencyEmployees->fetch()) {
                        $idAYOPSA = $agencyId;
                    }
                }

                $employeesAssigned = (array) json_decode($employeesAssigned);
                foreach ($employeesAssigned as $employee => $employeeData) {
                    $employeeData = (array) $employeeData;

                    //VALIDAR SI EL PROCESO SE ENCUENTRA EN LA AGENCIA FINANCIERA
                    if ($employeeData["ayopsa"] == $idAYOPSA) {
                        if ($trustedHome == "true") {
                            $trustedHome = 1;
                        } else {
                            $trustedHome = 0;
                        }
                        if ($requestImage == "true") {
                            $requestImage = 1;
                        } else {
                            $requestImage = 0;
                        }
                        if ($privacyAdvice == "true") {
                            $privacyAdvice = 1;
                        } else {
                            $privacyAdvice = 0;
                        }
                        if ($identificationImage == "true") {
                            $identificationImage = 1;
                        } else {
                            $identificationImage = 0;
                        }
                        if ($payerImage == "true") {
                            $payerImage = 1;
                        } else {
                            $payerImage = 0;
                        }
                        if ($agreegmentImage == "true") {
                            $agreegmentImage = 1;
                        } else {
                            $agreegmentImage = 0;
                        }
                        if ($trustedHome == 1 || $requestImage == 1 || $privacyAdvice == 1 || $identificationImage == 1 || $payerImage == 1 || $agreegmentImage == 1) {
                            $validate = 1;
                        } else {
                            $validate = 0;
                        }
                        //BUSCAMOS EL ESTATUS DE VALIDACION DEL FORMULARIO EN BASE A SU ID
                        $getFormSellValidation = $conn->prepare("SELECT id, validate FROM form_sells_validation WHERE idFormSell = ?;");
                        $getFormSellValidation->bind_param("i", $formNumber);
                        if ($getFormSellValidation->execute()) {
                            $getFormSellValidation->store_result();
                            $getFormSellValidation->bind_result($idFormValidation, $validateStatus);
                            if ($getFormSellValidation->fetch()) {
                                if ($validateStatus == 0) {
                                    //$getFormSellValidation->close();
                                    //$conn->next_result();
                                    $imagesStatus = $conn->prepare("UPDATE form_sells_validation SET trustedHome = ?, requestImage = ?, privacyAdvice = ?, identificationImage = ?, payerImage = ?, agreegmentImage = ?, validate = ?, updated_at = NOW() WHERE id = ?;");
                                    $imagesStatus->bind_param("iiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validate, $idFormValidation);
                                    $imagesStatus->execute();


                                    //$updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
                                    //$updateEstatusContrato->bind_param("iii", $idStatusPH, $employee, $reportID);
                                    //$updateEstatusContrato->execute();

                                    //$imagesStatus->close();
                                    //$conn->next_result();
                                    $validateTemp = 0;
                                    if ($reason != "" || $reason != null) {
                                        foreach ($reason as $key) {
                                            $key = (array)$key;
                                            $val = intval($key['Val']);

                                            $rejectedReson = $conn->prepare("INSERT INTO form_sells_rejected_reason(idSell, idRejectedReason, valid, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
                                            $rejectedReson->bind_param("iii", $formNumber, $val, $validateTemp);
                                            $rejectedReson->execute();
                                            //$rejectedReson->close();
                                        }
                                    }
                                }
                            } else {
                                //FORM SELL VALIDATION NO EXISTE, TENEMOS QUE INSERTAR LA VALIDACIÃ“N
                                //$getFormSellValidation->close();
                                //$conn->next_result();
                                $validateTemp = 0;
                                $imagesStatus = $conn->prepare("INSERT INTO form_sells_validation (trustedHome, requestImage, privacyAdvice, identificationImage, payerImage, agreegmentImage, validate, idFormSell, created_at, updated_at, active) VALUES (?, ?, ?, ?, ? ,?, ?, ?, NOW(), NOW(), 1);");
                                $imagesStatus->bind_param("iiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validate, $formNumber);
                                $imagesStatus->execute();
                                //$imagesStatus->close();
                                //$conn->next_result();
                                if ($reason != "" || $reason != null) {
                                    foreach ($reason as $key) {
                                        $key = (array)$key;
                                        $val = intval($key['Val']);
                                        $rejectedReson = $conn->prepare("INSERT INTO form_sells_rejected_reason(idSell, idRejectedReason, valid, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
                                        $rejectedReson->bind_param("iii", $formNumber, $val, $validateTemp);
                                        $rejectedReson->execute();
                                        //$rejectedReson->close();
                                    }
                                }
                            }
                        }
                        if ($validate == 1) {
                            /*
                             * Revisar inserciÃ³n de workflow status y/o status de la venta.
                             */
                            $assignedID = "";
                            $updateReportEmployees = $conn->prepare("UPDATE report SET employeesAssigned = ? WHERE id = ?;");
                            $updateReportEmployees->bind_param("si", $assignedID, $idReport);
                            if ($updateReportEmployees->execute()) {
                                $response["status"] = "OK";
                                $response["code"] = "200";
                                $response["response"] = "Financiera asignada correctamente";
                                echo json_encode($response);
                            } else {
                                $response["status"] = "BAD";
                                $response["code"] = "500";
                                $response["response"] = "Error al asignar financiera";
                                echo json_encode($response);
                            }
                        }
                    } else {
                        //Cuando la sentencia sea verdadera significa que lo tiene asignado un administrador de mexicana,
                        // y deberÃ¡ ser validado para poderse asignar a un agencia financiera la cual validarÃ¡ si este pasa el proceso
                        // -- Pendiente revisar si la agencia es quien valida o los empleados que la conforma.
                        if ($employeeData["idUserAdmin"] == $ip_usr_id) {
                            if ($trustedHome == "true") {
                                $trustedHome = 1;
                            } else {
                                $trustedHome = 0;
                            }
                            if ($requestImage == "true") {
                                $requestImage = 1;
                            } else {
                                $requestImage = 0;
                            }
                            if ($privacyAdvice == "true") {
                                $privacyAdvice = 1;
                            } else {
                                $privacyAdvice = 0;
                            }
                            if ($identificationImage == "true") {
                                $identificationImage = 1;
                            } else {
                                $identificationImage = 0;
                            }
                            if ($payerImage == "true") {
                                $payerImage = 1;
                            } else {
                                $payerImage = 0;
                            }
                            if ($agreegmentImage == "true") {
                                $agreegmentImage = 1;
                            } else {
                                $agreegmentImage = 0;
                            }
                            if ($trustedHome == 1 || $requestImage == 1 || $privacyAdvice == 1 || $identificationImage == 1 || $payerImage == 1 || $agreegmentImage == 1) {
                                $validate = 1;
                            } else {
                                $validate = 0;
                            }
                            //insertar Id de usuario que realizÃ³ validaciÃ³n
                            //var_dump($validate);

                            //Antes de validar por parte de mexicana para pasar a AYOPSA es necesario llenar la tabla de validaciÃ³n de imÃ¡genes
                            //BUSCAMOS EL ESTATUS DE VALIDACION DEL FORMULARIO EN BASE A SU ID
                            $getFormSellValidation = $conn->prepare("SELECT id, validate FROM form_sells_validation WHERE idFormSell = ?;");
                            $getFormSellValidation->bind_param("i", $formNumber);
                            if ($getFormSellValidation->execute()) {
                                $getFormSellValidation->store_result();
                                $getFormSellValidation->bind_result($idFormValidation, $validateStatus);
                                //var_dump("Entrando a la area de validacion");
                                if ($getFormSellValidation->fetch()) {
                                    /*var_dump($idFormValidation);
                                    var_dump($validateStatus);*/
                                    if ($validateStatus == 0) {
                                        //$getFormSellValidation->close();
                                        //$conn->next_result();
                                        $validateTemp = 0;
                                        $imagesStatus = $conn->prepare("UPDATE form_sells_validation SET trustedHome = ?, requestImage = ?, privacyAdvice = ?, identificationImage = ?, payerImage = ?, agreegmentImage = ?, validate = ?, updated_at = NOW() WHERE id = ?;");
                                        $imagesStatus->bind_param("iiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validateTemp, $idFormValidation);
                                        $imagesStatus->execute();
                                        //$imagesStatus->close();
                                        //$conn->next_result();
                                        if ($reason != "" || $reason != null) {
                                            foreach ($reason as $key) {
                                                $key = (array)$key;
                                                $val = intval($key['Val']);

                                                $rejectedReson = $conn->prepare("INSERT INTO form_sells_rejected_reason(idSell, idRejectedReason, valid, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
                                                $rejectedReson->bind_param("iii", $formNumber, $val, $validateTemp);
                                                $rejectedReson->execute();
                                                //$rejectedReson->close();
                                            }
                                        }
                                    } else {
                                        $response["status"] = "BAD";
                                        $response["code"] = "500";
                                        $response["response"] = "Tratando de revalidar";
                                        echo json_encode($response);
                                    }
                                } else {
                                    //FORM SELL VALIDATION NO EXISTE, TENEMOS QUE INSERTAR LA VALIDACIÃ“N
                                    //$getFormSellValidation->close();
                                    //$conn->next_result();
                                    $validateTemp = 0;
                                    $imagesStatus = $conn->prepare("INSERT INTO form_sells_validation (trustedHome, requestImage, privacyAdvice, identificationImage, payerImage, agreegmentImage, validate, idFormSell, created_at, updated_at, active) VALUES (?, ?, ?, ?, ? ,?, ?, ?, NOW(), NOW(), 1);");
                                    $imagesStatus->bind_param("iiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validateTemp, $formNumber);
                                    $imagesStatus->execute();
                                    //$imagesStatus->close();
                                    //$conn->next_result();
                                    if ($reason != "" || $reason != null) {
                                        foreach ($reason as $key) {
                                            $key = (array)$key;
                                            $val = intval($key['Val']);
                                            $rejectedReson = $conn->prepare("INSERT INTO form_sells_rejected_reason(idSell, idRejectedReason, valid, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
                                            $rejectedReson->bind_param("iii", $formNumber, $val, $validateTemp);
                                            $rejectedReson->execute();
                                            //$rejectedReson->close();
                                        }
                                    }
                                }
                            }

                            if ($validate == 1) {
                                $agencyName = "AYOPSA";
                                //$rolID = 3; $agencyTypeID = 1; $agencyTypeNameID = "Financiera"; $agencyName = "AYOPSA";
                                $getFinancialAgencyEmployees = null;
                                //Asignar solicitud para validacion de agencia
                                $getFinancialAgencyEmployees = $conn->prepare("SELECT DISTINCT AG.id FROM user AS US INNER JOIN agency AS AG ON US.id = AG.idUser WHERE nickname = ? LIMIT 1;");
                                $getFinancialAgencyEmployees->bind_param("s", $agencyName);
                                if ($getFinancialAgencyEmployees->execute()) {
                                    $getFinancialAgencyEmployees->store_result();
                                    $getFinancialAgencyEmployees->bind_result($agencyId);
                                    if ($getFinancialAgencyEmployees->fetch()) {
                                        $nextAssigned['ayopsa'] = $agencyId;
                                        $AgencyFin[] = $nextAssigned;
                                    }
                                    $assignedID = json_encode($AgencyFin);
                                    /*
                                     * Revisar inserciÃ³n de workflow status y/o status de la venta.
                                     */
                                    $updateReportEmployees = $conn->prepare("UPDATE report SET employeesAssigned = ? WHERE id = ?;");
                                    $updateReportEmployees->bind_param("si", $assignedID, $idReport);
                                    if ($updateReportEmployees->execute()) {
                                        $response["status"] = "OK";
                                        $response["code"] = "200";
                                        $response["response"] = "Financiera asignada correctamente";
                                        echo json_encode($response);
                                    } else {
                                        $response["status"] = "BAD";
                                        $response["code"] = "500";
                                        $response["response"] = "Error al asignar financiera";
                                        echo json_encode($response);
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $employeesAssigned = (array)json_decode($employeesAssigned);
                //print_r($employeesAssigned);
                //var_dump($idReport); //exit;
                if (empty($employeesAssigned)) {

                    $getPlumberForm = $conn->prepare("SELECT DISTINCT FP.`id`, RP.`employeesAssigned`, RP.`idUserCreator` FROM report_employee_form AS REF INNER JOIN form_plumber AS FP ON REF.idForm = FP.id INNER JOIN report AS RP ON REF.idReport = RP.id WHERE RP.id = ?;");
                    $getPlumberForm->bind_param("i", $idReport);
                    if ($getPlumberForm->execute()) {
                        $getPlumberForm->store_result();
                        $getPlumberForm->bind_result($idForm, $employeesAssigned, $idUserCreator);
                        if (!$getPlumberForm->fetch()) {

                            //Asignar a plomerÃ­a
                            $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
                            $getEmployeeData->bind_param('i', intval($id));
                            if ($getEmployeeData->execute()) {
                                $getEmployeeData->store_result();
                                $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
                                if ($getEmployeeData->fetch()) {
                                    //AsignaciÃ³n de PlomerÃ­a
                                    $employeesID = "";
                                    $plumber = 3;
                                    if ($profileID == 3 || $profileID == 6 || $profileID == 7 || $profileID == 8) {
                                        //AsignaciÃ³n de PlomerÃ­a
                                        //Actualizar tipo de reporte a plomeria
                                        $reassingReport = $conn->prepare("UPDATE report AS RP SET RP.employeesAssigned = ?, RP.idEmployee = ?, RP.idReportType = ? WHERE RP.id = ?;");
                                        $reassingReport->bind_param("siii", $employeesID, $employee, $plumber, $idReport);
                                        if ($reassingReport->execute()) {
                                            $response["status"] = "OK";
                                            $response["code"] = "200";
                                            $response["response"] = "Reporte de plomerÃ­a asignado correctamente";
                                            echo json_encode($response);
                                        }
                                    } else {
                                        //En caso de que el empleado no tenga perfil de plomeria en su rol de empleado
                                        //asignar a la agencia de plomerÃ­a la cuÃ¡l se encargarÃ¡ de asignarlo a su empleado correspondiente
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
                                                //AsignaciÃ³n de PlomerÃ­a
                                                //Actualizar tipo de reporte a plomeria
                                                $reassingReport = $conn->prepare("UPDATE report SET employeesAssigned = ?, idEmployee = ?, idReportType = ? WHERE id = ?;");
                                                $reassingReport->bind_param("siii", $employeesID, $employee, $plumber, $idReport);
                                                $reassingReport->execute();
                                                if ($reassingReport->execute()) {
                                                    $response["status"] = "OK";
                                                    $response["code"] = "200";
                                                    $response["response"] = "Reporte de plomerÃ­a asignado correctamente";
                                                    echo json_encode($response);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $response["status"] = "ERROR";
                        $response["code"] = "500";
                        $response["response"] = "Es necesario que se encuentre realizada la plomerÃ­a";
                        echo json_encode($response);
                    }
                }
            }
        }
    }
}






















/*$getPlumberForm = $conn->prepare("SELECT DISTINCT FP.`id`, RP.`employeesAssigned`, RP.`idUserCreator` FROM report_employee_form AS REF INNER JOIN form_plumber AS FP ON REF.idForm = FP.id INNER JOIN report AS RP ON REF.idReport = RP.id WHERE RP.id = ?;");
$getPlumberForm->bind_param("i", $idReport);
if ($getPlumberForm->execute()) {
    $getPlumberForm->store_result();
    $getPlumberForm->bind_result($idForm, $employeesAssigned, $idUserCreator);
    if ($getPlumberForm->fetch()) {
        if ($employeesAssigned == "" && $idForm != 0) {

            var_dump("ValidaciÃ³n");*/

//BUSQUEDA DE CONTRATO PARA LA SOLICITUD
/*$getAgreementNumber = $conn->prepare("SELECT AGR.id, AGR.idAgency, AGR.payment, AGR.idReport, AGR.requestDate, AGR.clientlastName, AGR.clientlastName2, AGR.clientName, AGR.clientRFC, AGR.clientCURP, AGR.clientEmail, AGR.clientRelationship, AGR.clientgender, AGR.clientIdNumber, AGR.identificationType, AGR.clientBirthDate, AGR.clientBirthCountry, AGR.idState, AGR.idCity, AGR.idColonia, AGR.street, AGR.inHome, AGR.homeTelephone, AGR.celullarTelephone, AGR.agreementType, AGR.price, AGR.agreementExpires, AGR.agreementMonthlyPayment, AGR.agreementRi, AGR.agreementRiDate, AGR.clientJobEnterprise, AGR.clientJobLocation, AGR.clientJobRange, AGR.clientJobActivity, AGR.clientJobTelephone, AGR.latitude, AGR.longitude FROM report AS RP INNER JOIN agreement_employee_report AS AGEMP ON RP.id = AGEMP.idReport INNER JOIN agreement AS AGR ON AGEMP.idAgreement = AGR.id WHERE RP.id = ?;");
$getAgreementNumber->bind_param("i", $idReport);
if ($getAgreementNumber->execute()) {
    $getAgreementNumber->store_result();
    $getAgreementNumber->bind_result($idAgreement, $idAgency, $payment, $reportID, $requestDate, $clientlastName, $clientlastName2, $clientName, $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber,
        $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia, $street, $inHome, $homeTelephone, $celullarTelephone,
        $agreementType, $price, $agreementExpires, $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation, $clientJobRange,
        $clientJobActivity, $clientJobTelephone, $latitude, $longitude);
    if ($getAgreementNumber->fetch()) {
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

        print_r($sell);
        exit;

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
                        //AsignaciÃ³n de instalacion
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
        $responseJson = array(
            'result' => 0,
            'ip_contrato' => 0,
            'op_message' => 'No se ingreso correctamente los parametros de entrada'
        );
        echo json_encode($responseJson);
    }
}*/

/*} else {
    $response["status"] = "ERROR";
    $response["code"] = "500";
    $response["response"] = "Para la creaciÃ³n de una Segunda Venta es necesario que se encuentre realizada la plomerÃ­a";
    echo json_encode($response);
}
} else {
//Asignar a plomerÃ­a
$getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
$getEmployeeData->bind_param('i', intval($id));
if ($getEmployeeData->execute()) {
    $getEmployeeData->store_result();
    $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
    if ( $getEmployeeData->fetch() ) {
        //AsignaciÃ³n de PlomerÃ­a
        $employeesID = "";  $plumber = 3;
        if ($profileID == 3 || $profileID == 6 || $profileID == 7 || $profileID == 8) {
            //AsignaciÃ³n de PlomerÃ­a
            //Actualizar tipo de reporte a plomeria
            $reassingReport = $conn->prepare("UPDATE report AS RP SET RP.employeesAssigned = ?, RP.idEmployee = ?, RP.idReportType = ? WHERE RP.id = ?;");
            $reassingReport->bind_param("siii", $employeesID, $employee, $plumber, $idReport);
            if ($reassingReport->execute()) {
                $response["status"] = "OK";
                $response["code"] = "200";
                $response["response"] = "Report sell created";
                echo json_encode($response);
            }
        }  else {
            //En caso de que el empleado no tenga perfil de plomeria en su rol de empleado
            //asignar a la agencia de plomerÃ­a la cuÃ¡l se encargarÃ¡ de asignarlo a su empleado correspondiente
            $profile1 = 3; $profile2 = 6; $profile3 = 7; $profile4 = 8;
            $getAgencyID = $conn->prepare("SELECT DISTINCT AG.id FROM agency AS AG INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id WHERE PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ?;");
            $getAgencyID->bind_param("iiii", $profile1, $profile2, $profile3, $profile4);
            if ($getAgencyID->execute()) {
                $getAgencyID->store_result();
                $getAgencyID->bind_result($idAgencyToAssign);
                if ($getAgencyID->fetch()) {
                    //AsignaciÃ³n de PlomerÃ­a
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
}*/


/*}
} else {
foreach ($employeesAssigned as $employee) {
    $employee = (array)$employee;
    if ($employee["idUserAdmin"] == $ip_usr_id) {
        //  Revisar si tiene pendiente plomeria, en caso que si. Asignar a plomero.
        // Caso contrario validar segunda venta y cuando esta estÃ© creada se harÃ§a la generacion para el numero de vliente
        $employeesID = "";
        $plumberType = 3;

        //AsignaciÃ³n de PlomerÃ­a
        //PARA ASIGNAR LA PLOMERÃA PRIMERO VALIDAMOS QUE EL USUARIO CREADOR DE LA VENTA TENGA EL PERFIL DE
        //PLOMERO EN SU ROL DE EMPLEADO
        /*OBTENEMOS EL ID DEL EMPLEADO EN BASE A LA CREACIÃ“N DEL REPORTE DE VENTA*/
//Agregar inner join con formulario de venta para seleccionar el usuario que lo creÃ³
/*$reportEmployeeForm = $conn->prepare("SELECT `idEmployee` FROM `report_employee_form` AS RPEMP INNER JOIN `form_sells` AS FRMSELL ON RPEMP.`idForm` = FRMSELL.`id` WHERE `idReport` = ?");
$reportEmployeeForm->bind_param("i", $idReport);
if ($reportEmployeeForm->execute()) {
    $reportEmployeeForm->store_result();
    $reportEmployeeForm->bind_result($idEmployee);
    if ($reportEmployeeForm->fetch()) {
        $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `employee`.`id` = ?;");
        $getEmployeeData->bind_param('i', $idEmployee);
        if ($getEmployeeData->execute()) {
            $getEmployeeData->store_result();
            $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
            if ($getEmployeeData->fetch()) {
                if ($profileID == 3 || $profileID == 6 || $profileID == 7 || $profileID == 8) {
                    //AsignaciÃ³n de PlomerÃ­a
                    //Actualizar tipo de reporte a plomeria
                    $reassingReport = $conn->prepare("UPDATE report SET employeesAssigned = ?, idEmployee = ? WHERE id = ?;");
                    $reassingReport->bind_param("sii", $employeesID, $employee, $idReport);
                    if ($reassingReport->execute()) {
                        $status = 3;
                        $reassingReport = $conn->prepare("UPDATE workflow_status_report SET idStatus = ?, update_at = NOW() WHERE idReport = ?;");
                        $reassingReport->bind_param("sii", $status, $idReport);

                        $response["status"] = "OK";
                        $response["code"] = "200";
                        $response["response"] = "PlomerÃ­a asignada correctamente";
                        echo json_encode($response);
                    }
                }
            }
        }
    } else {
        //En caso de que el empleado no tenga perfil de plomeria en su rol de empleado
        //asignar a la agencia de plomerÃ­a la cuÃ¡l se encargarÃ¡ de asignarlo a su empleado correspondiente
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
                $reassingReport = $conn->prepare("UPDATE report SET employeesAssigned = ?, idEmployee = ? WHERE id = ?;");
                //$reassingReport->bind_param("siii", $employeesID, $employee, $plumberType, $idReport);
                $reassingReport->bind_param("sii", $employeesID, $employee, $idReport);
                if ($reassingReport->execute()) {
                    $status = 3;
                    $reassingReport = $conn->prepare("UPDATE workflow_status_report SET idStatus = ?, update_at = NOW() WHERE idReport = ?;");
                    $reassingReport->bind_param("sii", $status, $idReport);

                    $response["status"] = "OK";
                    $response["code"] = "200";
                    $response["response"] = "PlomerÃ­a asignada correctamente";
                    echo json_encode($response);
                }


            }
        }
    }
}
}
}
}
}
}
} else {
//Proceso fuera de validaciÃ³n de venta, se encuentra en PLOMERIA O SEGUNDA VENTA
$response["status"] = "BAD";
$response["code"] = "500";
$response["response"] = "Error al intentar completar verificaciÃ³n, la solicitud se encuentra asignada a plomeria o en la creaciÃ³n de segunda venta";
echo json_encode($response);
}
}*/