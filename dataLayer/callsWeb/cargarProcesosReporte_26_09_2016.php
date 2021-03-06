<?php
/**
 * Created by PhpStorm.
 * User: RJUAREZR
 * Date: 30/07/2016
 * Time: 11:25 AM
 */

include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
session_start();

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
$ESTATUS_TEXTO_EN_PROCESO = "EN PROCESO";
$ESTATUS_TEXTO_PENDIENTE_VALIDACION = "PENDIENTE VALIDACION";
$ESTATUS_TEXTO_VALIDADO_POR_MEXICANA = "VALIDADO POR MEXICANA";
$ESTATUS_TEXTO_RECHAZADO_POR_MEXICANA = "VALIDACION RECHAZADA POR MEXICANA";
$ESTATUS_TEXTO_RECHAZADO_POR_AYOPSA = "VALIDACION RECHAZADA POR AYOPSA";
$ESTATUS_TEXTO_VALIDACIONES_COMPLETAS = "VALIDACIONES COMPLETAS";
$ESTATUS_TEXTO_REAGENDADA = "REAGENDADA";
$ESTATUS_TEXTO_CANCELADA = "CANCELADA";
$ESTATUS_TEXTO_EN_PROCESO_DE_VALIDACION_DE_VENTA = "EN PROCESO DE VALIDACION DE VENTA";
$ESTATUS_TEXTO_VALIDADO_POR_CREDITO = "VALIDADO POR CREDITO";
$ESTATUS_TEXTO_VALIDADO_POR_CONTADO = "VALIDADO POR CONTADO";
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
$ESTATUS_TEXTO_REPORTE_EN_PROCESO = "REPORTE EN PROCESO";
$ESTATUS_TEXTO_REPORTE_COMPLETO = "REPORTE COMPLETO";
$ESTATUS_TEXTO_REPORTE_RECHAZADO = "REPORTE RECHAZADO";
$ESTATUS_TEXTO_RECHAZADA = "RECHAZADA";

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

/**ESTATUS DE SEGUNDA VENTA**/
$ESTATUS_SEGUNDA_VENTA_EN_PROCESO = 40;
$ESTATUS_SEGUNDA_VENTA_COMPLETA = 41;
$ESTATUS_SEGUNDA_VENTA_CANCELADA = 42;

/**ESTATUS DE INSTALACION**/
$ESTATUS_INSTALACION_EN_PROCESO = 50;
$ESTATUS_INSTALACION_COMPLETA = 51;
$ESTATUS_INSTALACION_RECHAZADA = 52;
$ESTATUS_INSTALACION_CANCELADA = 53;

/**ESTATUS DE REPORTE***/
$ESTATUS_REPORTE_EN_PROCESO = 60;
$ESTATUS_REPORTE_COMPLETO = 61;
$ESTATUS_REPORTE_RECHAZADO = 62;
$ESTATUS_REPORTE_REAGENDADO = 63;
$ESTATUS_REPORTE_CANCELADO = 64;

/**ESTATUS DE CENSO**/
$ESTATUS_CENSO_NO_APLICA = 0;
$ESTATUS_CENSO_EN_PROCESO = 70;
$ESTATUS_CENSO_COMPLETO = 71;
$ESTATUS_CENSO_REAGENDADO = 72;



