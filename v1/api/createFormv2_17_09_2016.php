<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
/**
 * Created by PhpStorm.
 * User: RJUAREZR
 * Date: 03/08/2016
 * Time: 10:38 AM
 */
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/


//echo base64_decode("eyJpZCI6OCwiZmVjaGFfYXNpZ25hY2lvbiI6IjEzLzkvMjAxNiIsImZlY2hhX2FnZW5kYWRhIjpudWxsLCJmbGFnX2FzaWduYWRhIjoiMSIsImhpc3RvcmljbyI6bnVsbCwiZXN0YXR1cyI6IjEiLCJlc3RhdHVzX2NvbmNsdWlkbyI6IkNvbXBsZXRhZG8iLCJpZENhbGxlamVybyI6IjkiLCJudW1fY29udHJhdG8iOiIyMzQ0NTUiLCJpbmRpY2VzX3JlY2hhem9zIjpudWxsLCJjYWxsZWplcm8iOnsiaWQiOjksImlkQXNpZ25hY2lvbiI6NDg3LCJtdW5pY2lwaW8iOiJNT05URVJSRVkiLCJpZFNvbGljaXR1ZCI6OCwiY29sb25pYSI6IkVTUEFDSU8gQ1VNQlJFUyIsImNhbGxlIjoiSlVMSU8gVkVSTkUgIiwiZW50cmVjYWxsZXMiOiIgRW50cmUgSC4gRy4gV0VMTFMgeSBKVUxJTyBWRVJORSAiLCJudW1lcm8iOiI0MjgiLCJuc2UiOm51bGwsImRpcmVjY2lvbk51ZXZhIjpudWxsLCJlbnRyZWNhbGxlc051ZXZhIjpudWxsLCJleGNsdXNpdmlkYWQiOm51bGwsInRpcG9NZXJjYWRvIjpudWxsLCJsYXRpdHVkIjpudWxsLCJsb25naXR1ZCI6bnVsbH0sImlkRm9ybU9sZFZlbnRhIjpudWxsLCJpZEZvcm11bGFyaW8iOjEsImZvcm11bGFyaW8iOnsiZmVjaGFfc29saWNpdHVkIjoiMTMvOS8yMDE2IiwiZXN0YV9pbnRlcmVzYWRvIjp0cnVlLCJtb3Rpdm9fZGVzaW50ZXJlcyI6IiIsImNvbWVudGFyaW9zIjoiIiwidGl0dWxhcl9lbmNvbnRyYWRvIjp0cnVlLCJjb25zZWN1dGl2byI6bnVsbCwibm9tYnJlIjoiUkMgQ0sgRkpMR0sgSEk1R0siLCJhcGVsbGlkb19wYXRlcm5vIjoiWEpDSkZJIEZVIiwiYXBlbGxpZG9fbWF0ZXJubyI6IlZaIFZEREpGUFUiLCJmb3JtYV9wYWdvIjp0cnVlLCJmaW5hbmNpZXJhIjp0cnVlLCJudW1fc29saWNpdHVkIjoiMjM0NDU1IiwiZm90b19jb250cmF0byI6IiIsImZvdG9fYXZpc29wcml2YWNpZGFkIjoiIiwiZm90b19pZGVudGlmaWNhY2lvbiI6IiIsImZvdG9fY29tcHJvYmFudGUiOiIiLCJmb3RvX3NvbGljaXR1ZCI6IiIsImZvdG9fcGFnYXJlIjoiIiwiZm90b3MiOltdLCJpZCI6MX0sImlkVHlwZUZvcm0iOiIxIiwibm9tYnJlX2NsaWVudGUiOm51bGwsImFwZWxsaWRvcF9jbGllbnRlIjpudWxsLCJhcGVsbGlkb21fY2xpZW50ZSI6bnVsbCwiZGlyZWNjacOzbiI6IkVTUEFDSU8gQ1VNQlJFUyAjNDI4IENvbC4gRVNQQUNJTyBDVU1CUkVTLCBKVUxJTyBWRVJORSAsICBFbnRyZSBILiBHLiBXRUxMUyB5IEpVTElPIFZFUk5FICBFU1BBQ0lPIENVTUJSRVMsIE51ZXZvIExlw7NuIiwib2JzZXJ2YWNpb25lcyI6IiIsInRpcG9fcGVyZmlsIjoicGxvbWVyb192ZW50YV9jZW5zb19pbnN0YWxhY2lvbiIsImlkVXN1YXJpbyI6IjEwIiwidGlwb19lc3RhdHVzIjoiQXNpZ25hZGEiLCJpZF9lc3RhdHVzIjoiNCIsImlkQXNpZ25hY2lvbiI6NDg3LCJtb3Rpdm9zX3JlY2hhem8iOm51bGwsImNvbnNlY3V0aXZvIjpudWxsLCJsYXRpdHVkIjoyNS42NzE0MDk1OSwibG9uZ2l0dWQiOi0xMDAuMzQ1MjUxNjIsImFsbG95X2lkIjoiOTE3MTIzYWUtM2QwYy1hOTVkLWY5ODAtMzE1OGY0ZGFjNjExIn0=");
//exit();


/**CONSTANTES A UTILIZAR **/
$STATUS_EXITO = "OK";
$STATUS_FALLO = "ERROR";
$CODE_EXITOSO = "200";
$CODE_FALLO = "500";

