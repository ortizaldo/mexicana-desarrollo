<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";


 //$_POST["token"] = "ODkmc2VydGVjNSZzZXJ0ZWVlZWNAZ21haWwuY29tJnBsb21lcm9fdmVudGFfY2Vuc28mU0VSVEVDJkVtcGxveWVlJjI2OTgwNyYxMTExMTE=";

if (isset($_POST["token"])) {
    $DB = new DAO();
    $conn = $DB->getConnect();
    

    
//    var_dump($_POST);exit();
    
    $token =  $_POST["token"];
    $token = base64_decode($token);

    list($id, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
    //var_dump(intval($id));
    $idUser=intval($id);
    $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
    $searchToken->bind_param('i', $idUser);
    $searchToken->execute();
    $searchToken->store_result();
    $searchToken->bind_result($userToken);
    $searchToken->fetch();

    $searchIdEmployee = $conn->prepare("SELECT id FROM employee WHERE idUser = ?;");
    $searchIdEmployee->bind_param("i", $idUser);
    //$idEmployee;
    //var_dump($idEmployee);
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
            
            
            $reasons = array();
            $rechazado = NULL;
            $iconta = 0;
            $status = NULL;
            $reportsPending = $conn->prepare("
                SELECT 
                    RP.id, 
                    RP.idSolicitudMovil, 
                    RP.agreementNumber, 
                    RP.idAgreement, 
                    RP.observations, 
                    RP.clientName,
                    RP.clientLastName1, 
                    RP.clientLastName2, 
                    RP.colonia, 
                    RP.street, 
                    RP.idCity, 
                    RP.betweenStreets, 
                    RP.nse, 
                    RP.newStreet,
                    RP.streets, 
                    RP.coloniaType, 
                    RP.marketType, 
                    RP.innerNumber, 
                    RP.outterNumber, 
                    RP.street, 
                    RP.cp, 
                    UsEMP.nickname,
                    RP.idReportType, 
                    UsCreator.nickname, 
                    RP.idSolicitudMovil AS idSolicitud, 
                    RP.idFormulario,
                    RP.idForm, 
                    RP.dot_latitude, 
                    RP.dot_longitude,
                    RPH.idReportType
                FROM report AS RP
                INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
                LEFT JOIN user AS UsEMP ON UsEMP.id = EMP.idUser
                LEFT JOIN reportHistory AS RPH ON RP.id = RPH.idReport
                LEFT JOIN user AS UsCreator ON UsCreator.id = RPH.idUserAssigned
                INNER JOIN reportType AS RPT ON RPH.idReportType = RPT.id
                WHERE 0=0
                AND RPH.idUserAssigned = ?
                AND (RPH.idStatusReport=4 OR RPH.idStatusReport=7 OR RPH.idStatusReport=2)");

            $reportsPending->bind_param("i", $idUser);
            if ($reportsPending->execute()) {
                //var_dump($reportsPending);
                $reportsPending->store_result();
                $reportsPending->bind_result($idReport, $idSolicitud, $agreementNumber, $idAgreement, $observations, $clientName,
                    $clientLastName1, $clientLastName2, $colonia, $street, $city, $betweenStreets, $nse, $newStreet,
                    $streets, $coloniaType, $marketType, $innerNumber, $outterNumber, $street, $cp, $employeeName,
                    $reportType, $userCreator, $idRequest, $formulario, $idForm, $latitude, $longitude, $idReportTypeH);
                while ($reportsPending->fetch()) {
                    
                    $reasons = array();
                    $rechazado = NULL;
                    
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
                    $requests["ciudad"] = $city;
                    $requests["empleado"] = $employeeName;

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
                    $consecutive = $conn->prepare("SELECT trustedHome, requestImage, privacyAdvice, identificationImage, payerImage, agreegmentImage,validateFinanciera, validate FROM form_sells_validation WHERE idFormSell = ? ;");
                    $consecutive->bind_param("i", $formulario);                    
                    
                    if ($consecutive->execute()) {
                        $consecutive->store_result();
                        $consecutive->bind_result($trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage,$validateFinanciera, $validate);
                        if ($consecutive->fetch()) {
                            if ( $trustedHome == 0 ){
                                $indices[] = 1;
                            }
                            if ( $requestImage == 0 ){
                                $indices[] = 2;
                            }
                               
                            if ( $privacyAdvice == 0 ){
                                $indices[] = 3;
                            }
                            if ( $identificationImage == 0 ){
                                $indices[] = 4;
                            }
                            if ( $payerImage == 0 ){
                                $indices[] = 5;
                            }
                            if ( $agreegmentImage == 0 ){
                                $indices[] = 6;
                            }
                            if ( $validateFinanciera == 0 ){
                                $indices[] = 7;
                            }

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
                    
                   
                    if($requests["rechazado"])
                    {
                        // CAMBIO PARA QUE EN MOVIL SE VEA LA FORMA TIPO VENTA 
                        switch ($profile)
                        {
                            case "Venta":
                                $requests["tipoReporte"] = 2;
                                break;
                            case "censo_venta":
                                $requests["tipoReporte"] = 2;
                                break;
                            case "plomero_venta":
                                $requests["tipoReporte"] = 2;
                                break;
                            case "plomero_venta_censo":
                                $requests["tipoReporte"] = 2;
                                break;
                            case "plomero_venta_censo_instalacion":
                                $requests["tipoReporte"] = 2;
                                break;
                            default:
                                $requests["tipoReporte"] = $reportType;
                                break;
                        }
                    }
                    else
                    {
                        $requests["tipoReporte"] = $reportType;
                    }
                    
                    $requests["creador"] = $userCreator;
                    //Status del documento de tipo En Proceso(Asignado), Rechazado, FinalizadoNav
                    $requests["estatus"] = $status;
                    $requests["solicitud"] = $idRequest;
                    $requests["idFormulario"] = $idForm;
                    $requests["latitud"] = $latitude;
                    $requests["longitud"] = $longitude;

                    $TYPE_VENTA = 2;
                    $TYPE_PLOMERO = 3;
                    $TYPE_INSTALACION = 4;
                    $TYPE_SEGUNDAVENTA = 5;
                    $response_venta = null;
                    $response_plomero = null;
                    $response_instalacion = null;
                    $response_segundav = null;

                    $stmHistory = $conn->prepare("SELECT idStatusReport, idReportType, idFormSell, rechazado, idSolicitud, idFormulario, reasignado, idUserAssigned, liberadoAnomalia FROM reportHistory WHERE idReport = ? and idUserAssigned=? and idReportType=?;");
                    $stmHistory->bind_param("iii", $idReport, $idUser,$idReportTypeH); 

                    if ($stmHistory->execute()) {
                        $stmHistory->store_result();
                        $stmHistory->bind_result($idStatusR, $idReportT, $idFormS, $rech, $idS, $idF, $reasignado, $idUserAssigned, $liberadoAnomalia);
                        while ($stmHistory->fetch()) {
                            switch ($idReportT) {
                                case $TYPE_VENTA:
                                    $response_venta = array(
                                        "idReporte" =>  $idReport,
                                        "idStatus" =>  $idStatusR,
                                        "idReporType" =>  $idReportT,
                                        "idFormSell" =>  $idFormS,
                                        "rechazado" =>  ($rech == 1) ? true : false,
                                        "idSolicitud" =>  $idS,
                                        "idFormulario" =>  $idF,
                                        "reasignado" =>  $reasignado,
                                        "idUserAssigned" => $idUserAssigned
                                    );
                                    break;
                                case $TYPE_PLOMERO:
                                    $response_plomero = array(
                                        "idReporte" =>  $idReport,
                                        "idStatus" =>  $idStatusR,
                                        "idReporType" =>  $idReportT,
                                        "idFormSell" =>  $idFormS,
                                        "reasignado" =>  $reasignado,
                                        "idUserAssigned" => $idUserAssigned
                                    );
                                    break;
                                case $TYPE_INSTALACION:
                                   $response_instalacion = array(
                                        "idReporte" =>  $idReport,
                                        "idStatus" =>  $idStatusR,
                                        "idReporType" =>  $idReportT,
                                        "idFormSell" =>  $idFormS,
                                        "reasignado" =>  $reasignado,
                                        "idUserAssigned" => $idUserAssigned,
                                        "liberadoAnomalia" => $liberadoAnomalia
                                    );
                                    break;
                                case $TYPE_SEGUNDAVENTA:
                                    //echo "entredd";
                                   $response_segundav = array(
                                        "idReporte" =>  $idReport,
                                        "idStatus" =>  $idStatusR,
                                        "idReporType" =>  $idReportT,
                                        "idFormulario" =>  $idForm,
                                        "reasignado" =>  $reasignado,
                                        "idUserAssigned" => $idUserAssigned
                                    );
                                    break;
                            }
                        }
                    }

                    $requests['reportHistory'] = [
                        array(
                            "plomeria" => $response_plomero,
                        ), 
                        array(
                            "venta" => $response_venta,
                        ),
                        array(
                            "instalacion" => $response_instalacion
                        ), 
                        array(
                            "segundaVenta" => $response_segundav,
                        ),
                    ];

                    $tasksElems[] = $requests;
                    $iconta++;
                }//end while

                $info["solicitudes"] = $tasksElems;

                $info["reportesReasignados"] = getReportesReasignados($idUser);
                
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

function getReportesReasignados($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepRQL = "SELECT 
                            a.id, a.idRepReasign, a.tipoReporte
                        FROM
                            reportesReasignados a, reportHistory b
                        WHERE 0=0
                        AND a.idRepReasign = b.idReport
                        AND a.tipoReporte = b.idReportType
                        AND idUSerAnterior = $idUser
                        AND a.activo=1;";
        $result = $conn->query($getIdRepRQL);
        $res=[];
        if ($result->num_rows > 0) {
            $cont=0;
            while($row = $result->fetch_array()) {
                $res[$cont]["idRepReasign"]=intval($row[1]);
                $res[$cont]["tipoReporte"]=intval($row[2]);
                //actualizamosReasignados($row[0]);
                $cont++;
            }
        }
        $conn->close();
    }
    return $res;
}

function actualizamosReasignados($idRepReasign)
{
    //actualizamos
    if ($idRepReasign != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $stmtReportR = "UPDATE reportesReasignados SET activo=0, updated_at=NOW() WHERE id = ?;";
        if ($stmtReportAnt = $conn->prepare($stmtReportR)) {
            $stmtReportAnt->bind_param("i", $idRepReasign);
            $stmtReportAnt->execute();
        }else{
            error_log("error ".$conn->error);
        }
    }
}