if (
isset($_POST["idReporte"])
) {

    $isCallCenter = isset($_POST["tipoAgencia"]) && $_POST["tipoAgencia"] == "CallCenter";
    /****OBTENEMOS LA INFORMACION DEL ESTATUS DEL REPORTE DESDE LA TABLA DE ESTATUS DE CONTRATO PARA IR CONSTRUYENDO LAS REGLAS
     * Y ASI IR PINTANDO EL DROPDOWN DEL PROCESO**/

    $DB = new DAO();
    $conn = $DB->getConnect();
    $idReporte = $_POST["idReporte"];
    $tipoReporte = $_POST["tipoReporte"];
    $estatus = $_POST["estatus"];
    $response = obtenerEstatusContratoParaReporte($conn, $idReporte);

    /***EMPEZAMOS LAS VALIDACIONES PARA SABER QUE VAMOS A IMPRIMIR DEPENDIENDO DE NUESTRAS REGLAS DE NEGOCIO Y LA COMPARATIVA DE LOS ESTATUS EN LA TABLA DE
     * ESTATUS CONTRATO****/
    $CodigoRespuesta = $response["CodigoRespuesta"];
    if ($CodigoRespuesta == 0) {
        echo json_encode($response);
    } else {
        $estatusCenso = $response["estatusCenso"];
        $estatusReporte = $response["estatusReporte"];
        $estatusVenta = $response["estatusVenta"];

        $validadoMexicana = $response["validadoMexicana"];
        $validadoAyopsa = $response["validadoAyopsa"];

        $phEstatus = $response["phEstatus"];
        $estatusSegundaVenta = isset($response["estatusSegundaVenta"]) ? $response["estatusSegundaVenta"] :"";


        $validacionSegundaVenta = $response["validacionSegundaVenta"];
        $validacionInstalacion = $response["validacionInstalacion"];
        $estatusAsignacionInstalacion = $response["estatusAsignacionInstalacion"];

        $permisosDelProceso = generarDropDownEnBaseAPermisos($response, $isCallCenter);
        $estatusTexto = validarEstatusDesdeLaTablaDeEstatusControl($estatusCenso, $estatusReporte, $estatusVenta, $validadoMexicana, $validadoAyopsa,
            $phEstatus, $estatusSegundaVenta, $validacionSegundaVenta,
            $validacionInstalacion, $estatusAsignacionInstalacion);
        $estatusColores = generarSpanDeEstatusPorColor($estatusTexto);
        $idReporte = $response["idReporte"];
        $botonAsignarTarea = generarBotonAsignarTarea($estatusCenso, $estatus, $idReporte);

        $arrayProcesosReporte = array(
            'permisosDelProceso' => $permisosDelProceso,
            'estatusColores' => $estatusColores,
            'botonAsignarTarea' => $botonAsignarTarea
        );

        echo json_encode($arrayProcesosReporte);

    }

} else {
    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "DATOS INGRESADOS INCORRECTAMENTE";
    echo json_encode($response);
}