$directorioImagenesSubida = "../../uploads/";
$extensionPng = ".png";
$tipoDeMIME = "image/png";

/***CONSTANTES TIPOS DE FORMULARIO****/

$FORMULARIO_TIPO_CENSO = 0;
$FORMULARIO_TIPO_VENTA = 1;
$FORMULARIO_TIPO_PLOMERO = 2;
$FORMULARIO_TIPO_INSTALACION = 3;
$FORMULARIO_TIPO_SEGUNDA_VENTA = 4;


if (isset($_POST["token"]) && isset($_POST["solicitud"])) {
    $DB = new DAO();
    $conn = $DB->getConnect();

    $tokenBase64 = $_POST["token"];
    $solicitudBase64 = $_POST["solicitud"];
    $token = base64_decode($tokenBase64);
    list($idUsuario, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
    /**PRIMERO CORROBORAMOS QUE EL ID EN EL TOKEN QUE ENVIA EL USUARIO ESTE REGISTRADO EN EL SISTEMA****/
    $responseToken = compararUsuarioToken($conn, $idUsuario);

	 //  grabarLog($responseToken["code"]);
	   
    /**SI FUE EXITOSO LA BUSQUEDA DEL ID DEL TOKEN CONTINUAMOS CON DESCIFRAR LA SOLICITUD PARA HACERLA UN OBJETO LEEIBLE**/
    if ($responseToken["code"] == $CODE_EXITOSO) {
        $solicitudJson = base64_decode($solicitudBase64);
        $solicitud = (array)json_decode($solicitudJson);

		 grabarLog($solicitudJson);
		 
        $idSolicitudMovil = $solicitud["id"];
        $idAsignacion = $solicitud["idAsignacion"];
        $fechaDeAsignacion = $solicitud["fecha_asignacion"];
        $fechaAgendada = $solicitud["fecha_agendada"];
        $historico = $solicitud["historico"];
        $estatus = $solicitud["estatus"];
        $estatusCompletoDesdeMovil = $solicitud["estatus_concluido"];

        $nombreDelCliente = $solicitud["nombre_cliente"];
        $direcciones = $solicitud["dirección"];
        $observaciones = $solicitud["observaciones"];
        $tipoDePerfil = $solicitud["tipo_perfil"];
        $tipoDeEstatus = $solicitud["tipo_estatus"];
        $tipoDeFormulario = $solicitud["idTypeForm"];
        $idFormulario = $solicitud["idFormulario"];
        $latitud = (double)$solicitud["latitud"];
        if (!is_numeric($latitud)) {
            $latitud = 0.00;
        }
        $longitud = (double)$solicitud["longitud"];
        if (!is_numeric($longitud)) {
            $longitud = 0.00;
        }
        

        /**FORMULARIO QUE ES EL ***/
        $formularioArray = (array)$solicitud["formulario"];

        /**CALLEJERO QUE ES EL ***/
        $callejeroArray = (array)$solicitud["callejero"];


        /***SI EL CALLEJERO VIENE COMPLETO ENTONCES OBTENEMOS SU INFORMACION***/
        if (isset($callejeroArray) && $callejeroArray != null) {
            $idMunicipioCallejero = $callejeroArray["municipio"];
            $idSolicitudCallejero = $callejeroArray["idSolicitud"];
            $coloniaCallejero = $callejeroArray["colonia"];
            $streetCallejero = $callejeroArray["calle"];
            $betweenStreetsCallejero = $callejeroArray["entrecalles"];
            $innerNumberCallejero = $callejeroArray["numero"];
            if ($innerNumberCallejero == NULL) {
                $innerNumberCallejero = 0;
            }
            $nseCallejero = $callejeroArray["nse"];
            $newStreetCallejero = $callejeroArray["direccionNueva"];
            $streetsCallejero = $callejeroArray["entrecallesNueva"];
            $coloniaTypeCallejero = $callejeroArray["exclusividad"];
            $marketTypeCallejero = $callejeroArray["tipoMercado"];
            $latitudeCallejero = (double)$callejeroArray["latitud"];
            if (!is_numeric($latitudeCallejero)) {
                $latitudeCallejero = 0.00;
            }
            $longitudeCallejero = (double)$callejeroArray["longitud"];
            if (!is_numeric($longitudeCallejero)) {
                $longitudeCallejero = 0.00;
            }
        }

        ////grabarLog(json_encode($solicitud));
        /**TOMAMOS EL ESTATUS QUE NOS ENVIA EL MOVIL, PARA TRANSFORMARLO EN UN CODIGO DE ESTATUS A INSERTAR
         * EN LA BASE DE DATOS DEPENDIENDO PARA EL WORKFLOW***/
        $estatusWorkFlow = validarEstatusWorkFlow($estatusCompletoDesdeMovil);
        $tipoDeReporte = validarReportTypeDependiendoDelTipoDeFormulario($tipoDeFormulario);

        /**AHORA DEPENDIENDO DEL TIPO DE FORMULARIO ES COMO EJECUTAMOS LA RUTINA DE CADA UNO***/
        if ($tipoDeFormulario == $FORMULARIO_TIPO_CENSO) {

            /**OBTENEMOS LOS VALORES DEL FORMULARIO DE CENSO EN CASO DE QUE VENGA***/
            $lote_type = $formularioArray["tipo_lote"];
            $requestNum = $formularioArray["id"];
            $homeLevel = $formularioArray["nivel_vivienda"];
            $socialLevel = $formularioArray["nivel_socioeconomico"];
            $area = $formularioArray["giro"];
            $acometida = $formularioArray["acometida"];
            $arrayFotografias = $formularioArray["fotos"];
            $comments = $formularioArray["observaciones"];
            $color = $formularioArray["color_tapon"];
            $measurer = $formularioArray["medidor"];
            $measurerType = $formularioArray["tipo_medidor"];
            $measurerSerialNumber = $formularioArray["num_serie"];
            $measurerBrand = $formularioArray["marca"];
            $niple = $formularioArray["niple_decorte"];

            $responseArrayInsertarFormularioTipoCenso=array();
            $responseArrayInsertarFormularioTipoCenso = insertarFormularioTipoCenso($conn,
                $idUsuario, $idAsignacion, $estatusWorkFlow, $coloniaCallejero, $streetCallejero,
                $betweenStreetsCallejero, $innerNumberCallejero, $newStreetCallejero, $nseCallejero,$coloniaTypeCallejero,
                $marketTypeCallejero, 1, 1, $idMunicipioCallejero, 0,
                $tipoDeReporte, $idUsuario, $idFormulario, $latitud,
                $longitudeCallejero,
                $lote_type, $homeLevel, $socialLevel, $area, $acometida,
                $comments, $color, $measurer, $measurerBrand, $measurerType,
                $measurerSerialNumber, $niple
            );

            if ($responseArrayInsertarFormularioTipoCenso["code"] == $CODE_EXITOSO) {
                /**SACAMOS EL ID REPORTE QUE GENERO LA CONSULTA**/
                $idFormCensusGenerado= $responseArrayInsertarFormularioTipoCenso["idFormCensusGenerado"];
                /***AHORA DEBEMOS INSERTAR LA RELACION DE LAS FOTOGRAFIAS CON EL CENSO ESTO
                 * RECORRIENDO EL ARREGLO DE FOTOGRAFIAS***/
                $sizeFotografias = sizeof($arrayFotografias);
                $i = 0;
                for ($i = 0; $i < $sizeFotografias; $i++) {

                    $fotografia = $arrayFotografias[$i];
                    $responseFotografia = insertarRelacionFotografiasDelCenso($conn, $idFormCensusGenerado, $fotografia,$i,$sizeFotografias);
                }
                /**SI LAS FOTOGRAFIAS SE INSERTARON CON EXITO ENTONCES IMPRIMIMOS LA RESPUESTA DE LA INSERCION
                 * SATISFACTORIA DEL CENSO***/
                if ($responseFotografia["code"] == $CODE_EXITOSO) {
                    echo json_encode($responseArrayInsertarFormularioTipoCenso);
                } else {
                    /**SI ALGO OCURRIO MAL, ENTONCES DE CUALQUIER MANERA REALIZAMOS LA MUESTRA
                     * DE LA INSERCION DEL FORMULARIO TIPO CENSO**/
                    echo json_encode($responseArrayInsertarFormularioTipoCenso);
                }
            } else {
                echo json_encode($responseArrayInsertarFormularioTipoCenso);
            }
        } else if ($tipoDeFormulario == $FORMULARIO_TIPO_VENTA) {

            /**OBTENEMOS LOS VALORES DEL FORMULARIO DE VENTA EN CASO DE QUE VENGA***/
            //$lote_type = $formularioArray["fecha_solicitud"];
            $inUninteresting = $formularioArray["esta_interesado"];
            $inCommentsUninteresting = $formularioArray["motivo_desinteres"];
            $inComments = $formularioArray["comentarios"];
            $inOwner = $formularioArray["titular_encontrado"];
            $consecutive = $formularioArray["consecutivo"];
            $inClientName = $formularioArray["nombre"];
            $inClientLastName1 = $formularioArray["apellido_paterno"];
            $inClientLastName2 = $formularioArray["apellido_materno"];
            $inPayment = $formularioArray["forma_pago"];
            $inFinancialService = $formularioArray["financiera"];
            $inRequestNumber = $formularioArray["num_solicitud"];
            $arrayFotografias = $formularioArray["fotos"];
            $inAgreementNumber = $solicitud["num_contrato"];
           // $niple = $formularioArray["id"];

            if($inAgreementNumber==null){
                $inAgreementNumber="-";
            }

            if($inUninteresting==false){
                $inUninteresting='false';
            }else if($inUninteresting==true){
                $inUninteresting='true';
            }
            /**CUANDO TENEMOS EL MOTIVO DE DESINTERES EN TRUE, DEBEMOS TOMAR LOS COMENTARIOS
             ***/
            $responseArrayInsertarFormularioTipoVentaUno=array();
            $responseArrayInsertarFormularioTipoVentaUno = insertarFormularioTipoVentaUno($conn,
                $idUsuario,$idAsignacion,$estatusWorkFlow,$tipoDeReporte,
                $inAgreementNumber, $inClientName, $inClientLastName1, $inClientLastName2, $coloniaCallejero,
                $streetCallejero, $betweenStreetsCallejero, $innerNumberCallejero, $innerNumberCallejero, $nseCallejero,
                $newStreetCallejero, $streetsCallejero, $coloniaTypeCallejero, $marketTypeCallejero, 1,
                1, $idMunicipioCallejero, 0, $idFormulario,$latitud,
                $longitud,
                1, $inUninteresting,$inCommentsUninteresting, $inComments, $inOwner, $inClientName,
                $inClientLastName1, $inClientLastName2, $inPayment, $inFinancialService,  $inRequestNumber,
                1,$idSolicitudMovil
            );

            if ($responseArrayInsertarFormularioTipoVentaUno["code"] == $CODE_EXITOSO) {
                /**SACAMOS EL ID REPORTE QUE GENERO LA CONSULTA**/
                $idFormSellsGenerado= $responseArrayInsertarFormularioTipoVentaUno["idFormSellsGenerado"];
                /***AHORA DEBEMOS INSERTAR LA RELACION DE LAS FOTOGRAFIAS CON LA VENTA ESTO
                 * RECORRIENDO EL ARREGLO DE FOTOGRAFIAS***/
                $sizeFotografias = sizeof($arrayFotografias);
                $i = 0;
                for ($i = 0; $i < $sizeFotografias; $i++) {
                    $fotografia = $arrayFotografias[$i];
                    $responseFotografia = insertarRelacionFotografiasDeLaVenta($conn, $idFormSellsGenerado, $fotografia,$i,$sizeFotografias);
                }

                /**SI LAS FOTOGRAFIAS SE INSERTARON CON EXITO ENTONCES IMPRIMIMOS LA RESPUESTA DE LA INSERCION
                 * SATISFACTORIA DEL CENSO***/
                if ($responseFotografia["code"] == $CODE_EXITOSO) {
                    echo json_encode($responseArrayInsertarFormularioTipoVentaUno);                    
                } else {

                    /**SI ALGO OCURRIO MAL, ENTONCES DE CUALQUIER MANERA REALIZAMOS LA MUESTRA
                     * DE LA INSERCION DEL FORMULARIO TIPO CENSO**/
                    echo json_encode($responseArrayInsertarFormularioTipoVentaUno);
                }
            } else {
                echo json_encode($responseArrayInsertarFormularioTipoVentaUno);
            }
            error_log(json_encode($solicitud));
        } else if ($tipoDeFormulario == $FORMULARIO_TIPO_PLOMERO) {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $name = $formularioArray["nombre"];
            $lastName = $formularioArray["apellido_paterno"];
            $request = $formularioArray["num_solicitud"];
            $documentNumber = $formularioArray["num_solicitud"];
            if ($ri == null || $ri == 0 || $ri == "") {
                $ri = 0;
            } else {
                $ri = $formularioArray["ri"];
            }
            $tapon = $formularioArray["color_tapon"];
            if ($tapon == true) {
                $tapon = 1;
            } else {
                $tapon = 0;
            }
            $dictamen = $formularioArray["num_dictamen"];
            $preassureFalls = $formularioArray["calculos"];
            $diagrama = $formularioArray["diagrama"];
            $comments = $formularioArray["observaciones"];
            $newPipe = $formularioArray["serequiere_tuberia"];
            if ($newPipe == true) {
                $newPipe = 1;
            } else {
                $newPipe = 0;
            }
            $pipesCount = $formularioArray["num_tomas"];
            $ph = $formularioArray["resultado_ph"];
            $fotos = $formularioArray["fotos"];
            $diagram = $formularioArray["diagrama"];
            $latitude = $solicitud["latitud"];
            $longitude = $solicitud["longitud"];
            $reportID=intval($solicitud['idAsignacion']);
            $id=intval($solicitud['idUsuario']);
            $IDSTatus=intval($solicitud['id_estatus']);
            $consecutivo=intval($solicitud['consecutivo']);
            $sizeFotografias = sizeof($arrayFotografias);
            $i = 0;
            $createPlumberFormSQL ="INSERT INTO `form_plumber`(`consecutive`,`name`, `lastName`, `request`, `tapon`, `documentNumber`, `diagram`, `comments`, `newPipe`, `ph`, `pipesCount`, `idStatus`,`latitude`, `longitude`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?, NOW(), NOW(), 1);";
            if ($stmt = $conn->prepare($createPlumberFormSQL)) {
                $stmt->bind_param("isssisssiiiddd", $consecutivo, $name, $lastName, $request, $tapon, $dictamen, $diagram, $comments, $newPipe, $ph, $pipesCount, $IDSTatus,$latitude, $longitude);
                if ($stmt->execute()) {
                    $idPlumberForm = $stmt->insert_id;//id del formulario plomero
                    if(count($fotos) > 0){
                        foreach ($fotos as $imgID) {
                            if ($imgID != null && $imgID != 'null' && $imgID != "" && $imgID && 'undefined') {
                                $insertPlumberPipesPhoto ="INSERT INTO `form_plumber_multimedia`(`idFormPlumber`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());";
                                if ($stmtFotos = $conn->prepare($insertPlumberPipesPhoto)) {
                                    $stmtFotos->bind_param("ii", $idPlumberForm, $imgID);
                                    if (!$stmtFotos->execute()) {
                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] = "Falla en el enlace de la Imagen de Tuberias con el Reporte de Plomero: " . $conn->error;
                                        echo json_encode($response);
                                    }
                                }else{
                                    $res='Error en insert de fotos Plomero '.$conn->error;
                                }   
                            }
                        }
                    }
                    if (count($preassureFalls) > 0) {
                        foreach ($preassureFalls as $key) {
                            $data = (array)$key;
                            $createPlumberDetails = "INSERT INTO `form_plumber_details`(`path`, `distance`, `pipe`, `fall`, `idFormPlumber`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());";
                            if ($stmtDetails = $conn->prepare($createPlumberDetails)) {
                                $stmtDetails->bind_param("ssssi", $data["tramo"], $data["distancia"], $data["tuberia"], $data["caida"], $idPlumberForm);
                                if (!$stmtDetails->execute()) {
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] = "Falla en el registro de material: " . $conn->error;
                                    echo json_encode($response);
                                }   
                            }else{
                                $res='Error en insert de detalles Plomero '.$conn->error;
                            }
                        }
                    }
                    $reportEmployeeForm = "INSERT INTO `report_employee_form`(`idReport`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());";
                    if ($reportEmployeeFormSTMT = $conn->prepare($reportEmployeeForm)) {
                        $reportEmployeeFormSTMT->bind_param("iii", $reportID, $id, $idPlumberForm);
                        $reportEmployeeFormSTMT->execute();
                        $idWorkflow = 1;
                        if ($statusType == "Completo") {
                            $idStatus = 3;
                        } else if ($statusType == "Prendiente Reagendado") {
                            $idStatus = 7;
                        } else {
                            $idStatus = 4;
                        }
                    }else{
                        $res='Error en insert de report_employee_form Plomero '.$conn->error;
                    }
                    $searchReports = $conn->prepare("SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned FROM report AS RP WHERE RP.`id` = ?;");
                    $searchReports->bind_param("i", $reportID);
                    if ($searchReports->execute()) {
                        $searchReports->store_result();
                        $searchReports->bind_result($idReport, $employee, $creator, $reportType, $employeeAssigned);
                        $searchReports->fetch();
                        $insertStatusReport = "INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);";
                        if ($insertStatusReportSTMT = $conn->prepare($insertStatusReport)) {
                            $insertStatusReportSTMT->bind_param("iii", $idWorkflow, $idStatus, $reportID);
                            $insertStatusReportSTMT->execute();
                            $response["status"] = "OK";
                            $response["code"] = "200";
                            $response["response"] = "Creacion de Reporte para Plomero de Manera Exitosa.";
                            $response["reportId"] = $idPlumberForm;
                            echo json_encode($response);
                        }

                        $idStatusPH = 31;
                        $updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
                        $updateEstatusContrato->bind_param("iii", $idStatusPH, $employee, $reportID);
                        $updateEstatusContrato->execute();
                        $conn->close;
                        $idStatus=3;
                        $updateReportAssignedStatus = $conn->prepare("UPDATE `report_AssignedStatus` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
                        $updateReportAssignedStatus->bind_param('ii', $idStatus, $reportID);

                        if($updateReportAssignedStatus->execute()) {

                        }
                    }
                }
            }else{
                $conn->error;
            }
        } else if ($tipoDeFormulario == $FORMULARIO_TIPO_SEGUNDA_VENTA) {
        } else if ($tipoDeFormulario == $FORMULARIO_TIPO_INSTALACION) {
        }
    } else {
        echo json_encode($responseToken);
    }
} else {
    $response["status"] = $GLOBALS[STATUS_FALLO];
    $response["code"] = $GLOBALS[CODE_FALLO];
    $response["response"] = "DATOS INGRESADOS ERRONEAMENTE";
    echo json_encode($response);
}

