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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="assets/js/clases/callejeroSolicitarDir.js"></script>

<?php if($_SESSION['typeAgency']== 'Comercializadora' || $_SESSION['typeAgency'] == 'Instalacion' || $_SESSION['typeAgency'] == 'Instalacion y Comercializadora'): ?>
    <button type="button" id="btnSearchDir" class="btn btn-success btn-lg"><i class="fa fa-search"></i>Consultar Direcciones</button>
<?php endif; ?>
<!-- Codigo -->
<div class="row">
    <div class="col-lg-12">
        <section class="panel" style="overflow: scroll">
            <header class="panel-heading col-lg-9">
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
                                <li class="col-md-3 active"><a href="#liberacionDirecciones" class="liberacionDirecciones" data-toggle="tab">Liberar direcciones</a></li>
                                <?php endif; ?>
                                
                                <?php
                                    if (($_SESSION["typeAgency"] == "Comercializadora" || 
                                         $_SESSION["typeAgency"] == "Instalacion y Comercializadora" ||
                                         $_SESSION["typeAgency"] == "Instalacion") &&
                                        (strtoupper($_SESSION["nickname"]) != 'AYOPSA' && strtoupper($_SESSION["nickname"]) != 'SUPERADMIN')) {
                                        echo '<li class="col-md-3 active" ><a href="#solicitarDireccion" class="solicitarDireccion" data-toggle="tab">Solicitar Direccion</a></li>';
                                    }
                                ?>
                            </ul>
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
                                    </form>
                                </div>
                            </header>
                            <p></p>

                            <div class="tab-content">
                                <?php if(($_SESSION["typeAgency"] == "Comercializadora" || 
                                         $_SESSION["typeAgency"] == "Instalacion y Comercializadora" ||
                                         $_SESSION["typeAgency"] == "Instalacion") &&
                                        (strtoupper($_SESSION["nickname"]) != 'AYOPSA' && strtoupper($_SESSION["nickname"]) != 'SUPERADMIN')): ?>
                                    <div class="tab-pane fade active in" id="liberacionDirecciones">
                                        <div class='row'>
                                            <section class='panel'>
                                                <table id='liberacionDireccionesTable' class='table table-striped custom-table table-hover dataTable no-footer'>
                                                    <thead>
                                                        <tr>
                                                            <td style='background-color: #f0f0f0;'>Municipio</td>
                                                            <td style='background-color: #f0f0f0;'>Colonia</td>
                                                            <td style='background-color: #f0f0f0;'>Direccion</td>
                                                            <td style='background-color: #f0f0f0;'>Estatus</td>
                                                            <td style='background-color: #f7f7f7;'>Solicitar</td>
                                                        </tr>
                                                    </thead>
                                                <tbody id='bodyData'>
                                                </tbody>
                                                </table>
                                            </section>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if($_SESSION["id"] == 1): ?>
                                <div class="tab-pane fade active in" id="solicitarDireccion">
                                    <div class='row'>
                                        <section class='panel'>
                                            <table id='liberacionDirecciones' class='table table-striped custom-table table-hover dataTable no-footer'>
                                                <thead>
                                                    <tr>
                                                        <td style='background-color: #f7f7f7;'>Direcci&oacute;n</td>
                                                        <td style='background-color: #f0f0f0;'>Estatus</td>
                                                        <td style='background-color: #f0f0f0;'>Agencia</td>
                                                        <td style='background-color: #f0f0f0;'>Liberacion</td> 
                                                        <td style='background-color: #f0f0f0;'>Comentarios</td>
                                                        <td style='background-color: #f0f0f0;'>Fec. Sol</td>
                                                        <td style='background-color: #f0f0f0;'>Fec. Fin</td>
                                                        <td style='background-color: #f0f0f0;'>Tiempo Liberacion</td>
                                                        <td style='background-color: #f0f0f0;'>Liberar</td>
                                                    </tr>
                                                </thead>
                                            <tbody id='bodyDataDirecciones'>
                                            </tbody>
                                            </table>
                                        </section>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade disable-scroll" id="modalform" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" id="btnModalFormClose" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="titleCompany">Consulta de direcciones</h4>
                        </div>
                        <div class="modal-body">
                            <form class="cmxform" role="form" id="formNewSale">
                                <div><input type="text" id="txtid" hidden></div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <!--<Add company according the user>-->
                                        <label>Municipio</label>
                                        <!--<input type="text" id="txMun" name="txMun" class="form-control" value="">-->
                                        <select id="txMun" name="txMun" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6" id="colonia">
                                        <!--<Add company according the user>-->
                                        <label>Colonia</label>
                                        <select id="txtCol" name="txtCol" class="form-control"></select>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>Calle</label>
                                        <select id="street" name="street" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label>N&uacute;mero</label>
                                        <select id="txtNumber" name="txtNumber" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <br/>
                                    <div class="col-xs-7">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button type="button" class="btn btn-danger" id="btnCancelSearch">CANCELAR</button>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <button type="button" class="btn btn-success" id="btnCreateSearch">CONSULTAR</button>
                                    </div>
                                </div>
                            </form>
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
            <script type="text/javascript" src="assets/js/pace.js"></script>
            <!--data table init-->
            <script src="assets/js/data-table-init.js"></script>
            <link rel="stylesheet" href="assets/css/dcalendar.picker.min.css"/>
            <link rel="stylesheet" href="assets/css/pace.css"/>
            <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
            <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
            <script type="text/javascript">
                var concentratedArray = [];
                $(document).ready(function () {
                    paceOptions = {
                        document: false, // disabled
                        eventLag: false, // disabled
                        restartOnRequestAfter: false,
                        elements: {
                            selectors: ['.panel']
                        }
                    };
                    var string_nickname = $("#nicknameZone").html();
                    string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
                    string_nickname = string_nickname.trim();
                    $("#titleHeader").html("Liberacion de Direcciones");
                    $("#subtitle-header").html("Detalle");
                    $("#tbHeaders").append();
                    $('#dateFrom').dcalendarpicker({format: "yyyy-mm-dd"});
                    $('#dateTo').dcalendarpicker({format: "yyyy-mm-dd"});
                    $('.nav-tabs').tab();
                    var lengthAncore = $("a.liberacionDirecciones").length;
                    if (lengthAncore === 1) {
                        $('#bodyData').html('');
                        loadSolicitudes();
                    }
                });

                $(document).on('click', '.liberacionDirecciones', function () {
                    $('#dateFrom').val('');
                    $('#dateTo').val('');
                    $('#bodyData').html('');
                    loadSolicitudes();
                });

                $(document).on('click', '.liberacionDirecciones', function () {
                    //loadConcentratedStatus();
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

                $('#btnSearchDir').click(function () {
                    $("#txtStreet").val('');
                    $("#txtRoads").val('');
                    $("#txtAddressNew").val('');
                    $("#txtNumber").val('');
                    $("#txtLevel").val('');
                    $('#txMun').select2({
                        placeholder: "Seleccionar una Colonia",
                        allowClear: true,
                    });
                    $('#txtRoads').select2({
                        placeholder: "Seleccionar una Entre Calle",
                        allowClear: true,
                    });
                    $('#txtNumber').select2({
                        placeholder: "Seleccionar un Numero",
                        allowClear: true,
                    });
                    $('#street').select2({
                        placeholder: "Seleccionar una Calle",
                        allowClear: true,
                    });
                    $('#txtCol').select2({
                        placeholder: "Seleccionar una Colonia",
                        allowClear: true,
                    });
                    //$('#txMun').select2();
                    loadCities();
                    //$('#modalform').modal('show');
                    $('#modalform').modal({backdrop: 'static', keyboard: false}, 'show'); 
                });

                $(document).on('change', '#txtCol', function () {
                    var city = $("#txMun").val();
                    var colonia = $("#txtCol").val();
                    var streetLoaded = $("#txtStreet").val();
                    console.log('colonia', colonia);
                    console.log('city', city);
                    $("#txtRoads").html('');
                    $("#txtNumber").html('');
                    $("#txtCP").html('');
                    if (colonia > 0) {
                        loadStreets(city, colonia);
                    } else {
                        $("#btnCreateSell").notify("No Hay Municipio/Colonia Seleccionados", "warn");
                        $("#street").html('');
                        $("#txtRoads").html('');
                        $("#txtNumber").html('');
                    }
                });

                $(document).on('change', '#txMun', function () {
                    var municipio = $('#txMun').val();
                    if (!_.isEmpty(municipio)) {
                        loadColonias(municipio);
                    }else{
                        $("#btnCreateSell").notify("No Hay Municipio/Colonia Seleccionados", "warn");
                        $("#txtCol").html('');
                        $("#street").html('');
                        $("#txtRoads").html('');
                        $("#txtNumber").html('');
                    }
                });

                $(document).on('change', '#street', function () {
                    var streetSelected = $("#street").val();
                    var roads = [];
                    var numbers = [];
                    var idDireccion = [];
                    $("#txtNumber").html('');
                    if (_.isEmpty(streetSelected)) {
                        $("#txtNumber").html('');
                    }else{
                        if (!_.isUndefined(directions.length)) {
                            numbers = getNumeros(streetSelected, directions);
                            idDireccion = getIDireccion(streetSelected, directions);
                            numbers = deleteDuplicates(numbers);
                            $('#txtNumber').append("<option value=''></option>");
                            for (var number in numbers) {
                                if (numbers[number] !== null && numbers[number] !== "") {
                                    _.each(directions, function (rowD, idx) {
                                        if (rowD.numero_exterior === numbers[number]) {
                                            $('#txtNumber').append('<option value="' + rowD.id_direccion + '">' + rowD.numero_exterior + '</option>');
                                        }
                                    })
                                }
                            }
                            $('#txtNumber').select2({
                                placeholder: "Seleccionar un Numero",
                                allowClear: true,
                            });
                        }else{
                            if (!_.isEmpty(directions.numero_exterior)) {
                                $('#txtNumber').html('');
                                $('#txtNumber').append("<option value=''></option>");
                                $('#txtNumber').append('<option value="' + directions.id_direccion + '">' + directions.numero_exterior + '</option>');
                                $('#txtNumber').select2({
                                    placeholder: "Seleccionar un Numero",
                                    allowClear: true,
                                });
                            }else{
                                $('#txtNumber').html('');
                                $('#txtNumber').append("<option value=''></option>");
                            }
                        }
                    }
                });

                function deleteDuplicates(arrayToClean) {
                    var uniqueVals = [];
                    $.each(arrayToClean, function (i, el) {
                        if ($.inArray(el, uniqueVals) === -1) uniqueVals.push(el);
                    });
                    return uniqueVals;
                }

                $('#btnCancelSearch').click(function () {
                    $('#txMun').html('');
                    $("#txtCol").html('');
                    $("#txtNumber").html('');
                    $("#street").html('');
                    $('#modalform').modal('hide');
                });

                $('#btnCreateSearch:not(:disabled)').click(function (e) {
                    e.preventDefault();
                    $('#btnCreateSearch').prop('disabled', true);
                    var street = $('#street option:selected').text();
                    var txtNumber = $('#txtNumber option:selected').text();
                    var txMun = $('#txMun option:selected').text();
                    var txtCol = $('#txtCol option:selected').text();
                    if (_.isEmpty(street) &&
                        _.isEmpty(txtNumber) &&
                        _.isEmpty(txMun) &&
                        _.isEmpty(txtCol)) {
                        //enviamos una notificacion de error
                        $("#btnCreateSearch").notify("Municipio y Colonia no seleccionados..", "error");
                        return false;
                    }else if (_.isEmpty(street) &&
                              _.isEmpty(txtNumber) &&
                              _.isEmpty(txtCol)) {
                        //enviamos una notificacion de error
                        $("#btnCreateSearch").notify("Colonia no seleccionada..", "error");
                        return false;
                    }else{
                        var arrStatusDir = [];
                        $('#bodyData').html('');
                        $("#liberacionDireccionesTable").DataTable().destroy();
                        if (_.isEmpty(street) && _.isEmpty(txtNumber)) {
                            $("#bodyData").html("");
                            $("#liberacionDireccionesTable").DataTable().destroy();
                            if (!_.isUndefined(directions.length)) {
                                //proceso con una direccion con todas las direcciones
                                var arrObjDatos=[], myFailure, arrExcel=[], payload={}, reports=[];
                                //Pace.on('start', function(e){
                                Pace.track(function(){
                                    $.ajax({
                                        method: "POST",
                                        url: "dataLayer/callsWeb/siscomStatusDir.php",
                                        data: {
                                            datosDir: directions,
                                            colonia: txtCol,
                                            municipio: txMun,
                                            todos:1,
                                        },
                                        dataType: "JSON",
                                        success: function (data) {
                                            console.log('data', data);
                                            mySuccessFunction(data)
                                        }
                                    });
                                });
                            }else{
                                Pace.track(function(){
                                    $.ajax({
                                        method: "POST",
                                        url: "dataLayer/callsWeb/siscomStatusDir.php",
                                        data: {
                                            datosDir: directions,
                                            todos:0,
                                        },
                                        dataType: "JSON",
                                        success: function (data) {
                                            console.log('data', data);
                                            mySuccessFunction(data)
                                        }
                                    });
                                });
                            }
                        }else if (!_.isEmpty(street) && !_.isEmpty(txtNumber)) {
                            if (!_.isUndefined(directions.length)) {
                                _.each(directions, function (rowD) {
                                    if (rowD.calle === street && parseInt(rowD.numero_exterior) === parseInt(txtNumber)) {
                                        Pace.track(function(){
                                            $.ajax({
                                                method: "POST",
                                                url: "dataLayer/callsWeb/siscomStatusDir.php",
                                                data: {
                                                    datosDir: rowD,
                                                    todos:0,
                                                },
                                                dataType: "JSON",
                                                success: function (data) {
                                                    console.log('data', data);
                                                    mySuccessFunction(data)
                                                }
                                            });
                                        });
                                    }
                                });
                            }else{
                                Pace.track(function(){
                                    $.ajax({
                                        method: "POST",
                                        url: "dataLayer/callsWeb/siscomStatusDir.php",
                                        data: {
                                            datosDir: directions,
                                            todos:0,
                                        },
                                        dataType: "JSON",
                                        success: function (data) {
                                            console.log('data', data);
                                            mySuccessFunction(data)
                                        }
                                    });
                                });
                            }
                            $('#btnCreateSearch').prop('disabled', false);
                        }else if (!_.isEmpty(street) && _.isEmpty(txtNumber)) {
                            if (!_.isUndefined(directions.length)) {
                                var directionsAlt = [];
                                _.each(directions, function (rowD) {
                                    if (rowD.calle === street) {
                                        console.log('directions2', rowD);
                                        directionsAlt.push(rowD);
                                    }
                                });
                                Pace.track(function(){
                                    $.ajax({
                                        method: "POST",
                                        url: "dataLayer/callsWeb/siscomStatusDir.php",
                                        data: {
                                            datosDir: directionsAlt,
                                            colonia: txtCol,
                                            municipio: txMun,
                                            todos:1,
                                        },
                                        dataType: "JSON",
                                        success: function (data) {
                                            console.log('data', data);
                                            mySuccessFunction(data)
                                        }
                                    });
                                });
                            }else{
                                Pace.track(function(){
                                    $.ajax({
                                        method: "POST",
                                        url: "dataLayer/callsWeb/siscomStatusDir.php",
                                        data: {
                                            datosDir: directions,
                                            todos:0,
                                        },
                                        dataType: "JSON",
                                        success: function (data) {
                                            console.log('data', data);
                                            mySuccessFunction(data)
                                        }
                                    });
                                });
                            }
                            $('#btnCreateSearch').prop('disabled', false);
                        }
                    }
                });
                
                function mySuccessFunction(res){
                    console.log('res', res);
                    var htmlAppend = "";
                    var calle = "",entre_calles = "",id_direccion = "",numero_exterior = "",statusDireccion = "",direccion = "";
                    var txtCol = $('#txtCol option:selected').text();
                    var txMun = $('#txMun option:selected').text();
                    _.each(res, function (rowStDir, idx) {
                        console.log('rowStDir', rowStDir);
                        //if (idx === 0) {
                            calle = rowStDir.calle;
                            entre_calles = rowStDir.entre_calles;
                            id_direccion = rowStDir.id_direccion;
                            numero_exterior = rowStDir.numero_exterior;
                            statusDireccion = rowStDir.statusDireccion;
                            direccionFormato = '<address>';
                                direccionFormato += '<strong>'+calle+'</strong><br>';
                                direccionFormato += 'Num .- '+numero_exterior+'<br>';
                                direccionFormato += 'Entre Calles .- '+entre_calles+'';
                            direccionFormato += '</address>';
                            htmlAppend += '<tr data-id="'+id_direccion+'">';
                            htmlAppend += '<td class="mun" data-mun="'+txMun+'">'+txMun+'</td>';
                            htmlAppend += '<td class="col" data-col="'+txtCol+'">'+txtCol+'</td>';
                            htmlAppend += '<td>'+direccionFormato+'</td>';
                            htmlAppend += '<td data-status="'+statusDireccion+'" class="statusdir">'+statusDireccion+'</td>';
                            if (statusDireccion !== "El contrato está vigente" && statusDireccion !== "El contrato esta en Tramite") {
                                htmlAppend += '<td>';
                                    htmlAppend  += '<div class="idSolDir" data-id="'+id_direccion+'">';
                                        htmlAppend += '<button class="btn btn-default solicitarDireccion" type="button" data-id="'+id_direccion+'">';
                                        htmlAppend += '<i class="fa fa-paper-plane" aria-hidden="true">&nbsp;</i>Solicitar Direccion';
                                        htmlAppend += '</button>';
                                    htmlAppend += '</div>';
                                htmlAppend += '</td>';
                            }else{
                                htmlAppend += '<td></td>';
                            }
                            htmlAppend += '</tr>';
                        //}
                    });
                    //Pace.stop();
                    $('#bodyData').html('');
                    $("#liberacionDireccionesTable").DataTable().destroy();
                    $("#bodyData").append(htmlAppend);
                    $("#liberacionDireccionesTable").DataTable({
                        "columnDefs": [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
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
                    $('#btnCreateSearch').prop('disabled', false);
                    $('#txMun').html('');
                    $("#txtCol").html('');
                    $("#txtNumber").html('');
                    $("#street").html('');
                    $('#modalform').modal('hide');
                }

                $('#btnModalFormClose').click(function () {
                    $('#txMun').html('');
                    $("#txtCol").html('');
                    $("#txtNumber").html('');
                    $("#street").html('');
                    $('#modalform').modal('hide');
                });

                $(document).on('click', '.liberarDireccion:not(:disabled)', function (e) {
                    e.preventDefault();
                    //$(this).prop('disabled', true);
                    var button =  $(this);
                    var idSolicitud = $(this).parents("tr").attr('data-id');
                    var comentarios = $(this).parents("tr").find("textarea").val();
                    var fechaSol = $(this).parents("tr").find("td.fechaSol").attr("data-fecha");
                    var resDuration = getTotalDuration(fechaSol);
                    var now = moment().format("YYYY-MM-DDTHH:mm:ss");
                    var duracion = "00:00:00";
                    if (_.has(resDuration, '_data')) {
                        duracion = resDuration._data.hours+':'+resDuration._data.minutes+':'+resDuration._data.seconds;
                    }
                    Pace.track(function(){
                        $.ajax({
                            method: "POST",
                            url: "dataLayer/callsWeb/liberacionDeDirecciones.php",
                            data: {
                                idSolicitud : idSolicitud,
                                comentarios : comentarios,
                                fechaSol : now,
                                duracion : duracion,
                                tipoSentencia : "update"
                            },
                            dataType: "JSON",
                            success: function (data) {
                                var existeStatus = _.has(data, 'code');
                                if (existeStatus) {
                                    if (parseInt(data.code) === 200) {
                                        $.notify(data.result, "success");
                                        location.reload()
                                    }else{
                                        $.notify(data.result, "error");
                                        button.prop('disabled', false);
                                    }
                                }else{
                                    $.notify("Ocurrio un problema, comunicarse con el administrador..", "error");
                                    button.prop('disabled', false);
                                }
                            }
                        });
                    });
                });

                $(document).on('click', '.solicitarDireccion:not(:disabled)', function (e) {
                    e.preventDefault();
                    $(this).prop('disabled', true);
                    var button =  $(this);
                    var isUndefined = _.isUndefined(directions);
                    var idDireccion = $(this).attr('data-id');
                    var statusDireccion = $(this).parents("tr").find("td.statusdir").attr("data-status");
                    var mun = $(this).parents("tr").find("td.mun").attr("data-mun");
                    var col = $(this).parents("tr").find("td.col").attr("data-col");
                    var objDir = {};
                    if (!isUndefined) {
                        _.each(directions, function (rowD, idx) {
                            if (parseInt(rowD.id_direccion) === parseInt(idDireccion)) {
                                Pace.track(function(){
                                    $.ajax({
                                        method: "POST",
                                        url: "dataLayer/callsWeb/liberacionDeDirecciones.php",
                                        data: {
                                            agencia : localStorage.getItem("id"),
                                            calle : rowD.calle,
                                            entre_calles : rowD.entre_calles,
                                            id_direccion : rowD.id_direccion,
                                            numero_exterior : rowD.numero_exterior,
                                            mun : mun,
                                            col : col,
                                            status : statusDireccion
                                        },
                                        dataType: "JSON",
                                        success: function (data) {
                                            var existeStatus = _.has(data, 'code');
                                            if (existeStatus) {
                                                if (parseInt(data.code) === 200) {
                                                    $.notify(data.result, "success");
                                                    button.parents("tr").remove();
                                                }else{
                                                    $.notify(data.result, "error");
                                                    button.prop('disabled', false);
                                                }
                                            }else{
                                                $.notify("Ocurrio un problema, comunicarse con el administrador..", "error");
                                                button.prop('disabled', false);
                                            }
                                        }
                                    });
                                });
                            }
                        });
                    }else{
                        Pace.track(function(){
                            $.ajax({
                                method: "POST",
                                url: "dataLayer/callsWeb/liberacionDeDirecciones.php",
                                data: {
                                    agencia : localStorage.getItem("id"),
                                    calle : directions.calle,
                                    entre_calles : directions.entre_calles,
                                    id_direccion : directions.id_direccion,
                                    numero_exterior : directions.numero_exterior,
                                    mun : mun,
                                    col : col,
                                    status : statusDireccion
                                },
                                dataType: "JSON",
                                success: function (data) {
                                    var existeStatus = _.has(data, 'code');
                                    if (existeStatus) {
                                        if (parseInt(data.code) === 200) {
                                            $.notify(data.result, "success");
                                            button.parents("tr").remove();
                                        }else{
                                            $.notify(data.result, "error");
                                            button.prop('disabled', false);
                                        }
                                    }else{
                                        $.notify("Ocurrio un problema, comunicarse con el administrador..", "error");
                                        button.prop('disabled', false);
                                    }
                                }
                            });
                        });
                    }
                });


                function loadSolicitudes() {
                    $.ajax({
                        method: "GET",
                        //url: "dataLayer/callsWeb/sellsReport.php",
                        url: "dataLayer/callsWeb/obtenerSolicitudesLib.php",
                        dataType: "JSON",
                        data: {},
                        success: function (data) {
                            console.log('obtenerSolicitudesLib', data);
                            var existResponse = _.has(data, 'response');
                            if (existResponse) {
                                var htmlAppend = "", direccionFormato="";
                                var calle = "",entre_calles = "",id_direccion = "",numero_exterior = "",
                                    statusDireccion = "",direccion = "", colonia="", mun="", checkboxStatus = "", 
                                    comentarios="", fechaLib="", tiempoLib="";
                                _.each(data.response, function(sol, index) {
                                    //if (parseInt(sell.agreementNumber) === 34830) {
                                        calle = sol.calle;
                                        entre_calles = sol.entreCalles;
                                        id_direccion = sol.id_direccion;
                                        numero_exterior = sol.num;
                                        statusDireccion = sol.statusDireccion;
                                        colonia = sol.colonia;
                                        mun = sol.mun;
                                        fechaLib = sol.fechaLib;
                                        tiempoLib = sol.tiempoLib;
                                        comentarios=(sol.comentarios === '' || typeof(sol.comentarios) === 'undefined' || sol.comentarios === null) ? '' : sol.comentarios;
                                        fechaLib=(sol.fechaLib === '' || typeof(sol.fechaLib) === 'undefined' || sol.fechaLib === null) ? '' : sol.fechaLib;
                                        tiempoLib=(sol.tiempoLib === '' || typeof(sol.tiempoLib) === 'undefined' || sol.tiempoLib === null) ? '' : sol.tiempoLib;
                                        direccionFormato = '<address>';
                                            direccionFormato += '<strong>'+colonia+'</strong><br>';
                                            direccionFormato += 'Calle .- '+calle+'<br>';
                                            direccionFormato += 'Num .- '+numero_exterior+'<br>';
                                            direccionFormato += 'Entre Calles .- '+entre_calles+'<br>';
                                            direccionFormato += 'Municipio .- '+mun+'<br>';
                                        direccionFormato += '</address>';
                                        if (parseInt(sol.estatus) === 0) {
                                            checkboxStatus = '<span class="label label-danger">Direccion No Liberada</span>';
                                        }else if (parseInt(sol.estatus) === 1){
                                            checkboxStatus = '<span class="label label-success">Direccion Habilitada</span>';
                                        }
                                        htmlAppend += '<tr id="idSol'+sol.idsolicitud+'" data-id="'+sol.idsolicitud+'">';
                                        htmlAppend += '<td>' + direccionFormato + '</td>';
                                        htmlAppend += '<td>' + sol.estatusDir + '</td>';
                                        htmlAppend += '<td>' + sol.agencia + '</td>';
                                        htmlAppend += '<td>' + checkboxStatus + '</td>';
                                        if (_.isEmpty(comentarios) && parseInt(sol.estatus) === 0) {
                                            htmlAppend += '<td><textarea class="form-control" rows="3" placeholder="Escribe un comentario" class="comentLibDir"></textarea></td>';
                                        }else{
                                            htmlAppend += '<td>'+comentarios+'</td>';
                                        }
                                        htmlAppend += '<td class="fechaSol" data-fecha="'+sol.fechaSol+'">' + sol.fechaSol + '</td>';
                                        htmlAppend += '<td>'+fechaLib+'</td>';
                                        htmlAppend += '<td>'+tiempoLib+'</td>';
                                        if (parseInt(sol.estatus) === 0) {
                                            htmlAppend += '<td><button class="btn btn-default liberarDireccion" type="button">';
                                            htmlAppend += '<i class="fa fa-paper-plane" aria-hidden="true">&nbsp;</i>Liberar Direccion</button>';
                                            htmlAppend += '</td>';
                                        }else{
                                            htmlAppend += '<td></td>';
                                        }
                                        htmlAppend += '</tr>';
                                    //}
                                });
                            }
                            $("#liberacionDirecciones").DataTable().destroy();
                            $('#bodyDataDirecciones').html('');
                            $('#bodyDataDirecciones').empty();
                            $('#bodyDataDirecciones').append(htmlAppend);
                            $("#liberacionDirecciones").DataTable({
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

                function getTotalDuration(then) {
                    var totalTime = 0;
                    var now = moment(moment().format("YYYY-MM-DDTHH:mm:ss"));
                    var then = moment(then);
                    if ((!_.isEmpty(now) && !_.isNull(now)) && (!_.isEmpty(then) && !_.isNull(then))) {
                        totalTime = moment.duration(moment(now).diff(moment(then)));
                        //console.log('totalTime', totalTime);
                    }
                    return totalTime;
                }
            </script>
