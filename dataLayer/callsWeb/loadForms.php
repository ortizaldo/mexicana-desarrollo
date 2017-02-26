<?php include_once "../DAO.php";
session_start();
$DB = new DAO();
$conn = $DB->getConnect();

/**TIPOS DE REPORTE**/
$TIPOS_DE_REPORTE_CENSO = "Censo";
$TIPOS_DE_REPORTE_VENTA = "Venta";
$TIPOS_DE_REPORTE_PLOMERO = "Plomero";
$TIPOS_DE_REPORTE_INSTALACION = "Instalacion";
$TIPOS_DE_REPORTE_SEGUNDA_VENTA = "Segunda Venta";

/**ESTATUS TEXTOS**/
$ESTATUS_TEXTO_POR_ASIGNAR = "POR ASIGNAR";
$ESTATUS_TEXTO_PENDIENTE = "PENDIENTE";
$ESTATUS_TEXTO_COMPLETO = "COMPLETO";
$ESTATUS_TEXTO_CAPTURA_COMPLETA = "CAPTURA COMPLETADA";
$ESTATUS_TEXTO_EN_PROCESO = "EN PROCESO";
$ESTATUS_TEXTO_PENDIENTE_VALIDACION = "PENDIENTE VALIDACION";
$ESTATUS_TEXTO_VALIDADO_POR_MEXICANA = "VALIDADO POR MEXICANA";
$ESTATUS_TEXTO_RECHAZADO_POR_MEXICANA = "VALIDACION RECHAZADA POR MEXICANA";
$ESTATUS_TEXTO_RECHAZADO_POR_AYOPSA = "VALIDACION RECHAZADA POR AYOPSA";
$ESTATUS_TEXTO_VALIDACIONES_COMPLETAS = "VALIDACIONES COMPLETAS";
$ESTATUS_TEXTO_REAGENDADA = "REAGENDADA";
$ESTATUS_TEXTO_CANCELADA = "CANCELADA";
$ESTATUS_TEXTO_EN_PROCESO_DE_VALIDACION_DE_VENTA = "EN PROCESO DE VALIDACION DE VENTA";
$ESTATUS_TEXTO_VALIDADO_POR_CREDITO = "VALIDADO POR AYOPSA";
$ESTATUS_TEXTO_VALIDADO_POR_CONTADO = "VALIDADO POR MEXICANA";
$ESTATUS_TEXTO_RECHAZADO = "RECHAZADO";
$ESTATUS_TEXTO_NO_VALIDO = "NO VALIDO";
$ESTATUS_TEXTO_PH_EN_PROCESO = "PH EN PROCESO";
$ESTATUS_TEXTO_PH_COMPLETA = "PH COMPLETA";
$ESTATUS_TEXTO_PH_REAGENDADA = "PH REAGENDADA";
$ESTATUS_TEXTO_PH_RECHAZADA = "PH RECHAZADA";
$ESTATUS_TEXTO_SEGUNDA_VENTA_EN_PROCESO = "SEGUNDA VENTA EN PROCESO";
$ESTATUS_TEXTO_SEGUNDA_VENTA_COMPLETA = "SEGUNDA VENTA COMPLETA";
$ESTATUS_TEXTO_INSTALACION_EN_PROCESO = "INSTALACION EN PROCESO";
$ESTATUS_TEXTO_INSTALACION_COMPLETADA = "INSTALACION COMPLETADA";
$ESTATUS_TEXTO_INSTALACION_RECHAZADA = "INSTALACION RECHAZADA";
$ESTATUS_TEXTO_INSTALACION_ANOMALIA = "ANOMALIA";
$ESTATUS_TEXTO_REPORTE_EN_PROCESO = "REPORTE EN PROCESO";
$ESTATUS_TEXTO_REPORTE_COMPLETO = "REPORTE COMPLETO";
$ESTATUS_TEXTO_REPORTE_RECHAZADO = "REPORTE RECHAZADO";
$ESTATUS_TEXTO_RECHAZADA = "RECHAZADA";
$ESTATUS_TEXTO_SEGUNDA_VENTA_REVISION="REVISION";
$ESTATUS_TEXTO_ENVIADO="INSTALACION ENVIADA";
$ESTATUS_TEXTO_DEPURADO="ELIMINADO";

/**ESTATUS DE VENTA**/
$ESTATUS_VENTA_POR_ASIGNAR = 1;
$ESTATUS_VENTA_PENDIENTE = 2;
$ESTATUS_VENTA_COMPLETA = 3;
$ESTATUS_VENTA_EN_PROCESO = 4;
$ESTATUS_VENTA_PENDIENTE_VALIDACION = 5;
$ESTATUS_VENTA_VALIDADO_POR_MEXICANA_DE_GAS = 6;
$ESTATUS_VENTA_VALIDADO_POR_CREDITO=21;
$ESTATUS_VENTA_REAGENDADO = 7;
$ESTATUS_VENTA_CANCELADA = 8;
$ESTATUS_VENTA_RECHAZADO = 9;
$ESTATUS_VENTA_VALIDACIONES_COMPLETAS = 10;
$ESTATUS_VENTA_DEPURADO = 11;


/**ESTATUS DE VALIDACION AYOPSA Y MEXICANA**/
$ESTATUS_MEXICANA_AYOPSA_EN_PROCESO = 20;
$ESTATUS_MEXICANA_AYOPSA_CREDITO = 21;
$ESTATUS_MEXICANA_AYOPSA_CONTADO = 22;
$ESTATUS_MEXICANA_AYOPSA_RECHAZADO = 23;
$ESTATUS_MEXICANA_AYOPSA_NO_VALIDA = 24;
$ESTATUS_MEXICANA_AYOPSA_CANCELADA = 25;

/**ESTATUS DE PH**/
$ESTATUS_PH_EN_PROCESO = 30;
$ESTATUS_PH_COMPLETO = 31;
$ESTATUS_PH_REAGENDADO = 32;
$ESTATUS_PH_RECHAZADO = 33;
$ESTATUS_PH_CANCELADO = 34;
$ESTATUS_PH_DEPURADO = 35;

/**ESTATUS DE SEGUNDA VENTA**/
$ESTATUS_SEGUNDA_VENTA_EN_PROCESO = 40;
$ESTATUS_SEGUNDA_VENTA_COMPLETA = 41;
$ESTATUS_SEGUNDA_VENTA_VALIDADA = 42;
$ESTATUS_SEGUNDA_VENTA_REVISION = 43;
$ESTATUS_SEGUNDA_VENTA_CANCELADA = 44;
$ESTATUS_SEGUNDA_VENTA_DEPURADO = 45;

/**ESTATUS DE INSTALACION**/
$ESTATUS_INSTALACION_EN_PROCESO = 50;
$ESTATUS_INSTALACION_COMPLETA = 51;
$ESTATUS_INSTALACION_REAGENDADA = 52;
$ESTATUS_INSTALACION_CANCELADA = 53;
$ESTATUS_INSTALACION_ENVIADA = 54;
$ESTATUS_INSTALACION_DEPURADO = 55;
$ESTATUS_INSTALACION_ANOMALIA = 56;

/**ESTATUS DE REPORTE***/
$ESTATUS_REPORTE_EN_PROCESO = 60;
$ESTATUS_REPORTE_COMPLETO = 61;
$ESTATUS_REPORTE_RECHAZADO = 62;
$ESTATUS_REPORTE_REAGENDADO = 63;
$ESTATUS_REPORTE_CANCELADO = 64;
$ESTATUS_REPORTE_DEPURADO = 65;

/**ESTATUS DE CENSO**/
$ESTATUS_CENSO_NO_APLICA = 0;
$ESTATUS_CENSO_EN_PROCESO = 70;
$ESTATUS_CENSO_COMPLETO = 71;
$ESTATUS_CENSO_REAGENDADO = 72;


