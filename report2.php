<?php include("header.php") ?>


<!--Data Table-->
<link href="assets/js/data-table/css/jquery.dataTables.css" rel="stylesheet">
<link href="assets/js/data-table/css/dataTables.tableTools.css" rel="stylesheet">
<link href="assets/js/data-table/css/dataTables.colVis.min.css" rel="stylesheet">
<link href="assets/js/data-table/css/dataTables.responsive.css" rel="stylesheet">
<link href="assets/js/data-table/css/dataTables.scroller.css" rel="stylesheet">

<!--right slidebar-->
<script src="assets/js/slidebars.min.js"></script>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


<!-- Codigo -->
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading col-lg-12">
                <div class="form-inline">
                    <!--<p><b>CONSULTAS</b></p>-->
                </div>
            </header>

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- tabs -->
                        <div class="tabbable">
                            <ul class="nav nav-tabs">
                                <li class="active col-md-3"><a href="#fullStatus" class="fullStatus" data-toggle="tab">Concentrado
                                        de Estatus</a>
                                </li>
                                <li class="col-md-3"><a href="#sell" class="sell" data-toggle="tab">Venta</a></li>
                                <li class="col-md-3"><a href="#sellTime" class="sellTime" data-toggle="tab">Tiempo de
                                        venta</a></li>
                                <li class="col-md-3"><a href="#rejectedReason" class="rejectedReason" data-toggle="tab">Motivos
                                        de rechazo</a></li>
                            </ul>
                            <div class="row">
                                <div class="form-inline">
                                    <p></p>
                                    <Label class="col-md-11">REPORTE DE CONCENTRADO DE STATUS</Label>
                                    <button type="button" id="btn_chart" class="btn btn-success">
                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </button>
                                    <button type="submit" id="btn_download" class="btn btn-success">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <p></p>

                            <div class="form-inline">
                                <form method="POST" class="form-control-noBorder"
                                      style="background-image: none; background-color: initial; background-repeat: initial; background-attachment: initial; background-position: initial;"
                                      action="dataLayer/callsWeb/downloadExcelForms.php">
                                    <div class="form-inline">
                                        <label>Mostrar</label>
                                        <select name="report_length" aria-controls="report"
                                                class="form-control input-sm" id="txtQty">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        &nbsp;&nbsp;
                                        <label>Entradas</label>
                                        <label style="padding-left:1em;" class="text-capitalize">Fecha de:</label>&nbsp;
                                        <input class="form-control input-medium default-date-picker" size="12"
                                               type="text" id="dateFrom" value="">
                                        <label class="text-capitalize">Fecha hasta:</label>&nbsp;
                                        <input class="form-control input-medium default-date-picker" size="12"
                                               type="text" id="dateTo" value="">
                                        <button type="button" id="btn_search" class="btn btn-success">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </button>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="form-control input-medium" size="12" type="text" id="inputSearch"
                                               placeholder="B&uacute;squeda" value="">

                                        <div id="btnConfig"></div>
                                    </div>
                                </form>
                            </div>
                            <p></p>

                            <div class="tab-content">
                                <div class="tab-pane active" id="fullStatus"></div>
                                <div class="tab-pane" id="sell"></div>
                                <div class="tab-pane" id="sellTime"></div>
                                <div class="tab-pane" id="rejectedReason"></div>
                            </div>
                        </div>
                        <!-- /tabs -->
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

                                        <div class="form-group col-xs-3">
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
                                        <div class=" form-group col-xs-3">
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

                                        <div class=" form-group col-xs-3">
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
                                        <div class="form-group col-xs-3">
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

                                        <div class=" form-group col-xs-3">
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
                                        <div class=" form-group col-xs-3">
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

                                        <div class="form-group col-xs-3">
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
                                        <div class="form-group col-xs-3">
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

                                        <div class="form-group col-xs-3">
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
                                        <div class=" form-group col-xs-3">
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

                                        <div class="form-group col-xs-3">
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
                                        <div class=" form-group col-xs-3">
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

                                        <div class=" form-group col-xs-3">
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
                                        <div class=" form-group col-xs-3">
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

                                        <div class=" form-group col-xs-3">
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
                                        <div class="form-group col-xs-3">
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

                                        <div class=" form-group col-xs-3">
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
                                        <div class="form-group col-xs-3">
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

                                        <div class="form-group col-xs-3">
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
                                        <div class=" form-group col-xs-3">
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
                                    <div class="col-xs-9" style="margin-right: 10px;">
                                        <div class="col-xs-6">&nbsp;</div>
                                        <div class="col-xs-6">
                                            <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                                            <button type="button" class="btn btn-danger" id="btnCancelConfiguration"
                                                    style="width: 170px;height: 35px; margin-right: 10px;">CANCELAR
                                            </button>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
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
            <!--data table init-->
            <script src="assets/js/data-table-init.js"></script>
            <link rel="stylesheet" href="assets/css/dcalendar.picker.min.css"/>

            <script type="text/javascript">
                var concentratedArray = [];

                function loadHeaders() {
                    $("#fullStatus").html(
                        "<div class='row'>"
                        + "<section class='panel'>"
                        + "<table class='table table-striped custom-table table-hover'>"
                        + "<tr>"
                        + "<td colspan='2' style='background-color: #ffffff;'></td>"
                        + "<td colspan='5' style='background-color: #d9d9d9; text-align=center;'>Estatus de ventas</td>"
                        + "<td colspan='5' style='background-color: #bfbfbf; text-align=center;'>Estatus de PH</td>"
                        + "<td colspan='5' style='background-color: #808080; text-align=center;'>Estatus de Instalaci&oacute;n</td>"
                        + "<td colspan='2' style='background-color: #ffffff; text-align=center;'></td>"
                        + "</tr>"
                        + "<tr>"
                        + "<td>Id</td>"
                        + "<td>Agencia</td>"
                        + "<td style='background-color: #d9d9d9;'>Revisi&oacute;n Venta</td>"
                        + "<td style='background-color: #d9d9d9;'>Revisi&oacute;n Financiera</td>"
                        + "<td style='background-color: #d9d9d9;'>Rechazado Venta</td>"
                        + "<td style='background-color: #d9d9d9;'>Rechazado Financiera</td> "
                        + "<td style='background-color: #d9d9d9;'>Segunda Captura</td>"
                        + "<td style='background-color: #bfbfbf;'></td>"
                        + "<td style='background-color: #bfbfbf;'>Por asignar</td>"
                        + "<td style='background-color: #bfbfbf;'>En proceso</td>"
                        + "<td style='background-color: #bfbfbf;'>Pendiente</td>"
                        + "<td style='background-color: #bfbfbf;'>Completo</td>"
                        + "<td style='background-color: #808080;'>"
                        + "<td style='background-color: #808080;'>Por asignar</td>"
                        + "<td style='background-color: #808080;'>En proceso</td>"
                        + "<td style='background-color: #808080;'>Pendiente</td>"
                        + "<td style='background-color: #808080;'>Completo</td>"
                        + "<td>Fecha inicio</td>"
                        + "<td>Fecha final</td>"
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
                        + "<table class='table table-striped custom-table table-hover'>"
                        + "<thead>"
                        + "<tr>"
                        + "<th>Id</th>"
                        + "<th>Contrato</th>"
                        + "<th>No. Cliente</th>"
                        + "<th>PH</th>"
                        + "<th>Venta</th>"
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
                        + "<table class='table table-striped custom-table table-hover'>"
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
                        + "<table class='table table-striped custom-table table-hover'>"
                        + "<thead>"
                        + "<tr>"
                        + "<th>No. contrato</th>"
                        + "<th>Agencia</th>"
                        + "<th>Rechazado por</th>"
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

                $(document).ready(function () {
                    var string_nickname = $("#nicknameZone").html();
                    string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
                    string_nickname = string_nickname.trim();

                    $("#titleHeader").html("Reportes");
                    $("#subtitle-header").html("Detalle");
                    $("#tbHeaders").append();

                    loadHeaders();
                    loadConcentratedStatus();
                    loadTimeSells();
                    loadSell();
                    loadTimeSells();
                    $('#dateFrom').datepicker({
                        format: "yyyy-mm-dd",
                        dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S']
                    });
                    $('#dateTo').datepicker({
                        format: "yyyy-mm-dd",
                        dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S']
                    });
                });

                $(document).on('click', '.fullStatus', function () {
                    console.log("fullStatus Clicked");
                    $('#btnConfig').html('');
                    loadHeaders();
                });

                $(document).on('click', '.sell', function () {
                    console.log("sell Clicked");
                    $('#btnConfig').html('');
                    loadHeaders();
                });

                $(document).on('click', '.sellTime', function () {
                    $('#btnConfig').html('<small><button type="button" id="btnConfiguration" class="btn btn-success" style="height: 25px; width: 25px;"><span class="glyphicon glyphicon-download-alt" style="color:#fff;margin-bottom: 20px; margin-right:50px;"></span></button></small>');

                    $(document).on('click', '#btnConfiguration', function () {
                        $.ajax({
                            method: "GET",
                            url: "dataLayer/callsWeb/loadReportsConfig.php",
                            dataType: "JSON",
                            success: function (data) {
                                //console.log(data);
                                for (var elem in data) {
                                    /*console.log(data[elem].id);
                                     console.log(data[elem].name);
                                     console.log(data[elem].subId);
                                     console.log(data[elem].labelFrom);
                                     console.log(data[elem].labelTo);
                                     console.log(data[elem].labelName);
                                     console.log("Element ID :"+data[elem].id);*/
                                    if (data[elem].id == 1) {
                                        if (data[elem].subId == 1) {
                                            $('#txtTiempoRevGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoRevGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 2) {
                                            $('#txtTiempoRevYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoRevYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 3) {
                                            $('#txtTiempoRevRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 2) {
                                        if (data[elem].subId == 4) {
                                            $('#txtTiempoRevFincGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoRevFincGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 5) {
                                            $('#txtTiempoRevFincYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoRevFincYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 6) {
                                            $('#txtTiempoRevFincRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 3) {
                                        if (data[elem].subId == 7) {
                                            $('#txtTiempoDocsGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoDocsGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 8) {
                                            $('#txtTiempoDocsYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoDocsYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 9) {
                                            $('#txtTiempoDocsRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 4) {
                                        if (data[elem].subId == 10) {
                                            $('#txtTiempoCapturasGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoCapturasGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 11) {
                                            $('#txtTiempoCapturasYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoCapturasYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 12) {
                                            $('#txtTiempoCapturasRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 5) {
                                        if (data[elem].subId == 13) {
                                            $('#txtTiempoAsigGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoAsigGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 14) {
                                            $('#txtTiempoAsigYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoAsigYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 15) {
                                            $('#txtTiempoAsigRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 6) {
                                        if (data[elem].subId == 16) {
                                            $('#txtTiempoRealizadoGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoRealizadoGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 17) {
                                            $('#txtTiempoRealizadoYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoRealizadoYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 18) {
                                            $('#txtTiempoRealizadoRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 7) {
                                        if (data[elem].subId == 19) {
                                            $('#txtTiempoPhAnomaliasGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoPhAnomaliasGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 20) {
                                            $('#txtTiempoPhAnomaliasYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoPhAnomaliasYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 21) {
                                            $('#txtTiempoPhAnomaliasRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 8) {
                                        if (data[elem].subId == 22) {
                                            $('#txtTiempoAsigInstallGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoAsigInstalGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 23) {
                                            $('#txtTiempoAsigInstalYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoAsigInstalYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 24) {
                                            $('#txtTiempoAsigInstalRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 9) {
                                        if (data[elem].subId == 25) {
                                            $('#txtTiempoRealizInstalGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoRealizInstalGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 26) {
                                            $('#txtTiempoRealizInstalYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoRealizInstalYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 27) {
                                            $('#txtTiempoRealizInstalRed1').val(data[elem].labelFrom);
                                        }
                                    } else if (data[elem].id == 10) {
                                        if (data[elem].subId == 28) {
                                            $('#txtTiempoInstalAnomGreen1').val(data[elem].labelFrom);
                                            $('#txtTiempoInstalAnomGreen2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 29) {
                                            $('#txtTiempoInstalAnomYellow1').val(data[elem].labelFrom);
                                            $('#txtTiempoInstalAnomYellow2').val(data[elem].labelTo);
                                        } else if (data[elem].subId == 30) {
                                            $('#txtTiempoInstalAnomRed1').val(data[elem].labelFrom);
                                        }
                                    }
                                }
                                //$('#').html('');

                                /*var sitiesTemp = data.ot_municipios;
                                 sitiesTemp = sitiesTemp.ot_municipiosRow;

                                 for (var elem in sitiesTemp) {
                                 if (sitiesTemp[elem].nombre !== null) {
                                 $('#txMun').append('<option value="' + sitiesTemp[elem].idMunicipio + '">' + sitiesTemp[elem].nombre + '</option>');
                                 }
                                 }*/
                            }
                        });
                        $('#reportConfiguration').modal('show');
                    });
                });

                $(document).on('click', '.rejectedReason', function () {
                    console.log("rejectedReason Clicked");
                    $('#btnConfig').html('');
                    loadHeaders();
                });

                $(document).on('click', '#btnCancelConfiguration', function () {
                    console.log("btnCancelConfiguration Clicked");
                    $('#reportConfiguration').modal('hide');
                });

                $(document).on('click', '#btnSaveConfiguration', function () {
                    console.log("btnSaveConfiguration Clicked");
                    var txtTiempoRevGreen1 = $('#txtTiempoRevGreen1').val();
                    var txtTiempoRevGreen2 = $('#txtTiempoRevGreen2').val();
                    var txtTiempoRevYellow1 = $('#txtTiempoRevYellow1').val();
                    var txtTiempoRevYellow2 = $('#txtTiempoRevYellow2').val();
                    var txtTiempoRevRed1 = $('#txtTiempoRevRed1').val();

                    var txtTiempoRevFincGreen1 = $('#txtTiempoRevFincGreen1').val();
                    var txtTiempoRevFincGreen2 = $('#txtTiempoRevFincGreen2').val();
                    var txtTiempoRevFincYellow1 = $('#txtTiempoRevFincYellow1').val();
                    var txtTiempoRevFincYellow2 = $('#txtTiempoRevFincYellow2').val();
                    var txtTiempoRevFincRed1 = $('#txtTiempoRevFincRed1').val();

                    var txtTiempoDocsGreen1 = $('#txtTiempoDocsGreen1').val();
                    var txtTiempoDocsGreen2 = $('#txtTiempoDocsGreen2').val();
                    var txtTiempoDocsYellow1 = $('#txtTiempoDocsYellow1').val();
                    var txtTiempoDocsYellow2 = $('#txtTiempoDocsYellow2').val();
                    var txtTiempoDocsRed1 = $('#txtTiempoDocsRed1').val();

                    var txtTiempoCapturasGreen1 = $('#txtTiempoCapturasGreen1').val();
                    var txtTiempoCapturasGreen2 = $('#txtTiempoCapturasGreen2').val();
                    var txtTiempoCapturasYellow1 = $('#txtTiempoCapturasYellow1').val();
                    var txtTiempoCapturasYellow2 = $('#txtTiempoCapturasYellow2').val();
                    var txtTiempoCapturasRed1 = $('#txtTiempoCapturasRed1').val();

                    var txtTiempoAsigGreen1 = $('#txtTiempoAsigGreen1').val();
                    var txtTiempoAsigGreen2 = $('#txtTiempoAsigGreen2').val();
                    var txtTiempoAsigYellow1 = $('#txtTiempoAsigYellow1').val();
                    var txtTiempoAsigYellow2 = $('#txtTiempoAsigYellow2').val();
                    var txtTiempoAsigRed1 = $('#txtTiempoAsigRed1').val();

                    var txtTiempoRealizadoGreen1 = $('#txtTiempoRealizadoGreen1').val();
                    var txtTiempoRealizadoGreen2 = $('#txtTiempoRealizadoGreen2').val();
                    var txtTiempoRealizadoYellow1 = $('#txtTiempoRealizadoYellow1').val();
                    var txtTiempoRealizadoYellow2 = $('#txtTiempoRealizadoYellow2').val();
                    var txtTiempoRealizadoRed1 = $('#txtTiempoRealizadoRed1').val();

                    var txtTiempoPhAnomaliasGreen1 = $('#txtTiempoPhAnomaliasGreen1').val();
                    var txtTiempoPhAnomaliasGreen2 = $('#txtTiempoPhAnomaliasGreen2').val();
                    var txtTiempoPhAnomaliasYellow1 = $('#txtTiempoPhAnomaliasYellow1').val();
                    var txtTiempoPhAnomaliasYellow2 = $('#txtTiempoPhAnomaliasYellow2').val();
                    var txtTiempoPhAnomaliasRed1 = $('#txtTiempoPhAnomaliasRed1').val();

                    var txtTiempoAsigInstallGreen1 = $('#txtTiempoAsigInstallGreen1').val();
                    var txtTiempoAsigInstalGreen2 = $('#txtTiempoAsigInstalGreen2').val();
                    var txtTiempoAsigInstalYellow1 = $('#txtTiempoAsigInstalYellow1').val();
                    var txtTiempoAsigInstalYellow2 = $('#txtTiempoAsigInstalYellow2').val();
                    var txtTiempoAsigInstalRed1 = $('#txtTiempoAsigInstalRed1').val();

                    var txtTiempoRealizInstalGreen1 = $('#txtTiempoRealizInstalGreen1').val();
                    var txtTiempoRealizInstalGreen2 = $('#txtTiempoRealizInstalGreen2').val();
                    var txtTiempoRealizInstalYellow1 = $('#txtTiempoRealizInstalYellow1').val();
                    var txtTiempoRealizInstalYellow2 = $('#txtTiempoRealizInstalYellow2').val();
                    var txtTiempoRealizInstalRed1 = $('#txtTiempoRealizInstalRed1').val();

                    var txtTiempoInstalAnomGreen1 = $('#txtTiempoInstalAnomGreen1').val();
                    var txtTiempoInstalAnomGreen2 = $('#txtTiempoInstalAnomGreen2').val();
                    var txtTiempoInstalAnomYellow1 = $('#txtTiempoInstalAnomYellow1').val();
                    var txtTiempoInstalAnomYellow2 = $('#txtTiempoInstalAnomYellow2').val();
                    var txtTiempoInstalAnomRed1 = $('#txtTiempoInstalAnomRed1').val();

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
                            console.log(data);
                        }
                    });

                    $('#reportConfiguration').modal('hide');
                });

                $(document).on('click', '#btn_chart', function () {
                    console.log("Chart Clicked");
                    /*concentratedArray = getConcentratedArray(function() {
                        console.log("Finished");
                    });*/

                    /*var revisionDeVentas = [];
                    var revisionFinanciera = [];
                    var rechazadoVentas = [];
                    var rechazadoFinanciera = [];

                    var PHporAsignar = [];
                    var PHenProceso = [];
                    var PHpendiente = [];
                    var PHcompleto = [];

                    var InstallporAsignar = [];
                    var InstallenProceso = [];
                    var Installpendiente = [];
                    var Installcompleto = [];*/


                    var revisionDeVentas = 0;
                    var revisionFinanciera = 0;
                    var rechazadoVentas = 0;
                    var rechazadoFinanciera = 0;
                    var segundaCaptura = 0;
                    var PHporAsignar = 0;
                    var PHenProceso = 0;
                    var PHpendiente = 0;
                    var PHcompleto = 0;
                    var InstallporAsignar = 0;
                    var InstallenProceso = 0;
                    var Installpendiente = 0;
                    var Installcompleto = 0;
                    var dateStart = "";
                    var dateFinish = "";

                    //var concentrated = [];
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/concentratedStatus.php",
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            /*for (var elem in data) {
                             console.log(data[elem]);
                             concentrated.push(data[elem]);
                             }*/

                            $.each( data, function( i, itemData ) {
                                console.log("Whitin $.each");
                                console.log(i);
                                console.log(itemData);
                                //concentrated.push(itemData);
                                revisionDeVentas += parseInt(itemData.RevVentas);
                                revisionFinanciera += parseInt(itemData.RevFinanciera);
                                rechazadoVentas += parseInt(itemData.RechVenta);
                                rechazadoFinanciera += parseInt(itemData.RechFinanciera);
                                segundaCaptura += parseInt(itemData.SegCaptura);
                                PHporAsignar += parseInt(itemData.PHPorAsignar);
                                PHenProceso += parseInt(itemData.PHEnProceso);
                                PHpendiente += parseInt(itemData.PHPendiente);
                                PHcompleto += parseInt(itemData.PHCompleto);
                                InstallporAsignar += parseInt(itemData.IPorAsignar);
                                InstallenProceso += parseInt(itemData.IEnProceso);
                                Installpendiente += parseInt(itemData.IPendiente);
                                Installcompleto += parseInt(itemData.ICompletado);
                                dateStart += parseInt(itemData.FechIni);
                                dateFinish += parseInt(itemData.FechFi);

                                console.log(PHporAsignar);
                                console.log(PHenProceso);
                                console.log(PHpendiente);
                                console.log(PHcompleto);

                                console.log(InstallporAsignar);
                                console.log(InstallenProceso);
                                console.log(Installpendiente);
                                console.log(Installcompleto);

                                $(function () {
                                    $('#container').highcharts({
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'Concentrado Estatus'
                                        },
                                        xAxis: {
                                            //categories: ['Venta', 'PH', 'Instalación'],
                                            //categories: ['Revisión de ventas', 'Revisión financiera', 'Rechazado ventas', 'Rechazado Financiera', 'Segunda Captura'],
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
                                        series: [{
                                            name: 'Revisión de ventas',
                                            data: [revisionDeVentas]
                                        }, {
                                            name: 'Revisión financiera',
                                            data: [revisionFinanciera]
                                        }, {
                                            name: 'Rechazado ventas',
                                            data: [rechazadoVentas]
                                        }, {
                                            name: 'Rechazado Financiera',
                                            data: [rechazadoFinanciera]
                                        }, {
                                            name: 'Segunda Captura',
                                            data: [segundaCaptura]
                                        }, {
                                            name: 'Por asignar',
                                            data: [PHporAsignar]
                                        }, {
                                            name: 'En proceso',
                                            data: [PHenProceso]
                                        }, {
                                            name: 'Pendiente',
                                            data: [PHpendiente]
                                        }, {
                                            name: 'Completo',
                                            data: [PHcompleto]
                                        }, {
                                            name: 'Por asignar',
                                            data: [InstallporAsignar]
                                        }, {
                                            name: 'En proceso',
                                            data: [InstallenProceso]
                                        }, {
                                            name: 'Pendiente',
                                            data: [Installpendiente]
                                        }, {
                                            name: 'Completo',
                                            data: [Installcompleto]
                                        }]
                                    });
                                });

                                /*concentrated[i].id = itemData.Id;
                                 concentrated[i].agencia = itemData.Agencia;
                                 concentrated[i].revVentas = itemData.RevVentas;
                                 concentrated[i].revFinanciera = itemData.RevFinanciera;
                                 concentrated[i].rechVenta = itemData.RechVenta;
                                 concentrated[i].rechFinanciera = itemData.RechFinanciera;
                                 concentrated[i].segCaptura = itemData.SegCaptura;
                                 concentrated[i].pHPorAsignar = itemData.PHPorAsignar;
                                 concentrated[i].pHEnProceso = itemData.PHEnProceso;
                                 concentrated[i].pHPendiente = itemData.PHPendiente;
                                 concentrated[i].pHCompleto = itemData.PHCompleto;
                                 concentrated[i].instPorAsignar = itemData.IPorAsignar;
                                 concentrated[i].instEnProceso = itemData.IEnProceso;
                                 concentrated[i].instPendiente = itemData.IPendiente;
                                 concentrated[i].instCompletado = itemData.ICompletado;
                                 concentrated[i].fechIni = itemData.FechIni;
                                 concentrated[i].fechFin = itemData.FechFi;*/
                            });
                            //callback();
                        }
                    });
                    //return concentrated;

                    /*var revisionDeVentas = [];
                    var revisionFinanciera = [];
                    var rechazadoVentas = [];
                    var rechazadoFinanciera = [];

                    var PHporAsignar = [];
                    var PHenProceso = [];
                    var PHpendiente = [];
                    var PHcompleto = [];

                    var InstallporAsignar = [];
                    var InstallenProceso = [];
                    var Installpendiente = [];
                    var Installcompleto = [];

                    console.log("concentratedArray");
                    console.log(concentratedArray);

                    for (var element in concentratedArray) {
                        console.log("Whitin concentratedArray");
                        console.log(concentratedArray[element]);
                        //concentratedArray[element].;
                    }*/
                    $('#modalCharts').modal('show');
                });

                function loadRejectedReasonForm() {
                    console.log("desplegar los datos de la tabla de rechazados");
                    var limit = $('#txtQty').val();
                    var dateFrom = $('#dateFrom').val();
                    var dateTo = $('#dateTo').val();
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/rejectedReasonsTwo.php",
                        dataType: "JSON",
                        success: function (data) {
                            for (var rejected in data) {
                                $('#bodyDataRejectedReason').append('<tr><td>' + data[rejected].Contrato + '</td>' + '<td>' + data[rejected].Agencia + '</td>' + '<td>' + data[rejected].RechazadoPorAgencia + '</td>' + '<td>' + data[rejected].Agente + '</td>' + '<td>' + data[rejected].Motivo + '</td></tr>');
                            }


                        }
                    });
                }

                function loadSell() {
                    console.log("desplegar los datos de la tabla de ventas");
                    var limit = $('#txtQty').val();
                    var dateFrom = $('#dateFrom').val();
                    var dateTo = $('#dateTo').val();
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/sellsReport.php",
                        dataType: "JSON",
                        success: function (data) {
                            for (var sells in data) {
                                $('#sell').append('<tr><td>' + data[sells].Id + '</td>' + '<td>' + data[sells].Contrato + '</td>' + '<td>' + data[sells].Cliente + '</td>' + '<td>' + data[sells].Ph + '</td>' + '<td>' + data[sells].Venta + '</td>' + '<td>' + data[sells].Instalacion + '</td>' + '<td>' + data[sells].Municipio + '</td>' + '<td>' + data[sells].Colonia + '</td>' + '<td>' + date[sells].Calle + '</td>' + '<td>' + data[sells].Usuario + '</td>' + '<td>' + data[sells].Agencia + '</td></tr>');
                            }
                        }
                    });
                }

                function loadTimeSells() {
                    console.log("desplegar los datos de la tabla de tiempo venta");
                    var limit = $('#txtQty').val();
                    var dateFrom = $('#dateFrom').val();
                    var dateTo = $('#dateTo').val();
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/sellsTimesReport.php",
                        dataType: "JSON",
                        success: function (data) {
                            for (var timeSells in data) {
                                $('#bodyDataSellTimes').append('<tr><td>' + data[timeSells].Contrato + '</td>' + '<td>' + data[timeSells].Cliente + '</td>' + '<td>' + data[timeSells].TRevFinanciera + '</td>' + '<td>' + data[timeSells].TRevRechazada + '</td>' + '<td>' + data[timeSells].TPSCap + '</td>' + '<td>' + data[timeSells].TAPH + '</td>' + '<td>' + data[timeSells].TPHA + '</td>' + '<td>' + data[timeSells].TPHAN + '<td>' + data[timeSells].TAINS);
                            }
                        }
                    });
                }

                function loadConcentratedStatus() {
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/concentratedStatus.php",
                        dataType: "JSON",
                        success: function (data) {
                            for (var concentrated in data) {
                                $('#bodyData').append(
                                    '<tr>'
                                    + '<td style="background-color: #ffffff;">' + data[concentrated].Id + '</td>'
                                    + '<td style="background-color: #ffffff;">' + data[concentrated].Agencia + '</td>'
                                    + '<td style="background-color: #d9d9d9;">' + data[concentrated].RevVentas + '</td>'
                                    + '<td style="background-color: #d9d9d9;">' + data[concentrated].RechVenta + '</td>'
                                    + '<td style="background-color: #d9d9d9;">' + data[concentrated].RechFinanciera + '</td>'
                                    + '<td style="background-color: #d9d9d9;">' + data[concentrated].RechFinanciera + '</td>'
                                    + '<td style="background-color: #d9d9d9;">' + data[concentrated].SegCaptura + '</td>'
                                    + '<td style="background-color: #bfbfbf;">' + data[concentrated].PHPorAsignar + '</td>'
                                    + '<td style="background-color: #bfbfbf;">' + data[concentrated].PHEnProceso + '</td>'
                                    + '<td style="background-color: #bfbfbf;">' + data[concentrated].PHPendiente + '</td>'
                                    + '<td style="background-color: #bfbfbf;">' + data[concentrated].PHCompleto + '</td>'
                                    + '<td style="background-color: #808080;">' + data[concentrated].IPorAsignar + '</td>'
                                    + '<td style="background-color: #808080;">' + data[concentrated].IEnProceso + '</td>'
                                    + '<td style="background-color: #808080;">' + data[concentrated].IPendiente + '</td>'
                                    + '<td style="background-color: #808080;">' + data[concentrated].ICompletado + '</td>'
                                    + '<td style="background-color: #ffffff;">' + data[concentrated].FechIni + '</td>'
                                    + '<td style="background-color: #ffffff;">' + data[concentrated].FechFi + '</td>'
                                    + '<tr>');
                            }
                        }
                    });
                }

                function getConcentratedArray(callback) {
                    var concentrated = [];
                    $.ajax({
                        method: "GET",
                        url: "dataLayer/callsWeb/concentratedStatus.php",
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            /*for (var elem in data) {
                                console.log(data[elem]);
                                concentrated.push(data[elem]);
                            }*/

                            $.each( data, function( i, itemData ) {
                                console.log("Whitin $.each");
                                console.log(i);
                                console.log(itemData);
                                concentrated.push(itemData);
                                /*concentrated[i].id = itemData.Id;
                                concentrated[i].agencia = itemData.Agencia;
                                concentrated[i].revVentas = itemData.RevVentas;
                                concentrated[i].revFinanciera = itemData.RevFinanciera;
                                concentrated[i].rechVenta = itemData.RechVenta;
                                concentrated[i].rechFinanciera = itemData.RechFinanciera;
                                concentrated[i].segCaptura = itemData.SegCaptura;
                                concentrated[i].pHPorAsignar = itemData.PHPorAsignar;
                                concentrated[i].pHEnProceso = itemData.PHEnProceso;
                                concentrated[i].pHPendiente = itemData.PHPendiente;
                                concentrated[i].pHCompleto = itemData.PHCompleto;
                                concentrated[i].instPorAsignar = itemData.IPorAsignar;
                                concentrated[i].instEnProceso = itemData.IEnProceso;
                                concentrated[i].instPendiente = itemData.IPendiente;
                                concentrated[i].instCompletado = itemData.ICompletado;
                                concentrated[i].fechIni = itemData.FechIni;
                                concentrated[i].fechFin = itemData.FechFi;*/
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
            </script>
