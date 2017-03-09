<?php 
session_start();
include("header.php") ?>


<!--Data Table-->
<link href="assets/js/data-table/css/jquery.dataTables.css" rel="stylesheet">
<link href="assets/js/data-table/css/dataTables.tableTools.css" rel="stylesheet">
<link href="assets/js/data-table/css/dataTables.colVis.min.css" rel="stylesheet">
<link href="assets/js/data-table/css/dataTables.responsive.css" rel="stylesheet">
<link href="assets/js/data-table/css/dataTables.scroller.css" rel="stylesheet">

<!--right slidebar-->
<script src="assets/js/slidebars.min.js"></script>

<script type="text/javascript" src="assets/js/notify.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


<!-- Codigo -->
<div class="row">
    <div class="col-lg-12">
        <section class="panel" style="overflow: scroll">
            <header class="panel-heading col-lg-12">
                <div class="form-inline">
                    <!--<p><b>CONSULTAS</b></p>-->
                </div>
            </header>

            <div class="container" style="width: auto !important">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- tabs -->
                        <div class="tabbable">
                            <ul class="nav nav-tabs">
                                <?php if($_SESSION["id"] == 1): ?>
                                <li class="active col-md-3" onclick="cambiarTipoReporte(1);"><a href="#fullStatus" class="fullStatus" data-toggle="tab">Concentrado de Estatus</a></li>
                                <?php endif; ?>
                                
                                <li class="col-md-3 <?php echo ($_SESSION["id"] != 1) ? "active" : ""; ?>" onclick="cambiarTipoReporte(2);"><a href="#sell" class="sell" data-toggle="tab">Venta</a></li>
                                
                                <?php if($_SESSION["id"] == 1): ?>
                                <li class="col-md-3" onclick="cambiarTipoReporte(3);"><a href="#sellTime" class="sellTime" data-toggle="tab">Tiempo de venta</a></li>
                                <?php endif; ?>
                                
                                <?php if($_SESSION["id"] == 1): ?>
                                <li class="col-md-3" onclick="cambiarTipoReporte(4);"><a href="#rejectedReason" class="rejectedReason" data-toggle="tab">Motivos de rechazo</a></li>
                                <?php endif; ?>
                            </ul>
                            <div class="row">
                                <div class="form-inline">
                                    <p></p>
                                    <Label class="col-md-11 tituloReporte">REPORTE DE CONCENTRADO DE STATUS</Label>
                                    <button type="button" id="btn_chart" class="btn btn-success">
                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <p></p>
                            <header class="panel-heading">
                                <table id="filtrosPendCompletos" style="display: none">
                                    <tr>
                                        <td>
                                            <label id="labelFiltros">Seleccionar Tipo de filtro</label>
                                            <div class="checkbox" id="divFiltros">
                                                <label>
                                                    <input type="checkbox" id="general">
                                                    <i class="fa fa-tasks btn btn-info" aria-hidden="true">
                                                        Todos los estatus
                                                    </i>
                                                </label>
                                                <label>
                                                    <input type="checkbox" id="completos">
                                                    <i class="fa fa-check-circle btn btn-success" aria-hidden="true">
                                                        Completos
                                                    </i>
                                                </label>
                                                <label>
                                                    <input type="checkbox" id="pendientes">
                                                    <i class="fa fa-check-circle btn btn-warning" aria-hidden="true">
                                                        Pendientes
                                                    </i>
                                                </label>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                                <div class="form-inline">
                                    <p><b>CONSULTAS</b></p>

                                    <form method="POST" action="">
                                        <label>Filtros</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <select class="form-control" id="txtType" name="txtType" style="display: none">
                                            <option value="0">Todos los tipos</option>
                                            <option value="1">Censo</option>
                                            <option value="2">Plomer&iacute;a</option>
                                            <option value="3">Venta</option>
                                            <option value="4">Instalacion</option>
                                            <option value="5">Segunda Venta</option>
                                        </select>
                                        <select class="form-control" id="txtStatus" name="txtStatus" onchange="buscarPorEstatus()" style="display: none">
                                            <option value="0">Todos los estatus</option>
                                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="text-capitalize">Fecha de:</label>&nbsp;
                                        <input type='text' class="form-control" id="dateFrom" name="dateFrom"/>

                                        <label class="text-capitalize">Hasta:</label>&nbsp;
                                        <input type='text' class="form-control" id="dateTo" name="dateTo"/>

                                        <button type="button" id="btnFiltrarPorFechas" name="btnFiltrarPorFechas"
                                                class="btn btn-success" >
                                            <i class="fa fa-search">&nbsp;Filtrar por fechas</i>
                                        </button>
                                        <button type="button" id="limpiarFiltros" class="btn btn-success">
                                            <span class="glyphicon glyphicon-refresh" style="color:#fff;"></span>
                                        </button>

                                        <button type="button" id="btn_download" class="btn btn-success">
                                            <span class="fa fa-file-excel-o" style="color:#fff;"></span>
                                        </button>
                                        <button type="submit" id="b_download" class="btn btn-success" style="display: none">
                                            <a href=""></a>
                                        </button>
                                        <button type="button" id="btnConfiguration" class="btn btn-success" style="display: none">
                                            <span class="fa fa-sliders" style="color:#fff;"></span>
                                        </button>
                                    </form>
                                </div>
                            </header>
                            <p></p>

                            <div class="tab-content">
                                <div class="tab-pane <?php echo ($_SESSION["id"] == 1) ? "active" : ""; ?>" id="fullStatus"></div>
                                <div class="tab-pane <?php echo ($_SESSION["id"] != 1) ? "active" : ""; ?>" id="sell"></div>
                                <div class="tab-pane" id="sellTime"></div>
                                <div class="tab-pane" id="rejectedReason"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade fade disable-scroll" id="reportConfiguration" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" id="btnReportConfigClose" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="titleReportConfig">Configuraci&oacute;n de reporte</h4>
                        </div>
                        <div class="modal-body" id="ReportConfigBody" style="background-color: #e6e6e6 !important;">
                            <form class="cmxForm" role="form" id="formNewSale">
                                <div class="row">
                                    <div class="col-xs-5" style="margin-left: 10px">
                                        <p>TIEMPO REV. VENTA</p>

                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <Label style="text-align: center;">Verde</Label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>

                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoRevGreen1"
                                                       style="width: 35px; height:25px;"/>
                                                <label> a</label>
                                                <input type="text" class="form-control" id="txtTiempoRevGreen2"
                                                       style="width: 35px; height:25px;"/>
                                            </div>
                                        </div>
                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoRevYellow1"
                                                       style="width: 35px; height:25px;"/>
                                                <label> a</label>
                                                <input type="text" class="form-control" id="txtTiempoRevYellow2"
                                                       style="width: 35px; height:25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoRevRed1"
                                                       style="width: 35px; height:25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <p>TIEMPO REV. FINANCIERA</p>

                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoRevFincGreen1"
                                                       style="width: 35px; height:25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoRevFincGreen2"
                                                       style="width: 35px; height:25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>

                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoRevFincYellow1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoRevFincYellow2"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class=" form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoRevFincRed1"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5" style="margin-left: 10px;">
                                        <p>TIEMPO DOCUMENTACI&Oacute;N RECHAZADA</p>

                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoDocsGreen1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoDocsGreen2"
                                                       style="width: 35px; height:25px;"/>
                                            </div>
                                        </div>
                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoDocsYellow1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoDocsYellow2"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoDocsRed1"
                                                       style="width:35px; height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <p>TIEMPO 1ERA - 2DA CAPTURA</p>

                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoCapturasGreen1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoCapturasGreen2"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoCapturasYellow1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoCapturasYellow2"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoCapturasRed1"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5" style="margin-left: 10px;">
                                        <p>TIEMPO ASIGNACI&Oacute;N PH</p>

                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoAsigGreen1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoAsigGreen2"
                                                       style="width: 35px; height:  25px;"/>
                                            </div>
                                        </div>
                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoAsigYellow1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoAsigYellow2"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoAsigRed1"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <p>TIEMPO REALIZACI&Oacute;N PH</p>

                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoRealizadoGreen1"
                                                       style="width: 35px;height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoRealizadoGreen2"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoRealizadoYellow1"
                                                       style="width: 35px;height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoRealizadoYellow2"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoRealizadoRed1"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5" style="margin-left: 10px;">
                                        <p>TIEMPO PH CON ANOMAL&Iacute;AS</p>

                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>

                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoPhAnomaliasGreen1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label> a </label>
                                                <input type="text" class="form-control" id="txtTiempoPhAnomaliasGreen2"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>

                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoPhAnomaliasYellow1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoPhAnomaliasYellow2"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>

                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoPhAnomaliasRed1"
                                                       style="width: 35px; height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <p>TIEMPO ASIGNACI&Oacute;N INSTALACI&Oacute;N</p>

                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoAsigInstallGreen1"
                                                       style="width: 35px; height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoAsigInstallGreen2"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>

                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoAsigInstalYellow1"
                                                       style="width: 35px;height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoAsigInstalYellow2"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoAsigInstalRed1"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5" style="margin-left: 10px;">
                                        <p>TIEMPO REALIZACI&Oacute;N INSTALACI&Oacute;N</p>

                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoRealizInstalGreen1"
                                                       style="width: 35px;height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="from-control" id="txtTiempoRealizInstalGreen2"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control"
                                                       id="txtTiempoRealizInstalYellow1"
                                                       style="width: 35px;height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control"
                                                       id="txtTiempoRealizInstalYellow2"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoRealizInstalRed1"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <p>TIEMPO INSTALACI&Oacute;N CON ANOMAL&Iacute;S</p>

                                        <div class="form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Verde</label>
                                                <img src="assets/icons/estatus_verde.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoInstalAnomGreen1"
                                                       style="width: 35px;height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoInstalAnomGreen2"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class=" form-group col-xs-4">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Amarillo</label>
                                                <img src="assets/icons/estatus_amarillo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="txtTiempoInstalAnomYellow1"
                                                       style="width: 35px;height: 25px;"/>
                                                <label>a</label>
                                                <input type="text" class="form-control" id="txtTiempoInstalAnomYellow2"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <div class="form-inline">
                                                <label style="text-align: center;">Rojo</label>
                                                <img src="assets/icons/estatus_rojo.png"
                                                     style="width: 10px;height: 10px;">
                                            </div>
                                            <div class="form-inline">
                                                <label> > </label>
                                                <input type="text" class="form-control" id="txtTiempoInstalAnomRed1"
                                                       style="width: 35px;height: 25px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-9" style="margin-left: 217px;">
                                        <div class="col-xs-8">
                                            <button type="button" class="btn btn-danger" id="btnCancelConfiguration"
                                                    style="width: 170px;height: 35px; margin-right: 10px;">CANCELAR
                                            </button>
                                            <button type="button" class="btn btn-success" id="btnSaveConfiguration"
                                                    style="width: 170px;height: 35px;">GUARDAR
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade face disable-scroll" id="modalCharts" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" id="btnReportConfigClose" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="titleChart">Gr&aacute;fica</h4>
                        </div>
                        <div class="modal-body" id="myChart">
                            <Label>CONCENTRADO STATUS</Label>

                            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

            </div>

            <?php include("footer.php") ?>

            <!--Data Table-->
            <script src="assets/js/data-table/js/jquery.dataTables.min.js"></script>
            <script src="assets/js/data-table/js/dataTables.tableTools.min.js"></script>
            <script src="assets/js/data-table/js/bootstrap-dataTable.js"></script>
            <script src="assets/js/data-table/js/dataTables.colVis.min.js"></script>
            <script src="assets/js/data-table/js/dataTables.responsive.min.js"></script>
            <script src="assets/js/data-table/js/dataTables.scroller.min.js"></script>
            <script type="text/javascript" src="assets/js/clases/funciones.js"></script>
            <script type="text/javascript" src="assets/js/underscore.js"></script>
            <script type="text/javascript" src="assets/js/momentPrecise.js"></script>
            <!--data table init-->
            <script src="assets/js/data-table-init.js"></script>
            <link rel="stylesheet" href="assets/css/dcalendar.picker.min.css"/>

            <script type="text/javascript">
                var concentratedArray = [];

                function loadHeaders() {
                    $("#fullStatus").html(
                        "<div class='row'>"
                        + "<section class='panel'>"
                        + "<table id='tablaReporteEstatus' class='table table-striped custom-table table-hover dataTable no-footer'>"
                        + "<thead>"
                        + "<tr>"
                        + "<td colspan='1' style='background-color: #ffffff;'></td>"
                        + "<td colspan='6' style='background-color: #d9d9d9; text-align=center;'>Estatus de ventas</td>"
                        + "<td colspan='4' style='background-color: #bfbfbf; text-align=center;'>Estatus de PH</td>"
                        + "<td colspan='4' style='background-color: #808080; text-align=center;'>Estatus de Instalaci&oacute;n</td>"
                        + "</tr>"
                        + "<tr>"
                        + "<td>Agencia</td>"
                        + "<td style='background-color: #d9d9d9;'>Revisi&oacute;n Venta</td>"
                        + "<td style='background-color: #d9d9d9;'>Revisi&oacute;n Financiera</td>"
                        + "<td style='background-color: #d9d9d9;'>Rechazado Venta</td>"
                        + "<td style='background-color: #d9d9d9;'>Rechazado Financiera</td> "
                        + "<td style='background-color: #d9d9d9;'>Segunda Captura</td>"
                        + "<td style='background-color: #d9d9d9;'>Revision Segunda Captura</td>"
                        + "<td style='background-color: #bfbfbf;'></td>"
                        + "<td style='background-color: #bfbfbf;'>Por asignar</td>"
                        + "<td style='background-color: #bfbfbf;'>En proceso</td>"
                        + "<td style='background-color: #bfbfbf;'>Completo</td>"
                        + "<td style='background-color: #808080;'>"
                        + "<td style='background-color: #808080;'>Por asignar</td>"
                        + "<td style='background-color: #808080;'>En proceso</td>"
                        + "<td style='background-color: #808080;'>Completo</td>"
                        + "</tr>"
                        + "</thead>"
                        + "<tbody id='bodyData'>"
                        + "</tbody>"
                        + "</table>"
                        + "</section>"
                        + "</div>");

                    $("#sell").html("<div class='row'>"
                        + "<div class='col-lg-12'>"
                        + "<section class='panel'>"
                        + "<table id='tablaReporteVentas' class='table table-striped custom-table table-hover'>"
                        + "<thead>"
                        + "<tr>"
                        + "<th>Id</th>"
                        + "<th>Contrato</th>"
                        + "<th>No. Cliente</th>"
                        + "<th>PH</th>"
                        + "<th>Venta</th>"
                        + "<th>Segunda Venta</th>"
                        + "<th>Instalaci&oacute;n</th>"
                        + "<th>Municipio</th>"
                        + "<th>Colonia</th>"
                        + "<th>Calle</th>"
                        + "<th>Usuario</th>"
                        + "<th>Agencia</th>"
                        + "<th>Fecha</th>"
                        + "</tr>"
                        + "</thead>"
                        + "<tbody id='bodyDataSells'>"
                        + "</tbody>"
                        + "</table>"
                        + "</section>"
                        + "</div>"
                        + "</div>");

                    $("#sellTime").html("<div class='row'>"
                        + "<div class='col-lg-12'>"
                        + "<section class='panel'>"
                        + "<table id='tablaReporteTVentas' class='table table-striped custom-table table-hover'>"
                        + "<thead>"
                        + "<tr>"
                        + "<th>No. cliente</th>"
                        + "<th>Tiempo Rev. Venta</th>"
                        + "<th>Tiempo Rev. Financiera</th>"
                        + "<th>Tiempo doc. rechazada</th>"
                        + "<th>Tiempo 1era-2da Cap.</th>"
                        + "<th>Tiempo asign. PH</th>"
                        + "<th>Tiempo realiz. PH</th>"
                        + "<th>Tiempo PH anomali&iacute;a</th>"
                        + "<th>Tiempo asign. Inst.</th>"
                        + "<th>Tiempo realiz. Inst.</th>"
                        + "<th>Tiempo Inst. anomali&iacute;a</th>"
                        + "<th>Fecha</th>"
                        + "</tr>"
                        + "</thead>"
                        + "<tbody id='bodyDataSellTimes'>"
                        + "</tbody>"
                        + "</table>"
                        + "</section>"
                        + "</div>"
                        + "</div>");

                    $("#rejectedReason").html("<div class='row'>"
                        + "<div class='col-lg-12'>"
                        + "<section class='panel'>"
                        + "<table id='tablaReporteRechazos' class='table table-striped custom-table table-hover'>"
                        + "<thead>"
                        + "<tr>"
                        + "<th>No. contrato</th>"
                        + "<th>Agencia</th>"
                        + "<th>Rechazado por</th>"
                        + "<th>Motivo de rechazo</th>"
                        + "</tr>"
                        + "</thead>"
                        + "<tbody id='bodyDataRejectedReason'>"
                        + "</tbody>"
                        + "</table>"
                        + "</section>"
                        + "</div>"
                        + "</div>");
                }

                function buscarPorTipo() {
                    var estatus = [
                            '<option>PH EN PROCESO</option>' +
                            '<option>PH COMPLETA</option>' +
                            '<option>PH REAGENDADA</option>' +
                            '<option>PH RECHAZADA</option>' +
                            '<option>PH CANCELADO</option>' +
                            '<option>PH DEPURADA</option>' +
                            '<option>PH ELIMINADA</option>' ,
                        
                            '<option>PENDIENTE</option>'+
                            '<option>COMPLETO</option>'+
                            '<option>VALIDADO POR MEXICANA</option>'+
                            '<option>VALIDADO POR AYOPSA</option>'+
                            '<option>VALIDACIONES COMPLETAS</option>'+
                            '<option>VENTA DEPURADA</option>',
                    
                            '<option>INSTALACION EN PROCESO</option>'+
                            '<option>INSTALACION COMPLETADA</option>'+
                            '<option>INSTALACION RECHAZADA </option>'+
                            '<option>INSTALACION ENVIADA</option>'+
                            '<option>INSTALACION DEPURADA</option>'+
                            '<option>REAGENDADA</option>',
                    
                    
                            '<option>SEGUNDA VENTA PROCESO</option>'+
                            '<option>SEG. VENTA CAPTURA COMPLETADA</option>'+
                            '<option>SEGUNDA VENTA CANCELADA</option>'+
                            '<option>SEGUNDA VENTA DEPURADA</option>'+
                            '<option>SEGUNDA VENTA ELIMINADA</option>'+
                            '<option>SEGUNDA VENTA COMPLETO</option>'+
                            '<option>REVISION SEGUNDA CAPTURA</option>'
                        ];

                    $("#txtStatus").html('');
                    $("#txtStatus").append('<option>Todos los estatus</option>');

                    var tipo = $("#txtType option:selected").text();
                    
                    if (tipo === 'Censo') {
                        $("#txtStatus").append(estatus[0]);
                    }
                    
                    if (tipo === 'Plomería') {
                        tipo= 'Plomero';
                        $("#txtStatus").append(estatus[1]);
                    }
                    
                    if(tipo === 'Venta')
                        $("#txtStatus").append(estatus[2]);

                    if(tipo === 'Instalacion')
                        $("#txtStatus").append(estatus[3]);
                    
                    if(tipo === 'Segunda Venta')
                        $("#txtStatus").append(estatus[4]);

                    // alert(tipo);
                    var table = $('#tablaReporte').DataTable();

                    if (tipo != 'Todos los tipos') {
                        table
                            .columns(4)
                            .search('^'+tipo+'$', true)
                            .draw();
                    }
                }

                function buscarPorEstatus() {
                    var status = $("#txtStatus option:selected").text();
                    //alert(status);
                    var tipoRep = $('.tituloReporte').text();
                    if (tipoRep === "REPORTE DE VENTAS") {
                        var table = $('#tablaReporteVentas').DataTable();
                        table.search('').columns().search( '' ).draw();
                    }
                    var tipoReporte = $("#txtType option:selected").text();
                    var col=0;
                    switch(tipoReporte) {
                        case "Plomería":
                            col = 3;
                            break;
                        case "Venta":
                            col = 4;
                            break;
                        case "Instalacion":
                            col = 6;
                            break;
                        case "Segunda Venta":
                            col = 5;
                            break;
                    }
                    if (status != 'Todos los estatus') {
                        table
                            .columns(col)
                            .search('^'+status+'$', true)
                            .draw();
                    } else {
                        table.search( '' ).search( '' ).draw();
                    }

                }
                // #myInput is a <input type="text"> element
                $('#txtType').on( 'change', function () {
                    var tipoRep = $('.tituloReporte').text();
                    if (tipoRep === "REPORTE DE VENTAS") {
                        var table = $('#tablaReporteVentas').DataTable();
                        table.search('').columns().search( '' ).draw();
                    }
                    var estatus = [
                            '<option>PH EN PROCESO</option>' +
                            '<option>PH COMPLETA</option>' +
                            '<option>PH REAGENDADA</option>' +
                            '<option>PH RECHAZADA</option>' +
                            '<option>PH CANCELADO</option>' +
                            '<option>PH DEPURADA</option>' +
                            '<option>PH ELIMINADA</option>' ,
                        
                            '<option>EN PROCESO</option>'+
                            '<option>CAPTURA COMPLETADA</option>'+
                            '<option>VALIDADO POR MEXICANA</option>'+
                            '<option>VALIDACIONES COMPLETAS</option>'+
                            '<option>VENTA DEPURADA</option>',
                    
                            '<option>INSTALACION EN PROCESO</option>'+
                            '<option>INSTALACION COMPLETADA</option>'+
                            '<option>INSTALACION RECHAZADA </option>'+
                            '<option>INSTALACION ENVIADA</option>'+
                            '<option>INSTALACION DEPURADA</option>'+
                            '<option>REAGENDADA</option>',
                    
                    
                            '<option>SEGUNDA VENTA PROCESO</option>'+
                            '<option>SEG. VENTA CAPTURA COMPLETADA</option>'+
                            '<option>SEGUNDA VENTA CANCELADA</option>'+
                            '<option>SEGUNDA VENTA DEPURADA</option>'+
                            '<option>SEGUNDA VENTA ELIMINADA</option>'+
                            '<option>COMPLETO</option>'+
                            '<option>REVISION SEGUNDA CAPTURA</option>'
                        ];

                    $("#txtStatus").html('');
                    $("#txtStatus").append('<option>Todos los estatus</option>');

                    var tipo = $("#txtType option:selected").text();
                    
                    if (tipo === 'Plomería') {
                        tipo= 'Plomero';
                        $("#txtStatus").append(estatus[0]);
                    }
                    
                    if(tipo === 'Venta')
                        $("#txtStatus").append(estatus[1]);

                    if(tipo === 'Instalacion')
                        $("#txtStatus").append(estatus[2]);
                    
                    if(tipo === 'Segunda Venta')
                        $("#txtStatus").append(estatus[3]);
                });

                $(document).ready(function () {
                    var string_nickname = $("#nicknameZone").html();
                    string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
                    string_nickname = string_nickname.trim();
                    $("#titleHeader").html("Reportes");
                    $("#subtitle-header").html("Detalle");
                    $("#tbHeaders").append();
                    loadHeaders();
                    
                    var countTab = $('ul.nav-tabs li').length;
                    if (countTab === 1) {
                        $('#dateFrom').val('');
                        $('#dateTo').val('');
                        $('#btnConfiguration').hide();
                        $('.tituloReporte').html('REPORTE DE VENTAS');
                        $('#btn_chart').hide();
                        loadSell();
                    }else if(countTab > 1){
                        loadConcentratedStatus();
                    }
                    $('#dateFrom').dcalendarpicker({format: "yyyy-mm-dd"});
                    $('#dateTo').dcalendarpicker({format: "yyyy-mm-dd"});
                });

                $(document).on('click', '.fullStatus', function () {
                    $('#dateFrom').val('');
                    $('#dateTo').val('');
                    $('#btnConfiguration').hide();
                    $('#btn_chart').show();
                    $('.tituloReporte').html('REPORTE CONCENTRADO DE ESTATUS');
                    loadHeaders();
                    loadConcentratedStatus();
                });

                $(document).on('click', '.sell', function () {
                    $('#dateFrom').val('');
                    $('#dateTo').val('');
                    $('#btnConfiguration').hide();
                    $('.tituloReporte').html('REPORTE DE VENTAS');
                    $('#btn_chart').hide();
                    loadHeaders();
                    var tipo = 'pendientes';
                    loadSell(tipo);
                });

                $(document).on('click', '.sellTime', function () {
                    $('#dateFrom').val('');
                    $('#dateTo').val('');
                    $('#btnConfiguration').show();
                    $('.tituloReporte').html('REPORTE TIEMPO DE VENTAS');
                    $('#btn_chart').hide();
                    $(document).on('click', '#btnConfiguration', function () {
                        $.ajax({
                            method: "GET",
                            url: "dataLayer/callsWeb/loadReportsConfig.php",
                            dataType: "JSON",
                            success: function (data) {
                                console.log('data', data);
                                _.each(data, function(config, index) {
                                    if (parseInt(config.idTimes) === 1) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoRevGreen1').val(config.labelFrom);
                                            $('#txtTiempoRevGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoRevYellow1').val(config.labelFrom);
                                            $('#txtTiempoRevYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoRevRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 2) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoRevFincGreen1').val(config.labelFrom);
                                            $('#txtTiempoRevFincGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoRevFincYellow1').val(config.labelFrom);
                                            $('#txtTiempoRevFincYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoRevFincRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 3) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoDocsGreen1').val(config.labelFrom);
                                            $('#txtTiempoDocsGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoDocsYellow1').val(config.labelFrom);
                                            $('#txtTiempoDocsYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoDocsRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 4) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoCapturasGreen1').val(config.labelFrom);
                                            $('#txtTiempoCapturasGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoCapturasYellow1').val(config.labelFrom);
                                            $('#txtTiempoCapturasYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoCapturasRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 5) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoAsigGreen1').val(config.labelFrom);
                                            $('#txtTiempoAsigGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoAsigYellow1').val(config.labelFrom);
                                            $('#txtTiempoAsigYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoAsigRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 6) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoRealizadoGreen1').val(config.labelFrom);
                                            $('#txtTiempoRealizadoGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoRealizadoYellow1').val(config.labelFrom);
                                            $('#txtTiempoRealizadoYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoRealizadoRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 7) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoPhAnomaliasGreen1').val(config.labelFrom);
                                            $('#txtTiempoPhAnomaliasGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoPhAnomaliasYellow1').val(config.labelFrom);
                                            $('#txtTiempoPhAnomaliasYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoPhAnomaliasRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 8) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoAsigInstallGreen1').val(config.labelFrom);
                                            $('#txtTiempoAsigInstallGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoAsigInstalYellow1').val(config.labelFrom);
                                            $('#txtTiempoAsigInstalYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoAsigInstalRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 9) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoRealizInstalGreen1').val(config.labelFrom);
                                            $('#txtTiempoRealizInstalGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoRealizInstalYellow1').val(config.labelFrom);
                                            $('#txtTiempoRealizInstalYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoRealizInstalRed1').val(config.labelFrom);
                                        }
                                    } else if (parseInt(config.idTimes) === 10) {
                                        if (parseInt(config.subIdTimes) === 1) {
                                            $('#txtTiempoInstalAnomGreen1').val(config.labelFrom);
                                            $('#txtTiempoInstalAnomGreen2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 2) {
                                            $('#txtTiempoInstalAnomYellow1').val(config.labelFrom);
                                            $('#txtTiempoInstalAnomYellow2').val(config.labelTo);
                                        } else if (parseInt(config.subIdTimes) === 3) {
                                            $('#txtTiempoInstalAnomRed1').val(config.labelFrom);
                                        }
                                    }
                                });
                            }
                        });
                        $('#reportConfiguration').modal('show');
                    });
                    loadTimeSells();
                });

                $(document).on('click', '.rejectedReason', function () {
                    $('#btnConfiguration').hide();
                    $('.tituloReporte').html('REPORTE MOTIVOS DE RECHAZO');
                    $('#btn_chart').hide();
                    loadHeaders();
                    loadRejectedReasonForm();
                });

                $(document).on('click', '#btnCancelConfiguration:not(:disabled)', function () {
                    $('#reportConfiguration').modal('hide');
                    $('.cmxForm input').val('');
                });

                $(document).on('click', '#btnSaveConfiguration:not(:disabled)', function (e) {
                    e.preventDefault();
                    $('#btnSaveConfiguration').prop('disabled', true);
                    var txtTiempoRevGreen1 = $('#txtTiempoRevGreen1').val();
                    var txtTiempoRevGreen2 = $('#txtTiempoRevGreen2').val();
                    var txtTiempoRevYellow1 = $('#txtTiempoRevYellow1').val();
                    var txtTiempoRevYellow2 = $('#txtTiempoRevYellow2').val();
                    var txtTiempoRevRed1 = $('#txtTiempoRevRed1').val();

                    if ((parseInt(txtTiempoRevGreen1) > parseInt(txtTiempoRevGreen2)) ||
                        (parseInt(txtTiempoRevGreen2) < parseInt(txtTiempoRevGreen1)) ||
                        (parseInt(txtTiempoRevYellow1) > parseInt(txtTiempoRevYellow2)) ||
                        (parseInt(txtTiempoRevYellow2) < parseInt(txtTiempoRevYellow1)) ||
                        (parseInt(txtTiempoRevRed1) < parseInt(txtTiempoRevYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoRevRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoRevFincGreen1 = $('#txtTiempoRevFincGreen1').val();
                    var txtTiempoRevFincGreen2 = $('#txtTiempoRevFincGreen2').val();
                    var txtTiempoRevFincYellow1 = $('#txtTiempoRevFincYellow1').val();
                    var txtTiempoRevFincYellow2 = $('#txtTiempoRevFincYellow2').val();
                    var txtTiempoRevFincRed1 = $('#txtTiempoRevFincRed1').val();

                    if ((parseInt(txtTiempoRevFincGreen1) > parseInt(txtTiempoRevFincGreen2)) ||
                        (parseInt(txtTiempoRevFincGreen2) < parseInt(txtTiempoRevFincGreen1)) ||
                        (parseInt(txtTiempoRevFincYellow1) > parseInt(txtTiempoRevFincYellow2)) ||
                        (parseInt(txtTiempoRevFincYellow2) < parseInt(txtTiempoRevFincYellow1)) ||
                        (parseInt(txtTiempoRevFincRed1) < parseInt(txtTiempoRevFincYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoRevFincRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoDocsGreen1 = $('#txtTiempoDocsGreen1').val();
                    var txtTiempoDocsGreen2 = $('#txtTiempoDocsGreen2').val();
                    var txtTiempoDocsYellow1 = $('#txtTiempoDocsYellow1').val();
                    var txtTiempoDocsYellow2 = $('#txtTiempoDocsYellow2').val();
                    var txtTiempoDocsRed1 = $('#txtTiempoDocsRed1').val();

                    if ((parseInt(txtTiempoDocsGreen1) > parseInt(txtTiempoDocsGreen2)) ||
                        (parseInt(txtTiempoDocsGreen2) < parseInt(txtTiempoDocsGreen1)) ||
                        (parseInt(txtTiempoDocsYellow1) > parseInt(txtTiempoDocsYellow2)) ||
                        (parseInt(txtTiempoDocsYellow2) < parseInt(txtTiempoDocsYellow1)) ||
                        (parseInt(txtTiempoDocsRed1) < parseInt(txtTiempoDocsYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoDocsRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoCapturasGreen1 = $('#txtTiempoCapturasGreen1').val();
                    var txtTiempoCapturasGreen2 = $('#txtTiempoCapturasGreen2').val();
                    var txtTiempoCapturasYellow1 = $('#txtTiempoCapturasYellow1').val();
                    var txtTiempoCapturasYellow2 = $('#txtTiempoCapturasYellow2').val();
                    var txtTiempoCapturasRed1 = $('#txtTiempoCapturasRed1').val();

                    if ((parseInt(txtTiempoCapturasGreen1) > parseInt(txtTiempoCapturasGreen2)) ||
                        (parseInt(txtTiempoCapturasGreen2) < parseInt(txtTiempoCapturasGreen1)) ||
                        (parseInt(txtTiempoCapturasYellow1) > parseInt(txtTiempoCapturasYellow2)) ||
                        (parseInt(txtTiempoCapturasYellow2) < parseInt(txtTiempoCapturasYellow1)) ||
                        (parseInt(txtTiempoCapturasRed1) < parseInt(txtTiempoCapturasYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoCapturasRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoAsigGreen1 = $('#txtTiempoAsigGreen1').val();
                    var txtTiempoAsigGreen2 = $('#txtTiempoAsigGreen2').val();
                    var txtTiempoAsigYellow1 = $('#txtTiempoAsigYellow1').val();
                    var txtTiempoAsigYellow2 = $('#txtTiempoAsigYellow2').val();
                    var txtTiempoAsigRed1 = $('#txtTiempoAsigRed1').val();

                    if ((parseInt(txtTiempoAsigGreen1) > parseInt(txtTiempoAsigGreen2)) ||
                        (parseInt(txtTiempoAsigGreen2) < parseInt(txtTiempoAsigGreen1)) ||
                        (parseInt(txtTiempoAsigYellow1) > parseInt(txtTiempoAsigYellow2)) ||
                        (parseInt(txtTiempoAsigYellow2) < parseInt(txtTiempoAsigYellow1)) ||
                        (parseInt(txtTiempoAsigRed1) < parseInt(txtTiempoAsigYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoAsigRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoRealizadoGreen1 = $('#txtTiempoRealizadoGreen1').val();
                    var txtTiempoRealizadoGreen2 = $('#txtTiempoRealizadoGreen2').val();
                    var txtTiempoRealizadoYellow1 = $('#txtTiempoRealizadoYellow1').val();
                    var txtTiempoRealizadoYellow2 = $('#txtTiempoRealizadoYellow2').val();
                    var txtTiempoRealizadoRed1 = $('#txtTiempoRealizadoRed1').val();

                    if ((parseInt(txtTiempoRealizadoGreen1) > parseInt(txtTiempoRealizadoGreen2)) ||
                        (parseInt(txtTiempoRealizadoGreen2) < parseInt(txtTiempoRealizadoGreen1)) ||
                        (parseInt(txtTiempoRealizadoYellow1) > parseInt(txtTiempoRealizadoYellow2)) ||
                        (parseInt(txtTiempoRealizadoYellow2) < parseInt(txtTiempoRealizadoYellow1)) ||
                        (parseInt(txtTiempoRealizadoRed1) < parseInt(txtTiempoRealizadoYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoRealizadoRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoPhAnomaliasGreen1 = $('#txtTiempoPhAnomaliasGreen1').val();
                    var txtTiempoPhAnomaliasGreen2 = $('#txtTiempoPhAnomaliasGreen2').val();
                    var txtTiempoPhAnomaliasYellow1 = $('#txtTiempoPhAnomaliasYellow1').val();
                    var txtTiempoPhAnomaliasYellow2 = $('#txtTiempoPhAnomaliasYellow2').val();
                    var txtTiempoPhAnomaliasRed1 = $('#txtTiempoPhAnomaliasRed1').val();

                    if ((parseInt(txtTiempoPhAnomaliasGreen1) > parseInt(txtTiempoPhAnomaliasGreen2)) ||
                        (parseInt(txtTiempoPhAnomaliasGreen2) < parseInt(txtTiempoPhAnomaliasGreen1)) ||
                        (parseInt(txtTiempoPhAnomaliasYellow1) > parseInt(txtTiempoPhAnomaliasYellow2)) ||
                        (parseInt(txtTiempoPhAnomaliasYellow2) < parseInt(txtTiempoPhAnomaliasYellow1)) ||
                        (parseInt(txtTiempoPhAnomaliasRed1) < parseInt(txtTiempoPhAnomaliasYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoPhAnomaliasRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoAsigInstallGreen1 = $('#txtTiempoAsigInstallGreen1').val();
                    var txtTiempoAsigInstalGreen2 = $('#txtTiempoAsigInstallGreen2').val();
                    var txtTiempoAsigInstalYellow1 = $('#txtTiempoAsigInstalYellow1').val();
                    var txtTiempoAsigInstalYellow2 = $('#txtTiempoAsigInstalYellow2').val();
                    var txtTiempoAsigInstalRed1 = $('#txtTiempoAsigInstalRed1').val();

                    if ((parseInt(txtTiempoAsigInstallGreen1) > parseInt(txtTiempoAsigInstalGreen2)) ||
                        (parseInt(txtTiempoAsigInstalGreen2) < parseInt(txtTiempoAsigInstallGreen1)) ||
                        (parseInt(txtTiempoAsigInstalYellow1) > parseInt(txtTiempoAsigInstalYellow2)) ||
                        (parseInt(txtTiempoAsigInstalYellow2) < parseInt(txtTiempoAsigInstalYellow1)) ||
                        (parseInt(txtTiempoAsigInstalRed1) < parseInt(txtTiempoAsigInstalYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoAsigInstalRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoRealizInstalGreen1 = $('#txtTiempoRealizInstalGreen1').val();
                    var txtTiempoRealizInstalGreen2 = $('#txtTiempoRealizInstalGreen2').val();
                    var txtTiempoRealizInstalYellow1 = $('#txtTiempoRealizInstalYellow1').val();
                    var txtTiempoRealizInstalYellow2 = $('#txtTiempoRealizInstalYellow2').val();
                    var txtTiempoRealizInstalRed1 = $('#txtTiempoRealizInstalRed1').val();

                    if ((parseInt(txtTiempoRealizInstalGreen1) > parseInt(txtTiempoRealizInstalGreen2)) ||
                        (parseInt(txtTiempoRealizInstalGreen2) < parseInt(txtTiempoRealizInstalGreen1)) ||
                        (parseInt(txtTiempoRealizInstalYellow1) > parseInt(txtTiempoRealizInstalYellow2)) ||
                        (parseInt(txtTiempoRealizInstalYellow2) < parseInt(txtTiempoRealizInstalYellow1)) ||
                        (parseInt(txtTiempoRealizInstalRed1) < parseInt(txtTiempoRealizInstalYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoRealizInstalRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    var txtTiempoInstalAnomGreen1 = $('#txtTiempoInstalAnomGreen1').val();
                    var txtTiempoInstalAnomGreen2 = $('#txtTiempoInstalAnomGreen2').val();
                    var txtTiempoInstalAnomYellow1 = $('#txtTiempoInstalAnomYellow1').val();
                    var txtTiempoInstalAnomYellow2 = $('#txtTiempoInstalAnomYellow2').val();
                    var txtTiempoInstalAnomRed1 = $('#txtTiempoInstalAnomRed1').val();

                    if ((parseInt(txtTiempoInstalAnomGreen1) > parseInt(txtTiempoInstalAnomGreen2)) ||
                        (parseInt(txtTiempoInstalAnomGreen2) < parseInt(txtTiempoInstalAnomGreen1)) ||
                        (parseInt(txtTiempoInstalAnomYellow1) > parseInt(txtTiempoInstalAnomYellow2)) ||
                        (parseInt(txtTiempoInstalAnomYellow2) < parseInt(txtTiempoInstalAnomYellow1)) ||
                        (parseInt(txtTiempoInstalAnomRed1) < parseInt(txtTiempoInstalAnomYellow2))) {
                        $('#btnSaveConfiguration').prop('disabled', false);
                        $("#txtTiempoInstalAnomRed1").notify("Las cantidades deben de introducirse de menor a mayor", "error");
                        return false;
                    }

                    $("#btnSaveConfiguration").notify("Cargando..", "success");
                    $.ajax({
                        method: "POST",
                        url: "dataLayer/callsWeb/insertReportConfig.php",
                        data: {
                            txtTiempoRevGreen1: txtTiempoRevGreen1,
                            txtTiempoRevGreen2: txtTiempoRevGreen2,
                            txtTiempoRevYellow1: txtTiempoRevYellow1,
                            txtTiempoRevYellow2: txtTiempoRevYellow2,
                            txtTiempoRevRed1: txtTiempoRevRed1,
                            txtTiempoRevFincGreen1: txtTiempoRevFincGreen1,
                            txtTiempoRevFincGreen2: txtTiempoRevFincGreen2,
                            txtTiempoRevFincYellow1: txtTiempoRevFincYellow1,
                            txtTiempoRevFincYellow2: txtTiempoRevFincYellow2,
                            txtTiempoRevFincRed1: txtTiempoRevFincRed1,
                            txtTiempoDocsGreen1: txtTiempoDocsGreen1,
                            txtTiempoDocsGreen2: txtTiempoDocsGreen2,
                            txtTiempoDocsYellow1: txtTiempoDocsYellow1,
                            txtTiempoDocsYellow2: txtTiempoDocsYellow2,
                            txtTiempoDocsRed1: txtTiempoDocsRed1,
                            txtTiempoCapturasGreen1: txtTiempoCapturasGreen1,
                            txtTiempoCapturasGreen2: txtTiempoCapturasGreen2,
                            txtTiempoCapturasYellow1: txtTiempoCapturasYellow1,
                            txtTiempoCapturasYellow2: txtTiempoCapturasYellow2,
                            txtTiempoCapturasRed1: txtTiempoCapturasRed1,
                            txtTiempoAsigGreen1: txtTiempoAsigGreen1,
                            txtTiempoAsigGreen2: txtTiempoAsigGreen2,
                            txtTiempoAsigYellow1: txtTiempoAsigYellow1,
                            txtTiempoAsigYellow2: txtTiempoAsigYellow2,
                            txtTiempoAsigRed1: txtTiempoAsigRed1,
                            txtTiempoRealizadoGreen1: txtTiempoRealizadoGreen1,
                            txtTiempoRealizadoGreen2: txtTiempoRealizadoGreen2,
                            txtTiempoRealizadoYellow1: txtTiempoRealizadoYellow1,
                            txtTiempoRealizadoYellow2: txtTiempoRealizadoYellow2,
                            txtTiempoRealizadoRed1: txtTiempoRealizadoRed1,
                            txtTiempoPhAnomaliasGreen1: txtTiempoPhAnomaliasGreen1,
                            txtTiempoPhAnomaliasGreen2: txtTiempoPhAnomaliasGreen2,
                            txtTiempoPhAnomaliasYellow1: txtTiempoPhAnomaliasYellow1,
                            txtTiempoPhAnomaliasYellow2: txtTiempoPhAnomaliasYellow2,
                            txtTiempoPhAnomaliasRed1: txtTiempoPhAnomaliasRed1,
                            txtTiempoAsigInstallGreen1: txtTiempoAsigInstallGreen1,
                            txtTiempoAsigInstalGreen2: txtTiempoAsigInstalGreen2,
                            txtTiempoAsigInstalYellow1: txtTiempoAsigInstalYellow1,
                            txtTiempoAsigInstalYellow2: txtTiempoAsigInstalYellow2,
                            txtTiempoAsigInstalRed1: txtTiempoAsigInstalRed1,
                            txtTiempoRealizInstalGreen1: txtTiempoRealizInstalGreen1,
                            txtTiempoRealizInstalGreen2: txtTiempoRealizInstalGreen2,
                            txtTiempoRealizInstalYellow1: txtTiempoRealizInstalYellow1,
                            txtTiempoRealizInstalYellow2: txtTiempoRealizInstalYellow2,
                            txtTiempoRealizInstalRed1: txtTiempoRealizInstalRed1,
                            txtTiempoInstalAnomGreen1: txtTiempoInstalAnomGreen1,
                            txtTiempoInstalAnomGreen2: txtTiempoInstalAnomGreen2,
                            txtTiempoInstalAnomYellow1: txtTiempoInstalAnomYellow1,
                            txtTiempoInstalAnomYellow2: txtTiempoInstalAnomYellow2,
                            txtTiempoInstalAnomRed1: txtTiempoInstalAnomRed1
                        },
                        dataType: "JSON",
                        success: function (data) {
                            console.log('data', data);
                            $('.cmxForm input').val('');
                            $('#btnSaveConfiguration').prop('disabled', false);
                            $('#reportConfiguration').modal('hide');
                        }
                    });
                });

                $(document).on('click', '#btn_chart', function () {
                    console.log("Chart Clicked");
                    var revisionDeVentas = 0,
                        revisionFinanciera = 0,
                        rechazadoVentas = 0,
                        rechazadoFinanciera = 0,
                        segundaCaptura = 0,
                        revisionSegundaCaptura= 0,
                        PHporAsignar = 0,
                        PHenProceso = 0,
                        PHpendiente = 0,
                        PHcompleto = 0,
                        InstallporAsignar = 0,
                        InstallenProceso = 0,
                        Installpendiente = 0,
                        Installcompleto = 0,
                        dateStart = "",
                        dateFinish = "";
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/getReporteEstatus.php",
                        dataType: "JSON",
                        success: function (data) {
                            console.log('data chart', data);

                            $.each( data.data, function( i, itemData ) {
                                //concentrated.push(itemData);
                                revisionDeVentas += parseInt(itemData.revisionVentas);
                                revisionFinanciera += parseInt(itemData.revisionFinanciera);
                                rechazadoVentas += parseInt(itemData.rechazadoVentas);
                                rechazadoFinanciera += parseInt(itemData.rechazadoFinanciera);
                                segundaCaptura += parseInt(itemData.segundaCaptura);
                                revisionSegundaCaptura += parseInt(itemData.revisionSegundaCaptura);
                                PHporAsignar += parseInt(itemData.phPorAsignar);
                                PHenProceso += parseInt(itemData.phEnProceso);
                                PHcompleto += parseInt(itemData.phCompleto);
                                InstallporAsignar += parseInt(itemData.insPorAsignar);
                                InstallenProceso += parseInt(itemData.insEnProceso);
                                Installcompleto += parseInt(itemData.insCompleto);

                                $(function () {
                                    $('#container').highcharts({
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'Concentrado Estatus'
                                        },
                                        xAxis: {
                                            categories: ['', '', '', '', '' , '', '', '', '' , '', '', '', ''],
                                            crosshair: true
                                        },
                                        yAxis: {
                                            min: 0,
                                            max: 10
                                        },
                                        tooltip: {
                                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                                            footerFormat: '</table>',
                                            shared: true,
                                            useHTML: true
                                        },
                                        plotOptions: {
                                            column: {
                                                pointPadding: 0.2,
                                                borderWidth: 0
                                            }
                                        },
                                        series: [
                                        {
                                            name: 'Revisión de ventas',
                                            data: [revisionDeVentas]
                                        }, 
                                        {
                                            name: 'Revisión financiera',
                                            data: [revisionFinanciera]
                                        }, 
                                        {
                                            name: 'Rechazado ventas',
                                            data: [rechazadoVentas]
                                        }, 
                                        {
                                            name: 'Rechazado Financiera',
                                            data: [rechazadoFinanciera]
                                        }, 
                                        {
                                            name: 'Segunda Captura',
                                            data: [segundaCaptura]
                                        },
                                        {
                                            name: 'Revision Segunda Captura',
                                            data: [revisionSegundaCaptura]
                                        }, 
                                        {
                                            name: 'Por asignar',
                                            data: [PHporAsignar]
                                        },
                                        {
                                            name: 'En proceso',
                                            data: [PHenProceso]
                                        },
                                        {
                                            name: 'Completo',
                                            data: [PHcompleto]
                                        },
                                        {
                                            name: 'Por asignar',
                                            data: [InstallporAsignar]
                                        },
                                        {
                                            name: 'En proceso',
                                            data: [InstallenProceso]
                                        },
                                        {
                                            name: 'Completo',
                                            data: [Installcompleto]
                                        }]
                                    });
                                });
                            });
                        }
                    });
                    $('#modalCharts').modal('show');
                });

                function loadRejectedReasonForm() {
                    $("#txtType").hide();
                    $("#txtStatus").hide();
                    $("#filtrosPendCompletos").hide();
                    var limit = $('#txtQty').val();
                    var dateFrom = $('#dateFrom').val();
                    var dateTo = $('#dateTo').val();
                    var htmlAppend = '';
                    //getReporteRechazo
                    $.ajax({
                        method: "GET",
                        //url: "dataLayer/callsWeb/rejectedReasonsTwo.php",
                        url: "dataLayer/callsWeb/getReporteRechazo.php",
                        dataType: "JSON",
                        success: function (data) {
                            _.each(data.data, function(rech, index) {
                                htmlAppend += '<tr>';
                                htmlAppend += '<td>' + rech.agreementNumber + '</td>';
                                htmlAppend += '<td>' + rech.agencia + '</td>';
                                htmlAppend += '<td>' + rech.validadoPor + '</td>';
                                htmlAppend += '<td>' + rech.reason + '</td>';
                                htmlAppend += '</tr>';
                            });
                            $('#bodyDataRejectedReason').html('');
                            $('#bodyDataRejectedReason').append(htmlAppend);
                            $("#tablaReporteRechazos").DataTable().destroy();
                            $("#tablaReporteRechazos").DataTable({
                                "order": [[0, 'desc']],
                                autoWidth: true,
                                searching: true,
                                "language": {
                                    "lengthMenu": "Mostrar _MENU_",
                                    "search": "Buscar:   ",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                    "paginate": {
                                        "first": "Primera",
                                        "previous": "Anterior",
                                        "next": "Siguiente",
                                        "last": "Ultima"
                                    }
                                }
                            });
                        }
                    });
                }

                function loadSell(tipoReporte) {
                    console.log('tipoReporte', tipoReporte);
                    $("#txtType").show();
                    $("#txtStatus").show();
                    $("#filtrosPendCompletos").show();
                    var limit = $('#txtQty').val();
                    var dateFrom = $('#dateFrom').val();
                    var dateTo = $('#dateTo').val();
                    var htmlAppend='',
                        id='',
                        agreementNumber='',
                        idClienteGenerado='',
                        estatus_ph='',
                        estatus_venta='',
                        estatus_instalacion='',
                        idCity='',
                        colonia='',
                        street='',
                        innerNumber = '',
                        outterNumber = '',
                        nombre_usuario='',
                        agencia='',
                        fecha='';
                    $.ajax({
                        method: "GET",
                        //url: "dataLayer/callsWeb/sellsReport.php",
                        url: "dataLayer/callsWeb/getReporteVentas.php",
                        dataType: "JSON",
                        data: {tipoReporte: tipoReporte},
                        success: function (data) {
                            console.log('getReporteVentas', data);
                            _.each(data.data, function(sell, index) {
                                //if (parseInt(sell.agreementNumber) === 34830) {
                                    id=(sell.id === '' || typeof(sell.id) === 'undefined' || sell.id === null) ? '--' : sell.id;
                                    agreementNumber=(sell.agreementNumber === '' || typeof(sell.agreementNumber) === 'undefined' || sell.agreementNumber === null) ? '--' : sell.agreementNumber;
                                    idClienteGenerado=(sell.idClienteGenerado === '' || typeof(sell.idClienteGenerado) === 'undefined' || sell.idClienteGenerado === null) ? '--' : sell.idClienteGenerado;
                                    estatus_ph=(sell.estatus_ph === '' || typeof(sell.estatus_ph) === 'undefined' || sell.estatus_ph === null) ? '--' : sell.estatus_ph;
                                    estatus_venta=(sell.estatus_venta === '' || typeof(sell.estatus_venta) === 'undefined' || sell.estatus_venta === null) ? '--' : sell.estatus_venta;
                                    idCity=(sell.idCity === '' || typeof(sell.idCity) === 'undefined' || sell.idCity === null) ? '--' : sell.idCity;
                                    colonia=(sell.colonia === '' || typeof(sell.colonia) === 'undefined' || sell.colonia === null) ? '--' : sell.colonia;
                                    street=(sell.street === '' || typeof(sell.street) === 'undefined' || sell.street === null) ? '--' : sell.street;
                                    innerNumber=(sell.innerNumber === '' || typeof(sell.innerNumber) === 'undefined' || sell.innerNumber === null) ? '--' : sell.innerNumber;
                                    outterNumber=(sell.outterNumber === '' || typeof(sell.outterNumber) === 'undefined' || sell.outterNumber === null) ? '--' : sell.outterNumber;
                                    nombre_usuario=(sell.nombre_usuario === '' || typeof(sell.nombre_usuario) === 'undefined' || sell.nombre_usuario === null) ? '--' : sell.nombre_usuario;
                                    agencia=(sell.agencia === '' || typeof(sell.agencia) === 'undefined' || sell.agencia === null) ? '--' : sell.agencia;
                                    estatus_instalacion =(sell.estatus_instalacion === '' || typeof(sell.estatus_instalacion) === 'undefined' || sell.estatus_instalacion === null) ? '--' : sell.estatus_instalacion;
                                    fecha=(sell.fecha === '' || typeof(sell.fecha) === 'undefined' || sell.fecha === null) ? '--' : sell.fecha;
                                    htmlAppend += '<tr id="idventa_'+id+'">';
                                    htmlAppend += '<td>' + id + '</td>';
                                    htmlAppend += '<td>' + agreementNumber + '</td>';
                                    htmlAppend += '<td>' + idClienteGenerado + '</td>';
                                    var etiquetaEsPH=etiquetaEstatusPH(sell.phEstatus,sell.estatus_ph, sell.estatusReporte);
                                    htmlAppend += '<td>' + etiquetaEsPH + '</td>';
                                    var etiquetaEsVenta=etiquetaEstatusVenta(sell.estatusVenta,sell.estatus_venta, sell.estatusReporte);
                                    htmlAppend += '<td>' + etiquetaEsVenta + '</td>';
                                    var etiquetaEsSegVenta=etiquetaEstatusSegundaVenta(sell.validacionSegundaVenta, sell.estatus_seg_venta,sell.estatusReporte);
                                    htmlAppend += '<td>' + etiquetaEsSegVenta + '</td>';
                                    var etiquetaEsInstalacion=etiquetaEstatusInstalacion(sell.estatusAsignacionInstalacion,sell.estatus_instalacion, sell.estatusReporte);
                                    htmlAppend += '<td>' + etiquetaEsInstalacion + '</td>';
                                    htmlAppend += '<td>' + idCity + '</td>';
                                    htmlAppend += '<td>' + colonia + '</td>';
                                    htmlAppend += '<td>' + street + ' - Num: '+innerNumber+'</td>';
                                    htmlAppend += '<td>' + nombre_usuario + '</td>';
                                    htmlAppend += '<td>' + agencia + '</td>';
                                    htmlAppend += '<td>' + fecha + '</td>';
                                    htmlAppend += '</tr>';
                                //}
                            });
                            $('#bodyDataSells').html('');
                            $('#bodyDataSells').append(htmlAppend);
                            $("#tablaReporteVentas").DataTable().destroy();
                            $("#tablaReporteVentas").DataTable({
                                "columnDefs": [{
                                    "defaultContent": "-",
                                    "targets": "_all"
                                }],
                                "order": [[0, 'desc']],
                                autoWidth: true,
                                searching: true,
                                "language": {
                                    "lengthMenu": "Mostrar _MENU_",
                                    "search": "Buscar:   ",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                    "paginate": {
                                        "first": "Primera",
                                        "previous": "Anterior",
                                        "next": "Siguiente",
                                        "last": "Ultima"
                                    }
                                }
                            });
                        }
                    });
                }
                $('#general').on('click', function(e) {
                    var tipoReporte = "general";
                    var general = $('#general').is(':checked');
                    var completos = $('#completos').is(':checked');
                    var pendientes = $('#pendientes').is(':checked');
                    if (general) {
                        $('#bodyDataSells').html('');
                        //$('#bodyDataSells').append(htmlAppend);
                        $("#tablaReporteVentas").DataTable().destroy();
                        $('#completos').prop('checked', false);
                        $('#pendientes').prop('checked', false);
                        loadSell(tipoReporte);
                    }
                });
                $('#completos').on('click', function(e) {
                    var tipoReporte = "completos";
                    var general = $('#general').is(':checked');
                    var completos = $('#completos').is(':checked');
                    var pendientes = $('#pendientes').is(':checked');
                    if (completos) {
                        $('#bodyDataSells').html('');
                        //$('#bodyDataSells').append(htmlAppend);
                        $("#tablaReporteVentas").DataTable().destroy();
                        $('#general').prop('checked', false);
                        $('#pendientes').prop('checked', false);
                        loadSell(tipoReporte);
                    }
                });
                $('#pendientes').on('click', function(e) {
                    var tipoReporte = "pendientes";
                    var general = $('#general').is(':checked');
                    var completos = $('#completos').is(':checked');
                    var pendientes = $('#pendientes').is(':checked');
                    if (pendientes) {
                        $('#bodyDataSells').html('');
                        //$('#bodyDataSells').append(htmlAppend);
                        $("#tablaReporteVentas").DataTable().destroy();
                        $('#completos').prop('checked', false);
                        $('#general').prop('checked', false);
                        loadSell(tipoReporte);
                    }
                });
                var EESTATUS_CAPTURA_COMPLETADA = 3,
                    EESTATUS_VALIDACIONES_COMPLETAS = 10,
                    EESTATUS_VENTA_ELIMINADA = 11,
                    EESTATUS_VENTA_RECHAZADA = 2,
                    ESTATUS_VENTA_VALIDADO_POR_MEXICANA = 6,
                    EESTATUS_VENTA_VALIDADO_POR_CREDITO = 21,
                    EESTATUS_PH_COMPLETADA = 31,
                    EESTATUS_PH_ELIMINADA = 35,
                    EESTATUS_PH_RECHAZADA= 33,


                    ESTATUS_SEGUNDA_VENTA_EN_PROCESO = 40,
                    ESTATUS_SEGUNDA_VENTA_COMPLETA = 41,
                    ESTATUS_SEGUNDA_VENTA_VALIDADA = 42,
                    ESTATUS_SEGUNDA_VENTA_REVISION = 43,
                    ESTATUS_SEGUNDA_VENTA_CANCELADA = 44,
                    ESTATUS_SEGUNDA_VENTA_DEPURADO = 45,
                    ESTATUS_SEGUNDA_VENTA_ELIMINADO = 46,



                    EESTATUS_INSTALACION_PROCESO = 50,
                    EESTATUS_INSTALACION_COMPLETA = 51,
                    EESTATUS_INSTALACION_RECHAZADA = 53,
                    EESTATUS_INSTALACION_REAGENDADA = 52,
                    EESTATUS_INSTALACION_ENVIADA = 54,
                    EESTATUS_INSTALACION_ELIMINADA = 55;

                function etiquetaEstatusPH(idEstatus, etiqueta, estatusReporte){
                    var etiquetaString = "---";
                    if((!_.isNull(idEstatus) || !_.isEmpty(idEstatus)) && idEstatus !== 0)
                    {
                        if (estatusReporte === 66) {
                            etiquetaString = "<span class=\"label label-danger\">CANCELADO</span>";
                        }else{
                            switch(idEstatus)
                            {
                                case EESTATUS_PH_COMPLETADA:
                                    etiquetaString = "<span class=\"label label-success\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_PH_RECHAZADA:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_PH_ELIMINADA:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                default:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                            }
                        }
                    }
                    return etiquetaString;
                }
                
                /**
                 * Metodo que se encarga de colocar las etiquetas de estatus decuerdo 
                 * @returns {undefined}
                 */
                function etiquetaEstatusVenta(idEstatus, etiqueta, estatusReporte){
                    var etiquetaString = "---";
                    if((!_.isNull(idEstatus) || !_.isEmpty(idEstatus)) && idEstatus !== 0)
                    {
                        if (estatusReporte === 66) {
                            etiquetaString = "<span class=\"label label-danger\">CANCELADO</span>";
                        }else{
                            switch(idEstatus)
                            {
                                case EESTATUS_VALIDACIONES_COMPLETAS:
                                    etiquetaString = "<span class=\"label label-success\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_VENTA_RECHAZADA:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_CAPTURA_COMPLETADA:
                                    etiquetaString = "<span class=\"label label-warning\">CAPTURA COMPLETADA</span>";
                                    break;
                                case ESTATUS_VENTA_VALIDADO_POR_MEXICANA:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_VENTA_VALIDADO_POR_CREDITO:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_VENTA_ELIMINADA:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                default:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                            }
                        }
                    }
                    return etiquetaString;
                }

                function etiquetaEstatusSegundaVenta(idEstatus, etiqueta, estatusReporte){
                    var etiquetaString = "---";
                    if((!_.isNull(idEstatus) || !_.isEmpty(idEstatus)) && idEstatus !== 0)
                    {
                        if (estatusReporte === 66) {
                            etiquetaString = "<span class=\"label label-danger\">CANCELADO</span>";
                        }else{
                            /*
                            ESTATUS_SEGUNDA_VENTA_EN_PROCESO = 40,
                            ESTATUS_SEGUNDA_VENTA_COMPLETA = 41,
                            ESTATUS_SEGUNDA_VENTA_VALIDADA = 42,
                            ESTATUS_SEGUNDA_VENTA_REVISION = 43,
                            ESTATUS_SEGUNDA_VENTA_CANCELADA = 44,
                            ESTATUS_SEGUNDA_VENTA_DEPURADO = 45,
                            ESTATUS_SEGUNDA_VENTA_ELIMINADO = 46,
                            */
                            switch(idEstatus)
                            {
                                case ESTATUS_SEGUNDA_VENTA_EN_PROCESO:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                                case ESTATUS_SEGUNDA_VENTA_COMPLETA:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                                case ESTATUS_SEGUNDA_VENTA_VALIDADA:
                                    etiquetaString = "<span class=\"label label-success\">COMPLETO</span>";
                                    break;
                                case ESTATUS_SEGUNDA_VENTA_REVISION:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                                case ESTATUS_SEGUNDA_VENTA_CANCELADA:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                case ESTATUS_SEGUNDA_VENTA_DEPURADO:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                case ESTATUS_SEGUNDA_VENTA_ELIMINADO:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                default:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                            }
                        }
                    }
                    return etiquetaString;
                }
                
                
                function etiquetaEstatusInstalacion(idEstatus, etiqueta, estatusReporte){
                    var etiquetaString = "---";
                    if((!_.isNull(idEstatus) || !_.isEmpty(idEstatus)) && idEstatus !== 0)
                    {
                        if (estatusReporte === 66) {
                            etiquetaString = "<span class=\"label label-danger\">CANCELADO</span>";
                        }else{
                            switch(idEstatus)
                            {
                                case EESTATUS_INSTALACION_COMPLETA:
                                    etiquetaString = "<span class=\"label label-success\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_INSTALACION_RECHAZADA:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_INSTALACION_ENVIADA:
                                    etiquetaString = "<span class=\"label label-success\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_INSTALACION_ELIMINADA:
                                    etiquetaString = "<span class=\"label label-danger\">"+etiqueta+"</span>";
                                    break;
                                case EESTATUS_INSTALACION_REAGENDADA:
                                    etiquetaString = "<span class=\"label label-info\">"+etiqueta+"</span>";
                                    break;
                                default:
                                    etiquetaString = "<span class=\"label label-warning\">"+etiqueta+"</span>";
                                    break;
                            }
                        }
                    }
                    return etiquetaString;
                }

                function loadTimeSells(flagExcel) {
                    $("#txtType").hide();
                    $("#txtStatus").hide();
                    $("#filtrosPendCompletos").hide();
                    var limit = $('#txtQty').val();
                    var dateFrom = $('#dateFrom').val();
                    var dateTo = $('#dateTo').val();
                    var htmlAppend='',
                        Id='',
                        No_Cliente='',
                        fechaInicioVenta='',
                        fechaFinVenta='',
                        fechaInicioFinanciera='',
                        fechaFinFinanciera='',
                        fechaInicioRechazo='',
                        fechaFinRechazo='',
                        fechaPrimeraCaptura='',
                        fechaSegundaCaptura='',
                        fechaInicioAsigPH='',
                        fechaFinAsigPH='',
                        fechaInicioRealizoPH='',
                        fechaFinRealizoPH='',
                        fechaInicioAnomPH='',
                        fechaFinAnomPH='',
                        fechaInicioAsigInst='',
                        fechaFinAsigInst='',
                        fechaInicioRealInst='',
                        fechaFinRealInst='',
                        fechaInicioAnomInst='',
                        now,
                        then,
                        fecTotVenta,
                        revVtaVerde,
                        revVtaAm,
                        revVtaRojo,
                        calcAm,
                        spanRevVenta,
                        fecTemp,
                        arrDatos=[],
                        fechaFinAnomInst='';
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/sellsTimesReport.php",
                        //url: "dataLayer/callsWeb/getReporteTiempos.php",
                        dataType: "JSON",
                        success: function (data) {
                            //console.log('data sells times', data);
                            _.each(data.calculos, function(sellT, idx) {
                                //if (idx === 10) {
                                //console.log('sellT', sellT);
                                    Id=sellT.Id;
                                    No_Cliente=(_.isEmpty(sellT.No_Cliente) || _.isNull(sellT.No_Cliente)) ? '--' : sellT.No_Cliente;
                                    fechaInicioVenta=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioVenta);
                                    fechaFinVenta=validaDiasFestivos(data.diasFestivos, sellT.fechaFinVenta);
                                    if ((!_.isEmpty(fechaInicioVenta) && !_.isNull(fechaInicioVenta)) && 
                                        (!_.isEmpty(fechaFinVenta) && !_.isNull(fechaFinVenta))) {
                                        fecTemp=moment(fechaInicioVenta).format('DD-MM-YYYY');
                                        fechaFinVenta=(fecTemp !== moment(sellT.fechaInicioVenta).format('DD-MM-YYYY')) ? moment(fechaFinVenta).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinVenta;
                                    } 
                                    now  = fechaInicioVenta;
                                    then = fechaFinVenta;
                                    fecTotVenta = getTotalDuration(then,now);
                                    convFecTotVenta=(fecTotVenta === 0) ? '--' : fecTotVenta['_data'].hours+' : '+fecTotVenta['_data'].minutes+' : '+fecTotVenta['_data'].seconds;
                                    flag=1;
                                    spanDurationTotVenta=getColorSpanTime(fecTotVenta, data.conf, flag, convFecTotVenta);

                                    fechaInicioFinanciera=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioFinanciera);
                                    fechaFinFinanciera=validaDiasFestivos(data.diasFestivos, sellT.fechaFinFinanciera);
                                    if ((!_.isEmpty(fechaInicioFinanciera) && !_.isNull(fechaInicioFinanciera)) && 
                                        (!_.isEmpty(fechaFinFinanciera) && !_.isNull(fechaFinFinanciera))) {
                                        fecTemp=moment(fechaInicioFinanciera).format('DD-MM-YYYY');
                                        fechaFinFinanciera=(fecTemp !== moment(sellT.fechaInicioFinanciera).format('DD-MM-YYYY')) ? moment(fechaFinFinanciera).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinFinanciera;
                                    }
                                    now='';
                                    then='';
                                    now  = fechaInicioFinanciera;
                                    then = fechaFinFinanciera;
                                    fecTotFin = getTotalDuration(then,now);
                                    convFecTotFin=(fecTotFin === 0) ? '--' : fecTotFin['_data'].hours+' : '+fecTotFin['_data'].minutes+' : '+fecTotFin['_data'].seconds;
                                    flag=2;
                                    spanDurationTotFin=getColorSpanTime(fecTotFin, data.conf, flag, convFecTotFin);

                                    fechaInicioRechazo=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioRechazo);
                                    fechaFinRechazo=validaDiasFestivos(data.diasFestivos, sellT.fechaFinRechazo);
                                    if ((!_.isEmpty(fechaInicioRechazo) && !_.isNull(fechaInicioRechazo)) && 
                                        (!_.isEmpty(fechaFinRechazo) && !_.isNull(fechaFinRechazo))) {
                                        fecTemp=moment(fechaInicioRechazo).format('DD-MM-YYYY');
                                        fechaFinRechazo=(fecTemp !== moment(sellT.fechaInicioRechazo).format('DD-MM-YYYY')) ? moment(fechaFinRechazo).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinRechazo;
                                    }
                                    now='';
                                    then='';
                                    now  = fechaInicioRechazo;
                                    then = fechaFinRechazo;
                                    fecTotRech = getTotalDuration(then,now);
                                    convFecTotRech=(fecTotRech === 0) ? '--' : fecTotRech['_data'].hours+' : '+fecTotRech['_data'].minutes+' : '+fecTotRech['_data'].seconds;
                                    flag=3;
                                    spanDurationTotRech=getColorSpanTime(fecTotRech, data.conf, flag, convFecTotRech);

                                    fechaPrimeraCaptura=validaDiasFestivos(data.diasFestivos, sellT.fechaPrimeraCaptura);
                                    fechaSegundaCaptura=validaDiasFestivos(data.diasFestivos, sellT.fechaSegundaCaptura);
                                    if ((!_.isEmpty(fechaPrimeraCaptura) && !_.isNull(fechaPrimeraCaptura)) && 
                                        (!_.isEmpty(fechaSegundaCaptura) && !_.isNull(fechaSegundaCaptura))) {
                                        fecTemp=moment(fechaPrimeraCaptura).format('DD-MM-YYYY');
                                        fechaSegundaCaptura=(fecTemp !== moment(sellT.fechaPrimeraCaptura).format('DD-MM-YYYY')) ? moment(fechaSegundaCaptura).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaSegundaCaptura;
                                    }
                                    now='';
                                    then='';
                                    now  = fechaPrimeraCaptura;
                                    then = fechaSegundaCaptura;
                                    fecTotSegC = getTotalDuration(then,now);
                                    convFecTotSegC=(fecTotSegC === 0) ? '--' : fecTotSegC['_data'].hours+' : '+fecTotSegC['_data'].minutes+' : '+fecTotSegC['_data'].seconds;
                                    flag=4;
                                    spanDurationTotSegC=getColorSpanTime(fecTotSegC, data.conf, flag, convFecTotSegC);

                                    fechaInicioAsigPH=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioAsigPH);
                                    fechaFinAsigPH=validaDiasFestivos(data.diasFestivos, sellT.fechaFinAsigPH);
                                    if ((!_.isEmpty(fechaInicioAsigPH) && !_.isNull(fechaInicioAsigPH)) && 
                                        (!_.isEmpty(fechaFinAsigPH) && !_.isNull(fechaFinAsigPH))) {
                                        fecTemp=moment(fechaInicioAsigPH).format('DD-MM-YYYY');
                                        fechaFinAsigPH=(fecTemp !== moment(sellT.fechaInicioAsigPH).format('DD-MM-YYYY')) ? moment(fechaFinAsigPH).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinAsigPH;
                                    }
                                    now='';
                                    then='';
                                    now  = fechaInicioAsigPH;
                                    then = fechaFinAsigPH;
                                    fecTotPHA = getTotalDuration(then,now);
                                    convFecTotPHA=(fecTotPHA === 0) ? '--' : fecTotPHA['_data'].hours+' : '+fecTotPHA['_data'].minutes+' : '+fecTotPHA['_data'].seconds;
                                    flag=5;
                                    spanDurationTotPHA=getColorSpanTime(fecTotPHA, data.conf, flag, convFecTotPHA);

                                    fechaInicioRealizoPH=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioRealizoPH);
                                    fechaFinRealizoPH=validaDiasFestivos(data.diasFestivos, sellT.fechaFinRealizoPH);
                                    if ((!_.isEmpty(fechaInicioRealizoPH) && !_.isNull(fechaInicioRealizoPH)) && 
                                        (!_.isEmpty(fechaFinRealizoPH) && !_.isNull(fechaFinRealizoPH))) {
                                        fecTemp=moment(fechaInicioRealizoPH).format('DD-MM-YYYY');
                                        fechaFinRealizoPH=(fecTemp !== moment(sellT.fechaInicioRealizoPH).format('DD-MM-YYYY')) ? moment(fechaFinRealizoPH).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinRealizoPH;
                                    }
                                    now='';
                                    then='';
                                    now  = fechaInicioRealizoPH;
                                    then = fechaFinRealizoPH;
                                    fecTotRPH = getTotalDuration(then,now);
                                    convFecTotRPH=(fecTotRPH === 0) ? '--' : fecTotRPH['_data'].hours+' : '+fecTotRPH['_data'].minutes+' : '+fecTotRPH['_data'].seconds;
                                    flag=6;
                                    spanDurationTotRPH=getColorSpanTime(fecTotRPH, data.conf, flag, convFecTotRPH);

                                    fechaInicioAnomPH=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioAnomPH);
                                    fechaFinAnomPH=validaDiasFestivos(data.diasFestivos, sellT.fechaFinAnomPH);
                                    if ((!_.isEmpty(fechaInicioAnomPH) && !_.isNull(fechaInicioAnomPH)) && 
                                        (!_.isEmpty(fechaFinAnomPH) && !_.isNull(fechaFinAnomPH))) {
                                        fecTemp=moment(fechaInicioAnomPH).format('DD-MM-YYYY');
                                        fechaFinAnomPH=(fecTemp !== moment(sellT.fechaInicioAnomPH).format('DD-MM-YYYY')) ? moment(fechaFinAnomPH).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinAnomPH;
                                    }
                                    now='';
                                    then='';
                                    now  = fechaInicioAnomPH;
                                    then = fechaFinAnomPH;
                                    fecTotAnPH = getTotalDuration(then,now);
                                    convFecTotAnPH=(fecTotAnPH === 0) ? '--' : fecTotAnPH['_data'].hours+' : '+fecTotAnPH['_data'].minutes+' : '+fecTotAnPH['_data'].seconds;
                                    flag=7;
                                    spanDurationTotAnPH=getColorSpanTime(fecTotAnPH, data.conf, flag, convFecTotAnPH);

                                    fechaInicioAsigInst=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioAsigInst);
                                    fechaFinAsigInst=validaDiasFestivos(data.diasFestivos, sellT.fechaFinAsigInst);
                                    if ((!_.isEmpty(fechaInicioAsigInst) && !_.isNull(fechaInicioAsigInst)) && 
                                        (!_.isEmpty(fechaFinAsigInst) && !_.isNull(fechaFinAsigInst))) {
                                        fecTemp=moment(fechaInicioAsigInst).format('DD-MM-YYYY');
                                        fechaFinAsigInst=(fecTemp !== moment(sellT.fechaInicioAsigInst).format('DD-MM-YYYY')) ? moment(fechaFinAsigInst).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinAsigInst;
                                    }
                                    now='';
                                    then='';
                                    now  = fechaInicioAsigInst;
                                    then = fechaFinAsigInst;
                                    fecTotRIAs = getTotalDuration(then,now);
                                    convFecTotRIAs=(fecTotRIAs === 0) ? '--' : fecTotRIAs['_data'].hours+' : '+fecTotRIAs['_data'].minutes+' : '+fecTotRIAs['_data'].seconds;
                                    flag=8;
                                    spanDurationTotRIAs=getColorSpanTime(fecTotRIAs, data.conf, flag, convFecTotRIAs);

                                    fechaInicioRealInst=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioRealInst);
                                    fechaFinRealInst=validaDiasFestivos(data.diasFestivos, sellT.fechaFinRealInst);
                                    if ((!_.isEmpty(fechaInicioRealInst) && !_.isNull(fechaInicioRealInst)) && 
                                        (!_.isEmpty(fechaFinRealInst) && !_.isNull(fechaFinRealInst))) {
                                        fecTemp=moment(fechaInicioRealInst).format('DD-MM-YYYY');
                                        fechaFinRealInst=(fecTemp !== moment(sellT.fechaInicioRealInst).format('DD-MM-YYYY')) ? moment(fechaFinRealInst).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinRealInst;
                                    }
                                    now='';
                                    then='';
                                    now  = fechaInicioRealInst;
                                    then = fechaFinRealInst;
                                    fecTotRI = getTotalDuration(then,now);
                                    convFecTotRI=(fecTotRI === 0) ? '--' : fecTotRI['_data'].hours+' : '+fecTotRI['_data'].minutes+' : '+fecTotRI['_data'].seconds;
                                    
                                    flag=9;
                                    spanDurationTotRI=getColorSpanTime(fecTotRI, data.conf, flag, convFecTotRI);

                                    now='';
                                    then='';
                                    fechaInicioAnomInst=validaDiasFestivos(data.diasFestivos, sellT.fechaInicioAnomInst);
                                    fechaFinAnomInst=validaDiasFestivos(data.diasFestivos, sellT.fechaFinAnomInst);
                                    if ((!_.isEmpty(fechaInicioAnomInst) && !_.isNull(fechaInicioAnomInst)) && 
                                        (!_.isEmpty(fechaFinAnomInst) && !_.isNull(fechaFinAnomInst))) {
                                        fecTemp=moment(fechaInicioAnomInst).format('DD-MM-YYYY');
                                        fechaFinAnomInst=(fecTemp !== moment(sellT.fechaInicioAnomInst).format('DD-MM-YYYY')) ? moment(fechaFinAnomInst).add(1, 'd').format('YYYY-MM-DD HH:mm:ss') : fechaFinAnomInst;
                                    }
                                    now  = fechaInicioAnomInst;
                                    then = fechaFinAnomInst;
                                    fecTotRIAn = getTotalDuration(then,now);
                                    convFecTotRIAn=(fecTotRIAn === 0) ? '--' : fecTotRIAn['_data'].hours+' : '+fecTotRIAn['_data'].minutes+' : '+fecTotRIAn['_data'].seconds;
                                    flag=10;
                                    spanDurationTotRIAn=getColorSpanTime(fecTotRIAn, data.conf, flag, convFecTotRIAn);

                                    if (flagExcel === 1) {
                                        console.log('entre');
                                        arrDatos.push({
                                            No_Cliente:No_Cliente,
                                            convFecTotVenta:convFecTotVenta,
                                            convFecTotFin:convFecTotFin,
                                            convFecTotRech:convFecTotRech,
                                            convFecTotSegC:convFecTotSegC,
                                            convFecTotPHA:convFecTotPHA,
                                            convFecTotRPH:convFecTotRPH,
                                            convFecTotAnPH:convFecTotAnPH,
                                            convFecTotRIAs:convFecTotRIAs,
                                            convFecTotRI:convFecTotRI,
                                            convFecTotRIAn:convFecTotRIAn
                                        });
                                    }else{
                                        console.log('entre', _.isUndefined(flagExcel));
                                        htmlAppend += '<tr>';
                                        htmlAppend += '<td>'+No_Cliente+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotVenta+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotFin+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotRech+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotSegC+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotPHA+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotRPH+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotAnPH+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotRIAs+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotRI+'</td>';
                                        htmlAppend += '<td>'+spanDurationTotRIAn+'</td>';
                                        htmlAppend += '<td>'+fechaInicioVenta+'</td>';
                                        htmlAppend += '</tr>';
                                    }
                                    now='';
                                    then='';
                                //}
                            });
                            if (flagExcel === 1) {
                                console.log('arrDatos', arrDatos);
                                generarExcelTVtas(arrDatos)
                            }else{
                                $('#bodyDataSellTimes').html('');
                                $('#bodyDataSellTimes').append(htmlAppend);
                                $("#tablaReporteTVentas").DataTable().destroy();
                                $("#tablaReporteTVentas").DataTable({
                                    "columnDefs": [{
                                        "defaultContent": "-",
                                        "targets": "_all"
                                    }],
                                    "order": [[0, 'desc']],
                                    autoWidth: true,
                                    searching: true,
                                    "language": {
                                        "lengthMenu": "Mostrar _MENU_",
                                        "search": "Buscar:   ",
                                        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                        "paginate": {
                                            "first": "Primera",
                                            "previous": "Anterior",
                                            "next": "Siguiente",
                                            "last": "Ultima"
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
                function validaDiasFestivos(objDias, fecComparar) {
                    if (!_.isEmpty(fecComparar) && !_.isNull(fecComparar)) {
                        _.each(objDias, function(diasF, index) {
                            fecTemp=moment(fecComparar).format('YYYY-MM-DD');
                            if (diasF.dia === fecTemp) {
                                var startdate = moment(fecComparar);
                                startdate.add(1, 'd');
                                fecComparar=moment(startdate).format('YYYY-MM-DD HH:mm:ss');
                            }
                        });
                    }
                    return fecComparar;
                }
                function getTotalDuration(then, now) {
                    var totalTime = 0;
                    if ((!_.isEmpty(now) && !_.isNull(now)) && (!_.isEmpty(then) && !_.isNull(then))) {
                        totalTime = moment.duration(moment(then).diff(moment(now)));
                        //console.log('totalTime', totalTime);
                    }
                    return totalTime;
                }
                function getColorSpanTime(objDuration, objConf, flag, text) {
                    if (objDuration !== 0) {
                        var revVtaVerde,
                            revVtaAm,
                            revVtaRojo;
                        switch (flag) {
                            case 1:
                                revVtaVerde=objConf[0];
                                revVtaAm=objConf[1];
                                revVtaRojo=objConf[2];
                            break;
                            case 2:
                                revVtaVerde=objConf[3];
                                revVtaAm=objConf[4];
                                revVtaRojo=objConf[5];
                            break;
                            case 3:
                                revVtaVerde=objConf[6];
                                revVtaAm=objConf[7];
                                revVtaRojo=objConf[8];
                            break;
                            case 4:
                                revVtaVerde=objConf[9];
                                revVtaAm=objConf[10];
                                revVtaRojo=objConf[11];
                            break;
                            case 5:
                                revVtaVerde=objConf[12];
                                revVtaAm=objConf[13];
                                revVtaRojo=objConf[14];
                            break;
                            case 6:
                                revVtaVerde=objConf[15];
                                revVtaAm=objConf[16];
                                revVtaRojo=objConf[17];
                            break;
                            case 7:
                                revVtaVerde=objConf[18];
                                revVtaAm=objConf[19];
                                revVtaRojo=objConf[20];
                            break;
                            case 8:
                                revVtaVerde=objConf[21];
                                revVtaAm=objConf[22];
                                revVtaRojo=objConf[23];
                            break;
                            case 9:
                                revVtaVerde=objConf[24];
                                revVtaAm=objConf[25];
                                revVtaRojo=objConf[26];
                            break;
                            case 10:
                                revVtaVerde=objConf[27];
                                revVtaAm=objConf[28];
                                revVtaRojo=objConf[29];
                            break;
                        }
                        calcAm=parseInt(revVtaAm.labelFrom) + parseInt(revVtaAm.labelTo);
                        if (((parseInt(objDuration['_data'].hours) <= parseInt(revVtaVerde.labelFrom)) && 
                            (parseInt(objDuration['_data'].hours) >= parseInt(revVtaVerde.labelFrom))) || 
                            (parseInt(objDuration['_data'].hours) <= parseInt(revVtaVerde.labelTo))) {
                            //console.log('verde');
                            span='<span class="label label-success">'+text+'</span>';
                        }else if((parseInt(objDuration['_data'].hours) >= parseInt(revVtaAm.labelFrom)) && 
                                 (parseInt(objDuration['_data'].hours) <= parseInt(revVtaAm.labelTo))){
                            //console.log('amarillo');
                            span='<span class="label label-warning">'+text+'</span>';
                        }else if(parseInt(objDuration['_data'].hours) >= parseInt(revVtaRojo.labelFrom)){
                            spanDuration='<span=getColorSpanTime(console, data.conf, flag, text);'
                            //console.log('rojo');
                            span='<span class="label label-danger">'+text+'</span>';
                        }
                    }else{
                        span=text;
                    }
                    revVtaVerde=null;
                    revVtaAm=null;
                    revVtaRojo=null;
                    return span;
                }
                function loadConcentratedStatus() {
                    $("#txtType").hide();
                    $("#txtStatus").hide();
                    $("#filtrosPendCompletos").hide();
                    var htmlAppend='';
                    $.ajax({
                        method: "GET",
                        //url: "dataLayer/callsWeb/concentratedStatus.php",
                        url: "dataLayer/callsWeb/getReporteEstatus.php",
                        dataType: "JSON",
                        success: function (data) {
                            console.log('data', data);
                            _.each(data.data, function(conc, index) {
                                console.log('index', index);
                                htmlAppend+= '<tr>';
                                htmlAppend+= '<td style="background-color: #ffffff;">' + conc.agency + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.revisionVentas + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.revisionFinanciera + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.rechazadoVentas + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.rechazadoFinanciera + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.segundaCaptura + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.revisionSegundaCaptura + '</td>';
                                htmlAppend+= "<td style='background-color: #bfbfbf;'></td>";
                                htmlAppend+= '<td style="background-color: #bfbfbf;">' + conc.phPorAsignar + '</td>';
                                htmlAppend+= '<td style="background-color: #bfbfbf;">' + conc.phEnProceso + '</td>';
                                htmlAppend+= '<td style="background-color: #bfbfbf;">' + conc.phCompleto + '</td>';
                                htmlAppend+= "<td style='background-color: #808080;'></td>";
                                htmlAppend+= '<td style="background-color: #808080;">' + conc.insPorAsignar + '</td>';
                                htmlAppend+= '<td style="background-color: #808080;">' + conc.insEnProceso + '</td>';
                                htmlAppend+= '<td style="background-color: #808080;">' + conc.insCompleto + '</td>';
                                htmlAppend+= '</tr>';
                            });
                            $('#tablaReporteEstatus tbody').append(htmlAppend);
                            $("#tablaReporteEstatus").DataTable().destroy();
                            $("#tablaReporteEstatus").DataTable({
                                "order": [[0, 'desc']],
                                autoWidth: true,
                                searching: true,
                                "language": {
                                    "lengthMenu": "Mostrar _MENU_",
                                    "search": "Buscar:   ",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                    "paginate": {
                                        "first": "Primera",
                                        "previous": "Anterior",
                                        "next": "Siguiente",
                                        "last": "Ultima"
                                    }
                                }
                            });
                        }
                    });
                }

                function getConcentratedArray(callback) {
                    var concentrated = [];
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/getReporteEstatus.php",
                        dataType: "JSON",
                        success: function (data) {
                            _.each(data.data, function(dato, index) {
                                concentrated.push(dato);
                            });
                            callback();
                        }
                    });
                    return concentrated;
                }

                function getReviews(callback) {
                    $.ajax({
                        /*...*/
                        success  : function (data) {
                            $.each( data.reviews, function( i, itemData ) {
                                reviewArray[i] = itemData.review;
                            });
                            callback();
                        },
                        /*...*/
                    });
                }

                function createExcel(tipodDeTabla) {

                    if (tipoDeTabla == 1) {

                    } else if (tipoDeTabla == 2) {

                    } else if (tipoDeTabla == 3) {
                        var Id;
                        var Contrato;
                        var Cliente;
                        var Ph;
                        var Venta;
                        var Instalacion;
                        var MunicipioVenta;
                        var Colonia;
                        var UsuarioVenta;
                        var Agencia;
                        $.ajax({
                            method: "GET",
                            url: "dataLayer/callsWeb/sellsReport.php",
                            dataType: "JSON",
                            success: function (data) {
                                for (var sells in data) {
                                    Id = data[sells].Id,
                                        Contrato = data[sells].Contrato,
                                        Cliente = data[sells].Cliente,
                                        Ph = data[sells].Ph,
                                        Venta = data[sells].Venta,
                                        Instalacion = data[sells].Instalacion,
                                        MunicipioVenta = data[sells].Municipio,
                                        Colonia = data[sells].Colonia,
                                        UsuarioVenta = data[sells].Usuario,
                                        Agencia = data[sells].Agencia
                                }
                            }
                        });

                    } else if (tipoDeTabla == 4) {
                        var Contrato;
                        var Agencia;
                        var RechazadoPorAgencia;
                        var Agente;
                        var Motivo;

                        $.ajax({
                            method: "GET",
                            url: "dataLayer/callsWeb/rejectedReasonsTwo.php",
                            dataType: "JSON",
                            success: function (data) {
                                for (var rejected in data) {
                                    Contrato = data[rejected].Contrato,
                                        Agencia = data[rejected].Agencia,
                                        RechazadoPorAgencia = data[rejected].RechazadoPorAgencia,
                                        Agente = data[rejected].Agente,
                                        Motivo = data[rejected].Motivo
                                }
                            }
                        });


                    }
                }
                $(document).on('click', '#btnFiltrarPorFechas', function () {
                    filtrarPorFechas();
                });
                function filtrarPorFechas() {
                    var weekDayFI =moment($("#dateFrom").val()).format('d');
                    var weekDayFF =moment($("#dateTo").val()).format('d');
                    var titulo=$(".tituloReporte").text();
                    var oTable;
                    console.log('titulo', titulo);
                    switch (titulo) {
                        case 'REPORTE DE CONCENTRADO DE STATUS':
                            validaFechaVentas(oTable, titulo);
                        break;
                        case 'REPORTE DE VENTAS':
                            oTable = $("#tablaReporteVentas").dataTable();
                            validaFechaVentas(oTable, titulo);
                        break;
                        case 'REPORTE TIEMPO DE VENTAS':
                            oTable = $("#tablaReporteTVentas").dataTable();
                            validaFechaVentas(oTable, titulo);
                        break;
                        case 'REPORTE MOTIVOS DE RECHAZO':
                            validaFechaVentas(oTable, titulo);
                        break;
                    }
                }
                function validaFechaVentas(objTable, titulo) {
                    console.log('validaFechaVentas');
                    var dateT=$('#dateTo').val(), dateF=$('#dateFrom').val();
                    if (!_.isEmpty(dateF) && !_.isEmpty(dateT)) {
                        var fechainicial = $('#dateFrom').val();
                        var fechafinal = $('#dateTo').val();
                        if (fechainicial === "" && fechafinal === "") {
                            MostrarToast(2, "Rango fechas erroneo", "Las fechas no pueden ir vacias");
                        }else if (fechainicial != "" && fechafinal === "") {
                            MostrarToast(2, "Rango fechas erroneo", "La fecha final no puede ir vacia");
                        }else if (fechafinal != "" && fechainicial === "") {
                            MostrarToast(2, "Rango fechas erroneo", "La fecha inicial no puede ir vacia");
                        }else if (fechainicial > fechafinal) {
                            MostrarToast(2, "Rango fechas erroneo", "La fecha inicial no puede ser mayor a la fecha final");
                        } else if (fechafinal < fechainicial) {
                            MostrarToast(2, "Rango fechas erroneo", "La fecha final no puede ser mayor a la fecha final");
                        } else {
                            console.log('titulo', titulo);
                            if (titulo === 'REPORTE DE VENTAS' || titulo === 'REPORTE TIEMPO DE VENTAS') {
                                objTable.fnDraw();
                            }else if (titulo === 'REPORTE DE CONCENTRADO DE STATUS'){
                                loadConcentratedStatus(fechainicial,fechafinal);
                            }else if(titulo === 'REPORTE MOTIVOS DE RECHAZO'){
                                console.log('entre a filtrar por fechas rechazo');
                                loadRejectedReasonForm(fechainicial,fechafinal);
                            }
                        }
                    }else{

                    }
                }
                //ESTE PLUGIN SE INVOCA AL MOMENTO DE ESTAR INSERTANDO LA INFORMACION DE LA TABLA
                $.fn.dataTableExt.afnFiltering.push(
                    function (settings, data, iDataIndex) {
                        //console.log('data', data);
                        var iFini = $('#dateFrom').val();
                        var iFfin = $('#dateTo').val();
                        var titulo=$(".tituloReporte").text();
                        if (titulo === 'REPORTE DE VENTAS') {
                            var fecData = moment(data[11]).format('YYYY-MM-DD');
                            if((typeof(iFini) !== 'undefined' && iFini !== null && iFini !== '')){
                            if ((moment(fecData).isSame(iFini) || moment(fecData).isAfter(iFini)) && 
                                (moment(fecData).isSame(iFfin) || moment(fecData).isBefore(iFfin)) ) {
                                console.log('filtra');
                                return true;
                            }else {
                                return false;
                            }
                            }else{
                                return true;
                            }
                        }else if (titulo === 'REPORTE TIEMPO DE VENTAS') {
                            //validamos si la fecha de inicio es lunes para traer los reportes de sabado y domingo
                            var dateT=$('#dateTo').val(), dateF=$('#dateFrom').val();
                            if (!_.isEmpty(dateF) && !_.isEmpty(dateT)) {
                                var date = moment($("#dateFrom").val());
                                var dow = date.day();
                                var fdate=moment($("#dateFrom").val()).hour(17).minute(01);
                                var tdate=moment($("#dateTo").val()).hour(17).minute(00);
                                var startdate = moment(fdate);
                                if (dow === 1) {
                                    startdate.subtract(3, 'd');
                                    console.log('startdate', startdate);
                                }else{
                                    startdate.subtract(1, 'd');
                                }
                                var fecData = moment(data[11]);
                                if((typeof(startdate) !== 'undefined' && startdate !== null && startdate !== '')){
                                if ((moment(fecData).isSame(startdate) || moment(fecData).isAfter(startdate)) && 
                                    (moment(fecData).isSame(tdate) || moment(fecData).isBefore(tdate)) ) {
                                    return true;
                                }else {
                                    return false;
                                }
                                }else{
                                    return true;
                                }
                            }else{
                                return true;
                            } 
                        }else{
                            return true;
                        }
                        
                    }
                );
                $('#limpiarFiltros').click(function () {
                    $('#dateFrom').val('');
                    $('#dateTo').val('');

                    var titulo=$(".tituloReporte").text();
                    switch (titulo) {
                        case 'REPORTE DE CONCENTRADO DE STATUS':
                            loadConcentratedStatus();
                        break;
                        case 'REPORTE DE VENTAS':
                            var oTable = $("#tablaReporteVentas").DataTable();
                            $( "#txtType" ).val(0).change();
                            oTable.search('').columns().search( '' ).draw();
                        break;
                        case 'REPORTE TIEMPO DE VENTAS':
                            var oTable = $("#tablaReporteTVentas").DataTable();
                            $( "#txtType" ).val(0).change();
                            oTable.search('').columns().search( '' ).draw();
                            //oTable.fnDraw();
                        break;
                        case 'REPORTE MOTIVOS DE RECHAZO':
                            loadRejectedReasonForm();
                        break;
                    }
                });

                function loadRejectedReasonForm(dateF,dateT) {
                    $("#txtType").hide();
                    $("#txtStatus").hide();
                    $("#filtrosPendCompletos").hide();
                    console.log("desplegar los datos de la tabla de rechazados");
                    var limit = $('#txtQty').val();
                    var dateFrom = $('#dateFrom').val();
                    var dateTo = $('#dateTo').val();
                    var htmlAppend = '';
                    //getReporteRechazo
                    $.ajax({
                        method: "GET",
                        //url: "dataLayer/callsWeb/rejectedReasonsTwo.php",
                        url: "dataLayer/callsWeb/getReporteRechazo.php",
                        data: {
                            dateF:dateF,
                            dateT:dateT,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if ($.fn.DataTable.isDataTable('#tablaReporteRechazos')){
                                console.log('existe dataTable');
                                $("#tablaReporteRechazos").DataTable().destroy();
                                $('#tablaReporteRechazos tbody').empty();
                            }
                            console.log('data loadRejectedReasonForm', data);
                            _.each(data.data, function(rech, index) {
                                htmlAppend += '<tr>';
                                htmlAppend += '<td>' + rech.agreementNumber + '</td>';
                                htmlAppend += '<td>' + rech.agencia + '</td>';
                                htmlAppend += '<td>' + rech.validadoPor + '</td>';
                                htmlAppend += '<td>' + rech.reason + '</td>';
                                htmlAppend += '</tr>';
                            });
                            $('#bodyDataRejectedReason').html('');
                            $('#bodyDataRejectedReason').append(htmlAppend);
                            $("#tablaReporteRechazos").DataTable().destroy();
                            $("#tablaReporteRechazos").DataTable({
                                "order": [[0, 'desc']],
                                autoWidth: true,
                                searching: true,
                                "language": {
                                    "lengthMenu": "Mostrar _MENU_",
                                    "search": "Buscar:   ",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                    "paginate": {
                                        "first": "Primera",
                                        "previous": "Anterior",
                                        "next": "Siguiente",
                                        "last": "Ultima"
                                    }
                                }
                            });
                        }
                    });
                }
                function loadConcentratedStatus(dateF,dateT) {
                    $("#txtType").hide();
                    $("#txtStatus").hide();
                    $("#filtrosPendCompletos").hide();
                    var htmlAppend='';
                    $.ajax({
                        method: "GET",
                        //url: "dataLayer/callsWeb/concentratedStatus.php",
                        url: "dataLayer/callsWeb/getReporteEstatus.php",
                        data: {
                            dateF:dateF,
                            dateT:dateT,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            console.log('data', data);
                            if ($.fn.DataTable.isDataTable('#tablaReporteEstatus')){
                                console.log('existe dataTable');
                                $("#tablaReporteEstatus").DataTable().destroy();
                                $('#tablaReporteEstatus tbody').empty();
                            }
                            _.each(data.data, function(conc, index) {
                                console.log('index', index);
                                htmlAppend+= '<tr>';
                                htmlAppend+= '<td style="background-color: #ffffff;">' + conc.agency + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.revisionVentas + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.revisionFinanciera + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.rechazadoVentas + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.rechazadoFinanciera + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.segundaCaptura + '</td>';
                                htmlAppend+= '<td style="background-color: #d9d9d9;">' + conc.revisionSegundaCaptura + '</td>';
                                htmlAppend+= "<td style='background-color: #bfbfbf;'></td>";
                                htmlAppend+= '<td style="background-color: #bfbfbf;">' + conc.phPorAsignar + '</td>';
                                htmlAppend+= '<td style="background-color: #bfbfbf;">' + conc.phEnProceso + '</td>';
                                htmlAppend+= '<td style="background-color: #bfbfbf;">' + conc.phCompleto + '</td>';
                                htmlAppend+= "<td style='background-color: #808080;'></td>";
                                htmlAppend+= '<td style="background-color: #808080;">' + conc.insPorAsignar + '</td>';
                                htmlAppend+= '<td style="background-color: #808080;">' + conc.insEnProceso + '</td>';
                                htmlAppend+= '<td style="background-color: #808080;">' + conc.insCompleto + '</td>';
                                htmlAppend+= '</tr>';
                            });
                            $('#tablaReporteEstatus tbody').append(htmlAppend);
                            $("#tablaReporteEstatus").DataTable().destroy();
                            $("#tablaReporteEstatus").DataTable({
                                "order": [[0, 'desc']],
                                autoWidth: true,
                                searching: true,
                                "language": {
                                    "lengthMenu": "Mostrar _MENU_",
                                    "search": "Buscar:   ",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                    "paginate": {
                                        "first": "Primera",
                                        "previous": "Anterior",
                                        "next": "Siguiente",
                                        "last": "Ultima"
                                    }
                                }
                            });
                        }
                    });
                }

                //exportacion de informacion
                $('#btn_download:not(:disabled)').click(function () {
                    $('#btn_download').prop('disabled', true);
                    $("#btn_download").notify("Empezo proceso de exportacion", "info");
                    var dateFrom = $('#dateFrom').val();
                    var dateTo = $('#dateTo').val();
                    if (dateFrom !== '') {
                        dateFrom = moment($('#dateFrom').val()).format("YYYY-MM-DD HH:mm:ss");
                    }
                    if (dateTo !== '') {
                        dateTo = moment($('#dateTo').val()).format("YYYY-MM-DD HH:mm:ss");
                    }
                    if(dateFrom !== '' && dateTo !== ''){
                        if (dateFrom > dateTo) {
                            MostrarToast(2, "Rango fechas erroneo", "La fecha inicial no puede ser mayor a la fecha final");
                        } else if (dateTo < dateFrom) {
                            MostrarToast(2, "Rango fechas erroneo", "La fecha final no puede ser mayor a la fecha final");
                        } else {
                            dateFrom = moment($('#dateFrom').val()).format("YYYY-MM-DD");
                            //dateTo = moment($('#dateTo').val()).format("YYYY-MM-DD");
                            dateTo = moment($('#dateTo').val(), "YYYY-MM-DD").add(1, 'days');
                            dateTo = dateTo.format("YYYY-MM-DD");
                            var titulo=$(".tituloReporte").text(), urlD='';
                            switch (titulo) {
                                case 'REPORTE DE CONCENTRADO DE STATUS':
                                    urlD  = "dataLayer/callsWeb/downloadExcelReporteEstatus.php";
                                    generarExcelMisc(dateFrom,dateTo, urlD);
                                break;
                                case 'REPORTE DE VENTAS':
                                    urlD  = "dataLayer/callsWeb/downloadExcelReporteVentas.php";
                                    generarExcelMisc(dateFrom,dateTo, urlD);
                                break;
                                case 'REPORTE TIEMPO DE VENTAS':
                                    urlD  = "dataLayer/callsWeb/downloadExcelReporteTiempos.php";
                                break;
                                case 'REPORTE MOTIVOS DE RECHAZO':
                                    urlD  = "dataLayer/callsWeb/downloadExcelRechazos.php";
                                    generarExcelMisc(dateFrom,dateTo, urlD);
                                break;
                            }
                        }
                    }else{
                        var titulo=$(".tituloReporte").text(), urlD='';
                        switch (titulo) {
                            case 'REPORTE DE CONCENTRADO DE STATUS':
                                urlD  = "dataLayer/callsWeb/downloadExcelReporteEstatus.php";
                                generarExcelMisc(dateFrom,dateTo, urlD);
                            break;
                            case 'REPORTE DE VENTAS':
                                urlD  = "dataLayer/callsWeb/downloadExcelReporteVentas.php";
                                generarExcelMisc(dateFrom,dateTo, urlD);
                            break;
                            case 'REPORTE TIEMPO DE VENTAS':
                                urlD  = "dataLayer/callsWeb/downloadExcelReporteTiempos.php";
                                var flagExcel=1;
                                $('#btn_download').prop('disabled', false);
                                loadTimeSells(flagExcel);
                            break;
                            case 'REPORTE MOTIVOS DE RECHAZO':
                                urlD  = "dataLayer/callsWeb/downloadExcelRechazos.php";
                                generarExcelMisc(dateFrom,dateTo, urlD);
                            break;
                        }
                        
                    }
                });
                function generarExcelMisc(dateFrom,dateTo, urlD){
                    $.ajax({
                        method: "POST",
                        //url: "dataLayer/callsWeb/concentratedStatus.php",
                        url: urlD,
                        data: {
                            dateF:dateFrom,
                            dateT:dateTo,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            $("#btn_download").notify("El archivo termino de generarse correctamente", "success");
                            $('#b_download').show();
                            var $a = $("<a>");
                            $a.attr("href",data.file);
                            $("#b_download").append($a);
                            $a.attr("download","ReporteFormularios_.xls");
                            $a[0].click();
                            $('#b_download').hide();
                            $('#b_download a').remove();
                            $('#btn_download').prop('disabled', false);
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            console.log('textStatus', textStatus);
                            $("#btn_download").notify("Ocurrio un problema al generar el archivo", "error");
                            $('#btn_download').prop('disabled', false);
                        }
                    });
                }
                function generarExcelTVtas(collectionData){
                    $.ajax({
                        method: "POST",
                        url: "dataLayer/callsWeb/downloadExcelReporteTiempos.php",
                        data: {
                            collectionData:collectionData
                        },
                        dataType: "JSON",
                        success: function (data) {
                            console.log('data', data);
                            $("#btn_download").notify("El archivo termino de generarse correctamente", "success");
                            $('#b_download').show();
                            var $a = $("<a>");
                            $a.attr("href",data.file);
                            $("#b_download").append($a);
                            $a.attr("download","ReporteFormularios_.xls");
                            $a[0].click();
                            $('#b_download').hide();
                            $('#b_download a').remove();
                            $('#btn_download').prop('disabled', false);
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            console.log('textStatus', textStatus);
                            $("#btn_download").notify("Ocurrio un problema al generar el archivo", "error");
                            $('#btn_download').prop('disabled', false);
                        }
                    });
                }
            </script>