if (isset($_POST["idUsuario"])) {
    /***VIENEN LOS DOS PARAMETROS SIN PROBLEMA**/
    $idUsuario = $_POST["idUsuario"];
    $tipoAgencia = $_POST["tipoAgencia"];
    $reportData = []; $returnData = [];$reportData = [];

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtObtenerContratos = $conn->prepare("call spObtenerContratos(?);");
    mysqli_stmt_bind_param($stmtObtenerContratos, 'i', $idUsuario);
    if ($stmtObtenerContratos->execute()) {
        $stmtObtenerContratos->store_result();
        $stmtObtenerContratos->bind_result($idClienteGenerado, $id, $agreementNumber, $name, $idStatus, 
                                           $description, $idCity, $colonia, $street,$innerNumber,$estatusReporte,$estatusCenso,$estatusVenta,$idEmpleadoParaVenta,
                                           $asignadoMexicana,$asignadoAyopsa,$validadoMexicana,$validadoAyopsa,$phEstatus,$idEmpleadoPhAsignado,
                                           $asignacionSegundaVenta,$idEmpleadoSegundaVenta,$validacionSegundaVenta,$idClienteGenerado,$validacionInstalacion,
                                           $estatusAsignacionInstalacion,$idEmpleadoInstalacion,$idAgenciaInstalacion, $nicknameEmpleado, $nicknameAgencia, 
                                           $created_at,$idReportType, $fechaInicioVenta,$fechaFinVenta,$fechaInicioFinanciera,$fechaFinFinanciera,
                                           $fechaInicioRechazo,$fechaFinRechazo,$fechaPrimeraCaptura,$fechaSegundaCaptura,$fechaInicioAsigPH,
                                           $fechaFinAsigPH,$fechaInicioRealizoPH,$fechaFinRealizoPH,$fechaInicioAnomPH,$fechaFinAnomPH,
                                           $fechaInicioAsigInst,$fechaFinAsigInst,$fechaInicioRealInst,$fechaFinRealInst,$fechaInicioAnomInst,$fechaFinAnomInst);
        $contador=0;
        while ($stmtObtenerContratos->fetch()) {
            if (intval($agreementNumber) == 35547) {

                $descriptionStatus=validarEstatusDesdeLaTablaDeEstatusControl(
                        $idReporte,$estatusCenso, $estatusReporte, $estatusVenta, $validadoMexicana, $validadoAyopsa,
                        $phEstatus, $estatusSegundaVenta, $validacionSegundaVenta,
                        $validacionInstalacion, $estatusAsignacionInstalacion, $idReportType
                );
                //echo "agreementNumber ".$descriptionStatus;
                if (($_POST["tipoReportes"] == "pendientes") && 
                    (intval($estatusAsignacionInstalacion) != 54 && intval($estatusAsignacionInstalacion) != 53 && intval($estatusAsignacionInstalacion) != 55)) 
                {

                    $reportData["Id"] = $id;
                    $reportData["idReportType"] = $idReportType;
                    $reportData['idStatus'] =$idStatus;
                    $reportData["primerTD"] = "";
                    $reportData["segundoTD"] = "";
                    $reportData["estatusVenta"] = $estatusVenta;
                    $reportData["idClienteGenerado"] = '<div class="idCliente" data-id="'.$idClienteGenerado.'">'.$idClienteGenerado.'</div>';
                    $reportData["Contrato"] = '<div class="contrato" data-id="'.$agreementNumber.'">'.$agreementNumber.'</div>';
                    $reportData["Tipo"] = '<div class="tipoReporte" data-id="'.$name.'">'.$name.'</div>';
                    $reportData["Status"] = $description;
                    $reportData["Municipio"] = $idCity;
                    $reportData["Colonia"] = $colonia;
                    $reportData['Calle'] = $street.' - Num: '.$innerNumber;
                    if (($descriptionStatus == "EN PROCESO" || $descriptionStatus == "REAGENDADA") &&
                        ($nicknameEmpleado != "Pendiente de Asignar" && 
                        ($_SESSION["nickname"] != "SuperAdmin" && 
                         $_SESSION["nickname"] != "AYOPSA" &&
                         $_SESSION["nickname"] != "CallCenter"))) {
                        $button  = '<div class="idUsuario" data-id="'.$id.'">';
                            $button .= '<button class="btn btn-warning openReasignForm" type="button" data-id="'.$id.'">';
                            $button .= '<i class="fa fa-chain-broken" aria-hidden="true">&nbsp;</i>'.$nicknameEmpleado;
                            $button .= '</button>';
                        $button .= '</div>';
                        $reportData["Usuario"] = $button;
                    }else{
                        $reportData["Usuario"] = $nicknameEmpleado;
                    }
                    $reportData["Agencia"] = $nicknameAgencia;
                    $reportData["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
                    $fecha = $created_at;
                    switch ($name) {
                        case 'Venta':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "CAPTURA COMPLETADA" || $descriptionStatus == "REAGENDADA") {
                                $fecha=$fechaInicioVenta;
                            }elseif ($descriptionStatus == "RECHAZADO") {
                                if ($fechaInicioRechazo != "" && $fechaFinRechazo != "") {
                                    $fecha=$fechaFinRechazo;
                                }elseif ($fechaInicioRechazo != "" && $fechaFinRechazo == "") {
                                    $fecha=$fechaInicioRechazo;
                                }elseif ($fechaInicioRechazo == "" && $fechaFinRechazo != "") {
                                    $fecha=$fechaFinRechazo;
                                }
                            }elseif ($descriptionStatus == "VALIDADO POR MEXICANA") {
                                $fecha=$fechaInicioFinanciera;
                            }elseif ($descriptionStatus == "VALIDACIONES COMPLETAS") {
                                if ($fechaInicioFinanciera != "" && $fechaFinFinanciera != "") {
                                    $fecha=$fechaFinFinanciera;
                                }elseif ($fechaInicioFinanciera != "" && $fechaFinFinanciera == "") {
                                    $fecha=$fechaInicioFinanciera;
                                }elseif ($fechaInicioFinanciera == "" && $fechaFinFinanciera != "") {
                                    $fecha=$fechaFinFinanciera;
                                }
                            }
                        break;
                        case 'Plomero':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "REAGENDADA") {
                                $fecha = $fechaInicioAsigPH;
                            }elseif ($descriptionStatus == "COMPLETO") {
                                $fecha = $fechaFinRealizoPH;
                            }
                        break;
                        case 'Instalacion':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "REAGENDADA") {
                                $fecha = $fechaInicioAsigInst;
                            }elseif ($descriptionStatus == "COMPLETO") {
                                $fecha = $fechaFinAsigInst;
                            }elseif ($descriptionStatus == "INSTALACION ENVIADA") {
                                $fecha = $fechaFinRealInst;
                            }
                        break;
                        case 'Segunda Venta':
                            if ($descriptionStatus == "EN PROCESO") {
                                $fecha = $fechaPrimeraCaptura;
                            }elseif ($descriptionStatus == "COMPLETO" || $descriptionStatus == "REVISION_SEGUNDA_CAPTURA") {
                                $fecha = $fechaSegundaCaptura;
                            }
                        break;
                        case 'Censo':
                            $fecha = $created_at;
                        break;
                    }
                    if ($fecha == null || $fecha == "") {
                        $fecha = $created_at;
                    }
                    $reportData["Fecha"] = $fecha;
                    $reportData["html"] = getHTMLButtons($id,$tipoAgencia,$name,$description, 
                                            $estatusReporte,$estatusCenso,$estatusVenta,
                                                         $idEmpleadoParaVenta,$asignadoMexicana,
                                                         $asignadoAyopsa,$validadoMexicana,$validadoAyopsa,$phEstatus,$idEmpleadoPhAsignado,
                                                         $asignacionSegundaVenta,$idEmpleadoSegundaVenta,$validacionSegundaVenta,$idClienteGenerado,
                                                         $validacionInstalacion,$estatusAsignacionInstalacion,$idEmpleadoInstalacion,
                                                         $idAgenciaInstalacion, $idReportType);
                    $returnData[] = $reportData;
                }elseif (($_POST["tipoReportes"] == "completos") &&
                         (intval($estatusAsignacionInstalacion) == 54 || intval($estatusAsignacionInstalacion) == 53 || intval($estatusAsignacionInstalacion) == 55)){
                    $reportData["Id"] = $id;
                    $reportData["idReportType"] = $idReportType;
                    $reportData['idStatus'] =$idStatus;
                    $reportData["primerTD"] = "";
                    $reportData["segundoTD"] = "";
                    $reportData["estatusVenta"] = $estatusVenta;
                    $reportData["idClienteGenerado"] = $idClienteGenerado;
                    $reportData["Contrato"] = $agreementNumber;
                    $reportData["Tipo"] = $name;
                    $reportData["Status"] = $description;
                    $reportData["Municipio"] = $idCity;
                    $reportData["Colonia"] = $colonia;
                    $reportData['Calle'] = $street.' - Num: '.$innerNumber;
                    $reportData["Usuario"] = $nicknameEmpleado;
                    $reportData["Agencia"] = $nicknameAgencia;
                    $reportData["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
                    $fecha = $created_at;
                    switch ($name) {
                        case 'Venta':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "CAPTURA COMPLETADA" || $descriptionStatus == "REAGENDADA") {
                                $fecha=$fechaInicioVenta;
                            }elseif ($descriptionStatus == "RECHAZADO") {
                                $fecha=$fechaFinRechazo;
                            }elseif ($descriptionStatus == "VALIDADO POR MEXICANA") {
                                $fecha=$fechaInicioFinanciera;
                            }elseif ($descriptionStatus == "VALIDACIONES COMPLETAS") {
                                if ($fechaInicioFinanciera != "" && $fechaFinFinanciera != "") {
                                    $fecha=$fechaFinFinanciera;
                                }elseif ($fechaInicioFinanciera != "" && $fechaFinFinanciera == "") {
                                    $fecha=$fechaInicioFinanciera;
                                }elseif ($fechaInicioFinanciera == "" && $fechaFinFinanciera != "") {
                                    $fecha=$fechaFinFinanciera;
                                }
                            }
                        break;
                        case 'Plomero':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "REAGENDADA") {
                                $fecha = $fechaInicioAsigPH;
                            }elseif ($descriptionStatus == "COMPLETO") {
                                $fecha = $fechaFinRealizoPH;
                            }
                        break;
                        case 'Instalacion':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "REAGENDADA") {
                                $fecha = $fechaInicioAsigInst;
                            }elseif ($descriptionStatus == "COMPLETO") {
                                $fecha = $fechaFinAsigInst;
                            }elseif ($descriptionStatus == "INSTALACION ENVIADA") {
                                $fecha = $fechaFinRealInst;
                            }
                        break;
                        case 'Segunda Venta':
                            if ($descriptionStatus == "EN PROCESO") {
                                $fecha = $fechaPrimeraCaptura;
                            }elseif ($descriptionStatus == "COMPLETO" || $descriptionStatus == "REVISION_SEGUNDA_CAPTURA") {
                                $fecha = $fechaSegundaCaptura;
                            }
                        break;
                        case 'Censo':
                            $fecha = $created_at;
                        break;
                    }
                    if ($fecha == null || $fecha == "") {
                        $fecha = $created_at;
                    }
                    $reportData["Fecha"] = $fecha;
                    $reportData["html"] = getHTMLButtons($id,$tipoAgencia,$name,$description, 
                                            $estatusReporte,$estatusCenso,$estatusVenta,
                                                         $idEmpleadoParaVenta,$asignadoMexicana,
                                                         $asignadoAyopsa,$validadoMexicana,$validadoAyopsa,$phEstatus,$idEmpleadoPhAsignado,
                                                         $asignacionSegundaVenta,$idEmpleadoSegundaVenta,$validacionSegundaVenta,$idClienteGenerado,
                                                         $validacionInstalacion,$estatusAsignacionInstalacion,$idEmpleadoInstalacion,
                                                         $idAgenciaInstalacion, $idReportType);
                    $returnData[] = $reportData;
                }elseif ($_POST["tipoReportes"] == "general"){
                    $reportData["Id"] = $id;
                    $reportData["idReportType"] = $idReportType;
                    $reportData['idStatus'] =$idStatus;
                    $reportData["primerTD"] = "";
                    $reportData["segundoTD"] = "";
                    $reportData["idClienteGenerado"] = $idClienteGenerado;
                    $reportData["Contrato"] = $agreementNumber;
                    $reportData["Tipo"] = $name;
                    $reportData["Status"] = $description;
                    $reportData["Municipio"] = $idCity;
                    $reportData["Colonia"] = $colonia;
                    $reportData['Calle'] = $street.' - Num: '.$innerNumber;
                    $reportData["Usuario"] = $nicknameEmpleado;
                    $reportData["Agencia"] = $nicknameAgencia;
                    $fecha = $created_at;
                    switch ($name) {
                        case 'Venta':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "CAPTURA COMPLETADA" || $descriptionStatus == "REAGENDADA") {
                                $fecha=$fechaInicioVenta;
                            }elseif ($descriptionStatus == "RECHAZADO") {
                                $fecha=$fechaFinRechazo;
                            }elseif ($descriptionStatus == "VALIDADO POR MEXICANA") {
                                $fecha=$fechaInicioFinanciera;
                            }elseif ($descriptionStatus == "VALIDACIONES COMPLETAS") {
                                if ($fechaInicioFinanciera != "" && $fechaFinFinanciera != "") {
                                    $fecha=$fechaFinFinanciera;
                                }elseif ($fechaInicioFinanciera != "" && $fechaFinFinanciera == "") {
                                    $fecha=$fechaInicioFinanciera;
                                }elseif ($fechaInicioFinanciera == "" && $fechaFinFinanciera != "") {
                                    $fecha=$fechaFinFinanciera;
                                }
                            }
                        break;
                        case 'Plomero':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "REAGENDADA") {
                                $fecha = $fechaInicioAsigPH;
                            }elseif ($descriptionStatus == "COMPLETO") {
                                $fecha = $fechaFinRealizoPH;
                            }
                        break;
                        case 'Instalacion':
                            if ($descriptionStatus == "EN PROCESO" || $descriptionStatus == "REAGENDADA") {
                                $fecha = $fechaInicioAsigInst;
                            }elseif ($descriptionStatus == "COMPLETO") {
                                $fecha = $fechaFinAsigInst;
                            }elseif ($descriptionStatus == "INSTALACION ENVIADA") {
                                $fecha = $fechaFinRealInst;
                            }
                        break;
                        case 'Segunda Venta':
                            if ($descriptionStatus == "EN PROCESO") {
                                $fecha = $fechaPrimeraCaptura;
                            }elseif ($descriptionStatus == "COMPLETO" || $descriptionStatus == "REVISION_SEGUNDA_CAPTURA") {
                                $fecha = $fechaSegundaCaptura;
                            }
                        break;
                        case 'Censo':
                            $fecha = $created_at;
                        break;
                    }
                    if ($fecha == null || $fecha == "") {
                        $fecha = $created_at;
                    }
                    $reportData["Fecha"] = $fecha;
                    $reportData["html"] = getHTMLButtons($id,$tipoAgencia,$name,$description, 
                                            $estatusReporte,$estatusCenso,$estatusVenta,
                                                         $idEmpleadoParaVenta,$asignadoMexicana,
                                                         $asignadoAyopsa,$validadoMexicana,$validadoAyopsa,$phEstatus,$idEmpleadoPhAsignado,
                                                         $asignacionSegundaVenta,$idEmpleadoSegundaVenta,$validacionSegundaVenta,$idClienteGenerado,
                                                         $validacionInstalacion,$estatusAsignacionInstalacion,$idEmpleadoInstalacion,
                                                         $idAgenciaInstalacion, $idReportType);
                    $returnData[] = $reportData;
                }
            }
            $contador++;
        }
        $conn->close();
    }
    echo json_encode($returnData);
}