function validarEstatusWorkFlow($estatusCompletoDesdeMovil)
{
    /***CONSTANTES PARA WORKFLOW CODIGOS TEXTO*****/
    $WORKFLOW_POR_ASIGNAR_TEXTO = "Por asignar ";
    $WORKFLOW_PENDIENTE_TEXTO = "Pendiente";
    $WORKFLOW_COMPLETO_TEXTO = "Completado";
    $WORKFLOW_EN_PROCESO_TEXTO = "En proceso";
    $WORKFLOW_REEAGENDADO_TEXTO = "Pendiente Reagendado";


    /***CONSTANTES PARA WORKFLOW CODIGOS*****/
    $WORKFLOW_POR_ASIGNAR = 1;
    $WORKFLOW_PENDIENTE = 2;
    $WORKFLOW_COMPLETO = 3;
    $WORKFLOW_EN_PROCESO = 4;
    $WORKFLOW_REEAGENDADO = 7;

    $workflowCode = "";

    if ($estatusCompletoDesdeMovil == $WORKFLOW_POR_ASIGNAR_TEXTO) {
        $workflowCode = $WORKFLOW_POR_ASIGNAR;
    } else if ($estatusCompletoDesdeMovil == $WORKFLOW_PENDIENTE_TEXTO) {
        $workflowCode = $WORKFLOW_PENDIENTE;
    } else if ($estatusCompletoDesdeMovil == $WORKFLOW_COMPLETO_TEXTO) {
        $workflowCode = $WORKFLOW_COMPLETO;
    } else if ($estatusCompletoDesdeMovil == $WORKFLOW_EN_PROCESO_TEXTO) {
        $workflowCode = $WORKFLOW_EN_PROCESO;
    } else if ($estatusCompletoDesdeMovil == $WORKFLOW_REEAGENDADO_TEXTO) {
        $workflowCode = $WORKFLOW_REEAGENDADO;
    }

    return $workflowCode;
}
function validarReportTypeDependiendoDelTipoDeFormulario($tipoDeFormulario)
{
    if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_CENSO]) {
        $reportType = 1;
    } else if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_VENTA]) {
        $reportType = 2;
    } else if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_PLOMERO]) {
        $reportType = 3;
    } else if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_INSTALACION]) {
        $reportType = 4;
    } else if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_SEGUNDA_VENTA]) {
        $reportType = 5;
    }
    return $reportType;
}