function generarDropDownEnBaseAPermisos($response, $isCallCenter)
{
    /**VALIDAMOS EL PRIMER NIVEL QUE ES SABER SI TIENE HABILITADO EL ESTATUS DEL CENSO, ESTO PARA SABER QUE DEVOLVERA UN BOTON DE CENSO O BIEN
     * SI ENTRARA AL DROPDOWN DE PROCESOS DE VENTA**/

    $idReporte = $response["idReporte"];
    $estatusReporte = $response["estatusReporte"];
    $estatusCenso = $response["estatusCenso"];
    $estatusVenta = $response["estatusVenta"];
    $idEmpleadoParaVenta = $response["idEmpleadoParaVenta"];
    $asignadoMexicana = $response["asignadoMexicana"];
    $asignadoAyopsa = $response["asignadoAyopsa"];
    $validadoMexicana = $response["validadoMexicana"];
    $validadoAyopsa = $response["validadoAyopsa"];
    $phEstatus = $response["phEstatus"];
    $idEmpleadoPhAsignado = $response["idEmpleadoPhAsignado"];
    $asignacionSegundaVenta = $response["asignacionSegundaVenta"];
    $idEmpleadoSegundaVenta = $response["idEmpleadoSegundaVenta"];
    $validacionSegundaVenta = $response["validacionSegundaVenta"];
    $idClienteGenerado = $response["idClienteGenerado"];
    $validacionInstalacion = $response["validacionInstalacion"];
    $estatusAsignacionInstalacion = $response["estatusAsignacionInstalacion"];
    $idEmpleadoInstalacion = $response["idEmpleadoInstalacion"];
    $idAgenciaInstalacion = $response["idAgenciaInstalacion"];
    $fechaAlta = $response["fechaAlta"];
    $fechaMod = $response["fechaMod"];

    $permisosDelProceso = "";
    $NicknameUsuarioLogeado = $_SESSION["nickname"];
    $idUsuario =  $_SESSION["id"];
    if ($NicknameUsuarioLogeado != 'AYOPSA') {
        /**PRIMERO CHECAMOS SI EL REPORTE YA ESTA TERMINADO DE LO CONTRARIO SEGUIMOS CON EL PROCESO DE VENTA***/
        if ($estatusReporte == $GLOBALS['ESTATUS_REPORTE_EN_PROCESO']) {
            /**AHORA CHECAMOS QUE NO SEA UN CENSO DICHO REPORTE PARA CONTINUAR MOSTRANDO LAS VENTAS****/
            if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_NO_APLICA']) {
                /**SI ENTRAMOS AQUI SIGNIFICA QUE ES UNA VENTA, REVISAMOS PRIMERAMENTE SI ESTA VALIDADA POR MEXICANA Y AYOPSA, ENTONCES DEBE ESTAR
                 * HABILITADA LA SEGUNDA VENTA, DE LO CONTRARIO SOLO MOSTRAMOS LA PRIMERA VENTA, ADEMAS DE CHECAR EN QUE ESTATUS ESTA LA MISMA***/

                if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {

                        $permisosDelProceso .= !$isCallCenter ? 
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            : 
                            '<li style="background-color: lightyellow;">' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_COMPLETA']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ? 
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';

                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_EN_PROCESO']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ? 
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_RECHAZADO']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: red; " ><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>' .
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_REAGENDADO']) {
                    $permisosDelProceso =
                        '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: blue;"><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE_VALIDACION']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDADO_POR_MEXICANA_DE_GAS']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_CANCELADA']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: gray; " ><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>' .
                        '<li style="background-color: gray; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>' .
                        '<li style="background-color: gray; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>' .
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                }

                /**DE LO CONTRARIO ENTONCES TENEMOS QUE PINTAR EL BOTON DE CENSOS Y POR SU ESTATUS CAMBIAR DE COLOR***/
            } else {
                if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_COMPLETO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-success" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_EN_PROCESO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-warning" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_REAGENDADO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-primary" onclick="loadForm(' . $idReporte . ',"' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                }
            }
        } else if ($estatusReporte == $GLOBALS['ESTATUS_REPORTE_COMPLETO']) {
            /**AHORA CHECAMOS QUE NO SEA UN CENSO DICHO REPORTE PARA CONTINUAR MOSTRANDO LAS VENTAS****/
            if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_NO_APLICA']) {
                /**SI ENTRAMOS AQUI SIGNIFICA QUE ES UNA VENTA, REVISAMOS PRIMERAMENTE SI ESTA VALIDADA POR MEXICANA Y AYOPSA, ENTONCES DEBE ESTAR
                 * HABILITADA LA SEGUNDA VENTA, DE LO CONTRARIO SOLO MOSTRAMOS LA PRIMERA VENTA, ADEMAS DE CHECAR EN QUE ESTATUS ESTA LA MISMA***/

                if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDACIONES_COMPLETAS']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_COMPLETA']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li style="background-color: green;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_INSTALACION'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';

                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_EN_PROCESO']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . '
                        );"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_RECHAZADO']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: red; " ><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>' .
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_REAGENDADO']) {
                    $permisosDelProceso =
                        '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: blue;"><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ? 
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE_VALIDACION']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDADO_POR_MEXICANA_DE_GAS']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                }


                else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDADO_POR_CREDITO']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';
                    }


                else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_CANCELADA']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: gray; " ><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>' .
                        '<li style="background-color: gray; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>' .
                        '<li style="background-color: gray; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>' .
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                }

                /**DE LO CONTRARIO ENTONCES TENEMOS QUE PINTAR EL BOTON DE CENSOS Y POR SU ESTATUS CAMBIAR DE COLOR***/
            } else {
                if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_COMPLETO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-success" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_EN_PROCESO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-warning" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_REAGENDADO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-primary" onclick="loadForm(' . $idReporte . ',"' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                }
            }
        } else if ($estatusReporte == $GLOBALS['ESTATUS_REPORTE_CANCELADO']) {
            /**AHORA CHECAMOS QUE NO SEA UN CENSO DICHO REPORTE PARA CONTINUAR MOSTRANDO LAS VENTAS****/
            if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_NO_APLICA']) {
                /**SI ENTRAMOS AQUI SIGNIFICA QUE ES UNA VENTA, REVISAMOS PRIMERAMENTE SI ESTA VALIDADA POR MEXICANA Y AYOPSA, ENTONCES DEBE ESTAR
                 * HABILITADA LA SEGUNDA VENTA, DE LO CONTRARIO SOLO MOSTRAMOS LA PRIMERA VENTA, ADEMAS DE CHECAR EN QUE ESTATUS ESTA LA MISMA***/

                if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDACIONES_COMPLETAS']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_COMPLETA']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';

                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_EN_PROCESO']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_RECHAZADO']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: red; " ><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>' .
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_REAGENDADO']) {
                    $permisosDelProceso =
                        '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: blue;"><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE_VALIDACION']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDADO_POR_MEXICANA_DE_GAS']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';

                    /**CHECAMOS AHORA LA SEGUNDA VENTA ESTA COMPLETA O EN PROCESO PARA SABER DE QUE COLOR PINTARLA****/
                    if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_EN_PROCESO']) {
                        $permisosDelProceso .= !$isCallCenter ?
                            '<li style="background-color: lightyellow;" class="dropdown-submenu dropdown-menu-left">' .
                            '<a tabindex="1" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a>' .
                            '<ul class="dropdown-menu">' .
                            '<li><a tabindex="1" href="javascript:showSecondSell(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;Crear segunda venta</a>' .
                            '</li>' .
                            '</ul>' .
                            '</li>'
                            :
                            '<li style="background-color: lightyellow;" >' .
                            '<a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    } else if ($validacionSegundaVenta == $GLOBALS['ESTATUS_SEGUNDA_VENTA_COMPLETA']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>';
                    }

                    /***AHORA VALIDAMOS LOS ESTATUS PARA EL PHP SI ESTA COMPLETO O EN PROCESO****/
                    if ($phEstatus == $GLOBALS['ESTATUS_PH_EN_PROCESO']) {
                        $permisosDelProceso .= '<li style="background-color: lightyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_COMPLETO']) {
                        $permisosDelProceso .= '<li style="background-color: greenyellow;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_REAGENDADO']) {
                        $permisosDelProceso .= '<li style="background-color: aqua;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } elseif ($phEstatus == $GLOBALS['ESTATUS_PH_RECHAZADO']) {
                        $permisosDelProceso .= '<li style="background-color: red;"><a href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    } else {
                        $permisosDelProceso .= '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>';
                    }

                    $permisosDelProceso .=
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_CANCELADA']) {
                    $permisosDelProceso = '<div class="btn-group">' .
                        '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                        '<ul role="menu" class="dropdown-menu">' .
                        '<li style="background-color: gray; " ><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>' .
                        '<li style="background-color: gray; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_SEGUNDA_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-dollar "></i>&nbsp;&nbsp;Segunda venta</a></li>' .
                        '<li style="background-color: gray; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_PLOMERO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-road "></i>&nbsp;&nbsp;PH</a></li>' .
                        '<li class="divider"></li>' .
                        '<li><a href="javascript:mensajeToastFormularioAunNoDisponible()"><i class="fa fa-wrench "></i>&nbsp;&nbsp;Instalaci&oacute;n</a></li>' .
                        '</ul>' .
                        '</div>';
                }

                /**DE LO CONTRARIO ENTONCES TENEMOS QUE PINTAR EL BOTON DE CENSOS Y POR SU ESTATUS CAMBIAR DE COLOR***/
            } else {
                if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_COMPLETO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-success" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_EN_PROCESO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-warning" onclick="loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_REAGENDADO']) {
                    $permisosDelProceso = '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-primary" onclick="loadForm(' . $idReporte . ',"' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_CENSO'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-file-text-o"></i></button>';
                }
            }
        } else {


        }
        return $permisosDelProceso;
    } else {
        /**PRIMERO CHECAMOS SI EL REPORTE YA ESTA TERMINADO DE LO CONTRARIO SEGUIMOS CON EL PROCESO DE VENTA***/

        /**AHORA CHECAMOS QUE NO SEA UN CENSO DICHO REPORTE PARA CONTINUAR MOSTRANDO LAS VENTAS****/
        if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_NO_APLICA']) {
            /**SI ENTRAMOS AQUI SIGNIFICA QUE ES UNA VENTA, REVISAMOS PRIMERAMENTE SI ESTA VALIDADA POR MEXICANA Y AYOPSA, ENTONCES DEBE ESTAR
             * HABILITADA LA SEGUNDA VENTA, DE LO CONTRARIO SOLO MOSTRAMOS LA PRIMERA VENTA, ADEMAS DE CHECAR EN QUE ESTATUS ESTA LA MISMA***/

            if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE']) {
                $permisosDelProceso = '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';
                '</ul>' .
                '</div>';
            } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_COMPLETA']) {
                $permisosDelProceso = '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';
                '</ul>' .
                '</div>';
            } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDACIONES_COMPLETAS']) {
                $permisosDelProceso = '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';
                '</ul>' .
                '</div>';

            } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_EN_PROCESO']) {
                $permisosDelProceso = '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';
                '</ul>' .
                '</div>';
            } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_RECHAZADO']) {
                $permisosDelProceso = '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: red; " ><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>' .
                    '</ul>' .
                    '</div>';
            } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_REAGENDADO']) {
                $permisosDelProceso =
                    '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: blue;"><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';
                '</ul>' .
                '</div>';
            } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE_VALIDACION']) {
                $permisosDelProceso = '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';
                '</ul>' .
                '</div>';
            } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDADO_POR_MEXICANA_DE_GAS']) {
                $permisosDelProceso = '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: lightyellow; " ><a style="color:black;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>';
                '</ul>' .
                '</div>';
            } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_CANCELADA']) {
                $permisosDelProceso = '<div class="btn-group">' .
                    '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><i class="fa fa-gear "></i>&nbsp;Proceso&nbsp;&nbsp;<span class="caret"></span></button>' .
                    '<ul role="menu" class="dropdown-menu">' .
                    '<li style="background-color: gray; " ><a style="color:white;" href="javascript:loadForm(' . $idReporte . ',' . chr(39) . $GLOBALS['TIPOS_DE_REPORTE_VENTA'] . chr(39) . ',' . $idUsuario . ');"><i class="fa fa-money "></i>&nbsp;&nbsp;Primera venta</a></li>' .
                    '</ul>' .
                    '</div>';
            }
        }


        return $permisosDelProceso;
    }

}