function getHTMLButtons($idReporte,$tipoAgencia,$tipoReporte,$estatus, 
                        $estatusReporte,$estatusCenso,$estatusVenta,$idEmpleadoParaVenta,$asignadoMexicana,
                        $asignadoAyopsa,$validadoMexicana,$validadoAyopsa,$phEstatus,$idEmpleadoPhAsignado,
                        $asignacionSegundaVenta,$idEmpleadoSegundaVenta,$validacionSegundaVenta,$idClienteGenerado,
                        $validacionInstalacion,$estatusAsignacionInstalacion,$idEmpleadoInstalacion,
                        $idAgenciaInstalacion, $idReportType)
{
    if (isset($idReporte)) {
        //error_log('message estatusCenso1 '.$estatusCenso);
        $isCallCenter = isset($tipoAgencia) && $tipoAgencia == "CallCenter";
        $estatusSegundaVenta = $asignacionSegundaVenta;
        
        $permisosDelProceso = generarDropDownEnBaseAPermisos($idReporte,$idReportType, $estatusReporte,$estatusCenso,$estatusVenta,$idEmpleadoParaVenta,$asignadoMexicana,$asignadoAyopsa,$validadoMexicana,$validadoAyopsa,$phEstatus,$idEmpleadoPhAsignado,$asignacionSegundaVenta,$idEmpleadoSegundaVenta,$validacionSegundaVenta,$idClienteGenerado,$validacionInstalacion,$estatusAsignacionInstalacion,$idEmpleadoInstalacion,$idAgenciaInstalacion, $isCallCenter);
        
        /** SE CREA EL ESTATUS CON EL COLOR */
        $estatusTexto = validarEstatusDesdeLaTablaDeEstatusControl(
                $idReporte,$estatusCenso, $estatusReporte, $estatusVenta, $validadoMexicana, $validadoAyopsa,
                $phEstatus, $estatusSegundaVenta, $validacionSegundaVenta,
                $validacionInstalacion, $estatusAsignacionInstalacion, $idReportType
        );
        
        $estatusColores = generarSpanDeEstatusPorColor($estatusTexto);
        $botonAsignarTarea = generarBotonAsignarTarea($estatusCenso, $estatus, $idReporte, $estatusVenta, $idClienteGenerado, $tipoReporte, $estatusAsignacionInstalacion);

        $arrayProcesosReporte = array(
            'permisosDelProceso' => $permisosDelProceso,
            'estatusColores' => $estatusColores,
            'botonAsignarTarea' => $botonAsignarTarea
        );

        //echo json_encode($arrayProcesosReporte);
        return $arrayProcesosReporte;
    } else {
        $response["CodigoRespuesta"] = 0;
        $response["MensajeRespuesta"] = "DATOS INGRESADOS INCORRECTAMENTE";
        echo json_encode($response);
    }
}

