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
                                <li class="active col-md-3" ><a href="#liberacionDirecciones" class="liberacionDirecciones" data-toggle="tab">Liberar direcciones</a></li>
                                <?php endif; ?>
                                
                                <?php
                                if{
                                    if (($_SESSION["typeAgency"] == "Comercializadora" || 
                                         $_SESSION["typeAgency"] == "Instalacion y Comercializadora" ||
                                         $_SESSION["typeAgency"] == "Instalacion") &&
                                        (strtoupper($_SESSION["nickname"]) != 'AYOPSA' && strtoupper($_SESSION["nickname"]) != 'SUPERADMIN')) {
                                        echo '<li class="col-md-3" ><a href="#solicitarDireccion" class="solicitarDireccion" data-toggle="tab">Solicitar Direccion</a></li>';
                                    }
                                }?>
                            </ul>
                            <div class="row">
                                <div class="form-inline">
                                    <p></p>
                                    <Label class="col-md-11 tituloReporte">LiberarDirecciones</Label>
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
                }

                $(document).ready(function () {
                    var string_nickname = $("#nicknameZone").html();
                    string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
                    string_nickname = string_nickname.trim();
                    $("#titleHeader").html("Reportes");
                    $("#subtitle-header").html("Detalle");
                    $("#tbHeaders").append();
                    $('#dateFrom').dcalendarpicker({format: "yyyy-mm-dd"});
                    $('#dateTo').dcalendarpicker({format: "yyyy-mm-dd"});
                });

                $(document).on('click', '.fullStatus', function () {
                    $('#dateFrom').val('');
                    $('#dateTo').val('');
                    $('#btnConfiguration').hide();
                    $('#btn_chart').show();
                    $('.tituloReporte').html('REPORTE CONCENTRADO DE ESTATUS');
                    //loadHeaders();
                    //loadConcentratedStatus();
                });

                $(document).on('click', '.sell', function () {
                    $('#dateFrom').val('');
                    $('#dateTo').val('');
                    $('#btnConfiguration').hide();
                    $('.tituloReporte').html('REPORTE DE VENTAS');
                    $('#btn_chart').hide();
                    //loadHeaders();
                    var tipo = 'pendientes';
                    //loadSell(tipo);
                });

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
            </script>
