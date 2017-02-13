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


if (
    isset($_POST["token"])
    && isset($_POST["solicitud"])
) {
    $DB = new DAO();
    $conn = $DB->getConnect();

    $tokenBase64 = $_POST["token"];
    $solicitudBase64 = $_POST["solicitud"];
    $token = base64_decode($tokenBase64);
    list($idUsuario, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
    /**PRIMERO CORROBORAMOS QUE EL ID EN EL TOKEN QUE ENVIA EL USUARIO ESTE REGISTRADO EN EL SISTEMA****/
    $responseToken = compararUsuarioToken($conn, $idUsuario);

    /**SI FUE EXITOSO LA BUSQUEDA DEL ID DEL TOKEN CONTINUAMOS CON DESCIFRAR LA SOLICITUD PARA HACERLA UN OBJETO LEEIBLE**/
    if ($responseToken["code"] == $CODE_EXITOSO) {
        $solicitudJson = base64_decode($solicitudBase64);
        $solicitud = (array)json_decode($solicitudJson);

        grabarLog($solicitudJson);

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

        //grabarLog(json_encode($solicitud));
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
                1
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

        } else if ($tipoDeFormulario == $FORMULARIO_TIPO_PLOMERO) {
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
                                     $inMmeeting)
{

    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $responseArray = array();
    $status = "";
    $code = "";
    $response = "";
    $idFormSellsGenerado="";
    $idReporteGenerado = "";

    $stmtinsertarFormularioTipoVentaUno = $conn->prepare("call spInsertarFormularioVentaUno(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmtinsertarFormularioTipoVentaUno, 'iiiissssssssssssssiisiiddisssssssssss',
        $idUsuario,$idAsignacion,$estatusWorkFlow,$inReportType,
        $inAgreementNumber, $inClientName, $inClientLastName1, $inClientLastName2, $inColonia,
        $inStreet, $inBetweenStreets, $innerNumber, $inOutterNumber, $inNse,
        $inNewStreet, $inStreets, $inColoniaType, $inMarketType, $inIdCountry,
        $inIdState, $inIdCity, $inCp, $inIdFormulario,$inDot_latitude,
        $inDot_longitude,
        $inProspect, $inUninteresting,$inCommentsUninteresting, $inComments, $inOwner, $inName,
        $inLastName, $inLastNameOp, $inPayment, $inFinancialService,  $inRequestNumber,
        $inMmeeting
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
    grabarLog(json_encode($responseArray));
    /**CERRAMOS EL RESULT DE CompararUsuarioToken***/
    $stmtinsertarFormularioTipoVentaUno->close();
    /***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
     * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
     * http://stackoverflow.com/a/10745472 ***/
    $conn->next_result();
    return $responseArray;
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