/**
 * FUNCION QUE SE ENCARGA DE GENERAR EL BOTON DE ACUERDO AL TIPO DE PROCESO (VENTA, PLOMERIA, SEGUNDA VENTA E INSTALACION)
 * DE ACUERDO A LOS PERMISOS DEL USUARIO Y ALA AGENCIA EN QUE PERTENECE 
 * 
 * @param INT $idReportType
 * @param ARRAY $response
 * @param BOOLEAN $isCallCenter
 * @return STRING
 */
function generarDropDownEnBaseAPermisos($idReporte,$idReportType, $estatusReporte,$estatusCenso,$estatusVenta,$idEmpleadoParaVenta,$asignadoMexicana,$asignadoAyopsa,$validadoMexicana,$validadoAyopsa,$phEstatus,$idEmpleadoPhAsignado,$asignacionSegundaVenta,$idEmpleadoSegundaVenta,$validacionSegundaVenta,$idClienteGenerado,$validacionInstalacion,$estatusAsignacionInstalacion,$idEmpleadoInstalacion,$idAgenciaInstalacion, $isCallCenter)
{
    /**VALIDAMOS EL PRIMER NIVEL QUE ES SABER SI TIENE HABILITADO EL ESTATUS DEL CENSO, ESTO PARA SABER QUE DEVOLVERA UN BOTON DE CENSO O BIEN
     * SI ENTRARA AL DROPDOWN DE PROCESOS DE VENTA**/
    
    $permisosDelProceso = "";
    $NicknameUsuarioLogeado = $_SESSION["nickname"];
    //echo "NicknameUsuarioLogeado ".$NicknameUsuarioLogeado;
    $idUsuario =  $_SESSION["id"];
    if (intval($estatusCenso) == $GLOBALS['ESTATUS_CENSO_NO_APLICA']) 
    {
        //error_log('message entre ');
            switch(intval($idReportType))
            {
                // tipo venta
                case 2:
                        $permisosDelProceso = getBotonVenta($idReporte, $idUsuario, $NicknameUsuarioLogeado,  $estatusVenta, $isCallCenter, $asignacionSegundaVenta);
                    break;

                // tipo plomero
                case 3:
                        $permisosDelProceso = getBotonPlomero($idReporte,$idUsuario, $NicknameUsuarioLogeado, $phEstatus);
                    break;
        
                // tipo instalacion
                case 4:
                        $permisosDelProceso = getBotonInstalacion($idReporte,$idUsuario,$NicknameUsuarioLogeado, $estatusReporte, $estatusAsignacionInstalacion);
                    break;
        
                // tipo segundaventa
                case 5:
                        $permisosDelProceso = getBotonSegundaVenta($idReporte, $idUsuario, $NicknameUsuarioLogeado, $validacionSegundaVenta, $isCallCenter);
                    break;
            }
    }
    else 
    {
        $permisosDelProceso = getBotonCenso($NicknameUsuarioLogeado, $estatusCenso, $idUsuario, $idReporte);
    }
    //error_log('message permisosDelProceso '.$permisosDelProceso);
    return $permisosDelProceso;
}


/**
 * SE ENCARGA DE VALIDAR EL ESTATUS DE LA VENTA, INDEPENDIENTEEMENTE DEL USUARIO EL BOTON DE PRIMERA VENTA
 * SIEMPRE SE MUESTRA AL USUARIO QUE INICIO SESIÓN
 * 
 * @param int $estatusVenta 
 */
function getBotonVenta($idReporte, $idUsuario, $NicknameUsuarioLogeado,  $estatusVenta, $isCallCenter, $asignacionSegundaVenta)
{
    
    $permisosDelProceso = "";
    //error_log('message estatusVenta '.$estatusVenta);
    if(intval($estatusVenta) == $GLOBALS['ESTATUS_VENTA_EN_PROCESO']){
        $permisosDelProceso .= '<div class="btn-group">';
            $permisosDelProceso .= '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-money"></i>&nbsp;Primera Venta<span class="caret"></span></button>';
            $permisosDelProceso .= '<ul role="menu" class="dropdown-menu">';
                $permisosDelProceso .= '<li style="background-color: lightyellow;" >';
                $permisosDelProceso .= '<a class="firstSell" data-id="' . $idReporte . '" tabindex="1" href="javascript:mensajeCallejeroNoLlenado();"><i class="fa fa-money"></i>&nbsp;&nbsp;Primera Venta</a></li>';
            $permisosDelProceso .= '</ul>';
        $permisosDelProceso .='</div>';
    }else if(intval($estatusVenta) == $GLOBALS['ESTATUS_VENTA_CANCELADA']){
        $permisosDelProceso = '<button id="" name="" data-toggle="warning" style="width:144px"  class="btn btn-default" onclick="mensajeVentaCancelada();"><i class="fa fa-money"></i>&nbsp;&nbsp;Primera Venta</button>';
    }else{
        $permisosDelProceso = '<button id="" name="" data-toggle="warning" style="width:144px"  class="btn btn-default" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money"></i>&nbsp;&nbsp;Primera Venta</button>';
        if((!$isCallCenter && intval($asignacionSegundaVenta) == 0) && intval($estatusVenta) != $GLOBALS['ESTATUS_VENTA_CANCELADA']){
            // MEXICANA DE GAS
            $permisosDelProceso .= '<div class="btn-group">';
                $permisosDelProceso .= '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Segunda venta<span class="caret"></span></button>';
                $permisosDelProceso .= '<ul role="menu" class="dropdown-menu">';
                    $permisosDelProceso .= '<li style="background-color: lightyellow;" ><a class="secondSell" data-id="' . $idReporte . '" tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    $permisosDelProceso .= '<li><a class="secondSell" data-id="' . $idReporte . '" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>';
                $permisosDelProceso .= '</ul>';
            $permisosDelProceso .='</div>';
        }
    }    
    return $permisosDelProceso;
}


/**
 * SE CREO UNA FUNCION EN DONDE SE VALIDA LOS ESTATUS DEL CENSO YA QUE PRACTICAMENTE NO CAMBIAN  EN NINGUNA SITUACION, HAY QUE REVIZAR 
 * SI EL CENSO CUANDO EL USUARIO ES DE AYOPSA PUEDE REALIZAR ALGUNA ACCION, EN EL CODIGO VIENE EN BLANCO EL BOTON DE CENSO PARA AYOPSA
 * 
 * @param $estatusCenso Estatus del censo 
 * @return string
 */
function getBotonCenso($NicknameUsuarioLogeado, $estatusCenso, $idUsuario, $idReporte)
{
    $permisosDelProceso = "";
    
    if($NicknameUsuarioLogeado != "AYOPSA")
    {
        if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_COMPLETO']) {
            $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-success" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
        } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_EN_PROCESO']) {
            $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-warning" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
        } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_REAGENDADO']) {
            $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-primary" onclick="loadForm(' . $idReporte . ',"' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
        }
    }
    
    return $permisosDelProceso;
}

