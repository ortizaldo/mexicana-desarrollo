<?php 
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
//var_dump($_POST);
if (isset($_POST["idReport"]) && 
    isset($_POST["Consecutive"]) && 
    isset($_POST["NextSellPayment"]) && 
    isset($_POST["Agreement"]) && 
    isset($_POST["LastName1"])  && 
    isset($_POST["LastName2"]) && 
    isset($_POST["Name"]) && 
    isset($_POST["CURP"]) && 
    isset($_POST["Engagment"]) && 
    isset($_POST["IdCard"]) && 
    isset($_POST["NextSellAgency"]) && 
    isset($_POST["RequestDate"])) {
    $response["response"] = $_POST;
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $idAgency = $_POST["NextSellAgency"];
    $id = $_POST["idReport"];
    if ($id == null || $id == "") {
        $update = false;
    } else {
        $update = true;
    }
    $idReport = intval($_POST["idReport"]);
    $idEmployee = $_POST["idEmployee"];
    //echo json_encode($_POST);
    //exit();
    $getFormSellSQL = "SELECT DISTINCT FS.id, FS.financialService, RP.employeesAssigned,REF.idUserAssigned, REF.idReport 
                        FROM reportHistory AS REF 
                        INNER JOIN form_sells AS FS ON REF.idFormSell = FS.id
                        INNER JOIN report AS RP ON RP.id = REF.idReport
                        WHERE REF.idReportType=2 and REF.idReport = ?;";
    if ($getFormSell = $conn->prepare($getFormSellSQL)) {
        $getFormSell->bind_param("i", $idReport);
        if ($getFormSell->execute()) {
            $getFormSell->store_result();
            $getFormSell->bind_result($idForm, $financialService, $employeesAssigned, $idUserCreator, $idReport);
            if ($getFormSell->fetch()) {
                //echo "entre";
                if ($idForm != "") {
                    $Consecutive = $_POST["Consecutive"];
                    $Agency=$_POST["Agency"];
                    $NextSellPayment = $_POST["NextSellPayment"];
                    $Agreement = $_POST["Agreement"];
                    $LastName1 = $_POST["LastName1"];
                    $Name = $_POST["Name"];
                    $CURP = $_POST["CURP"];
                    $Engagment = $_POST["Engagment"];
                    $IdCard = $_POST["IdCard"];
                    $NextSellAgency = $_POST["NextSellAgency"];
                    $RequestDate = $_POST["RequestDate"];
                    $LastName2 = $_POST["LastName2"];
                    $RFC = $_POST["RFC"];
                    $Email = $_POST["Email"];
                    $NextSellGender = $_POST["NextSellGender"];
                    $NextSellIdentification = $_POST["NextSellIdentification"];
                    $NextSellBirthdate = $_POST["NextSellBirthdate"];
                    $NextStepSaleState = $_POST["NextStepSaleState"];
                    $NextStepSaleColonia = $_POST["NextStepSaleColonia"];
                    $NextStepSaleInHome = $_POST["NextStepSaleInHome"];
                    $NextSellCellularPhone = $_POST["NextSellCellularPhone"];
                    $NextSellEnterprise = $_POST["NextSellEnterprise"];
                    $NextSellPosition = $_POST["NextSellPosition"];
                    $NextSellJobTelephone = $_POST["NextSellJobTelephone"];
                    $NextSellCountry = $_POST["NextSellCountry"];
                    $NextStepSaleCity = $_POST["NextStepSaleCity"];
                    $NextStepSaleStreet = $_POST["NextStepSaleStreet"];
                    $NextSellPhone = $_POST["NextSellPhone"];
                    $NextSellJobLocation = $_POST["NextSellJobLocation"];
                    $NextSellJobActivity = $_POST["NextSellJobActivity"];
                    $NextStepSaleAgreegmentType = $_POST["NextStepSaleAgreegmentType"];
                    $idArticulo = $_POST["idArticulo"];
                    $NextSellPrice = $_POST["NextSellPrice"];
                    $NextSellPaymentTime = $_POST["NextSellPaymentTime"];
                    $NextSellMonthlyCost = $_POST["NextSellMonthlyCost"];
                    $NextSellRI = $_POST["NextSellRI"];
                    $NextSellDateRI = $_POST["NextSellDateRI"];
                    $outterNumber= $_POST["outterNumber"];
                    $inputNickUserLogg= $_POST["inputNickUserLogg"];
                    $latitude = 0;
                    $longitude = 0;
                    $idSecondSell=getSecondSell($idReport, $conn);
                    if($idSecondSell != ''){
                        $resActualizacion=actualizarInformacionSegVta($idForm, $idSecondSell, $_POST, $idReport);
                    }else{
                        $createSecondStepSellSQL = "INSERT INTO agreement(idAgency, payment, 
                                                                          idReport, requestDate,
                                                                          clientlastName, clientlastName2, 
                                                                          clientName,clientRFC, clientCURP, 
                                                                          clientEmail, clientRelationship,
                                                                          clientgender, clientIdNumber, 
                                                                          identificationType,clientBirthDate,
                                                                          clientBirthCountry,idState,idCity, 
                                                                          idColonia, street, inHome, homeTelephone, 
                                                                          celullarTelephone, agreementType,idArt, price,
                                                                          agreementMonthlyPayment,agreementExpires,
                                                                          agreementRi,agreementRiDate,clientJobEnterprise,
                                                                          clientJobLocation, clientJobRange,
                                                                          clientJobActivity, clientJobTelephone, 
                                                                          latitude,longitude,outterNumber,created_at, updated_at) 
                                                                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                                                                       ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?,?,?, ?, ?, ?,?,?, NOW(), NOW());";
                        //cachamos errores en MySQL
                        if ($createSecondStepSell = $conn->prepare($createSecondStepSellSQL)) {
                            //generamos las demas acciones correspondientes
                            $createSecondStepSell->bind_param("idisssssssssssssssssssssdididssssssdds",$Agency, $NextSellPayment, $idReport, $RequestDate, $LastName1,$LastName2, $Name,$RFC,$CURP, $Email,$Engagment, $NextSellGender,$IdCard,$NextSellIdentification, $NextSellBirthdate,$NextSellCountry,$NextStepSaleState, $NextStepSaleCity,$NextStepSaleColonia,$NextStepSaleStreet,$NextStepSaleInHome,$NextSellPhone,$NextSellCellularPhone,$NextStepSaleAgreegmentType,$idArticulo,$NextSellPrice, $NextSellMonthlyCost, $NextSellPaymentTime,$NextSellRI,$NextSellDateRI,$NextSellEnterprise,$NextSellJobLocation, $NextSellPosition,$NextSellJobActivity,$NextSellJobTelephone,$latitude, $longitude, $outterNumber);
                            if ($createSecondStepSell->execute()) {
                                $secondSell = $createSecondStepSell->insert_id;
                                //Después de crear la segunda venta es necesario asignar el reporte a instalación en base a la lógica del procedimiento definida
                                //ejecutamos el proceso de validacion de la segunda venta

                                $DB = new DAO();
                                $conn = $DB->getConnect();
                                //$resProcValid=validateSecondSell($_POST);
                                $cont=1;
                                foreach ($_POST["References"] as $referencias) {
                                    $data = (array)$referencias;
                                    $txtNombreRefrencia='txtNombreRefrencia'.$cont;
                                    $txtTelefonoDeTrabajoReferencia='txtTelefonoDeTrabajoReferencia'.$cont;
                                    $txtTelefonoParticularRefrencia='txtTelefonoParticularRefrencia'.$cont;
                                    $txtTelefonoTrabajoExtRefrencia='txtTelefonoTrabajoExtRefrencia'.$cont;
                                    if($data[$txtNombreRefrencia] != "" ||
                                       $data[$txtTelefonoDeTrabajoReferencia] != "" ||
                                       $data[$txtTelefonoParticularRefrencia] != "" ||
                                       $data[$txtTelefonoTrabajoExtRefrencia] != ""){
                                        //almacenamos las referencias
                                        $createReferenceSQL="INSERT INTO agreement_reference(name, telephone, jobTelephone, ext, idAgreement, created_at, updated_at) VALUES(?, ?, ?, ?, ?, NOW(), NOW());";
                                        if ($createReference = $conn->prepare($createReferenceSQL)) {
                                            $createReference->bind_param("ssssi", $data[$txtNombreRefrencia], 
                                                                                  $data[$txtTelefonoParticularRefrencia], 
                                                                                  $data[$txtTelefonoDeTrabajoReferencia], 
                                                                                  $data[$txtTelefonoTrabajoExtRefrencia], 
                                                                                  $secondSell);
                                            if (!$createReference->execute()) {
                                                $response = null;

                                                $response["status"] = "ERROR";
                                                $response["code"] = "500";
                                                $response["response"] = "Error en la creación de imagen de referencia" . $conn->error;
                                                echo json_encode($response);
                                            }
                                        }else{
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Error en la creación de imagen de referencia" . $conn->error;
                                            echo json_encode($response);
                                        }
                                    }
                                    $cont++;
                                }
                                $createAgreeReport = $conn->prepare("INSERT INTO agreement_employee_report(idAgreement, idEmployee, idReport, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
                                $createAgreeReport->bind_param("iii", $secondSell, $idEmployee, $idReport);

                                if ($createAgreeReport->execute()) {
                                    $idWorkflow = 2;
                                    $idStatus = 3;
                                    $idAgreegment = $createSecondStepSell->insert_id;
                                    $createAgreementStatus = $conn->prepare("INSERT INTO workflow_status_agreement(idWorkflow, idStatus, idAgreement, created_at, updated_at, active) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                    $createAgreementStatus->bind_param("iii", $idWorkflow, $idStatus, $idAgreegment);
                                    if ($createAgreementStatus->execute()) {
                                        $querySmt = "UPDATE reportHistory SET idStatusReport=3 WHERE idReport=? and idReportType=5";
                                        $idEstatusReport = 3;
                                        if ($stmt = $conn->prepare($querySmt)) {
                                            $stmt->bind_param("i", $idReport);
                                            if ($stmt->execute()) {
                                                $updateReportStatus = $conn->prepare("UPDATE workflow_status_report 
                                                                                SET idStatus = ?, updated_at = NOW() 
                                                                              WHERE idReport = ?;");
                                                $updateReportStatus->bind_param('ii', $idStatus, $idReport);
                                                if ($updateReportStatus->execute()) {
                                                    $updateReportAssignedStatus = $conn->prepare("UPDATE report_AssignedStatus 
                                                                                            SET idStatus = ?, updated_at = NOW() 
                                                                                          WHERE idReport = ?;");
                                                    $updateReportAssignedStatus->bind_param('ii', $idStatus, $idReport);
                                                    $updateReportAssignedStatus->execute();
                                                } else {
                                                    echo $updateReportStatus->error;
                                                }


                                                //generamos el insert o update en history
                                                $resIdHist=getIdReportHist($idReport);
                                                if ($resIdHist == "") {
                                                    $stmtInsertFirst="INSERT INTO `reportHistory`(`idReport`,`idFormSell`,`idReportType`,`idUserAssigned`,`idStatusReport`,`updated_at`,`created_at`)VALUES(?,?,5,?,3,NOW(),NOW());";
                                                    if ($insertHistory = $conn->prepare($stmtInsertFirst)) {
                                                        $insertHistory->bind_param("iii",$idReport, $Consecutive, $idEmployee);
                                                        $insertHistory->execute();
                                                    }else{
                                                        error_log($conn->error);
                                                    }
                                                }

                                                $statusSegundaVenta = 41;
                                                $dbSegVta = new DAO();
                                                $connSegVta = $dbSegVta->getConnect();
                                                $stmtTEstatus = "UPDATE tEstatusContrato SET validacionSegundaVenta = ?,idEmpleadoSegundaVenta = ? WHERE idReporte = ?;";
                                                if ($estatusCrontratoReport = $connSegVta->prepare($stmtTEstatus)) {
                                                    $estatusCrontratoReport->bind_param("iii", $statusSegundaVenta, $idEmployee, $idReport);
                                                    $estatusCrontratoReport->execute();
                                                }else{
                                                    error_log('maldito error '.$connSegVta->error);
                                                }
                                                if ($inputNickUserLogg == 'SuperAdmin') {
                                                    validateSecondSell($connSegVta,$idForm, $idReport, $_POST);
                                                }else{
                                                    $response["status"] = "EXITO";
                                                    $response["code"] = "200";
                                                    $response["response"] = "Segunda Venta creada con exito";
                                                    echo json_encode($response);
                                                }
                                                $connSegVta->close();
                                            }
                                        }
                                    } else {
                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] = "Error en la Creacion del status de Contrato: " . $conn->error;
                                        echo json_encode($response);
                                    }
                                } else {
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] = "Error en la Creacion de Formulario Contrato: " . $conn->error;
                                    echo json_encode($response);
                                }
                            } else {
                                $response["status"] = "ERROR";
                                $response["code"] = "500";
                                $response["response"] = "Error en la Creacion de Contrato1: " . $conn->error;
                                echo json_encode($response);
                            }
                        }else{
                            $response["status"] = "ERROR";
                            $response["code"] = "500";
                            $response["response"] = "Error en la Creacion de Contrato2: " . $conn->error;
                            $response["post"]=$_POST;
                            echo json_encode($response);
                        }
                    }
                }
            }else{
                echo 'error '.json_encode($getFormSell);
            }
        }else{
            $response["status"] = "ERROR";
            $response["code"] = "500";
            $response["response"] = "Problema al obtener la plomeria";
            echo json_encode($response);
        }
    }else{
        echo 'error '.$getFormSell->error;
    }
}else{
    $response["status"] = "ERROR";
    $response["code"] = "500";
    $response["response"] = "Aun existen campos vacios";
    $response["arrayPOST"] = $_POST;
    echo json_encode($response);
}