function compararUsuarioToken($conn, $inIdUsuario)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $responseArray = array();
    $status = "";
    $code = "";
    $response = "";

    $stmtCompararUsuarioToken = $conn->prepare("call spCompararUsuarioToken(?);");
    mysqli_stmt_bind_param($stmtCompararUsuarioToken, 'i', $inIdUsuario);
    if ($stmtCompararUsuarioToken->execute()) {
        $stmtCompararUsuarioToken->store_result();
        $stmtCompararUsuarioToken->bind_result($status, $code, $response);

        if ($stmtCompararUsuarioToken->fetch()) {
            $responseArray["status"] = $status;
            $responseArray["code"] = $code;
            $responseArray["response"] = $response;

        } else {
            $responseArray["status"] = $GLOBALS[STATUS_FALLO];
            $responseArray["code"] = $GLOBALS[CODE_FALLO];
            $responseArray["response"] = "Ocurrio un problema al momento de obtener el token";
        }
    }
    /**CERRAMOS EL RESULT DE CompararUsuarioToken***/
    $stmtCompararUsuarioToken->close();
    /***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
     * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
     * http://stackoverflow.com/a/10745472 ***/
    $conn->next_result();
    return $responseArray;
}

function insertarFormularioTipoCenso($conn,
                                     $inIdUsuario, $inIdReporte, $inWorkFlowStatus, $inColonia, $inStreet,
                                     $inBetweenStreets, $inInnerNumber, $inNewStreet, $inNse, $inColoniaType,
                                     $inMarketType, $inIdCountry, $inIdState, $inIdCity, $inCp,
                                     $inIdReportType, $inIdUserCreator, $inIdFormulario, $inDot_latitude,$inDot_longitude,
                                     $inLote, $inHouseStatus, $inNivel, $inGiro, $inAcometida,
                                     $inObservacion, $inTapon, $inMedidor, $inMarca, $inTipo,
                                     $inNoSerie, $inNiple)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $responseArray = array();
    $status = "";
    $code = "";
    $response = "";
    $idFormCensusGenerado="";
    $idReporteGenerado = "";

    $stmtinsertarFormularioTipoCenso = $conn->prepare("call spInsertarFormularioCenso(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmtinsertarFormularioTipoCenso, 'iiissssssssiisiiisddssssisiisssi',
        $inIdUsuario, $inIdReporte, $inWorkFlowStatus, $inColonia, $inStreet,
        $inBetweenStreets, $inInnerNumber, $inNewStreet, $inNse, $inColoniaType,
        $inMarketType, $inIdCountry, $inIdState, $inIdCity, $inCp,
        $inIdReportType, $inIdUserCreator, $inIdFormulario, $inDot_latitude,$inDot_longitude,
        $inLote, $inHouseStatus, $inNivel, $inGiro, $inAcometida,
        $inObservacion, $inTapon, $inMedidor, $inMarca, $inTipo,
        $inNoSerie, $inNiple
    );

    if ($stmtinsertarFormularioTipoCenso->execute()) {
        $stmtinsertarFormularioTipoCenso->store_result();
        $stmtinsertarFormularioTipoCenso->bind_result($status, $code, $response,$idFormCensusGenerado,$idReporteGenerado);

        if ($stmtinsertarFormularioTipoCenso->fetch()) {
            $responseArray["status"] = $status;
            $responseArray["code"] = $code;
            $responseArray["response"] = $response;
            $responseArray["idFormCensusGenerado"] = $idFormCensusGenerado;
            $responseArray["ReporteGenerado"] = $idReporteGenerado;

        } else {
            $responseArray["status"] = $GLOBALS[STATUS_FALLO];
            $responseArray["code"] = $GLOBALS[CODE_FALLO];
            $responseArray["response"] = "Ocurrio un problema al momento de obtener el token";
            $responseArray["idFormCensusGenerado"] = "";
            $responseArray["ReporteGenerado"] = "";
        }
    }else{

    }
    //print_r($stmtinsertarFormularioTipoCenso);
    /**CERRAMOS EL RESULT DE CompararUsuarioToken***/
    $stmtinsertarFormularioTipoCenso->close();
    /***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
     * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
     * http://stackoverflow.com/a/10745472 ***/
    $conn->next_result();
    return $responseArray;
}