/**
 * GENERA EL BOTON DEL ESTATUS DE PLOMERIA,PRACTICAMENTE ES LO MISMO PARA TODOS LOS CASOS EN DONDE EL USUARIO NOSEA AYOPSA  
 *  
 * @param type $NicknameUsuarioLogeado
 * @param type $phEstatus
 * @return string
 */
function getBotonPlomero($idReporte,$idUsuario,$NicknameUsuarioLogeado, $phEstatus)
{
    $permisosDelProceso = "";
    
    if($NicknameUsuarioLogeado != "AYOPSA")
    {
        //echo "phEstatus ".$phEstatus;
        switch(intval($phEstatus))
        {
            case $GLOBALS['ESTATUS_PH_COMPLETO']:
            case $GLOBALS['ESTATUS_PH_REAGENDADO']:
            case $GLOBALS['ESTATUS_PH_RECHAZADO']:
                $permisosDelProceso = '<button id="" name="" data-toggle="button" style="width:144px" class="btn btn-default" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road"></i> Plomeria</button>';
                break;
            case $GLOBALS['ESTATUS_PH_EN_PROCESO']:
                $permisosDelProceso .= '<div class="btn-group">';
                    $permisosDelProceso .= '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-road"></i>&nbsp;Plomeria<span class="caret"></span></button>';
                    $permisosDelProceso .= '<ul role="menu" class="dropdown-menu">';
                        $permisosDelProceso .= '<li style="background-color: lightyellow;" >';
                        $permisosDelProceso .= '<a class="plumberForm" data-id="' . $idReporte . '" tabindex="1" href="javascript:mensajeCallejeroNoLlenado();"><i class="fa fa-road"></i>&nbsp;&nbsp;Plomeria</a></li>';
                $permisosDelProceso .='</div>';
                break;
            default:
                $permisosDelProceso .= '<div class="btn-group">';
                    $permisosDelProceso .= '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-road"></i>&nbsp;Plomeria<span class="caret"></span></button>';
                    $permisosDelProceso .= '<ul role="menu" class="dropdown-menu">';
                        $permisosDelProceso .= '<li style="background-color: lightyellow;" >';
                        $permisosDelProceso .= '<a class="plumberForm" data-id="' . $idReporte . '" tabindex="1" href="javascript:mensajeCallejeroNoLlenado();"><i class="fa fa-road"></i>&nbsp;&nbsp;Plomeria</a></li>';
                    $permisosDelProceso .= '</ul>';
                $permisosDelProceso .='</div>';
                break;
        }
    }
    
    return $permisosDelProceso;
}

/**
 * GENERAMOS UN DROPDOWN CON LOS DATOS DEL ESTATUS DE LA SEGUNDA VENTA, VALIDAMOS QUE NO SEA UN USUARIO DE AYOPSA YA QUE NO TIENEN PERMISOS PARA CREAR SEGUNDA VENTA
 * ADEMAS VALIDAMOS SI ES UN CALLCENTER SOLO TIENE PERMISOS DE VER PERO SI ES DE MEXICANA TIENE PERMISOS DE VER Y CRERAR SEGUNDAS VENTAS
 */
function getBotonSegundaVenta($idReporte, $idUsuario, $NicknameUsuarioLogeado, $validacionSegundaVenta, $isCallCenter)
{
     $permisosDelProceso = "";
     if($NicknameUsuarioLogeado != "AYOPSA")
     {
         // SI NO ES CALLCENTER LE DAMOS LA OPCION DE AGREGAR UNA SEGUNDA VENTA
        if(!$isCallCenter)
        {
            // MEXICANA DE GAS
            $permisosDelProceso .= '<div class="btn-group">';
                $permisosDelProceso .= '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Segunda venta<span class="caret"></span></button>';
                $permisosDelProceso .= '<ul role="menu" class="dropdown-menu">';
                    /*$ESTATUS_SEGUNDA_VENTA_COMPLETA = 41;
                    $ESTATUS_SEGUNDA_VENTA_VALIDADA = 42;*/
                    //echo "validacionSegundaVenta ".$validacionSegundaVenta." ESTATUS_SEGUNDA_VENTA_COMPLETA".$ESTATUS_SEGUNDA_VENTA_COMPLETA;
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA'] ||
                        $validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_VALIDADA'] ||
                        $validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_REVISION']) {
                        //echo "validacionSegundaVenta ".$validacionSegundaVenta;
                        $permisosDelProceso .= '<li style="background-color: lightyellow;" ><a class="secondSell" data-id="' . $idReporte . '" tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }elseif($GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO'] == $validacionSegundaVenta){
                        $permisosDelProceso .= '<li><a class="secondSell" data-id="' . $idReporte . '" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>';
                    }
                $permisosDelProceso .= '</ul>';
            $permisosDelProceso .='</div>';
        }
        else
        {
            // CALLCENTER
            $permisosDelProceso .= '<div class="btn-group">';
                $permisosDelProceso = '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Segunda venta<span class="caret"></span></button>';
                $permisosDelProceso .= '<ul role="menu" class="dropdown-menu">';
                    $permisosDelProceso .= '<li style="background-color: lightyellow;" ><a  href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                $permisosDelProceso .= '</ul>';
            $permisosDelProceso .='</div>';
        }
     }
    
    return $permisosDelProceso;
}

/**
 * FUNCION QUE SE ENCARGA DE GENERAR EL BOTON DE INSTALACION 
 * 
 * @param type $idReporte
 * @param type $idUsuario
 * @param type $NicknameUsuarioLogeado
 * @param type $estatusReporte
 * @param type $estatusVenta
 * @return string
 */
function getBotonInstalacion($idReporte,$idUsuario,$NicknameUsuarioLogeado, $estatusReporte, $estatusAsignacionInstalacion)
{
    $permisosDelProceso= "";
    if($NicknameUsuarioLogeado != "AYOPSA")
    {
        if(($estatusReporte == $GLOBALS['ESTATUS_REPORTE_COMPLETO']) && 
           ($estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_COMPLETA'] ||
            $estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_ANOMALIA'] ||
            $estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_ENVIADA'])){
            $permisosDelProceso = '<button id="" name="" data-toggle="button" style="width:144px" class="btn btn-default" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_INSTALACION'] . chr(39) . ',' . $idUsuario . ')"><i class="fa fa-wrench"></i> Instalación</button>';
        }else{
            $permisosDelProceso = '<button id="" name="" data-toggle="button" style="width:144px" class="btn btn-default" onclick="mensajeToastFormularioAunNoDisponible();"><i class="fa fa-wrench"></i> Instalación</button>';
        }
    }
    
    return $permisosDelProceso;
}

/**
 * FUNCION QUE SE ENCARGA DE COLOCAR EL ESTATUS DEL PROCESO CON EL SPAN PARA COLOCAR EL COLOR DEL ESTATUS
 * @param type $idReporte
 * @param type $estatusCenso
 * @param type $estatusReporte
 * @param type $estatusVenta
 * @param type $validadoMexicana
 * @param type $validadoAyopsa
 * @param type $phEstatus
 * @param type $estatusSegundaVenta
 * @param type $validacionSegundaVenta
 * @param type $validacionInstalacion
 * @param type $estatusAsignacionInstalacion
 * @param type $idReprtType
 * @return type
 */