function grabarLog($logInfo, $nombreArchivo){
    $dir = "../../logs/";
    $fileName = $nombreArchivo . ".txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);
    $sell['tf_fpbahr'] = "";
}

function getSecondSell($idReport, $conn)
{
    if ($idReport != '') {
        $getSecondSel = "SELECT id
                            from agreement where idReport=$idReport";
        //echo $getReference;
        $result = $conn->query($getSecondSel);
        $res="";
        //var_dump($result);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
    }
    return $res;   
}
function validateSecondSell($conn, $idForm, $idReport, $arraySegundaVta)
{
    if ($idReport != "" && $idForm != "") {
        $getFormSellValidationSQL = "SELECT id, validate FROM form_sells_validation a, reportHistory b WHERE a.idFormSell=b.idFormSell and b.idFormSell = ? and b.idReportType=5 and b.idReport=?;";
        if ($getFormSellValidation = $conn->prepare($getFormSellValidationSQL)) {
            $getFormSellValidation->bind_param("ii", $idForm, $idReport);
            if ($getFormSellValidation->execute()) {
                $getFormSellValidation->store_result();
                $getFormSellValidation->bind_result($idFormValidation, $validateStatus);
                if ($getFormSellValidation->num_rows > 0) {
                    if ($getFormSellValidation->fetch()) {
                        if ($validateStatus == 0) {
                            $response["status"] = "ERROR";
                            $response["code"] = "500";
                            $response["response"] = "La venta no ha sido Validada1";
                            echo json_encode($response);
                        } else {
                            $getPlumberForm = $conn->prepare("SELECT DISTINCT FP.id, RP.employeesAssigned, RP.idUserCreator, RP.id 
                                                                FROM reportHistory AS REF 
                                                                INNER JOIN form_plumber AS FP ON REF.idFormulario = FP.id 
                                                                INNER JOIN report AS RP ON REF.idReport = RP.id
                                                                WHERE RP.id = ? and (REF.idReportType=3 and REF.idStatusReport=3);");
                            $getPlumberForm->bind_param("i", $idReport);
                            if ($getPlumberForm->execute()) {
                                $getPlumberForm->store_result();
                                $getPlumberForm->bind_result($idForm, $employeesAssigned, $idUserCreator, $idReport);
                                if ($getPlumberForm->num_rows > 0) {
                                    if ($getPlumberForm->fetch()) {
                                        $resProcValid=sendSecondSell($arraySegundaVta);
                                        $response["status"] = "EXITO";
                                        $response["code"] = "200";
                                        $response["response"] = "asignacion plomero instalador";
                                    }
                                }else{
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] = "Para la creación de una Segunda Venta es necesario que se encuentre realizada la plomería";
                                    echo json_encode($response);
                                }   
                            }
                        }
                    }
                }else{
                    $response["status"] = "ERROR";
                    $response["code"] = "500";
                    $response["response"] = "La venta no ha sido Validada";
                    echo json_encode($response);
                }
            }
        }else{
            $response["status"] = "ERROR";
            $response["code"] = "500";
            $response["response"] = "Error MySQL ".$conn->error;
            echo json_encode($response);
        }
    }else{
        $response["status"] = "ERROR";
        $response["code"] = "500";
        $response["response"] = "Datos Vacios";
        echo json_encode($response);
    }
}
function sendSecondSell($post)
{
    error_log(json_encode($post));
    $ahora=date('Y-m-d');
    $descContrato=getDescContrato($post['idArticulo']);
    $sell['tf_solcto_id'] = 0;
    $sell['tf_sucursal'] = 10;
    //$sell['tf_dir'] = $post['NextSellCountry'].' '.$post['NextStepSaleCity'].' '.$post['NextStepSaleStreet'];
    //$sell['tf_fecha_venta'] = $post['RequestDate'];
    $sell['tf_fecha_venta'] = $ahora;
    $sell['tf_nombre'] = $post['Name'];
    $sell['tf_appaterno'] = $post['LastName1'];
    $sell['tf_apmaterno'] = $post['LastName2'];
    $sell['tf_fnac'] = $post['NextSellBirthdate'];
    $sell['tf_rfc'] = $post['RFC'];
    //lo cambiaremos para mandar el nombre del empleado
    $sell['tf_vendedor'] = getNameVendedor($post['idEmployee']);
    //$sell['tf_vendedor'] = $post['idEmployee'];
    switch ($post['NextStepSaleInHome']) {
        case 'Propia pagada':
            $sell['tf_vivienda'] = 1;
            break;
        case 'Propia pagando':
            $sell['tf_vivienda'] = 2;
            break;
        case 'Rentada':
            $sell['tf_vivienda'] = 3;
            break;
    }

    $sell['tf_tiempo'] = 0;
    $sell['tf_uni_tiempo'] = "A";


    $sell['tf_nomemp'] = $post['NextSellEnterprise'];
    $sell['tf_diremp'] = $post['NextSellJobLocation'];
    $sell['tf_telemp'] = $post['NextSellJobTelephone'];
    $sell['tf_cbtelemp'] ='F';
    $sell['tf_ptoemp'] = $post['NextSellPosition'];

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
    $sell['tf_dir_id'] = $post["outterNumber"];
    $sell['tf_ffirma'] = $post['RequestDate'];
    $sell['tf_ffirma'] = date('Y-m-d',strtotime($post['RequestDate']));
    $sell['tf_fpago'] = $post['NextSellDateRI'];
    $sell['tf_antsol'] = 0;
    $sell['tf_mtsri'] = $post['NextSellRI'];
    $sell['tf_tomasri'] = 0;
    $sell['tf_fpbahr'] = $post['NextSellDateRI'];
    //mandaremos el nombre de la agencia
    $sell['tf_agencia'] = getAgencia($post['idEmployee']);
    //$sell['tf_docs'] = 0;
    $sell['tf_fredint'] = $post['NextSellDateRI'];
    $sell['tf_actemp'] = "";
    $sell['tf_actempfam'] = "";
    
    $sell['tf_email'] = $post['Email'];
    
    $sell['tf_enviar_cfdi'] = 0;
    $sell['tf_foliocto'] = $post['Agreement'];
    $sell['tf_foliopag'] = $post['NextSellPayment'];
    if (intval($post['financialService']) == 1) {
        $nombreFinanciera = "AYO";
    } else {
        $nombreFinanciera = "CMG";
    }
    error_log('message financialService '.$post['financialService']);
    error_log('message nombreFinanciera '.$nombreFinanciera);
    $sell['tf_serie'] = $nombreFinanciera;

    //tipo contrato
    $sell['tf_articulo'] =  $descContrato;
    $sell['tf_financiar'] = $post['NextSellPaymentTime'];
    $sell['tf_precio'] = $post['idArticulo'];
    
    $sell['tf_estufa'] = 0;
    $sell['tf_boiler'] = 0;
    $sell['tf_calentador'] = 0;
    $sell['tf_tpocto'] = 1;
    $cont=1;
    foreach ($post['References'] as $referencias) {
        $data = (array)$referencias;
        $txtNombreRefrencia='txtNombreRefrencia'.$cont;
        $txtTelefonoParticularRefrencia='txtTelefonoParticularRefrencia'.$cont;
        $txtTelefonoDeTrabajoReferencia='txtTelefonoDeTrabajoReferencia'.$cont;
        $txtTelefonoTrabajoExtRefrencia='txtTelefonoTrabajoExtRefrencia'.$cont;
        $txtTelefonoTrabajoExtRefrencia='txtTelefonoTrabajoExtRefrencia'.$cont;
        if($data[$txtNombreRefrencia] != "" ||
           $data[$txtTelefonoDeTrabajoReferencia] != "" ||
           $data[$txtTelefonoParticularRefrencia] != "" ||
           $data[$txtTelefonoTrabajoExtRefrencia] != ""){
            if ($cont == 1) {
                $sell['tf_tel1'] = $data[$txtTelefonoParticularRefrencia];
                $sell['tf_nomrefer'] = $data[$txtNombreRefrencia];
                $sell['tf_telTrab1'] = $data[$txtTelefonoDeTrabajoReferencia];
                $sell['tf_cbTipoTelRef1'] = "F";
                $sell['tf_telPart1'] = $data[$txtTelefonoParticularRefrencia];
                $sell['tf_cbTipoTelPartRef1'] = "F";
            }elseif($cont == 2){
                $sell['tf_tel2'] = $data[$txtTelefonoParticularRefrencia];
                $sell['tf_nomrefer2'] = $data[$txtNombreRefrencia];
                $sell['tf_telTrab2'] = $data[$txtTelefonoDeTrabajoReferencia];
                $sell['tf_cbTipoTelRef2'] = "F";
                $sell['tf_telPart2'] = $data[$txtTelefonoParticularRefrencia];
                $sell['tf_cbTipoTelPartRef2'] = "F";
            }            
        }
        $cont++;
    }
    //var_dump($sell);
    //die();
    $sell['tf_nomrefer3'] = "";
    $sell['tf_tel3'] = "";
    $sell['tf_movil'] = "";
    $sell['tf_ext2'] = "";
    $sell['tf_ext3'] = "";
    $sell['tf_telTrab3'] = "";
    $sell['tf_cbTipoTelRef3'] = "F";
    $sell['tf_telPart3'] = "";
    $sell['tf_cbTipoTelPartRef3'] = "F";
    //nombreReferencias


    $sell['tf_pais'] = "Mexico";
    
    $returnData['it_solicitudRow'] = $sell;
    $returnData = json_encode($returnData);

    $ip_cia_id = 1;
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "http://siscomcmg.com:8080/v1/api/WsSiscomGuardaContrato.php");
    //curl_setopt($curl, CURLOPT_URL, "http://siscomcmg.com/v1/api/WsSiscomGuardaContrato.php");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('ip_cia_id' => $ip_cia_id, 
                                                                  'ip_usr_id' => "migesa2", 
                                                                  'it_solicitud' => $returnData, 
                                                                  'ip_contrato' => 0, 
                                                                  'jsonItSolicitud' => $returnData));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    }
}

