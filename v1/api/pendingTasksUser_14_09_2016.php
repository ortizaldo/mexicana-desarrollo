<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (isset($_POST["token"])) {
    $DB = new DAO();
    $conn = $DB->getConnect();
    
//    $_POST["token"] = "MTAmRGFtaWFuIFRvZG8mRGFtaWFuQGdtYWlsLmNvbSZwbG9tZXJvX3ZlbnRhX2NlbnNvX2luc3RhbGFjaW9uJk1leGljYW5hRGVHYXNBZ2VuY2lhJkVtcGxveWVlJjY0MzY4OCYxMTExMTE=";
    
    $token =  $_POST["token"];
    $token = base64_decode($token);

    list($id, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
    $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
    $searchToken->bind_param('i', $id);
    $searchToken->execute();
    $searchToken->store_result();
    $searchToken->bind_result($userToken);
    $searchToken->fetch();

    $searchIdEmployee = $conn->prepare("SELECT id FROM employee WHERE idUser = ?;");
    $searchIdEmployee->bind_param("i", $id);
    $idEmployee;
    if ($searchIdEmployee->execute()){
        $searchIdEmployee->store_result();
        $searchIdEmployee->bind_result($idEmployee);
        if($searchIdEmployee->fetch()){
            $idEmployee;
        }
    }

    if (isset($userToken) && $userToken!= "") {
        
        if ($_POST["token"] == $userToken) {

            //Objeto donde se almacenaran los arreglos de reportes consultados
            $info = [];
            //Arreglo en el cual se guardaran los reportes referentes a las nuevas ventas o callejeros (report table)
            $sellsCallejero = [];
            //Arreglo en el cual se guardaran los reportes referentes a las nuevas tareas (task table)
            $tasksElems =  [];
            //Motivos de rechazo ["Falta fotografia", "Falta de informacion", "Datos del erroneos del cliente"];
            //Busqueda de razones de rechazo en la tabla rejected_reason y llenar el arreglo de acuerdo a los elementos
            /*$rejected = ["Falta fotografia", "Falta de informacion", "Datos del erroneos del cliente"];

            $reasons = [];

            $getReasons = "SELECT id, reason FROM rejected_reason;";
            $result = $conn->query($getReasons);

            while( $row = $result->fetch_array() ) {
                $reasons['id'] = $row[0];
                $reasons['reason'] = $row[1];
                $returnData[] = $reasons;
            }

            $result->free_result();*/

            /*$tasks = $conn->prepare("SELECT task.id, task.folio, task.street, task.number, task.colonia, task.state, task.annotations, task.zipCode, task.dateVisit, task.clientName, task.agreementNumber, task.idUserCreator, task.idUserAssigned, city.name, task.agendaDate, task.created_at, task.updated_at, task.dot_latitude, task.dot_longitude
            FROM task LEFT JOIN city ON city.id = task.idCity LEFT JOIN state ON state.id = city.idState WHERE task.idUserAssigned = ?;");
            $tasks->bind_param("i", $id);
            if ($tasks->execute()) {
                $tasks->store_result();
                $tasks->bind_result($idTask, $folio, $street, $number, $colonia, $state, $annotations, $zipCode, $dateVisit, $clientName,
                    $agreementNumber, $idUserCreator, $idUserAssigned, $city, $agenda, $created_at, $updated_at, $latitude, $longitude);
                while ($tasks->fetch()) {
                    $requests["id"] = $idTask;
                    $requests["folio"] = $folio;
                    $requests["street"] = $street;
                    $requests["number"] = $number;
                    $requests["colonia"] = $colonia;
                    $requests["state"] = $state;
                    $requests["annotations"] = $annotations;
                    $requests["zipCode"] = $zipCode;
                    $requests["dateVisit"] = $dateVisit;
                    $requests["clientName"] = $clientName;
                    $requests["agreementNumber"] = $agreementNumber;
                    $requests["idUserCreator"] = $idUserCreator;
                    $requests["idUserAssigned"] = $idUserAssigned;
                    $requests["city"] = $city;
                    $requests["agenda"] = $agenda;
                    $requests["created"] = $created;
                    $requests["updated"] = $updated;
                    $requests["latitude"] = $latitude;
                    $requests["longitude"] = $longitude;
                    $sellsCallejero[] = $requests;
                }//end while
                $info["callejero"] = $sellsCallejero;
                //print_r($info);
                //exit;
            } else {
                $response["status"] = "BAD";
                $response["code"] = "404";
                $response["response"] = "No se encontraron callejeros";
                echo json_encode($response);
            }//end if*/

            //Obtenemos solicitudes
            /*$reportsPending = $conn->prepare("SELECT RP.id, RP.idSolicitud, RP.agreementNumber, RP.clientName, RP.clientLastName1, RP.clientLastName2, RP.colonia, RP.street, RP.betweenStreets, RP.nse, RP.newStreet, RP.streets, RP.coloniaType, RP.marketType, RP.innerNumber, RP.outterNumber, RP.street, CNT.name, ST.name, CTY.name, RP.cp, UsEMP.nickname, RP.idReportType, UsCreator.nickname, STAS.id, RP.idSolicitud, RP.dot_latitude, RP.dot_longitude
    FROM report AS RP
    LEFT JOIN reportType AS RPT ON RP.idReportType = RPT.id
    INNER JOIN country AS CNT ON CNT.id = RP.idCountry
    INNER JOIN state AS ST ON ST.id = RP.idState
    INNER JOIN city AS CTY ON CTY.id = RP.idCity
    INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
    INNER JOIN user AS UsEMP ON EMP.idUser = UsEMP.id
    INNER JOIN user AS UsCreator ON RP.idUserCreator = UsCreator.id
    INNER JOIN report_AssignedStatus AS RPAS ON RP.id = RPAS.idRepopert
    LEFT JOIN status AS STAS ON STAS.id = RPAS.idStatus
    WHERE RP.idEmployee = ? OR RP.idUserCreator = ? AND RPT.id > 1;");*/
            
            
            $reasons = array();
            $rechazado = NULL;
            $iconta = 0;
            
            
            $reportsPending = $conn->prepare("SELECT RP.id, RP.idSolicitudMovil, RP.agreementNumber, RP.idAgreement, RP.observations, RP.clientName,
    RP.clientLastName1, RP.clientLastName2, RP.colonia, RP.street, RP.idCity, RP.betweenStreets, RP.nse, RP.newStreet,
    RP.streets, RP.coloniaType, RP.marketType, RP.innerNumber, RP.outterNumber, RP.street, RP.cp, UsEMP.nickname,
    RP.idReportType, UsCreator.nickname, STAS.id, RP.idSolicitudMovil AS idSolicitud, RP.idFormulario, RP.dot_latitude, RP.dot_longitude
    FROM report AS RP
    INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
    INNER JOIN report_AssignedStatus AS RPAS ON RP.id = RPAS.idReport
    INNER JOIN status AS STAS ON RPAS.idStatus= STAS.id
    INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
    LEFT JOIN user AS UsEMP ON UsEMP.id = EMP.idUser
    LEFT JOIN user AS UsCreator ON UsCreator.id = RP.idUserCreator
    WHERE RP.idEmployee = ? AND RPT.id > 1 OR RP.idUserCreator = ? AND RPT.id > 1;");
            
            $reportsPending->bind_param("ii", $idEmployee, $id);
            if ($reportsPending->execute()) {
                
                $reportsPending->store_result();
                $reportsPending->bind_result($idReport, $idSolicitud, $agreementNumber, $idAgreement, $observations, $clientName,
                    $clientLastName1, $clientLastName2, $colonia, $street, $city, $betweenStreets, $nse, $newStreet,
                    $streets, $coloniaType, $marketType, $innerNumber, $outterNumber, $street, $cp, $employeeName,
                    $reportType, $userCreator, $status, $idRequest, $formulario, $latitude, $longitude);
                while ($reportsPending->fetch()) {
                    
                    
                    
                    $requests["id"] = $idReport;
                    //$requests["callejero"] = $sellsCallejero;
                    $requests["idSolicitud"] = $idSolicitud;
                    $requests["contratoIngresado"] = $agreementNumber;
                    $requests["contratoGenerado"] = $idAgreement;
                    $requests["observaciones"] = $observations;
                    $requests["nombre"] = $clientName;
                    $requests["apellidoPaterno"] = $clientLastName1;
                    $requests["apellidoMaterno"] = $clientLastName2;
                    $requests["colonia"] = $colonia;
                    $requests["calle"] = $street;
                    $requests["entreCalles"] = $betweenStreets;
                    $requests["nse"] = $nse;
                    $requests["NuevaDireccion"] = $newStreet;
                    $requests["NuevasentreCalles"] = $streets;
                    $requests["tipoColonia"] = $coloniaType;
                    $requests["tipo_mercado"] = $marketType;
                    $requests["numero"] = $innerNumber;
                    $requests["calle"] = $street;
                    //$requests["rechazado"] = $rejected;
                    $requests["ciudad"] = $city;
                    //$requests["cp"] = $cp;
                    $requests["empleado"] = $employeeName;
                    $requests["tipoReporte"] = $reportType;

                    //SELECT * FROM rejected_reason;
                    /*'1', 'Censo'
                    '2', 'Plomero'
                    '3', 'Venta'
                    '4', 'Instalacion'
                    '5', 'Segunda Venta'*/

                    $getReason = $conn->prepare("SELECT RJREA.reason FROM form_sells_rejected_reason AS FRMSLL INNER JOIN rejected_reason AS RJREA ON FRMSLL.idRejectedReason = RJREA.id WHERE FRMSLL.idSell = ? ;");
                    $getReason->bind_param("i", $formulario);
                    
                    if ($getReason->execute()) {
                        $getReason->store_result();
                        $getReason->bind_result($reason);
                        while ($getReason->fetch()) {
                            $reasons[] = $reason;
                        }
                        if ( $reasons != null)
                            $requests["motivos"] = $reasons;
                        else
                            $requests["motivos"] = [];
                    } else {
                        $requests["motivos"] = [];
                    }
                    $requests["consecutivo"] = $formulario;

                    
                    $indices = array();
                    $consecutive = $conn->prepare("SELECT trustedHome, requestImage, privacyAdvice, identificationImage, payerImage, agreegmentImage, validate FROM form_sells_validation WHERE idFormSell = ? ;");
                    $consecutive->bind_param("i", $formulario);                    
                    
                    if ($consecutive->execute()) {
                        $consecutive->store_result();
                        $consecutive->bind_result($trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validate);
                        if ($consecutive->fetch()) {
                            
                            if ( $trustedHome == 0 )
                                $indices[] = 1;
                            if ( $requestImage == 0 )
                                $indices[] = 2;
                               
                            if ( $privacyAdvice == 0 )
                                $indices[] = 3;
                            if ( $identificationImage == 0 )
                                $indices[] = 4;
                            if ( $payerImage == 0 )
                                $indices[] = 5;
                            if ( $agreegmentImage == 0 )
                                $indices[] = 6;

                            if ( $validate == 0) {
                                $rechazado = true;
                            } else {
                                $rechazado = false;
                            }
                        }
                        
                        if ($rechazado == null) {
                            $requests["rechazado"] = false;
                        } else {
                            $requests["rechazado"] = $rechazado;
                        }

                        $requests["indices_rechazos"] = $indices;
                    } else {
                        $requests["rechazado"] = false;
                        $requests["indices_rechazos"] = [];
                    }
                    
                   
                    
                    
                    $requests["creador"] = $userCreator;
                    //Status del documento de tipo En Proceso(Asignado), Rechazado, FinalizadoNav
                    $requests["estatus"] = $status;
                    $requests["solicitud"] = $idRequest;
                    $requests["idFormulario"] = $formulario;
                    $requests["latitud"] = $latitude;
                    $requests["longitud"] = $longitude;
                    $tasksElems[] = $requests;
                    
                    
                    $iconta++;
                }//end while
                $info["solicitudes"] = $tasksElems;
                
               
                
            } else {
                $response["status"] = "BAD";
                $response["code"] = "404";
                $response["response"] = "No se encontraron callejeros";
                echo json_encode($response);
            }//end if
            
            $requests = null;
            $response["status"] = "OK";
            $response["code"] = "200";
            $response["response"] = $info;
            
            
            echo json_encode($response);

            $reportsPending->close();
        } else {
            $response["status"] = "ERROR";
            $response["code"] = "404";
            $response["response"] = "Error en el token";
            echo json_encode($response);
        }
    }
}