function validarEstatusDesdeLaTablaDeEstatusControl($idReporte,$estatusCenso, $estatusReporte, $estatusVenta, $validadoMexicana, $validadoAyopsa,
                                                    $phEstatus, $estatusSegundaVenta, $validacionSegundaVenta,
                                                    $validacionInstalacion, $estatusAsignacionInstalacion,  $idReprtType)
{

    /**ESTATUS DE VENTA**/
    $ESTATUS_VENTA_POR_ASIGNAR = 1;
    $ESTATUS_VENTA_PENDIENTE = 2;
    $ESTATUS_VENTA_COMPLETA = 3;
    $ESTATUS_VENTA_EN_PROCESO = 4;
    $ESTATUS_VENTA_PENDIENTE_VALIDACION = 5;
    $ESTATUS_VENTA_VALIDADO_POR_MEXICANA_DE_GAS = 6;
    $ESTATUS_VENTA_REAGENDADO = 7;
    $ESTATUS_VENTA_CANCELADA = 8;
    $ESTATUS_VENTA_RECHAZADO = 9;

    /**ESTATUS DE VALIDACION AYOPSA Y MEXICANA**/
    $ESTATUS_MEXICANA_AYOPSA_EN_PROCESO = 20;
    $ESTATUS_MEXICANA_AYOPSA_CREDITO = 21;
    $ESTATUS_MEXICANA_AYOPSA_CONTADO = 22;
    $ESTATUS_MEXICANA_AYOPSA_RECHAZADO = 23;
    $ESTATUS_MEXICANA_AYOPSA_NO_VALIDA = 24;
    $ESTATUS_MEXICANA_AYOPSA_CANCELADA = 25;
    
    
    /** ESTATUS DE PLOMERIA*/
    $ESTATUS_PLOMERIA_EN_PROCESO = 30;
    $ESTATUS_PLOMERIA_COMLETA = 31;
    $ESTATUS_PLOMERIA_REAGENDADA = 32;
    $eSTATUS_PLOMERIA_RECHAZADA = 33;
    
    /** ESTATUS PARA SEGUNDA VENTA */
    $ESTATUS_SEGUNDA_VENTA_EN_PROCESO = 40;
    $ESTATUS_SEGUNDA_VENTA_COMPLETA =  41;
    
    $estatus = "";
   
    switch($idReprtType)
    {
        // censo
        case 1:
            $estatus = getEstatusCenso($estatusCenso);
            break;

        // primera venta
        case 2:
            $estatus = getEstatusVenta($idReporte,$estatusReporte,$estatusVenta, $validadoMexicana, $validadoAyopsa);
            break;

        // plomero
        case 3:
            $estatus = getEstatusPlomero($idReporte,$phEstatus);
            break;

        // instalacion
        case 4:
            $estatus = getEstatusInstalacion($estatusAsignacionInstalacion);
            break;

        // segunda venta
        case 5:
            $estatus = getEstatusSegundaVenta($validacionSegundaVenta, $estatusVenta, $phEstatus, $idReporte);
            break;
    }
   
    return $estatus;
}

/**
 * FUNCION QUE DEVUELVE EL ESTATUS DEL CENSO
 * 
 * @param type $estatusCenso
 * @return string
 */
function getEstatusCenso($estatusCenso)
{
    $estatus = "";    
    switch($estatusCenso)
    {
        case $GLOBALS['ESTATUS_CENSO_EN_PROCESO']:
            $estatus = $GLOBALS['ESTATUS_REPORTE_EN_PROCESO'];
            break;
        case $GLOBALS['ESTATUS_CENSO_COMPLETO']:
            $estatus = $GLOBALS['ESTATUS_TEXTO_COMPLETO'];
            break;
        case $GLOBALS['ESTATUS_CENSO_REAGENDADO']:
            $estatus = $GLOBALS['ESTATUS_TEXTO_REAGENDADA'];
            break;
        default:
            $estatus= "";
            break;
    }
    return $estatus;
}

/**
 * FUNCION QUE DEVUELVE EL ESTATUS DE LA VENTA
 * @param type $idReporte
 * @param type $estatusVenta
 * @param type $validadoMexicana
 * @param type $validadoAyopsa
 * @return type
 */
function getEstatusVenta($idReporte,$estatusReporte,$estatusVenta, $validadoMexicana,$validadoAyopsa)
{
    $estatus = ""; 
    
//    var_dump(array("venta" => $estatusVenta, "mexicana" => $validadoMexicana, "ayopsa" => $validadoAyopsa));exit();
    /**SI REVISAMOS QUE NO ES UN CENSO, ENTONCES ESTA EN UN PROCESO DE VENTA DEBEMOS CONDICIONAR POR LAS VENTAS***/
    if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_POR_ASIGNAR']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_POR_ASIGNAR'];

    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_PENDIENTE'];

    } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_COMPLETA']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_CAPTURA_COMPLETA'];

    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_EN_PROCESO']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_EN_PROCESO'];

    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_REAGENDADO']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_REAGENDADA'];

    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_CANCELADA']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_CANCELADA'];

    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_RECHAZADO']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_RECHAZADO'];

    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE_VALIDACION']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_PENDIENTE_VALIDACION'];

    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDADO_POR_MEXICANA_DE_GAS']) {

        if ($validadoMexicana == $GLOBALS['ESTATUS_MEXICANA_AYOPSA_EN_PROCESO']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_PENDIENTE_VALIDACION'];

        } elseif ($validadoAyopsa == $GLOBALS['ESTATUS_MEXICANA_AYOPSA_RECHAZADO']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_RECHAZADO_POR_AYOPSA'];

        } elseif ($validadoMexicana == $GLOBALS['ESTATUS_MEXICANA_AYOPSA_RECHAZADO']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_RECHAZADO_POR_AYOPSA'];

        } elseif ($validadoMexicana == $GLOBALS['ESTATUS_MEXICANA_AYOPSA_CREDITO']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_CREDITO'];

        } elseif ($validadoMexicana == $GLOBALS['ESTATUS_MEXICANA_AYOPSA_CONTADO']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_CONTADO'];

        } else {
            $estatus = $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_MEXICANA'];
        }
    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDACIONES_COMPLETAS']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_VALIDACIONES_COMPLETAS'];
    } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_DEPURADO']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_DEPURADO'];
    }
    
    return $estatus;
}

/**
 * FUNCION  QUE DEVUELVE EL ESTATUS DEL PLOMEREO
 * @param type $idReporte
 * @param type $phEstatus
 * @return type
 */
function getEstatusPlomero($idReporte,$phEstatus)
{
    
    $estatus = "";
    if($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']){
        $estatus = $GLOBALS['ESTATUS_TEXTO_EN_PROCESO'];
    }elseif($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']){
        $estatus = $GLOBALS['ESTATUS_TEXTO_COMPLETO'];
    }elseif($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']){
        $estatus = $GLOBALS['ESTATUS_TEXTO_REAGENDADA'];
    }elseif($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']){
        $estatus = $GLOBALS['ESTATUS_TEXTO_RECHAZADO'];
    }elseif($phEstatus == $GLOBALS['ESTATUS_PH_CANCELADO']){
        $estatus = $GLOBALS['ESTATUS_TEXTO_CANCELADA'];
    }elseif($phEstatus == $GLOBALS['ESTATUS_PH_DEPURADO']){
        $estatus = $GLOBALS['ESTATUS_TEXTO_DEPURADO'];
    }
    
    return $estatus;
}

/**
 * FUNCION QUE DEVUELVE EL ESTATUS DE LA INSTALACION
 * @param type $estatusAsignacionInstalacion
 * @return type
 */
function getEstatusInstalacion($estatusAsignacionInstalacion)
{
    $estatus = "";
    if($estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_EN_PROCESO']){
        $estatus = $GLOBALS["ESTATUS_TEXTO_EN_PROCESO"];
    }elseif($estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_COMPLETA']){
        $estatus = $GLOBALS["ESTATUS_TEXTO_COMPLETO"];
    }elseif($estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_REAGENDADA']){
        $estatus = $GLOBALS["ESTATUS_TEXTO_REAGENDADA"];
    }elseif($estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_CANCELADA']){
        $estatus = $GLOBALS["ESTATUS_TEXTO_CANCELADA"];
    }elseif($estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_ENVIADA']){
        $estatus = $GLOBALS["ESTATUS_TEXTO_ENVIADO"];
    }elseif($estatusAsignacionInstalacion == $GLOBALS['ESTATUS_INSTALACION_DEPURADO']){
        $estatus = $GLOBALS['ESTATUS_TEXTO_DEPURADO'];
    }elseif (intval($estatusAsignacionInstalacion) == $GLOBALS['ESTATUS_INSTALACION_ANOMALIA']) {
        $estatus = $GLOBALS['ESTATUS_TEXTO_INSTALACION_ANOMALIA'];
    }
    //echo "estatus ".$estatus;
    return $estatus;
}

/**
 * FUNCION QUE DEVUELVE EL ESTATUS DE LA SEGUNDA VENTA
 * @param type $estatusSegundaVenta
 * @return type
 */