function formatDate($fecha){
    $RequestDate = date_create($fecha);
    $RequestDate = date_format($RequestDate, 'd-m-Y');
    return $RequestDate;
}

function getDescContrato($idContrato)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idContrato != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getDescContratoSQL = "SELECT claveArticuloContrato FROM catalogoTiposDeContrato WHERE idContrato = $idContrato;";
        $result = $conn->query($getDescContratoSQL);
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

function getIdReportHist($idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdReportHistSQL = "SELECT idReportHistory FROM reportHistory WHERE idReport = $idReport AND idReportType=5;";
        $result = $conn->query($getIdReportHistSQL);
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

function getNameVendedor($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT name, lastName, LastNameOP FROM user WHERE id = $idUser;";
        $result = $conn->query($getNameSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $name=ltrim($row[0]);
                $apPaterno=ltrim($row[1]);
                $LastNameOP=ltrim($row[2]);
                $res=$name.''.$apPaterno.' '.$LastNameOP;
            }
        }
        $conn->close();
    }
    return $res;
}

function getAgencia($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT a.id,a.nickname as nickNameEmp, (select nickname from user where id=c.idUser) as nickNameAgency 
                       FROM user a, employee b, agency c, agency_employee d
                       where 0=0
                       and a.id=b.idUser
                       and c.id=d.idAgency
                       and b.id=d.idemployee
                       and a.id = $idUser;";
        $result = $conn->query($getNameSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[2];
            }
        }
        $conn->close();
    }
    return $res;
}