function validarEstatusDesdeLaTablaDeEstatusControl($estatusCenso, $estatusReporte, $estatusVenta, $validadoMexicana, $validadoAyopsa,
                                                    $phEstatus, $estatusSegundaVenta, $validacionSegundaVenta,
                                                    $validacionInstalacion, $estatusAsignacionInstalacion)
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

    if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_NO_APLICA']) {
        /**SI REVISAMOS QUE NO ES UN CENSO, ENTONCES ESTA EN UN PROCESO DE VENTA DEBEMOS CONDICIONAR POR LAS VENTAS***/
        if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_POR_ASIGNAR']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_POR_ASIGNAR'];

        } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_PENDIENTE']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_PENDIENTE'];

        } else if ($estatusVenta == $GLOBALS['ESTATUS_VENTA_COMPLETA']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_COMPLETO'];

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
                $estatus = $GLOBALS['ESTATUS_TEXTO_RECHAZADO_POR_MEXICANA'];

            } elseif ($validadoMexicana == $GLOBALS['ESTATUS_MEXICANA_AYOPSA_CREDITO']) {
                $estatus = $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_MEXICANA'];

            } elseif ($validadoMexicana == $GLOBALS['ESTATUS_MEXICANA_AYOPSA_CONTADO']) {
                $estatus = $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_MEXICANA'];

            } else {
                $estatus = $GLOBALS['ESTATUS_TEXTO_VALIDADO_POR_MEXICANA'];
            }
        } elseif ($estatusVenta == $GLOBALS['ESTATUS_VENTA_VALIDACIONES_COMPLETAS']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_VALIDACIONES_COMPLETAS'];
        }
    } else
        if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_EN_PROCESO']) {
            $estatus = $GLOBALS['ESTATUS_REPORTE_EN_PROCESO'];
        } elseif ($estatusCenso == $GLOBALS['ESTATUS_CENSO_COMPLETO']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_COMPLETO'];
        } elseif ($estatusCenso == $GLOBALS['ESTATUS_CENSO_REAGENDADO']) {
            $estatus = $GLOBALS['ESTATUS_TEXTO_REAGENDADA'];
        } else {
        }
    return $estatus;

}