function getEstatusSegundaVenta($estatusSegundaVenta,$estatusVenta, $phEstatus, $idReporte)
{
    $estatus = "";
    if($estatusSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']){
        $estatus = $GLOBALS["ESTATUS_TEXTO_EN_PROCESO"];
    }elseif($estatusSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']){
        if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDACIONES_COMPLETAS'] && $phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
            $DB = new DAO();
            $conn = $DB->getConnect();
            //actualizamos testatusContrato
            $statusSegundaVenta=$GLOBALS['ESTATUS_SEGUNDA_VENTA_REVISION'];
            $stmtTEstatus = "UPDATE tEstatusContrato SET validacionSegundaVenta = ? WHERE idReporte = ?;";
            if ($estatusCrontratoReport = $conn->prepare($stmtTEstatus)) {
                $estatusCrontratoReport->bind_param("ii", $statusSegundaVenta,$idReporte);
                $estatusCrontratoReport->execute();
            }else{
                //error_log('maldito error '.$conn->error);
            }
            $conn->close();
            $estatus = "REVISION SEGUNDA CAPTURA";
        }else{
            $estatus = $GLOBALS["ESTATUS_TEXTO_CAPTURA_COMPLETA"];

        }
    }elseif($estatusSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_REVISION']){
        //echo "string ESTATUS_TEXTO_SEGUNDA_VENTA_REVISION ".$GLOBALS["ESTATUS_TEXTO_SEGUNDA_VENTA_REVISION"];
        $estatus = "REVISION SEGUNDA CAPTURA";
    }elseif ($estatusSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_VALIDADA']) {
        $estatus = $GLOBALS["ESTATUS_TEXTO_COMPLETO"];
    }elseif($estatusSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_CANCELADA']){
        $estatus = $GLOBALS["ESTATUS_TEXTO_CANCELADA"];
    }elseif($estatusSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_DEPURADO']){
        $estatus = $GLOBALS['ESTATUS_TEXTO_DEPURADO'];
    }else{
        $estatus = $GLOBALS["ESTATUS_TEXTO_PENDIENTE"];
    }
    return $estatus;
}

function generarSpanDeEstatusPorColor($estatus)
{
    if($GLOBALS['ESTATUS_TEXTO_CAPTURA_COMPLETA'] == $estatus){
        $estatusColores = '<span class="label label-warning">' . $estatus . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_POR_ASIGNAR']) {
        $estatusColores = '<span class="label label-info">' . $GLOBALS['ESTATUS_TEXTO_POR_ASIGNAR'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_PENDIENTE']) {
        $estatusColores = '<span class="label label-warning">' . $GLOBALS['ESTATUS_TEXTO_PENDIENTE'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_COMPLETO']) {
        $estatusColores = '<span class="label label-success">' . $GLOBALS['ESTATUS_TEXTO_COMPLETO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_EN_PROCESO']) {
        $estatusColores = '<span class="label label-warning">' . $GLOBALS['ESTATUS_TEXTO_EN_PROCESO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_PENDIENTE_VALIDACION']) {
        $estatusColores = '<span class="label label-warning">' . $GLOBALS['ESTATUS_TEXTO_PENDIENTE_VALIDACION'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_MEXICANA']) {
        $estatusColores = '<span class="label label-warning">' . $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_MEXICANA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_RECHAZADO_POR_MEXICANA']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_RECHAZADO_POR_MEXICANA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_RECHAZADO_POR_AYOPSA']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_RECHAZADO_POR_AYOPSA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_VALIDACIONES_COMPLETAS']) {
        $estatusColores = '<span class="label label-success">' . $GLOBALS['ESTATUS_TEXTO_VALIDACIONES_COMPLETAS'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_REAGENDADA']) {
        $estatusColores = '<span class="label label-info">' . $GLOBALS['ESTATUS_TEXTO_REAGENDADA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_CANCELADA']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_CANCELADA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_EN_PROCESO_DE_VALIDACION_DE_VENTA']) {
        $estatusColores = '<span class="label label-warning">' . $GLOBALS['ESTATUS_TEXTO_EN_PROCESO_DE_VALIDACION_DE_VENTA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_CREDITO']) {
        $estatusColores = '<span class="label label-primary">' . $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_CREDITO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_CONTADO']) {
        $estatusColores = '<span class="label label-primary">' . $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_CONTADO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_RECHAZADO']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_RECHAZADO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_NO_VALIDO']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_NO_VALIDO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_PH_EN_PROCESO']) {
        $estatusColores = '<span class="label label-warning">' . $GLOBALS['ESTATUS_TEXTO_PH_EN_PROCESO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_PH_COMPLETA']) {
        $estatusColores = '<span class="label label-success">' . $GLOBALS['ESTATUS_TEXTO_PH_COMPLETA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_PH_REAGENDADA']) {
        $estatusColores = '<span class="label label-primary">' . $GLOBALS['ESTATUS_TEXTO_PH_REAGENDADA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_PH_RECHAZADA']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_PH_RECHAZADA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_SEGUNDA_VENTA_EN_PROCESO']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_SEGUNDA_VENTA_EN_PROCESO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_SEGUNDA_VENTA_COMPLETA']) {
        $estatusColores = '<span class="label label-success">' . $GLOBALS['ESTATUS_TEXTO_SEGUNDA_VENTA_COMPLETA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_INSTALACION_EN_PROCESO']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_INSTALACION_EN_PROCESO'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_INSTALACION_COMPLETADA']) {
        $estatusColores = '<span class="label label-success">' . $GLOBALS['ESTATUS_TEXTO_INSTALACION_COMPLETADA'] . '</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_INSTALACION_RECHAZADA']) {
        $estatusColores = '<span class="label label-danger">' . $GLOBALS['ESTATUS_TEXTO_INSTALACION_RECHAZADA'] . '</span>';
    } elseif ($estatus == "REVISION_SEGUNDA_CAPTURA") {
        $estatusColores = '<span class="label label-warning">REVISION SEGUNDA CAPTURA</span>';
    } elseif ($estatus == $GLOBALS['ESTATUS_TEXTO_ENVIADO']) {
        $estatusColores = '<span class="label label-success">'.$GLOBALS['ESTATUS_TEXTO_ENVIADO'].'</span>';
    }elseif($estatus == $GLOBALS['ESTATUS_TEXTO_DEPURADO']){
        $estatusColores = '<span class="label label-danger">'.$GLOBALS['ESTATUS_TEXTO_DEPURADO'].'</span>';
    }elseif($estatus == $GLOBALS['ESTATUS_TEXTO_INSTALACION_ANOMALIA']){
        $estatusColores = '<span class="label label-danger">'.$GLOBALS['ESTATUS_TEXTO_INSTALACION_ANOMALIA'].'</span>';
    }
    return $estatusColores;
}

function generarBotonAsignarTarea($estatusCenso, $estatusReporte, $id, $estatusVenta, $idClienteGenerado, $tipoReporte, $estatusAsignacionInstalacion)
{
    $NicknameUsuarioLogeado = $_SESSION["nickname"];
    //echo "estatusAsignacionInstalacion ".$estatusAsignacionInstalacion;
    if (($NicknameUsuarioLogeado != "AYOPSA" && $NicknameUsuarioLogeado != "SuperAdmin") &&
        (intval($estatusAsignacionInstalacion) != 53 && intval($estatusAsignacionInstalacion) != 55)) {
        if ($estatusCenso != $GLOBALS['ESTATUS_CENSO_NO_APLICA']) {
            if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_COMPLETO']) {
                $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-default " onclick="mensajeToastBotonTareaAsignarNoDisponible()"><i class="fa fa-calendar-o"></i></button>';
            } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_EN_PROCESO']) {
                $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-default " onclick="mensajeToastBotonTareaAsignarNoDisponible()"><i class="fa fa-calendar-o"></i></button>';
            } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_REAGENDADO']) {
                $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-default " onclick="mensajeToastBotonTareaAsignarNoDisponible()"><i class="fa fa-calendar-o"></i></button>';
            } else {
                $botonAsignarTarea = '<table>';
                    $botonAsignarTarea .= '<tr>';
                        $botonAsignarTarea .= '<td>';
                            $botonAsignarTarea .= '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-info " onclick="asignarTarea(' . $id . ')"><i class="fa fa-calendar-o"></i></button>';
                        $botonAsignarTarea .= '</td>';
                    $botonAsignarTarea .= '</tr>';
                $botonAsignarTarea .= '</table>';
            }
        }else if ($estatusVenta != 8) {
            $botonAsignarTarea = '<table>';
                $botonAsignarTarea .= '<tr>';
                    $botonAsignarTarea .= '<td>';
                        $botonAsignarTarea .= '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-info " onclick="asignarTarea(' . $id . ')"><i class="fa fa-calendar-o"></i></button>&nbsp;';
                    $botonAsignarTarea .= '</td>';
                    if ($idClienteGenerado == "" && $tipoReporte == "Venta") {
                        $botonAsignarTarea .= '<td>';
                            $botonAsignarTarea .= '<button id="btnDepurarAdmin" name="btnDepurarAdmin" data-toggle="button" style="width: 50px;" class="btn btn-danger btnDepurarAdmin" data-id="'.$id.'"><i class="fa fa-trash" aria-hidden="true"></i>';
                        $botonAsignarTarea .= '</td>';
                    }
                $botonAsignarTarea .= '</tr>';
            $botonAsignarTarea .= '</table>';
        }else if ($estatusVenta == 8) {
            $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-default " onclick="mensajeToastBotonTareaAsignarNoDisponible()"><i class="fa fa-calendar-o"></i></button>';
        }
    }elseif (($NicknameUsuarioLogeado == "SuperAdmin" && $tipoReporte == "Venta") &&
             (intval($estatusAsignacionInstalacion) != 53 && intval($estatusAsignacionInstalacion) != 55)) {
        $botonAsignarTarea = '<button id="btnDepurarAdmin" name="btnDepurarAdmin" data-toggle="button" style="width: 50px;" class="btn btn-danger btnDepurarAdmin" data-id="'.$id.'"><i class="fa fa-trash" aria-hidden="true"></i>';
    }else {
        $botonAsignarTarea = '';
    }
    return $botonAsignarTarea;

}