function insertarRelacionFotografiasDelCenso($conn, $idFormCensusGenerado, $idFotografia,$key,$sizeArrayFotografias)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $responseArray = array();
    $status = "";
    $code = "";
    $response = "";



    $stmtinsertarRelacionFotografiasDelCenso = $conn->prepare("call spInsertarRelacionFotografiasDelCenso(?,?);");
    mysqli_stmt_bind_param($stmtinsertarRelacionFotografiasDelCenso, 'ii', $idFormCensusGenerado, $idFotografia);
    if ($stmtinsertarRelacionFotografiasDelCenso->execute()) {
        $stmtinsertarRelacionFotografiasDelCenso->store_result();
        $stmtinsertarRelacionFotografiasDelCenso->bind_result($status, $code, $response);

        if ($stmtinsertarRelacionFotografiasDelCenso->fetch()) {
            $responseArray["status"] = $status;
            $responseArray["code"] = $code;
            $responseArray["response"] = $response;

        } else {
            $responseArray["status"] = $GLOBALS[STATUS_FALLO];
            $responseArray["code"] = $GLOBALS[CODE_FALLO];
            $responseArray["response"] = "Ocurrio un problema al momento de insertar la fotografia " . $idFotografia . "para el censo " . $idFormCensusGenerado;
        }


    }
    //print_r($stmtinsertarRelacionFotografiasDelCenso);
    /**CERRAMOS EL RESULT DE insertarRelacionFotografiasDelCenso***/
    $stmtinsertarRelacionFotografiasDelCenso->close();
   // print_r("idFotografias "+$idFotografia);
   //print_r("key "+$key);
   // print_r("sizeArrayFotografias "+$sizeArrayFotografias);

    /**GENERAMOS ESTA CONDICION PARA QUE HASTA QUE TERMINE EL RECORRIDO DEL FOREACH
     * ENTONCES SE CIERRE LA CONEXION A LA BASE DE DATOS
     * MIENTRAS NO SE TERMINE SEGUIMOS LLAMANDO AL SIGUIENTE STOREPROCEDURE***/
    if ($key < $sizeArrayFotografias - 1) {
        /***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
         * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
         * http://stackoverflow.com/a/10745472 ***/
        $conn->next_result();
    } else {
        return($response);

        $conn->close();
    }
}