function actualizarInformacionSegVta($idForm, $idSecondSell, $arraySegundaVta, $idReport){
    $Agency=$arraySegundaVta['Agency'];
    $Agreement=$arraySegundaVta['Agreement'];
    $CURP=$arraySegundaVta['CURP'];
    $Consecutive=$arraySegundaVta['Consecutive'];
    $Email=$arraySegundaVta['Email'];
    $Engagment=$arraySegundaVta['Engagment'];
    $IdCard=$arraySegundaVta['IdCard'];
    $LastName1=$arraySegundaVta['LastName1'];
    $LastName2=$arraySegundaVta['LastName2'];
    $Name=$arraySegundaVta['Name'];
    $NextSellAgency=$arraySegundaVta['NextSellAgency'];
    $NextSellBirthdate=$arraySegundaVta['NextSellBirthdate'];
    $NextSellCellularPhone=$arraySegundaVta['NextSellCellularPhone'];
    $NextSellCountry=$arraySegundaVta['NextSellCountry'];
    $NextSellDateRI=$arraySegundaVta['NextSellDateRI'];
    $NextSellEnterprise=$arraySegundaVta['NextSellEnterprise'];
    $NextSellGender=$arraySegundaVta['NextSellGender'];
    $NextSellIdentification=$arraySegundaVta['NextSellIdentification'];
    $NextSellJobActivity=$arraySegundaVta['NextSellJobActivity'];
    $NextSellJobLocation=$arraySegundaVta['NextSellJobLocation'];
    $NextSellJobTelephone=$arraySegundaVta['NextSellJobTelephone'];
    $NextSellMonthlyCost=$arraySegundaVta['NextSellMonthlyCost'];
    $NextSellPayment=$arraySegundaVta['NextSellPayment'];
    $NextSellPaymentTime=$arraySegundaVta['NextSellPaymentTime'];
    $NextSellPhone=$arraySegundaVta['NextSellPhone'];
    $NextSellPosition=$arraySegundaVta['NextSellPosition'];
    $NextSellPrice=$arraySegundaVta['NextSellPrice'];
    $NextSellRI=$arraySegundaVta['NextSellRI'];
    $NextStepSaleAgreegmentType=$arraySegundaVta['NextStepSaleAgreegmentType'];
    $NextStepSaleCity=$arraySegundaVta['NextStepSaleCity'];
    $NextStepSaleColonia=$arraySegundaVta['NextStepSaleColonia'];
    $NextStepSaleInHome=$arraySegundaVta['NextStepSaleInHome'];
    $NextStepSaleState=$arraySegundaVta['NextStepSaleState'];
    $NextStepSaleStreet=$arraySegundaVta['NextStepSaleStreet'];
    $txtNextStepSaleStreetNumber=$arraySegundaVta['txtNextStepSaleStreetNumber'];
    $RFC=$arraySegundaVta['RFC'];
    $References=$arraySegundaVta['References'];
    $outterNumber= $arraySegundaVta["outterNumber"];
    $RequestDate=$arraySegundaVta['RequestDate'];
    $idArticulo=$arraySegundaVta['idArticulo'];
    $idEmployee=$arraySegundaVta['idEmployee'];
    $idReport=$arraySegundaVta['idReport'];
    $inputNickUserLogg= $arraySegundaVta["inputNickUserLogg"];
    //echo json_encode($arraySegundaVta);
    if (count($arraySegundaVta) > 0 && $idSecondSell > 0) {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $updateSecondStepSellSQL = "UPDATE agreement SET 
                                              idAgency=?, payment=?, 
                                              idReport=?, requestDate=?,
                                              clientlastName=?, clientlastName2=?, 
                                              clientName=?,clientRFC=?, clientCURP=?, 
                                              clientEmail=?, clientRelationship=?,
                                              clientgender=?, clientIdNumber=?, 
                                              identificationType=?,clientBirthDate=?,
                                              clientBirthCountry=?,idState=?,idCity=?, 
                                              idColonia=?, street=?, inHome=?, homeTelephone=?, 
                                              celullarTelephone=?, agreementType=?, price=?,
                                              agreementMonthlyPayment=?,agreementExpires=?,
                                              agreementRi=?,agreementRiDate=?,clientJobEnterprise=?,
                                              clientJobLocation=?, clientJobRange=?,
                                              clientJobActivity=?, clientJobTelephone=?, idArt=?,outterNumber=?,updated_at=NOW()
                                              WHERE id=?;";
        if ($updateSecondStepSell = $conn->prepare($updateSecondStepSellSQL)) {
            $idArticulo=intval($idArticulo);
            $updateSecondStepSell->bind_param("idisssssssssssssssssssssddidssssssisi", $Agency, $NextSellPayment, $idReport, $RequestDate, $LastName1,$LastName2, $Name,$RFC,$CURP, $Email,$Engagment, $NextSellGender,$IdCard,$NextSellIdentification, $NextSellBirthdate,$NextSellCountry,$NextStepSaleState, $NextStepSaleCity,$NextStepSaleColonia,$NextStepSaleStreet,$NextStepSaleInHome,$NextSellPhone,$NextSellCellularPhone,$NextStepSaleAgreegmentType,$NextSellPrice, $NextSellMonthlyCost, $NextSellPaymentTime,$NextSellRI,$NextSellDateRI,$NextSellEnterprise,$NextSellJobLocation, $NextSellPosition,$NextSellJobActivity,$NextSellJobTelephone,$idArticulo,$outterNumber,$idSecondSell);
            if ($updateSecondStepSell->execute()) {
                //actualizamos las referencias
                $cont=1;
                $deleteReferenciasSQL="DELETE FROM agreement_reference where idAgreement=?;";//$idSecondSell
                if ($deleteReferencias = $conn->prepare($deleteReferenciasSQL)) {
                    $deleteReferencias->bind_param("i", $idSecondSell);
                    if ($deleteReferencias->execute()) {
                        foreach ($_POST["References"] as $referencias) {
                            $data = (array)$referencias;
                            $txtNombreRefrencia='txtNombreRefrencia'.$cont;
                            $txtTelefonoDeTrabajoReferencia='txtTelefonoDeTrabajoReferencia'.$cont;
                            $txtTelefonoParticularRefrencia='txtTelefonoParticularRefrencia'.$cont;
                            $txtTelefonoTrabajoExtRefrencia='txtTelefonoTrabajoExtRefrencia'.$cont;
                            if($data[$txtNombreRefrencia] != "" ||
                               $data[$txtTelefonoDeTrabajoReferencia] != "" ||
                               $data[$txtTelefonoParticularRefrencia] != "" ||
                               $data[$txtTelefonoTrabajoExtRefrencia] != ""){
                                $createReferenceSQL="INSERT INTO agreement_reference(name, telephone, jobTelephone, ext, idAgreement, created_at, updated_at) VALUES(?, ?, ?, ?, ?, NOW(), NOW());";
                                if ($createReference = $conn->prepare($createReferenceSQL)) {
                                    $createReference->bind_param("ssssi", $data[$txtNombreRefrencia], 
                                                                          $data[$txtTelefonoParticularRefrencia], 
                                                                          $data[$txtTelefonoDeTrabajoReferencia], 
                                                                          $data[$txtTelefonoTrabajoExtRefrencia], 
                                                                          $idSecondSell);
                                    if (!$createReference->execute()) {
                                        $response = null;

                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] = "Error en la creación de imagen de referencia" . $conn->error;
                                        echo json_encode($response);
                                    }
                                }else{
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] = "Error en la creación de imagen de referencia" . $conn->error;
                                    echo json_encode($response);
                                }
                            }
                            $cont++;
                        }
                        if ($inputNickUserLogg == 'SuperAdmin') {
                            validateSecondSell($conn, $idForm, $idReport, $arraySegundaVta);
                        }else{
                            $response["status"] = "EXITO";
                            $response["code"] = "200";
                            $response["response"] = "Segunda Venta actualizada con exito";
                            echo json_encode($response);
                        }
                    }
                }
                //update a report

                /*NextStepSaleCity
                NextStepSaleColonia
                NextStepSaleStreet
                txtNextStepSaleStreetNumber*/
                $stmtReport = "UPDATE report SET colonia = ?, street = ?, innerNumber = ?, outterNumber = ?, idCity = ? WHERE id = ?;";
                if ($stmtReport = $conn->prepare($stmtReport)) {
                    error_log('message NextStepSaleColonia '.$NextStepSaleColonia);
                    error_log('message NextStepSaleStreet '.$NextStepSaleStreet);
                    error_log('message txtNextStepSaleStreetNumber '.$txtNextStepSaleStreetNumber);
                    error_log('message txtNextStepSaleStreetNumber '.$txtNextStepSaleStreetNumber);
                    error_log('message NextStepSaleCity '.$NextStepSaleCity);
                    $stmtReport->bind_param("sssssi", $NextStepSaleColonia, $NextStepSaleStreet, $txtNextStepSaleStreetNumber, $txtNextStepSaleStreetNumber, $NextStepSaleCity, $idReport);
                    if ($stmtReport->execute()) {
                        //echo "se actualizo";
                    }else{
                        //echo "error1 ".$conn->error;
                    }
                }else{
                    //echo "error2 ".$conn->error;
                }
            }
        }
    }
    $conn->close();
}