function ObtenerEstatusContratoParaReporte($conn, $idInReporte)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $response = array();
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";
    $idReporte = "";
    $estatusReporte = "";
    $estatusCenso = "";
    $estatusVenta = "";
    $idEmpleadoParaVenta = "";
    $asignadoMexicana = "";
    $asignadoAyopsa = "";
    $validadoMexicana = "";
    $validadoAyopsa = "";
    $phEstatus = "";
    $idEmpleadoPhAsignado = "";
    $asignacionSegundaVenta = "";
    $idEmpleadoSegundaVenta = "";
    $validacionSegundaVenta = "";
    $idClienteGenerado = "";
    $validacionInstalacion = "";
    $estatusAsignacionInstalacion = "";
    $idEmpleadoInstalacion = "";
    $idAgenciaInstalacion = "";
    $fechaAlta = "";
    $fechaMod = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtObtenerEstatusContratoParaReporte = $conn->prepare("call spObtenerEstatusContratoParaReporte(?);");
    mysqli_stmt_bind_param($stmtObtenerEstatusContratoParaReporte, 'i', $idInReporte);

    if ($stmtObtenerEstatusContratoParaReporte->execute()) {
        $stmtObtenerEstatusContratoParaReporte->store_result();
        $stmtObtenerEstatusContratoParaReporte->bind_result($CodigoRespuesta, $MensajeRespuesta,
            $idReporte, $estatusReporte, $estatusCenso, $estatusVenta, $idEmpleadoParaVenta, $asignadoMexicana,
            $asignadoAyopsa, $validadoMexicana, $validadoAyopsa, $phEstatus, $idEmpleadoPhAsignado,
            $asignacionSegundaVenta, $idEmpleadoSegundaVenta, $validacionSegundaVenta, $idClienteGenerado, $validacionInstalacion,
            $estatusAsignacionInstalacion, $idEmpleadoInstalacion, $idAgenciaInstalacion,
            $fechaAlta, $fechaMod);

        if ($stmtObtenerEstatusContratoParaReporte->fetch()) {
            $response["CodigoRespuesta"] = $CodigoRespuesta;
            $response["MensajeRespuesta"] = $MensajeRespuesta;
            $response["idClienteGenerado"] = $idClienteGenerado;
            $response["idReporte"] = $idReporte;
            $response["estatusReporte"] = $estatusReporte;
            $response["estatusCenso"] = $estatusCenso;
            $response["estatusVenta"] = $estatusVenta;
            $response["idEmpleadoParaVenta"] = $idEmpleadoParaVenta;
            $response["asignadoMexicana"] = $asignadoMexicana;
            $response["asignadoAyopsa"] = $asignadoAyopsa;
            $response["validadoMexicana"] = $validadoMexicana;
            $response["validadoAyopsa"] = $validadoAyopsa;
            $response["phEstatus"] = $phEstatus;
            $response["idEmpleadoPhAsignado"] = $idEmpleadoPhAsignado;
            $response["asignacionSegundaVenta"] = $asignacionSegundaVenta;
            $response["idEmpleadoSegundaVenta"] = $idEmpleadoSegundaVenta;
            $response["validacionSegundaVenta"] = $validacionSegundaVenta;
            $response["idClienteGenerado"] = $idClienteGenerado;
            $response["validacionInstalacion"] = $validacionInstalacion;
            $response["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
            $response["idEmpleadoInstalacion"] = $idEmpleadoInstalacion;
            $response["idAgenciaInstalacion"] = $idAgenciaInstalacion;
            $response["fechaAlta"] = $fechaAlta;
            $response["fechaMod"] = $fechaMod;

        } else {
            $response["CodigoRespuesta"] = 0;
            $response["MensajeRespuesta"] = "NO SE OBTUVIERON CORRECTAMENTE LOS ESTATUS DE ESTE REPORTE";
        }
        $stmtObtenerEstatusContratoParaReporte->free_result();
        return $response;
    }
}

function getDateStatus($tipoReporte, $id, $statusReporte, $created_at)
{
    if ($tipoReporte != "" && $id != "" && $statusReporte != "") {
        //generamos las consultas de pendiendo el estatus de cada reporte
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNumContratoSQL = "SELECT id,idReporte,fechaInicioVenta,fechaFinVenta,fechaInicioFinanciera,fechaFinFinanciera,fechaInicioRechazo,fechaFinRechazo,fechaPrimeraCaptura,fechaSegundaCaptura,fechaInicioAsigPH,fechaFinAsigPH,fechaInicioRealizoPH,fechaFinRealizoPH,fechaInicioAnomPH,fechaFinAnomPH,fechaInicioAsigInst,fechaFinAsigInst,fechaInicioRealInst,fechaFinRealInst,fechaInicioAnomInst,fechaFinAnomInst FROM reportTiempoVentas where 0=0 and idReporte = $id;";
        $fecha=$created_at;
        if ($getNumContratoSQL != "") {
            $result = $conn->query($getNumContratoSQL);
            $res=[];
            if ($result->num_rows > 0) {
                $contador=0;
                while($row = $result->fetch_array()) {
                    switch ($tipoReporte) {
                        case 'Venta':
                            if ($statusReporte == "EN PROCESO" || $statusReporte == "CAPTURA COMPLETADA" || $statusReporte == "REAGENDADA") {
                                $fecha=$row[2];
                            }elseif ($statusReporte == "RECHAZADO") {
                                $fecha=$row[6];
                            }elseif ($statusReporte == "VALIDADO POR MEXICANA") {
                                $fecha=$row[3];
                            }elseif ($statusReporte == "VALIDACIONES COMPLETAS") {
                                if ($row[3] != "" && $row[5] != "") {
                                    $fecha=$row[5];
                                }elseif ($row[3] != "" && $row[5] == "") {
                                    $fecha=$row[3];
                                }elseif ($row[3] == "" && $row[5] != "") {
                                    $fecha=$row[5];
                                }
                            }
                        break;
                        case 'Plomero':
                            if ($statusReporte == "EN PROCESO" || $statusReporte == "REAGENDADA") {
                                $fecha = $row[10];
                            }elseif ($statusReporte == "COMPLETO") {
                                $fecha = $row[11];
                            }
                        break;
                        case 'Instalacion':
                            if ($statusReporte == "EN PROCESO" || $statusReporte == "REAGENDADA") {
                                $fecha = $row[16];
                            }elseif ($statusReporte == "COMPLETO") {
                                $fecha = $row[17];
                            }elseif ($statusReporte == "INSTALACION ENVIADA") {
                                $fecha = $row[19];
                            }
                        break;
                        case 'Segunda Venta':
                            if ($statusReporte == "EN PROCESO") {
                                $fecha = $row[8];
                            }elseif ($statusReporte == "COMPLETO" || $statusReporte == "REVISION_SEGUNDA_CAPTURA") {
                                $fecha = $row[9];
                            }
                        break;
                        case 'Censo':
                            $fecha = $created_at;
                        break;
                    }
                }
            }
            $conn->close();
        }
    }
    return $fecha;
}