function insertarFormularioTipoVentaUno($conn,
                                     $idUsuario,$idAsignacion,$estatusWorkFlow,$inReportType,
                                     $inAgreementNumber, $inClientName, $inClientLastName1, $inClientLastName2, $inColonia,
                                     $inStreet, $inBetweenStreets, $innerNumber, $inOutterNumber, $inNse,
                                     $inNewStreet, $inStreets, $inColoniaType, $inMarketType, $inIdCountry,
                                     $inIdState, $inIdCity, $inCp, $inIdFormulario,$inDot_latitude,
                                     $inDot_longitude,
                                     $inProspect, $inUninteresting,$inCommentsUninteresting, $inComments, $inOwner, $inName,
                                     $inLastName, $inLastNameOp, $inPayment, $inFinancialService,  $inRequestNumber,
                                     $inMmeeting,$idSolicitudMovil)
{

    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $responseArray = array();
    $status = "";
    $code = "";
    $response = "";
    $idFormSellsGenerado="";
    $idReporteGenerado = "";

    $stmtinsertarFormularioTipoVentaUno = $conn->prepare("call spInsertarFormularioVentaUno(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmtinsertarFormularioTipoVentaUno, 'iiiissssssssssssssiisiiddisssssssssssi',
        $idUsuario,$idAsignacion,$estatusWorkFlow,$inReportType,
        $inAgreementNumber, $inClientName, $inClientLastName1, $inClientLastName2, $inColonia,
        $inStreet, $inBetweenStreets, $innerNumber, $inOutterNumber, $inNse,
        $inNewStreet, $inStreets, $inColoniaType, $inMarketType, $inIdCountry,
        $inIdState, $inIdCity, $inCp, $inIdFormulario,$inDot_latitude,
        $inDot_longitude,
        $inProspect, $inUninteresting,$inCommentsUninteresting, $inComments, $inOwner, $inName,
        $inLastName, $inLastNameOp, $inPayment, $inFinancialService,  $inRequestNumber,
        $inMmeeting,$idSolicitudMovil
    );

    if ($stmtinsertarFormularioTipoVentaUno->execute()) {
        $stmtinsertarFormularioTipoVentaUno->store_result();
        $stmtinsertarFormularioTipoVentaUno->bind_result($status, $code, $response,$idFormSellsGenerado,$idReporteGenerado);

        if ($stmtinsertarFormularioTipoVentaUno->fetch()) {
            $responseArray["status"] = $status;
            $responseArray["code"] = $code;
            $responseArray["response"] = $response;
            $responseArray["idFormSellsGenerado"] = $idFormSellsGenerado;
            $responseArray["ReporteGenerado"] = $idReporteGenerado;
            $responseArray["RespuestaActPlom"] = validaEmpleadoTipoPlomero($idUsuario, $idReporteGenerado, $inAgreementNumber);
            //$validarPlomero=validaEmpleadoTipoPlomero($conn,$idUsuario, $idReporteGenerado);
        } else {
            $responseArray["status"] = $GLOBALS[STATUS_FALLO];
            $responseArray["code"] = $GLOBALS[CODE_FALLO];
            $responseArray["response"] = "Ocurrio un problema al momento de obtener el token";
            $responseArray["idFormSellsGenerado"] = "";
            $responseArray["ReporteGenerado"] = "";
        }
    }else{

    }
    //print_r($stmtinsertarFormularioTipoVentaUno);
    ////grabarLog(json_encode($responseArray));
    /**CERRAMOS EL RESULT DE CompararUsuarioToken***/
    $stmtinsertarFormularioTipoVentaUno->close();
    /***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
     * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
     * http://stackoverflow.com/a/10745472 ***/
    $conn->next_result();
    return $responseArray;
}
function validaEmpleadoTipoPlomero($userID, $idReporte, $inAgreementNumber)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    if ($userID != "" && $idReporte != "") {
        $queryEncontrarPlomero="SELECT EMP.id
                                FROM user AS USAG
                                INNER JOIN agency AS AG ON USAG.id = AG.idUser
                                INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency
                                INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id
                                INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
                                INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
                                WHERE 0=0 AND
                                ((PRF.id = 3) OR 
                                (PRF.id = 6) OR 
                                (PRF.id = 7) OR 
                                (PRF.id = 8)) AND
                                USEMP.id=?";
        $plumber=3;
        $employeesAssigned=0;
        $error='';
        $log='userID '.$userID.' idReporte'.$idReporte;
        //grabarLog($log);
        if ($stmt = $conn->prepare($queryEncontrarPlomero)) {
            $stmt->bind_param("i", $userID);
            if ($stmt->execute()) {
                $stmt->store_result();
                $stmt->bind_result($idUser);
                if ($stmt->fetch()) {
                    //grabarLog($idUser);
                    if ($idUser != '') {
                        $querySmt="UPDATE report as RP SET RP.agreementNumber=?, RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? 
                                   WHERE RP.`id` = ?";
                        //var_dump($conn->prepare($querySmt));
                        if ($stmtUpdate = $conn->prepare($querySmt)) {
                            $stmtUpdate->bind_param("iiisi", $inAgreementNumber,$idUser, $plumber, $employeesAssigned, $idReporte);
                            //$stmtUpdate->execute();
                            if ($stmtUpdate->execute()) {
                                /*,id,idReport,idStatus,notes,meeting,created_at,updated_at
                                */
                                $createReport = $conn->prepare("INSERT INTO report_AssignedStatus(idReport,idStatus,created_at,updated_at) VALUES(?, ?, NOW(), NOW());");
                                $status=4;
                                $createReport->bind_param("ii", $idReporte, $status);
                                //$createReport->execute();
                                if ($createReport->execute()) {
                                    $idStatusPH = 30;
                                    $updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
                                    $updateEstatusContrato->bind_param("iii", $idStatusPH, $idUser, $idReporte);
                                    $updateEstatusContrato->execute();
                                    $result["status"] = "OK";
                                    $result["code"] = "200";
                                    $result["result"] = "Reportes Asignados Exitosamente";
                                    $error=$result;
                                }
                            }
                        }else{
                            //printf("Errormessage: %s\n", $conn->error);
                            $error=$conn->error;
                        }
                    }else{
                        $error="El Vendedor no tiene perfil Plomero";
                    }
                }
            }
        }else{
            //printf("Errormessage: %s\n", $conn->error);
            $error=$conn->error;
        }
        return $error;
    }
    $conn->close();
    //validamos si el empleado asignado al formulario tipoVenta es plomero si no es plomero asignamos un plomero
}
function insertarRelacionFotografiasDeLaVenta($conn, $idFormSellsGenerado, $idFotografia,$key,$sizeArrayFotografias)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $responseArray = array();
    $status = "";
    $code = "";
    $response = "";

    $stmtinsertarRelacionFotografiasDeLaVenta = $conn->prepare("call spInsertarRelacionFotografiasDeLaVenta(?,?);");
    mysqli_stmt_bind_param($stmtinsertarRelacionFotografiasDeLaVenta, 'ii', $idFormSellsGenerado, $idFotografia);
    if ($stmtinsertarRelacionFotografiasDeLaVenta->execute()) {
        $stmtinsertarRelacionFotografiasDeLaVenta->store_result();
        $stmtinsertarRelacionFotografiasDeLaVenta->bind_result($status, $code, $response);

        if ($stmtinsertarRelacionFotografiasDeLaVenta->fetch()) {
            $responseArray["status"] = $status;
            $responseArray["code"] = $code;
            $responseArray["response"] = $response;

        } else {
            $responseArray["status"] = $GLOBALS[STATUS_FALLO];
            $responseArray["code"] = $GLOBALS[CODE_FALLO];
            $responseArray["response"] = "Ocurrio un problema al momento de insertar la fotografia " . $idFotografia . "para el censo " . $idFormCensusGenerado;
        }


    }
    //print_r($stmtinsertarRelacionFotografiasDeLaVenta);
    /**CERRAMOS EL RESULT DE insertarRelacionFotografiasDelCenso***/
    $stmtinsertarRelacionFotografiasDeLaVenta->close();
    // print_r("idFotografias "+$idFotografia);
    //print_r("key "+$key);
    // print_r("sizeArrayFotografias "+$sizeArrayFotografias);

    /**GENERAMOS ESTA CONDICION PARA QUE HASTA QUE TERMINE EL RECORRIDO DEL FOREACH
     * ENTONCES SE CIERRE LA CONEXION A LA BASE DE DATOS
     * MIENTRAS NO SE TERMINE SEGUIMOS LLAMANDO AL SIGUIENTE STOREPROCEDURE***/
    if ($key < $sizeArrayFotografias - 1) {
        /***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
         * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
         * http://stackoverflow.com/a/10745472 ***/
        $conn->next_result();
    } else {
        return($response);

        $conn->close();
    }
}


function grabarLog($logInfo)
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = "createFormV2.txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);

}