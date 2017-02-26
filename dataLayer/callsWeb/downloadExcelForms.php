<?php
date_default_timezone_set('America/Monterrey');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
/** Include PHPExcel */
require_once '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';

$DB = new DAO();
$conn = $DB->getConnect();

$dateFrom = $_POST["dateFrom"];
$dateTo = $_POST["dateTo"];
$idUsuario = $_POST["inputIdUser"];
$isCheckedCompletos = $_POST["isCheckedCompletos"];
$isCheckedPendientes = $_POST["isCheckedPendientes"];
$isCheckedGeneral = $_POST["isCheckedGeneral"];
$txtType = $_POST["txtType"];
$txtStatus = $_POST["txtStatus"];
$search = $_POST["search"];
$txtTypeVal = $_POST["txtTypeVal"];
$txtStatusVal = $_POST["txtStatusVal"];
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

$ROL_SUPERADMINISTRADOR=1;
$ROL_ADMINISTRADOR=2;
$ROL_AGENCIA=3;
$tipoAgencia=getTipoAgencia($idUsuario);
$rolDelUsuarioQueConsulta=getTipoRol($idUsuario);
$idAgenciaABuscar=getIdAgenciaABuscar($idUsuario);
$nombreAgenciaABuscar=getnombreAgenciaABuscar($idUsuario);
//echo 'message rol agencia '.$tipoAgencia;
//mysqli_stmt_bind_param($stmtObtenerReporteContratos, 'ssi', $dateFrom, $dateTo,$idUsuario);
$queryReporte ='SELECT ';
$queryReporte .='tr.id,';
$queryReporte .='(SELECT idClienteGenerado FROM tEstatusContrato where idReporte=tr.id) AS idCliente, ';
$queryReporte .='tr.agreementNumber,';
$queryReporte .='trt.name,';
$queryReporte .='tr.idCity,';
$queryReporte .='tr.colonia,';
$queryReporte .='tr.street,';
$queryReporte .='tr.innerNumber,';
$queryReporte .='tr.outterNumber,';
$queryReporte .='(SELECT tu.nickname FROM user AS tu ';
$queryReporte .='INNER JOIN employee AS te ON te.idUser=tu.id ';
$queryReporte .='WHERE te.idUser = trh.idUserAssigned)AS nicknameEmpleado, ';
$queryReporte .='(SELECT tu.nickname FROM user AS tu ';
$queryReporte .='INNER JOIN agency AS ta ON ta.idUser=tu.id ';
$queryReporte .='INNER JOIN agency_employee AS trae ON trae.idAgency=ta.id ';
$queryReporte .='INNER JOIN employee AS te ON te.id=trae.idEmployee ';
$queryReporte .='WHERE te.idUser = trh.idUserAssigned ';
$queryReporte .=')AS nicknameAgencia,';
$queryReporte .='trh.idFormSell, ';
$queryReporte .='trh.idFormulario, ';
$queryReporte .='te.estatusReporte,';
$queryReporte .='te.estatusCenso,';
$queryReporte .='te.estatusVenta,';
$queryReporte .='te.idEmpleadoParaVenta,';
$queryReporte .='te.asignadoMexicana,';
$queryReporte .='te.asignadoAyopsa,';
$queryReporte .='te.validadoMexicana,';
$queryReporte .='te.validadoAyopsa,';
$queryReporte .='te.phEstatus,';
$queryReporte .='te.asignacionSegundaVenta,';
$queryReporte .='te.validacionSegundaVenta,';
$queryReporte .='te.estatusAsignacionInstalacion,';
$queryReporte .='te.idEmpleadoInstalacion,';
$queryReporte .='te.idAgenciaInstalacion,';
$queryReporte .='te.validacionInstalacion,';
$queryReporte .='trh.idReportType,';
$queryReporte .='trh.idUserAssigned,';
$queryReporte .='tr.created_at, ';
$queryReporte .='rptv.fechaInicioVenta, ';
$queryReporte .='rptv.fechaFinVenta, ';
$queryReporte .='rptv.fechaInicioFinanciera, ';
$queryReporte .='rptv.fechaFinFinanciera, ';
$queryReporte .='rptv.fechaInicioRechazo, ';
$queryReporte .='rptv.fechaFinRechazo, ';
$queryReporte .='rptv.fechaPrimeraCaptura, ';
$queryReporte .='rptv.fechaSegundaCaptura, ';
$queryReporte .='rptv.fechaInicioAsigPH, ';
$queryReporte .='rptv.fechaFinAsigPH, ';
$queryReporte .='rptv.fechaInicioRealizoPH, ';
$queryReporte .='rptv.fechaFinRealizoPH, ';
$queryReporte .='rptv.fechaInicioAnomPH, ';
$queryReporte .='rptv.fechaFinAnomPH, ';
$queryReporte .='rptv.fechaInicioAsigInst, ';
$queryReporte .='rptv.fechaFinAsigInst, ';
$queryReporte .='rptv.fechaInicioRealInst, ';
$queryReporte .='rptv.fechaFinRealInst, ';
$queryReporte .='rptv.fechaInicioAnomInst, ';
$queryReporte .='rptv.fechaFinAnomInst ';
$queryReporte .='FROM report AS tr ';
$queryReporte .='INNER JOIN tEstatusContrato AS te ON te.idReporte=tr.id ';
$queryReporte .='INNER JOIN reportHistory AS trh ON trh.idReport=tr.id ';
$queryReporte .='INNER JOIN reportType AS trt ON trt.id=trh.idReportType ';
$queryReporte .='LEFT JOIN reportTiempoVentas AS rptv ON trh.idReport = rptv.idReporte ';
if (($rolDelUsuarioQueConsulta == $ROL_AGENCIA && $tipoAgencia == 'Instalacion') || $rolDelUsuarioQueConsulta == $ROL_AGENCIA) {
    if ($nombreAgenciaABuscar != 'AYOPSA') {
        $queryReporte .='INNER JOIN employee AS tae ON tae.id=tr.idEmployee ';
        $queryReporte .='INNER JOIN user as us on us.id=tae.idUser ';
        $queryReporte .='INNER JOIN agency_employee tarae ON tarae.idemployee=tae.id ';
        $queryReporte .='INNER JOIN agency AS taa ON taa.id=tarae.idAgency ';
    }
}
$queryReporte .='WHERE 0=0 ';
if ($rolDelUsuarioQueConsulta == $ROL_AGENCIA && $tipoAgencia == 'Instalacion') {
    $queryReporte .='AND trh.idReportType=4 ';
    $queryReporte .='AND (taa.id='.$idAgenciaABuscar.' 
                            OR tr.id in (select idReport from reportHistory as reph inner join employee as e on e.idUser=reph.idUserAssigned 
                            inner join agency_employee as ae on ae.idemployee=e.id where ae.idAgency='.$idAgenciaABuscar.')) ';
}elseif ($rolDelUsuarioQueConsulta == $ROL_AGENCIA && strtoupper($tipoAgencia) != strtoupper('CallCenter')) {
    if ($nombreAgenciaABuscar == 'AYOPSA') {
        $queryReporte .='AND trh.idFormSell in (select fs.id from form_sells fs where fs.id=trh.idFormSell and fs.financialService =1)';
        $queryReporte .='AND trh.idReportType = 2 ';
        $queryReporte .='AND ((te.validadoMexicana = 6 OR te.validadoMexicana = 22) OR (te.estatusVenta = 10) OR (te.validadoAyopsa = 21 OR te.validadoAyopsa = 20)) ';
    }else{
        $queryReporte .='AND (taa.id='.$idAgenciaABuscar.' 
                                OR tr.id in (select idReport from reportHistory as reph inner join employee as e on e.idUser=reph.idUserAssigned 
                                inner join agency_employee as ae on ae.idemployee=e.id where ae.idAgency='.$idAgenciaABuscar.') 
                              ) ';
    }
        
}
if ($search != "") {
    $queryReporte .='AND (tr.agreementNumber like "%'.$search.'%"
                          OR trt.name like "%'.$search.'%"
                          OR tr.idCity like "%'.$search.'%"
                          OR tr.colonia like "%'.$search.'%"
                          OR tr.street like "%'.$search.'%") ';
}
$queryReporte .=' order by tr.agreementNumber, tr.created_at desc';
$result = $conn->query($queryReporte);
$res="";
$cont=0;
$isCheckedCompletos = $isCheckedCompletos === 'true'? true: false;
$isCheckedPendientes = $isCheckedPendientes === 'true'? true: false;
$isCheckedGeneral = $isCheckedGeneral === 'true'? true: false;
if ($isCheckedCompletos === true) {
    $tipoReporte="completos";
}elseif ($isCheckedPendientes === true) {
    $tipoReporte="pendientes";
}elseif ($isCheckedGeneral === true) {
    $tipoReporte="general";
}else{
    $tipoReporte="pendientes";
}
//echo "tipoReporte ".$tipoReporte;
if ($result->num_rows > 0) {
    while($row = $result->fetch_array()) {
        //if (intval($row["agreementNumber"]) == 23493) {
            $idReporte=$row["id"];
            $estatusCenso=$row["estatusCenso"];
            $estatusReporte=$row["estatusReporte"];
            $estatusVenta=$row["estatusVenta"];
            $validadoMexicana=$row["validadoMexicana"];
            $validadoAyopsa=$row["validadoAyopsa"];
            $phEstatus=$row["phEstatus"];
            $estatusSegundaVenta=$row["asignacionSegundaVenta"];
            $validacionSegundaVenta=$row["validacionSegundaVenta"];
            $validacionInstalacion=$row["validacionInstalacion"];
            $estatusAsignacionInstalacion=$row["estatusAsignacionInstalacion"];
            $idReportType=$row["idReportType"];

            $estatusTexto = validarEstatusDesdeLaTablaDeEstatusControl(
                    $idReporte,$estatusCenso, $estatusReporte, $estatusVenta, $validadoMexicana, $validadoAyopsa,
                    $phEstatus, $estatusSegundaVenta, $validacionSegundaVenta,
                    $validacionInstalacion, $estatusAsignacionInstalacion, $idReportType
            );

            switch ($row["name"]) {
                case 'Venta':
                    if ($estatusTexto == "EN PROCESO" || $estatusTexto == "CAPTURA COMPLETADA" || $estatusTexto == "REAGENDADA") {
                        $fecha=$row['fechaInicioVenta'];
                    }elseif ($estatusTexto == "RECHAZADO") {
                        if ($row['fechaInicioRechazo'] != "" && $row['fechaFinRechazo'] != "") {
                            $fecha=$row['fechaFinRechazo'];
                        }elseif ($row['fechaInicioRechazo'] != "" && $row['fechaFinRechazo'] == "") {
                            $fecha=$row['fechaInicioRechazo'];
                        }elseif ($row['fechaInicioRechazo'] == "" && $row['fechaFinRechazo'] != "") {
                            $fecha=$row['fechaFinRechazo'];
                        }
                    }elseif ($estatusTexto == "VALIDADO POR MEXICANA") {
                        $fecha=$row['fechaInicioFinanciera'];
                    }elseif ($estatusTexto == "VALIDACIONES COMPLETAS") {
                        if ($row['fechaInicioFinanciera'] != "" && $row['fechaFinFinanciera'] != "") {
                            $fecha=$row['fechaFinFinanciera'];
                        }elseif ($row['fechaInicioFinanciera'] != "" && $row['fechaFinFinanciera'] == "") {
                            $fecha=$row['fechaInicioFinanciera'];
                        }elseif ($row['fechaInicioFinanciera'] == "" && $row['fechaFinFinanciera'] != "") {
                            $fecha=$row['fechaFinFinanciera'];
                        }
                    }
                break;
                case 'Plomero':
                    if ($estatusTexto == "EN PROCESO" || $estatusTexto == "REAGENDADA") {
                        $fecha = $row['fechaInicioAsigPH'];
                    }elseif ($estatusTexto == "COMPLETO") {
                        $fecha = $row['fechaFinRealizoPH'];
                    }
                break;
                case 'Instalacion':
                    if ($estatusTexto == "EN PROCESO" || $estatusTexto == "REAGENDADA") {
                        $fecha = $row['fechaInicioAsigInst'];
                    }elseif ($estatusTexto == "COMPLETO") {
                        $fecha = $row['fechaFinAsigInst'];
                    }elseif ($estatusTexto == "INSTALACION ENVIADA") {
                        $fecha = $row['fechaFinRealInst'];
                    }
                break;
                case 'Segunda Venta':
                    if ($estatusTexto == "EN PROCESO") {
                        $fecha = $row['fechaPrimeraCaptura'];
                    }elseif ($estatusTexto == "COMPLETO" || $estatusTexto == "REVISION_SEGUNDA_CAPTURA") {
                        $fecha = $row['fechaSegundaCaptura'];
                    }
                break;
                case 'Censo':
                    $fecha = $row["created_at"];
                break;
            }
            /*echo "txtTypeVal ".$txtTypeVal."\n";
            echo "txtStatusVal ".$txtStatusVal."\n";*/
            if (($txtTypeVal != "" && intval($txtTypeVal) > 0) &&
                ($txtStatusVal != "" && $txtStatusVal == "Todos los estatus")) {
                if ($txtType == $row["name"]) {
                    if ($dateFrom != "" && $dateTo != "") {
                        $reportDate = date('Y-m-d');
                        $reportDate=date('Y-m-d', strtotime($fecha));;
                        $dateFrom = date('Y-m-d', strtotime($dateFrom));
                        $dateTo = date('Y-m-d', strtotime($dateTo));
                        /*echo "reportDate ".$reportDate."\n";
                        echo "dateFrom ".$dateFrom."\n";
                        echo "dateTo ".$dateTo."\n";*/
                        if (($reportDate > $dateFrom) && ($reportDate < $dateTo)){
                            if (($tipoReporte == "pendientes") && (intval($row["estatusAsignacionInstalacion"]) != 54)){
                                $res[$cont]["id"]=$row["id"];
                                $res[$cont]["idCliente"]=$row["idCliente"];
                                $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                                $res[$cont]["name"]=$row["name"];
                                $res[$cont]["idCity"]=$row["idCity"];
                                $res[$cont]["colonia"]=$row["colonia"];
                                $res[$cont]["street"]=$row["street"];
                                $res[$cont]["innerNumber"]=$row["innerNumber"];
                                $res[$cont]["outterNumber"]=$row["outterNumber"];
                                $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                                $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                                $res[$cont]["idFormSell"]=$row["idFormSell"];
                                $res[$cont]["idFormulario"]=$row["idFormulario"];
                                $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                                $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                                $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                                $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                                $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                                $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                                $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                                $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                                $res[$cont]["phEstatus"]=$row["phEstatus"];
                                $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                                $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                                $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                                $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                                $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                                $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                                $res[$cont]["idReportType"]=$row["idReportType"];
                                $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                                $res[$cont]["created_at"]=$fecha;
                                $res[$cont]["created_at_orig"]=$row["created_at"];
                                $res[$cont]['estatusReporte']=$estatusTexto;
                            }elseif (($_POST["tipoReportes"] == "completos") &&
                                     (intval($estatusAsignacionInstalacion) == 54 || 
                                      intval($estatusAsignacionInstalacion) == 53 || 
                                      intval($estatusAsignacionInstalacion) == 55)){
                                $res[$cont]["id"]=$row["id"];
                                $res[$cont]["idCliente"]=$row["idCliente"];
                                $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                                $res[$cont]["name"]=$row["name"];
                                $res[$cont]["idCity"]=$row["idCity"];
                                $res[$cont]["colonia"]=$row["colonia"];
                                $res[$cont]["street"]=$row["street"];
                                $res[$cont]["innerNumber"]=$row["innerNumber"];
                                $res[$cont]["outterNumber"]=$row["outterNumber"];
                                $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                                $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                                $res[$cont]["idFormSell"]=$row["idFormSell"];
                                $res[$cont]["idFormulario"]=$row["idFormulario"];
                                $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                                $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                                $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                                $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                                $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                                $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                                $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                                $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                                $res[$cont]["phEstatus"]=$row["phEstatus"];
                                $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                                $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                                $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                                $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                                $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                                $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                                $res[$cont]["idReportType"]=$row["idReportType"];
                                $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                                $res[$cont]["created_at"]=$fecha;
                                $res[$cont]["created_at_orig"]=$row["created_at"];
                                $res[$cont]['estatusReporte']=$estatusTexto;
                            }elseif ($tipoReporte == "general"){
                                $res[$cont]["id"]=$row["id"];
                                $res[$cont]["idCliente"]=$row["idCliente"];
                                $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                                $res[$cont]["name"]=$row["name"];
                                $res[$cont]["idCity"]=$row["idCity"];
                                $res[$cont]["colonia"]=$row["colonia"];
                                $res[$cont]["street"]=$row["street"];
                                $res[$cont]["innerNumber"]=$row["innerNumber"];
                                $res[$cont]["outterNumber"]=$row["outterNumber"];
                                $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                                $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                                $res[$cont]["idFormSell"]=$row["idFormSell"];
                                $res[$cont]["idFormulario"]=$row["idFormulario"];
                                $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                                $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                                $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                                $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                                $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                                $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                                $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                                $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                                $res[$cont]["phEstatus"]=$row["phEstatus"];
                                $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                                $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                                $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                                $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                                $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                                $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                                $res[$cont]["idReportType"]=$row["idReportType"];
                                $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                                $res[$cont]["created_at"]=$fecha;
                                $res[$cont]["created_at_orig"]=$row["created_at"];
                                $res[$cont]['estatusReporte']=$estatusTexto;
                            }
                        }else{
                            //echo $cont." no esta entre el rango";
                        }
                    }else{
                        if (($tipoReporte == "pendientes") && (intval($row["estatusAsignacionInstalacion"]) != 54)){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }elseif (($tipoReporte == "completos") && (intval($row["estatusAsignacionInstalacion"]) == 54)){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }elseif ($tipoReporte == "general"){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }
                    }
                }
            }elseif (($txtTypeVal != "" && intval($txtTypeVal) > 0) &&
                     ($txtStatusVal != "" && $txtStatusVal != "Todos los estatus")) {
                if ($txtType == $row["name"] && $txtStatus == $estatusTexto) {
                    if ($dateFrom != "" && $dateTo != "") {
                        $reportDate = date('Y-m-d');
                        $reportDate=date('Y-m-d', strtotime($fecha));;
                        $dateFrom = date('Y-m-d', strtotime($dateFrom));
                        $dateTo = date('Y-m-d', strtotime($dateTo));
                        /*echo "reportDate ".$reportDate."\n";
                        echo "dateFrom ".$dateFrom."\n";
                        echo "dateTo ".$dateTo."\n";*/
                        if (($reportDate > $dateFrom) && ($reportDate < $dateTo)){
                            if (($tipoReporte == "pendientes") && (intval($row["estatusAsignacionInstalacion"]) != 54)){
                                $res[$cont]["id"]=$row["id"];
                                $res[$cont]["idCliente"]=$row["idCliente"];
                                $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                                $res[$cont]["name"]=$row["name"];
                                $res[$cont]["idCity"]=$row["idCity"];
                                $res[$cont]["colonia"]=$row["colonia"];
                                $res[$cont]["street"]=$row["street"];
                                $res[$cont]["innerNumber"]=$row["innerNumber"];
                                $res[$cont]["outterNumber"]=$row["outterNumber"];
                                $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                                $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                                $res[$cont]["idFormSell"]=$row["idFormSell"];
                                $res[$cont]["idFormulario"]=$row["idFormulario"];
                                $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                                $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                                $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                                $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                                $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                                $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                                $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                                $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                                $res[$cont]["phEstatus"]=$row["phEstatus"];
                                $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                                $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                                $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                                $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                                $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                                $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                                $res[$cont]["idReportType"]=$row["idReportType"];
                                $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                                $res[$cont]["created_at"]=$fecha;
                                $res[$cont]["created_at_orig"]=$row["created_at"];
                                $res[$cont]['estatusReporte']=$estatusTexto;
                            }elseif (($tipoReporte == "completos") && (intval($row["estatusAsignacionInstalacion"]) == 54)){
                                $res[$cont]["id"]=$row["id"];
                                $res[$cont]["idCliente"]=$row["idCliente"];
                                $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                                $res[$cont]["name"]=$row["name"];
                                $res[$cont]["idCity"]=$row["idCity"];
                                $res[$cont]["colonia"]=$row["colonia"];
                                $res[$cont]["street"]=$row["street"];
                                $res[$cont]["innerNumber"]=$row["innerNumber"];
                                $res[$cont]["outterNumber"]=$row["outterNumber"];
                                $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                                $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                                $res[$cont]["idFormSell"]=$row["idFormSell"];
                                $res[$cont]["idFormulario"]=$row["idFormulario"];
                                $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                                $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                                $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                                $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                                $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                                $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                                $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                                $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                                $res[$cont]["phEstatus"]=$row["phEstatus"];
                                $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                                $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                                $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                                $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                                $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                                $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                                $res[$cont]["idReportType"]=$row["idReportType"];
                                $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                                $res[$cont]["created_at"]=$fecha;
                                $res[$cont]["created_at_orig"]=$row["created_at"];
                                $res[$cont]['estatusReporte']=$estatusTexto;
                            }elseif ($tipoReporte == "general"){
                                $res[$cont]["id"]=$row["id"];
                                $res[$cont]["idCliente"]=$row["idCliente"];
                                $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                                $res[$cont]["name"]=$row["name"];
                                $res[$cont]["idCity"]=$row["idCity"];
                                $res[$cont]["colonia"]=$row["colonia"];
                                $res[$cont]["street"]=$row["street"];
                                $res[$cont]["innerNumber"]=$row["innerNumber"];
                                $res[$cont]["outterNumber"]=$row["outterNumber"];
                                $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                                $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                                $res[$cont]["idFormSell"]=$row["idFormSell"];
                                $res[$cont]["idFormulario"]=$row["idFormulario"];
                                $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                                $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                                $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                                $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                                $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                                $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                                $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                                $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                                $res[$cont]["phEstatus"]=$row["phEstatus"];
                                $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                                $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                                $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                                $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                                $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                                $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                                $res[$cont]["idReportType"]=$row["idReportType"];
                                $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                                $res[$cont]["created_at"]=$fecha;
                                $res[$cont]["created_at_orig"]=$row["created_at"];
                                $res[$cont]['estatusReporte']=$estatusTexto;
                            }
                        }else{
                            //echo $cont." no esta entre el rango";
                        }
                    }else{
                        if (($tipoReporte == "pendientes") && (intval($row["estatusAsignacionInstalacion"]) != 54)){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }elseif (($tipoReporte == "completos") && (intval($row["estatusAsignacionInstalacion"]) == 54)){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }elseif ($tipoReporte == "general"){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }
                    }
                }
            }elseif(($txtTypeVal != "" && intval($txtTypeVal) == 0) &&
                    ($txtStatusVal != "" && intval($txtStatusVal) == 0)){
                if ($dateFrom != "" && $dateTo != "") {
                    $reportDate = date('Y-m-d');
                    $reportDate=date('Y-m-d', strtotime($fecha));;
                    $dateFrom = date('Y-m-d', strtotime($dateFrom));
                    $dateTo = date('Y-m-d', strtotime($dateTo));
                    /*echo "reportDate ".$reportDate."\n";
                    echo "dateFrom ".$dateFrom."\n";
                    echo "dateTo ".$dateTo."\n";*/
                    if (($reportDate > $dateFrom) && ($reportDate < $dateTo)){
                        if (($tipoReporte == "pendientes") && (intval($row["estatusAsignacionInstalacion"]) != 54)){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }elseif (($tipoReporte == "completos") && (intval($row["estatusAsignacionInstalacion"]) == 54) ){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }elseif ($tipoReporte == "general"){
                            $res[$cont]["id"]=$row["id"];
                            $res[$cont]["idCliente"]=$row["idCliente"];
                            $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                            $res[$cont]["name"]=$row["name"];
                            $res[$cont]["idCity"]=$row["idCity"];
                            $res[$cont]["colonia"]=$row["colonia"];
                            $res[$cont]["street"]=$row["street"];
                            $res[$cont]["innerNumber"]=$row["innerNumber"];
                            $res[$cont]["outterNumber"]=$row["outterNumber"];
                            $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                            $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                            $res[$cont]["idFormSell"]=$row["idFormSell"];
                            $res[$cont]["idFormulario"]=$row["idFormulario"];
                            $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                            $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                            $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                            $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                            $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                            $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                            $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                            $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                            $res[$cont]["phEstatus"]=$row["phEstatus"];
                            $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                            $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                            $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                            $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                            $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                            $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                            $res[$cont]["idReportType"]=$row["idReportType"];
                            $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                            $res[$cont]["created_at"]=$fecha;
                            $res[$cont]["created_at_orig"]=$row["created_at"];
                            $res[$cont]['estatusReporte']=$estatusTexto;
                        }
                    }
                }else{
                    if (($tipoReporte == "pendientes") && (intval($row["estatusAsignacionInstalacion"]) != 54)){
                        $res[$cont]["id"]=$row["id"];
                        $res[$cont]["idCliente"]=$row["idCliente"];
                        $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                        $res[$cont]["name"]=$row["name"];
                        $res[$cont]["idCity"]=$row["idCity"];
                        $res[$cont]["colonia"]=$row["colonia"];
                        $res[$cont]["street"]=$row["street"];
                        $res[$cont]["innerNumber"]=$row["innerNumber"];
                        $res[$cont]["outterNumber"]=$row["outterNumber"];
                        $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                        $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                        $res[$cont]["idFormSell"]=$row["idFormSell"];
                        $res[$cont]["idFormulario"]=$row["idFormulario"];
                        $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                        $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                        $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                        $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                        $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                        $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                        $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                        $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                        $res[$cont]["phEstatus"]=$row["phEstatus"];
                        $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                        $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                        $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                        $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                        $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                        $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                        $res[$cont]["idReportType"]=$row["idReportType"];
                        $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                        $res[$cont]["created_at"]=$fecha;
                        $res[$cont]["created_at_orig"]=$row["created_at"];
                        $res[$cont]['estatusReporte']=$estatusTexto;
                    }elseif (($tipoReporte == "completos") && (intval($row["estatusAsignacionInstalacion"]) == 54)){
                        $res[$cont]["id"]=$row["id"];
                        $res[$cont]["idCliente"]=$row["idCliente"];
                        $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                        $res[$cont]["name"]=$row["name"];
                        $res[$cont]["idCity"]=$row["idCity"];
                        $res[$cont]["colonia"]=$row["colonia"];
                        $res[$cont]["street"]=$row["street"];
                        $res[$cont]["innerNumber"]=$row["innerNumber"];
                        $res[$cont]["outterNumber"]=$row["outterNumber"];
                        $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                        $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                        $res[$cont]["idFormSell"]=$row["idFormSell"];
                        $res[$cont]["idFormulario"]=$row["idFormulario"];
                        $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                        $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                        $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                        $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                        $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                        $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                        $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                        $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                        $res[$cont]["phEstatus"]=$row["phEstatus"];
                        $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                        $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                        $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                        $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                        $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                        $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                        $res[$cont]["idReportType"]=$row["idReportType"];
                        $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                        $res[$cont]["created_at"]=$fecha;
                        $res[$cont]["created_at_orig"]=$row["created_at"];
                        $res[$cont]['estatusReporte']=$estatusTexto;
                    }elseif ($tipoReporte == "general"){
                        $res[$cont]["id"]=$row["id"];
                        $res[$cont]["idCliente"]=$row["idCliente"];
                        $res[$cont]["agreementNumber"]=$row["agreementNumber"];
                        $res[$cont]["name"]=$row["name"];
                        $res[$cont]["idCity"]=$row["idCity"];
                        $res[$cont]["colonia"]=$row["colonia"];
                        $res[$cont]["street"]=$row["street"];
                        $res[$cont]["innerNumber"]=$row["innerNumber"];
                        $res[$cont]["outterNumber"]=$row["outterNumber"];
                        $res[$cont]["nicknameEmpleado"]=$row["nicknameEmpleado"];
                        $res[$cont]["nicknameAgencia"]=$row["nicknameAgencia"];
                        $res[$cont]["idFormSell"]=$row["idFormSell"];
                        $res[$cont]["idFormulario"]=$row["idFormulario"];
                        $res[$cont]["estatusReporte"]=$row["estatusReporte"];
                        $res[$cont]["estatusCenso"]=$row["estatusCenso"];
                        $res[$cont]["estatusVenta"]=$row["estatusVenta"];
                        $res[$cont]["idEmpleadoParaVenta"]=$row["idEmpleadoParaVenta"];
                        $res[$cont]["asignadoMexicana"]=$row["asignadoMexicana"];
                        $res[$cont]["asignadoAyopsa"]=$row["asignadoAyopsa"];
                        $res[$cont]["validadoMexicana"]=$row["validadoMexicana"];
                        $res[$cont]["validadoAyopsa"]=$row["validadoAyopsa"];
                        $res[$cont]["phEstatus"]=$row["phEstatus"];
                        $res[$cont]["asignacionSegundaVenta"]=$row["asignacionSegundaVenta"];
                        $res[$cont]["validacionSegundaVenta"]=$row["validacionSegundaVenta"];
                        $res[$cont]["estatusAsignacionInstalacion"]=$row["estatusAsignacionInstalacion"];
                        $res[$cont]["idEmpleadoInstalacion"]=$row["idEmpleadoInstalacion"];
                        $res[$cont]["idAgenciaInstalacion"]=$row["idAgenciaInstalacion"];
                        $res[$cont]["validacionInstalacion"]=$row["validacionInstalacion"];
                        $res[$cont]["idReportType"]=$row["idReportType"];
                        $res[$cont]["idUserAssigned"]=$row["idUserAssigned"];
                        $res[$cont]["created_at"]=$fecha;
                        $res[$cont]["created_at_orig"]=$row["created_at"];
                        $res[$cont]['estatusReporte']=$estatusTexto;
                    }
                }
            }
                
            //se obtienen los datos del formulario de venta
            $cont++;
        //}
    }
    echo json_encode($res);
}else{
    echo "tipoReporte ".$queryReporte;
    $response["status"] = "ERROR";
    $response["code"] = "500";
    $response["response"] = "No se encontraron datos";
    echo json_encode($response);
}
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
            $estatus = getEstatusVenta($idReporte,$estatusVenta, $validadoMexicana, $validadoAyopsa);
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
            $estatus = "REVISION_SEGUNDA_CAPTURA";
        }else{
            $estatus = $GLOBALS["ESTATUS_TEXTO_CAPTURA_COMPLETA"];

        }
    }elseif($estatusSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_REVISION']){
        //echo "string ESTATUS_TEXTO_SEGUNDA_VENTA_REVISION ".$GLOBALS["ESTATUS_TEXTO_SEGUNDA_VENTA_REVISION"];
        $estatus = "REVISION_SEGUNDA_CAPTURA";
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

function getTipoAgencia($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT ap.name
                       FROM agency_profile ap
                       inner join agency a on ap.idAgency = a.id 
                       where 0=0
                       and a.idUser = $idUser;";
        $result = $conn->query($getNameSQL);
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
function getTipoRol($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT idRol
                        FROM user_rol
                        WHERE idUser= $idUser;";
        $result = $conn->query($getNameSQL);
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
function getIdAgenciaABuscar($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT ta.id
                       FROM agency AS ta
                       INNER JOIN user AS tu ON tu.id=ta.idUser
                       WHERE tu.id= $idUser;";
        $result = $conn->query($getNameSQL);
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
function getnombreAgenciaABuscar($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {

        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT tu.nickname
                       FROM agency AS ta
                       INNER JOIN user AS tu ON tu.id=ta.idUser
                       WHERE tu.id= $idUser;";
        //echo "sql ".$getNameSQL;
        $result = $conn->query($getNameSQL);
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
