<?php include_once "../DAO.php";
require_once('../libs/nusoap_lib/nusoap.php');
session_start();


$DB = new DAO();
$conn = $DB->getConnect();


if (isset($_POST['form'])) {
    $formNumber = $_POST['form']; 
    $formNumber = intval($formNumber); 
    $userNickname = $_POST['id'];
    $reason = isset($_POST['reasons']) ? $_POST['reasons'] : array();

    $trustedHome = $_POST['trustedHome']; 
    $requestImage = $_POST['requestImage'];
    $privacyAdvice = $_POST['privacyAdvice']; 
    $identificationImage = $_POST['identificationImage'];
    $payerImage = $_POST['payerImage']; 
    $agreegmentImage = $_POST['agreegmentImage'];
    $estatusVenta = $_POST["validacionEstatus"];
    
    
    $returnData = []; 
    $employees = []; 
    $financialService;
    
    $idEstatusReportRechazado = 2;
    $idEstatusFormSell = 2;
    $idEstatusWorkflow = 2;
    $idReporte = (int) $_POST["idReporte"];
    $idEstatusContrato = 9;
    $idEmpleadoVenta = NULL;
    $idEmployee = NULL;
    $idTipoReporte = NULL;
    
    $idReporteEstatusAsig = NULL;
   

    $searchIdEmployee = $conn->prepare("SELECT id FROM user WHERE nickname = ?;");
    $searchIdEmployee->bind_param("s", $userNickname);
    if ($searchIdEmployee->execute()) {
        $searchIdEmployee->store_result();
        $searchIdEmployee->bind_result($ip_usr_id);
        if ($searchIdEmployee->fetch()) {
            $ip_usr_id;
        }
    }
    
    // seteamos los valores
    $trustedHome = ($trustedHome == "true") ? 1 : 0;
    $requestImage = ($requestImage == "true") ? 1 : 0;
    $privacyAdvice = ($privacyAdvice == "true") ? 1 : 0;
    $identificationImage = ($identificationImage == "true") ? 1 : 0;
    $payerImage = ($payerImage == "true") ? 1 : 0;
    $agreegmentImage = ($agreegmentImage == "true") ? 1 : 0;
    
    $validacion = 0;
            
    // REBISAMOS SI LA VALIDACION SE CUMPLIO, TIENEN QUE ESTAR TODOS LOS CHECK SELECCIONADOS
    if($trustedHome && $requestImage && $requestImage && $privacyAdvice && $identificationImage && $payerImage && $agreegmentImage)
    {
        $validacion = 1;
    }
  
    // VALIDAMOS SII EXISTE UNA VENTA CON ESTE ID 
    $getFormSell = $conn->prepare("SELECT DISTINCT FS.`id`, FS.`financialService`, RP.idEmployee,  RP.employeesAssigned, RP.`idUserCreator`, RP.`id` AS idReporte, RP.idReportType FROM report_employee_form AS REF INNER JOIN form_sells AS FS ON REF.idForm = FS.id INNER JOIN report AS RP ON REF.idReport = RP.id WHERE FS.id = ? AND RP.id = ?;");
    $getFormSell->bind_param("ii", $formNumber, $idReporte);
    if ($getFormSell->execute()) 
    {
        $getFormSell->store_result();
        $getFormSell->bind_result($idForm, $financialService, $idEmployee, $employeesAssigned, $idUserCreator, $idReport, $idTipoReporte);
        
        if ($getFormSell->fetch()) 
        {
            // INDEPENDIENTE MENTE DE QUIEN SEA EL USUARIO ALCER UN  RECHAZO SE DEBE DE GUARDAR LA VALIDACION EN FORM_SELL Y
            // GUARDAR LAS RAZONES DEL RECHAZO
            $getFormSellValidation = $conn->prepare("SELECT id, validate FROM form_sells_validation WHERE idFormSell = ?;");
            $getFormSellValidation->bind_param("i", $formNumber); 

            if ($getFormSellValidation->execute()) 
            {
                $getFormSellValidation->store_result();
                $getFormSellValidation->bind_result($idFormValidation, $validateStatus);
                
                
                // REVISAMOS CUAL ES EL EMPLEADO ASIGNADO 
                $aEmpleadosAsgined = json_decode($employeesAssigned);
                if(is_array($aEmpleadosAsgined) && count($aEmpleadosAsgined) > 0)
                {
                    $idEmpleadoVenta = (isset($aEmpleadosAsgined["venta"])) ? $aEmpleadosAsgined["venta"] : NULL;
                    if(is_null($idEmpleadoVenta))
                    {
                        $idEmpleadoVenta = (isset($aEmpleadosAsgined["ventas"])) ? $aEmpleadosAsgined["ventas"] : NULL;
                    }
                }
                else
                {
                    $idEmpleadoVenta = $idEmployee;
                }
                
                if ($getFormSellValidation->fetch()) 
                 {
                     // SI LA VENTA YA ESTA VALIDADA ANTERIORMENTE YA NO SE PUEDE CANCELAR, SI YA EXISTE TIENE QUE ESTAR CON VALIDACION 0
                     if($validateStatus == 0)
                     {
                        $imagesStatus = $conn->prepare("UPDATE form_sells_validation SET trustedHome = ?, requestImage = ?, privacyAdvice = ?, identificationImage = ?, payerImage = ?, agreegmentImage = ?, validate = ?, updated_at = NOW() WHERE id = ?;");
                        $imagesStatus->bind_param("iiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validate, $idFormValidation);
                        $imagesStatus->execute();

                         // SI NO ESTAN SELECCIONADOS TODOS LOS CHECKBOX Y HAY RAZONES DE RECHAZO 
                         if($validacion == 0 && isset($reason) && count($reason) > 0)
                         {   
                             //AGREGADO PARA SOLUCIONAR BUG DE QUE NO SE REGISTRA EL ESTATUS DEL RECHAZO
                             if($_SESSION["nickname"] == "AYOPSA")
                             {
                                 // CAMBIAMOS EL ESTATUS A TESTATUS CONTRATO 
                                 $getWorkFlow = $conn->prepare("UPDATE tEstatusContrato SET validadoAyopsa=?,estatusVenta=?, idEmpleadoParaVenta = ?, fechaMod=NOW() WHERE idReporte = ?;");
                                 $getWorkFlow->bind_param("iiii",$estatusVenta, $idEstatusContrato, $idEmpleadoVenta, $idReport);
                                 $getWorkFlow->execute();
                             }
                             else
                             {
                                 // CAMBIAMOS EL ESTATUS A TESTATUS CONTRATO 
                                 $getWorkFlow = $conn->prepare("UPDATE tEstatusContrato SET validadoMexicana=?,estatusVenta=?, idEmpleadoParaVenta = ?, fechaMod=NOW() WHERE idReporte = ?;");
                                 $getWorkFlow->bind_param("iiii", $estatusVenta, $idEstatusContrato, $idEmpleadoVenta, $idReport);
                                 $getWorkFlow->execute();
                             }
                             
                             /* AGREGADO PARA LA PARTE DE PROCESOS EN PARALELO */
                             $idReportHistory = NULL;
                             $sqlHistory = "SELECT  rh.idReportHistory FROM reportHistory AS rh WHERE rh.idReport = ? AND rh.idReportType = ?;";
                             $getHistory = $conn->prepare($sqlHistory);
                             $getHistory->bind_param("ii", $idReport, $idTipoReporte);
                             $getHistory->execute();

                             $getHistory->store_result();
                             $getHistory->bind_result($idReportHistory);

                             // SI HAY ACTUALIZA
                             $updateHistory = "UPDATE reportHistory SET  idUserAssigned = ?, idStatusReport = ?, updated_at = NOW() WHERE idReportHistory = ?;";
                             $updateHistory = $conn->prepare($updateHistory);
                             $updateHistory->bind_param("iii",$ip_usr_id, $idEstatusWorkflow, $idReportHistory);
                             $updateHistory->execute();
                             /* FIN DE LA PARTE PROCESOS EN PARALELO */
                             
                             
                             // RECUPERAMOS EL ID DEL WORKFLOW
                             $idWorkflow= 1;
                             $idWorkflowEstatusReport = 0;
                             $getWorkFlow = $conn->prepare("SELECT id, idWorkFlow FROM workflow_status_report WHERE idReport = ? ORDER BY created_at DESC LIMIT 1;");
                             $getWorkFlow->bind_param("i", $idReport);
                             $getWorkFlow->execute();
                             $getWorkFlow->store_result();
                             $getWorkFlow->bind_result($idWorkflowEstatusReport, $idWorkflow);
                             $getWorkFlow->fetch();
                             
                             // SI EXISTE EL ID GUARDAMOS EL NUEVO ESTATUS
                             if(isset($idWorkflow) && $idWorkflow > 0)
                             {
                                 $getWorkFlow = $conn->prepare("UPDATE workflow_status_report SET idWorkflow = ? ,idStatus = ?,idReport = ? ,updated_at = NOW() WHERE id = ?;");
                                 $getWorkFlow->bind_param("iiii", $idWorkflow, $idEstatusWorkflow, $idReport, $idWorkflowEstatusReport);
                                 $getWorkFlow->execute();
                             }
                             
                             // VERIFICAMOS SI EXISTE EL ESTATUS PARA ESTE REPORTE 
                             $revisarEstatus = $conn->prepare("SELECT id FROM report_AssignedStatus WHERE idReport = ?");
                             $revisarEstatus->bind_param("i", $idReport);
                             $revisarEstatus->execute();
                             $revisarEstatus->store_result();
                             $revisarEstatus->bind_result($idReporteEstatusAsig);
                             if($revisarEstatus->fetch())
                             {
                                 // SE CAMBIA EL ESTATUS A RECHAZADO
                                 $actEstatus = $conn->prepare("UPDATE report_AssignedStatus SET idStatus = ?,updated_at = NOW() WHERE id = ?;");
                                 $actEstatus->bind_param("ii", $idEstatusReportRechazado,  $idReporteEstatusAsig);
                                 $actEstatus->execute();
                             }
                             else
                             {
                                // SE CAMBIA EL ESTATUS A RECHAZADO
                                 $actEstatus = $conn->prepare("INSERT INTO report_AssignedStatus(id,idReport,idStatus,notes,meeting,created_at,updated_at) VALUES (NULL, ?,?,NULL,NULL,NOW(),NOW());");
                                 $actEstatus->bind_param("ii", $idReport, $idEstatusReportRechazado);
                                 $actEstatus->execute();
                             }
                             
                             $rejectedReson = $conn->prepare("DELETE FROM form_sells_rejected_reason WHERE idSell = ?;");
                             $rejectedReson->bind_param("i", $formNumber);
                             $rejectedReson->execute();

                             // SE GUARDAN LAS RAZONES DE RECHAZO
                             foreach ($reason AS $value)
                             {
                                 $val = intval($value['Val']);
                                 $rejectedReson = $conn->prepare("INSERT INTO form_sells_rejected_reason(idSell, idRejectedReason, valid, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
                                 $rejectedReson->bind_param("iii", $formNumber, $val, $idEstatusFormSell);
                                 $rejectedReson->execute();
                             }
                             
                             $response["status"] = "OK";
                             $response["code"] = "200";
                             $response["response"] = "El proceso a sido rechazado";
                             echo json_encode($response);
                         }
                         else
                         {
                             $response["status"] = "BAD";
                             $response["code"] = "500";
                             $response["response"] = "Debes seleccionar motivos de rechazo";
                             echo json_encode($response);
                         }
                     }
                     else
                     {
                        $response["status"] = "BAD";
                        $response["code"] = "500";
                        $response["response"] = "Tratando de revalidar";
                        echo json_encode($response);
                     }
                 }
                 else
                 {
                     // SE INSERTAN LAS RASONES DEL RECHAZO
                     if($validacion == 0 && isset($reason) && count($reason) > 0)
                     {  
                         //AGREGADO PARA SOLUCIONAR BUG DE QUE NO SE REGISTRA EL ESTATUS DEL RECHAZO
                         if($_SESSION["nickname"] == "AYOPSA")
                         {
                             // CAMBIAMOS EL ESTATUS A TESTATUS CONTRATO 
                             $getWorkFlow = $conn->prepare("UPDATE tEstatusContrato SET validadoAyopsa=?,estatusVenta=?, idEmpleadoParaVenta = ?, fechaMod=NOW() WHERE idReporte = ?;");
                             $getWorkFlow->bind_param("iiii",$estatusVenta, $idEstatusContrato, $idEmpleadoVenta, $idReport);
                             $getWorkFlow->execute();
                         }
                         else
                         {
                             // CAMBIAMOS EL ESTATUS A TESTATUS CONTRATO 
                             $getWorkFlow = $conn->prepare("UPDATE tEstatusContrato SET validadoMexicana=?,estatusVenta=?, idEmpleadoParaVenta = ?, fechaMod=NOW() WHERE idReporte = ?;");
                             $getWorkFlow->bind_param("iiii", $estatusVenta, $idEstatusContrato, $idEmpleadoVenta, $idReport);
                             $getWorkFlow->execute();
                         }
                         
                         
                         /* AGREGADO PARA LA PARTE DE PROCESOS EN PARALELO */
                         $idReportHistory = NULL;
                         $sqlHistory = "SELECT  rh.idReportHistory FROM reportHistory AS rh WHERE rh.idReport = ? AND rh.idReportType = ?;";
                         $getHistory = $conn->prepare($sqlHistory);
                         $getHistory->bind_param("ii", $idReport, $idTipoReporte);
                         $getHistory->execute();
                         
                         $getHistory->store_result();
                         $getHistory->bind_result($idReportHistory);
                         
                         // SI HAY ACTUALIZA
                         $updateHistory = "UPDATE reportHistory SET  idUserAssigned = ?, idStatusReport = ?, updated_at = NOW() WHERE idReportHistory = ?;";
                         $updateHistory = $conn->prepare($updateHistory);
                         $updateHistory->bind_param("iii",$ip_usr_id, $idEstatusWorkflow, $idReportHistory);
                         $updateHistory->execute();
                         /* FIN DE LA PARTE PROCESOS EN PARALELO */
                         
                         
                         // SI ES LA PRIMERA VEZ QUE SE REALIZA LA VALIDACION SE INSERTA
                         $idValidate = 0;
                         $imagesStatus = $conn->prepare("INSERT INTO form_sells_validation (trustedHome, requestImage, privacyAdvice, identificationImage, payerImage, agreegmentImage, validate, idFormSell, created_at, updated_at, active) VALUES (?, ?, ?, ?, ? ,?, ?, ?, NOW(), NOW(), 1);");
                         $imagesStatus->bind_param("iiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validacion, $formNumber);
                         $imagesStatus->execute();
                         
                         // RECUPERAMOS EL ID DEL WORKFLOW
                         $idWorkflow= 1;
                         $idWorkflowEstatusReport = 0;
                         $getWorkFlow = $conn->prepare("SELECT id, idWorkFlow FROM workflow_status_report WHERE idReport = ? ORDER BY created_at DESC LIMIT 1;");
                         $getWorkFlow->bind_param("i", $idReport);
                         $getWorkFlow->execute();
                         $getWorkFlow->store_result();
                         $getWorkFlow->bind_result($idWorkflowEstatusReport, $idWorkflow);
                         $getWorkFlow->fetch();

                         // SI EXISTE EL ID GUARDAMOS EL NUEVO ESTATUS
                         if(isset($idWorkflow) && $idWorkflow > 0)
                         {
                             $getWorkFlow = $conn->prepare("UPDATE workflow_status_report SET idWorkflow = ? ,idStatus = ?,idReport = ? ,updated_at = NOW() WHERE id = ?;");
                             $getWorkFlow->bind_param("iiii", $idWorkflow, $idEstatusWorkflow, $idReport, $idWorkflowEstatusReport);
                             $getWorkFlow->execute();
                         }
                         
                         
                         // VERIFICAMOS SI EXISTE EL ESTATUS PARA ESTE REPORTE 
                         $revisarEstatus = $conn->prepare("SELECT id FROM report_AssignedStatus WHERE idReport = ?");
                         $revisarEstatus->bind_param("i", $idReport);
                         $revisarEstatus->execute();
                         $revisarEstatus->store_result();
                         $revisarEstatus->bind_result($idReporteEstatusAsig);
                         if($revisarEstatus->fetch())
                         {
                             // SE CAMBIA EL ESTATUS A RECHAZADO
                             $actEstatus = $conn->prepare("UPDATE report_AssignedStatus SET idStatus = ?,updated_at = NOW() WHERE id = ?;");
                             $actEstatus->bind_param("ii", $idEstatusReportRechazado,  $idReporteEstatusAsig);
                             $actEstatus->execute();
                         }
                         else
                         {
                            // SE CAMBIA EL ESTATUS A RECHAZADO
                             $actEstatus = $conn->prepare("INSERT INTO report_AssignedStatus(id,idReport,idStatus,notes,meeting,created_at,updated_at) VALUES (NULL, ?,?,NULL,NULL,NOW(),NOW());");
                             $actEstatus->bind_param("ii", $idReport, $idEstatusReportRechazado);
                             $actEstatus->execute();
                         }
                         
                         $rejectedReson = $conn->prepare("DELETE FROM form_sells_rejected_reason WHERE idSell = ?;");
                         $rejectedReson->bind_param("i", $formNumber);
                         $rejectedReson->execute();

                         foreach ($reason AS $value)
                         {
                             $val = intval($value['Val']);
                             $rejectedReson = $conn->prepare("INSERT INTO form_sells_rejected_reason(idSell, idRejectedReason, valid, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
                             $rejectedReson->bind_param("iii", $formNumber, $val, $idEstatusFormSell);
                             $rejectedReson->execute();
                         }
                         
                         $response["status"] = "OK";
                         $response["code"] = "200";
                         $response["response"] = "El proceso a sido rechazado";
                         echo json_encode($response);
                     }
                     else
                     {
                         $response["status"] = "BAD";
                         $response["code"] = "500";
                         $response["response"] = "Debes seleccionar motivos de rechazo";
                         echo json_encode($response);
                     }
                 }
            }
            else
            {
                $response["status"] = "BAD";
                $response["code"] = "500";
                $response["response"] = "Error al tratar de recuperar el estatus de la valdación";
                echo json_encode($response);
            }
        }
        else
        {
            $response["status"] = "BAD";
            $response["code"] = "500";
            $response["response"] = "Venta no valida";
            echo json_encode($response);
        }
    }
    else
    {
        $response["status"] = "BAD";
        $response["code"] = "500";
        $response["response"] = "Venta no valida";
        echo json_encode($response);
    }
    
}

function grabarLog($logInfo)
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = "createAgreement2.txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);

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
        //PARA ASIGNAR LA PLOMERÃ�?A PRIMERO VALIDAMOS QUE EL USUARIO CREADOR DE LA VENTA TENGA EL PERFIL DE
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