function generarSpanDeEstatusPorColor($estatus)
{
    if ($estatus == $GLOBALS['ESTATUS_TEXTO_POR_ASIGNAR']) {
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
    }
    return $estatusColores;
}

function generarBotonAsignarTarea($estatusCenso, $estatusReporte, $id)
{
    $NicknameUsuarioLogeado = $_SESSION["nickname"];
    if ($NicknameUsuarioLogeado != 'AYOPSA') {
        if ($estatusCenso != $GLOBALS['ESTATUS_CENSO_NO_APLICA']) {
            if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_COMPLETO']) {
                $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-default " onclick="mensajeToastBotonTareaAsignarNoDisponible()"><i class="fa fa-calendar-o"></i></button>';
            } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_EN_PROCESO']) {
                $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-default " onclick="mensajeToastBotonTareaAsignarNoDisponible()"><i class="fa fa-calendar-o"></i></button>';
            } else if ($estatusCenso == $GLOBALS['ESTATUS_CENSO_REAGENDADO']) {
                $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-default " onclick="mensajeToastBotonTareaAsignarNoDisponible()"><i class="fa fa-calendar-o"></i></button>';

            } else {
                $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-info " onclick="asignarTarea(' . $id . ')"><i class="fa fa-calendar-o"></i></button>';
            }
        } else {

            $botonAsignarTarea = '<button id="btnAsignarTarea" name="btnAsignarTarea" data-toggle="button" style="width: 50px;" class="btn btn-info " onclick="asignarTarea(' . $id . ')"><i class="fa fa-calendar-o"></i></button>';
        }
    } else {
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

