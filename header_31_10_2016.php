<?php include_once "dataLayer/DAO.php";
include_once "dataLayer/libs/utils.php";

session_start();

if (!isset($_SESSION["nickname"]) || $_SESSION["nickname"] == NULL) {
    movePage(403, "login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="author" content="Migesa"/>
    <meta name="keyword" content="Mexicana de Gas"/>
    <meta name="description" content=""/>
    <link rel="shortcut icon" href="javascript:;" type="image/png">

    <title>Mexicana de Gas</title>
    <style>
        .centrar {
            width: 50%;
            width: 100px;
            height: 100px;
            margin: 0 auto;
        }

        .loader {
            width: 50%;
            margin: 0 auto;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid green;
            width: 100px;
            height: 100px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .dropdown-submenu{position:relative;}
        .dropdown-submenu>.dropdown-menu{top:0;left:-95%;max-width:180px;margin-top:-6px;margin-right:-1px;-webkit-border-radius:6px 6px 6px 6px;-moz-border-radius:6px 6px 6px 6px;border-radius:6px 6px 6px 6px;}
        .dropdown-submenu:hover>.dropdown-menu{display:block;}
        .dropdown-submenu>a:after{display:block;content:" ";float:left;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 5px 5px 0;border-right-color:#999;margin-top:5px;margin-right:10px;}
        .dropdown-submenu:hover>a:after{border-left-color:#ffffff;}
        .dropdown-submenu.pull-left{float:none;}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 6px 6px 6px;-moz-border-radius:6px 6px 6px 6px;border-radius:6px 6px 6px 6px;}
        .dropdown-menu-right {margin-left:0;}
    </style>

    <!--easy pie chart-->
    <link href="assets/js/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css"
          media="screen"/>


    <!--vector maps -->
    <link rel="stylesheet" href="assets/js/vector-map/jquery-jvectormap-1.1.1.css">

    <!--right slidebar-->
    <link href="assets/css/slidebars.css" rel="stylesheet">

    <!--switchery-->
    <link href="assets/js/switchery/switchery.min.css" rel="stylesheet" type="text/css" media="screen"/>

    <!--jquery-ui-->
    <link href="assets/js/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet"/>

    <!--iCheck-->
    <link href="assets/js/icheck/skins/all.css" rel="stylesheet">
    <link href="assets/css/owl.carousel.css" rel="stylesheet">

    <!--common style-->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <!--switch-->
    <link href="assets/css/bootstrap-switch.min.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

    <!--toastr-->
    <link href="assets/js/toastr-master/toastr.css" rel="stylesheet" type="text/css"/>
    <script src="assets/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js"></script>

    <script src="assets/js/bootstrap-switch.min.js"></script>

    <script src="assets/js/data-table/js/jquery.dataTables.min.js"></script>

</head>
<body class="sticky-header">
<section>
    <!-- sidebar left start-->
    <div class="sidebar-left">
        <!--responsive view logo start-->
        <div class="logo dark-logo-bg visible-xs-* visible-sm-*">
            <a href="index.php">
                <!--<img src="assets/img/logo-icon.png" alt="">-->
                <!--<i class="fa fa-maxcdn"></i>-->
                <span class="brand-name">Mexicana de Gas</span>
            </a>
        </div>
        <!--responsive view logo end-->

        <div class="sidebar-left-info">
            <!-- visible small devices start-->
            <div class=" search-field"></div>
            <!-- visible small devices end-->
            <!--sidebar nav start-->
            <ul class="nav nav-pills nav-stacked side-navigation">

                <li><h3 class="navigation-title">Administración</h3></li>
                <li id="geoAgency" class="limenu"><a href="agencies.php"><i class="fa fa-home"></i><span>Agencias</span></a>
                </li>
                <?php
                if ($_SESSION["rol"] == "Agency" && $_SESSION["typeAgency"] != "CallCenter") {
                    echo '<li id="gouser" class="limenu"><a href="empleadosDeAgencia.php"><i id="gouser" class="fa fa-user"></i><span>Empleados</span></a></li>';
                } else {
                    echo '<li id="gouser" class="limenu"><a href="admins.php"><i id="gouser" class="fa fa-user"></i><span>Usuarios</span></a></li>';
                } ?>

                <!--<li><h3 class="navigation-title">Formularios</h3></li>
                <li id="goform"   class="limenu"><a href="form.php"><i class="fa fa-file"></i> <span>Mis Formulario</span></a></li>-->

                <li><h3 class="navigation-title">Reportes</h3></li>

                <li id="gogeneral" class="limenu"><a href="forms.php"><i class="fa fa-search"></i><span>Consultas</span></a>
                </li>
                <?php if(!(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter")): ?>
                    <li id="goreports" class="limenu"><a href="report.php"><i class="fa fa-search"></i><span>Reportes</span></a>
                    </li>
                    <li id="gomap" class="limenu"><a href="map.php"><i class="fa fa-map-marker"></i><span>Ubicaciones</span></a>
                    </li>
                <?php endif; ?>
                <!--<li id="gochart" class="limenu"><a href="chart.php"><i i class="fa fa-bar-chart"></i> <span>Gráficas</span></a></li>-->
            </ul>
            <!--sidebar nav end-->
        </div>
    </div>
    <!-- sidebar left end-->

    <!-- body content start-->
    <div class="body-content">

        <!-- header section start-->
        <div class="header-section">

            <!--logo and logo icon start-->
            <div class="logo dark-logo-bg hidden-xs hidden-sm">
                <a href="index.php">
                    <!--<img src="assets/img/logo-icon.png" alt="">-->
                    <!--<i class="fa fa-maxcdn"></i>-->
                    <span class="brand-name">Mexicana de Gas</span>
                </a>
            </div>

            <div class="icon-logo dark-logo-bg hidden-xs hidden-sm">
                <a href="index.php">
                    <!--<img src="assets/img/logo-icon.png" alt="">-->
                    <!--<i class="fa fa-maxcdn"></i>-->
                </a>
            </div>
            <!--logo and logo icon end-->

            <!--toggle button start-->
            <a class="toggle-btn"><i class="fa fa-outdent"></i></a>
            <!--toggle button end-->

            <div class="notification-wrap">
                <!--left notification start-->
                <div class="left-notification">

                    <ul class="notification-menu">

                        <!-- NOTIFICACIONES QUE TENGA EL USUARIO EN EL SISTEMA, ESTA PARTE ES DINAMICA
                        Y SE VE EN EL ARCHIVO notificaciones.js CUANDO HACE LA LLAMADA CON EL METODO
                        leerNotificacionesPorIdUsuario-->

                        <!--notification info start-->
                        <li>
                            <a href="javascript:;" class="btn btn-default dropdown-toggle info-number"
                               data-toggle="dropdown">
                                <i class="fa fa-bell"></i>
                                <span id="spanCampanaNotificaciones" name="spanCampanaNotificaciones"
                                      class="badge bg-danger"></span>
                            </a>

                            <!--- INTEGRACION DEL MENUNOTIFICACIONES DINAMICO DESDE EL JAVASCRIPT notificaciones.js PARA INSERTAR
                            LAS NOTIFICIACIONES EN LISTA, SI ES QUE EL USUARIO CUENTA CON ELLAS-->

                            <div id="divDropdownMenuNotificaciones" name="divDropdownMenuNotificaciones"
                                 class="dropdown-menu dropdown-title ">
                            </div>
                        </li>
                        <!--notification info end-->
                    </ul>
                </div>
                <!--left notification end-->

                <!--right notification start-->
                <div class="right-notification">
                    <input id="inputIdUser" name="inputIdUser" type="text" class="hidden"
                           value="<?= $_SESSION["id"]; ?>"/>
                    <input id="inputRolUser" name="inputRolUser" type="text" class="hidden"
                           value="<?= $_SESSION["rol"]; ?>"/>

                    <?php if(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter"): ?>
                        <input id="typeAgency" name="typeAgency" type="text" class="hidden"
                           value="CallCenter"/>
                    <?php else: ?>
                        <input id="typeAgency" name="typeAgency" type="text" class="hidden"
                           value=""/>
                    <?php endif; ?>
                    <ul class="notification-menu">
                        <li>
                            <a href="javascript:;" class="menuPerfilDeUsuarioHeader btn btn-default dropdown-toggle"
                               id="nicknameZone" data-toggle="dropdown" style="color: #ffffff;">
                                <?= $_SESSION["nickname"]; ?>
                                <span class=" fa fa-caret-down "></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu green pull-right">
                                <?php
                                if ($_SESSION["rol"] == "SuperAdmin" || $_SESSION["rol"] == "Admin") {
                                    echo '<li><a onclick = "openCatalogs();" ><i class="fa fa-sign-out pull-left"></i> Mis cat&aacute;logos </a>
                                </li >';
                                    }else{
                                   echo '';
                                }?>
                                <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i>Cerrar
                                        sesi&oacute;n</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!--right notification end-->
            </div>
        </div>
        <!-- header section end-->

        <!-- page head start-->
        <div class="page-head">
            <h3 id="titleHeader"></h3>
            <span class="sub-title" id="subtitle-header"></span>
        </div>
        <!-- page head end-->
        <!--body wrapper start-->
        <div class="wrapper">

            <div class="modal fade disable-scroll" id="modalCatalogs" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" id="closeModal" data-dismiss="modalCatalogs" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Mis cat&aacute;logos</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-1">&nbsp;</div>
                                <div class="col-md-10">
                                    <div class="col-md-3">&nbsp;</div>
                                    <div class="col-md-6">
                                        <h2>Cat&aacute;logos</h2>
                                        <br/>
                                        <select id="catalogs" class="form-group">
                                            <option value="1">Motivos de reachazo</option>
                                            <option value="2">Tipos de Contrato</option>
                                            <option value="3">Anomalias</option>
                                            <option value="4">Nivel de Vivienda</option>
                                            <option value="5">Material de Instalacion</option>
                                            <option value="6">Tipo de Medidor</option>
                                            <option value="7">Tuberia</option>
                                            <option value="8">Motivos de Desinteres</option>
                                        </select>
                                        <br/>
                                        <input type="text" class="form-group" id="txtBoxReason" name="txtBoxReason" />&nbsp;&nbsp;<img id="btnAgregarMotivosRechazo" name="btnAgregarMotivosRechazo" src="assets/icons/agregar.png" onClick="javascript:addReason();" height = "26px" width = "26px"/>
                                    </div>
                                    <table class="table table-condensed" id="tableAddContrato" style="display: none">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>ID Articulo</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="idArticulo" name="idArticulo" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label>Articulo</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="articulo" name="articulo" />
                                                </th>
                                                <th>
                                                    <label>Descripcion</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="description" name="description" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label>Precio</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="Precio" name="Precio" />
                                                </th>
                                                <th>
                                                    <label>Pagos</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="pagos" name="pagos" />
                                                    <label>Plazo</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="plazo" name="plazo" />
                                                </th>
                                                <th>
                                                    <img id="btnAgregarPago" name="btnAgregarPago" src="assets/icons/agregar.png" height = "26px" width = "26px"/>
                                                    &nbsp;<h5>Agregar Plazo y pagos</h5>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <button type=button id="btnAgregarTipoContrato" class="btn btn-primary" >Agregar Contrato</button>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-condensed" id="tableAddAnomalias" style="display: none">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>Descripcion</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="descAnom" name="descAnom" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <button type=button id="btnAgregarRegCat" class="btn btn-primary" >Agregar Tipo de Anomalia</button>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-condensed" id="tableAddNivViv" style="display: none">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>Descripcion</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="descNivViv" name="descNivViv" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <button type=button id="btnAgregarRegCat" class="btn btn-primary" >Agregar Nivel de Vivienda</button>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <table class="table table-condensed" id="tableAddMatInst" style="display: none">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>Descripcion</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="descMatInst" name="descMatInst" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <button type=button id="btnAgregarRegCat" class="btn btn-primary" >Agregar Material de Instalacion</button>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <table class="table table-condensed" id="tableAddMedidor" style="display: none">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>Descripcion</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="descMedidor" name="descMedidor" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <button type=button id="btnAgregarRegCat" class="btn btn-primary" >Agregar Tipo de Medidor</button>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <table class="table table-condensed" id="tableAddTuberia" style="display: none">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>Descripcion</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="descTuberia" name="descTuberia" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <button type=button id="btnAgregarRegCat" class="btn btn-primary" >Agregar Tuberia</button>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <table class="table table-condensed" id="tableAddMotDesint" style="display: none">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label>Descripcion</label>
                                                    <div class="col-md-1">&nbsp;</div>
                                                    <input type="text" class="form-group" id="descMotDesint" name="descMotDesint" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <button type=button id="btnAgregarRegCat" class="btn btn-primary" >Agregar Motivos de Desinteres</button>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-condensed" id="tableAddTipoRechazo">
                                        <thead>
                                            <tr>
                                                <th id="idTitle">Motivos de Rechazo</th><th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                    <tbody id="reasonsText" name="reasonsText"></tbody></table><br/>
                                </div>
                                <div class="col-md-1">&nbsp;</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <br/><br/>
                            <button type=button id="btnCancel" class="btn btn-danger" onclick="closeCatalog();">CANCELAR</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button type=button id="btnSave" class="btn btn-primary" onclick="insertarMotivoDeRechazo();">GUARDAR</button>
                        </div>
                    </div>
                    <!-- modal content-->
                </div>
                <!-- modal-dialog-->
            </div>
            <!-- /.modal-->
            <!-- INVOCAMOS LA CLASE CON EL METODO LEERNOTIFICACIONES PARA QUE NOS TRAIGA
            LAS NOTIFICACIONES DEL USUARIO ACTUALMENTE LOGEADO-->
            <script type="text/javascript" src="assets/js/clases/notificaciones.js"></script>
            <script type="text/javascript" src="assets/js/clases/funciones.js"></script>
            <script type="text/javascript">
                var reasons = [];
                var list = new Array();
                var pageList = new Array();
                var arrPlazos = new Array();
                var arrPagos = new Array();
                var currentPage = 1;
                var numberPerPage = 3;
                var numberOfPages = 1;   // calculates the total number of pages
                function openCatalogs () {
                    obtenerCatalogoMotivosDeRechazo();
                    $('#modalCatalogs').modal('show');
                }
                $("#catalogs").on("change", function (event) {
                    var tableAddAnomalias=$('#tableAddAnomalias'),
                        tableAddNivViv=$('#tableAddNivViv'),
                        tableAddMatInst=$('#tableAddMatInst'),
                        tableAddMedidor=$('#tableAddMedidor'),
                        tableAddTuberia=$('#tableAddTuberia'),
                        tableAddMotDesint=$('#tableAddMotDesint'),
                        tableAddContrato=$("#tableAddContrato");
                        $("#tableAddAnomalias input").val('');
                        $("#tableAddNivViv input").val('');
                        $("#tableAddMatInst input").val('');
                        $("#tableAddMedidor input").val('');
                        $("#tableAddTuberia input").val('');
                        $("#tableAddMotDesint input").val('');
                        $("#tableAddContrato input").val('');
                        $("#tableAddTipoRechazo input").val('');
                        $("#txtBoxReason").hide();
                        $("#btnAgregarMotivosRechazo").hide();
                        $("#btnSave").hide();
                        var idCatalogo=$("#catalogs").val();
                        $('#reasonsText').html('');
                    if (parseInt(idCatalogo) === 1) {
                        $("#articulo").val('');
                        $("#description").val('');
                        $("#clasif").val('');
                        $("#financiamiento").val('');
                        $("#Precio").val('');
                        obtenerCatalogoMotivosDeRechazo();
                        $("#idTitle").text('');
                        $("#idTitle").html('Motivos de Rechazo');
                        $("#txtBoxReason").show();
                        $("#btnAgregarMotivosRechazo").show();
                        $("#btnSave").show();
                        arrPlazos=[];
                        arrPagos=[];
                        list=[];
                        pageList=[];
                    }else if (parseInt(idCatalogo) === 2){
                        $("#idTitle").text('');
                        $("#idTitle").html('Tipos de Contrato');
                        tableAddContrato.show();
                        tableAddAnomalias.hide();
                        tableAddNivViv.hide();
                        tableAddMatInst.hide();
                        tableAddMedidor.hide();
                        tableAddTuberia.hide();
                        tableAddMotDesint.hide();
                        obtenerCatalogoTiposDeContrato();
                    }else if(parseInt(idCatalogo) === 3){
                        $("#idTitle").text('');
                        $("#idTitle").html('Anomalias');
                        tableAddContrato.hide();
                        tableAddAnomalias.show();
                        tableAddNivViv.hide();
                        tableAddMatInst.hide();
                        tableAddMedidor.hide();
                        tableAddTuberia.hide();
                        tableAddMotDesint.hide();
                        obtenerCatalogos(idCatalogo);
                        arrPlazos=[];
                        arrPagos=[];
                        list=[];
                        pageList=[];
                    }else if(parseInt(idCatalogo) === 4){
                        $("#idTitle").text('');
                        $("#idTitle").html('Nivel de Vivienda');
                        tableAddContrato.hide();
                        tableAddAnomalias.hide();
                        tableAddNivViv.show();
                        tableAddMatInst.hide();
                        tableAddMedidor.hide();
                        tableAddTuberia.hide();
                        tableAddMotDesint.hide();
                        arrPlazos=[];
                        arrPagos=[];
                        list=[];
                        pageList=[];
                        obtenerCatalogos(idCatalogo);
                    }else if(parseInt(idCatalogo) === 5){
                        $("#idTitle").text('');
                        $("#idTitle").html('Material de Instalacion');
                        tableAddContrato.hide();
                        tableAddAnomalias.hide();
                        tableAddNivViv.hide();
                        tableAddMatInst.show();
                        tableAddMedidor.hide();
                        tableAddTuberia.hide();
                        tableAddMotDesint.hide();
                        arrPlazos=[];
                        arrPagos=[];
                        list=[];
                        pageList=[];
                        obtenerCatalogos(idCatalogo);
                    }else if(parseInt(idCatalogo) === 6){
                        $("#idTitle").text('');
                        $("#idTitle").html('Tipo de Medidor');
                        tableAddContrato.hide();
                        tableAddAnomalias.hide();
                        tableAddNivViv.hide();
                        tableAddMatInst.hide();
                        tableAddMedidor.show();
                        tableAddTuberia.hide();
                        tableAddMotDesint.hide();
                        arrPlazos=[];
                        arrPagos=[];
                        list=[];
                        pageList=[];
                        obtenerCatalogos(idCatalogo);
                    }else if(parseInt(idCatalogo) === 7){
                        $("#idTitle").text('');
                        $("#idTitle").html('Tuberia');
                        tableAddContrato.hide();
                        tableAddAnomalias.hide();
                        tableAddNivViv.hide();
                        tableAddMatInst.hide();
                        tableAddMedidor.hide();
                        tableAddTuberia.show();
                        tableAddMotDesint.hide();
                        arrPlazos=[];
                        arrPagos=[];
                        list=[];
                        pageList=[];
                        obtenerCatalogos(idCatalogo);
                    }else if(parseInt(idCatalogo) === 8){
                        $("#idTitle").text('');
                        $("#idTitle").html('Motivos de Desinteres');
                        tableAddContrato.hide();
                        tableAddAnomalias.hide();
                        tableAddNivViv.hide();
                        tableAddMatInst.hide();
                        tableAddMedidor.hide();
                        tableAddTuberia.hide();
                        tableAddMotDesint.show();
                        arrPlazos=[];
                        arrPagos=[];
                        list=[];
                        pageList=[];
                        obtenerCatalogos(idCatalogo);
                    }
                });

                $("#btnAgregarMotivosRechazo").click(function () {
                    var reasonText = $('#txtBoxReason').val();
                    if (reasonText == '') {
                        MostrarToast(2,"Motivo de rechazo invalido","No puedes agregar un motivo de rechazo vacio.");
                    } else {
                        //alert(reasonText);
                        reasons.push(reasonText);
                        $('#reasonsText').append('<tr><th scope="row">' + reasonText + '</th></tr><br/>');
                    }
                    $('#txtBoxReason').val('');
                });
                function obtenerCatalogoMotivosDeRechazo(){
                    $("#tableAddContrato").hide();
                    $('#reasonsText').html('');
                    $.ajax({
                        method: "POST",
                        url: "dataLayer/callsWeb/ObtenerCatalogoMotivosDeRechazo.php",
                        data: {},
                        dataType: "JSON",
                        success: function (data) {
                            var i=0;
                            var sizeData=data.length;
                            if(sizeData==0){

                            }else{
                                for(i=0;i<sizeData;i++){
                                    $('#reasonsText').append('<tr><th scope="row">' + data[i].reason + '</th><th><button id="btnEliminarDelCatalogoMotivoDeRechazo" name="btnEliminarDelCatalogoMotivoDeRechazo" onclick="eliminarMotivoDeRechazo('+data[i].id+')" class="btn btn-danger"><i class="fa fa-times-circle"></i></button></th></tr><br/>');
                                }
                            }
                        }
                    });
                }
                function obtenerCatalogos(tipoCatalogo){
                    $('#reasonsText').html('');
                    if (tipoCatalogo !== null && tipoCatalogo !== '' && typeof(tipoCatalogo) !== 'undefined') {
                        var urlCat;
                        switch(parseInt(tipoCatalogo)) {
                            case 3:
                                urlCat="dataLayer/callsWeb/catAnomalias.php";
                                break;
                            case 4:
                                urlCat="dataLayer/callsWeb/catCensoNivViv.php";
                                break;
                            case 5:
                                urlCat="dataLayer/callsWeb/catInstalacion.php";
                                break;
                            case 6:
                                urlCat="dataLayer/callsWeb/catMedidor.php";
                                break;
                            case 7:
                                urlCat="dataLayer/callsWeb/catPlom.php";
                                break;
                            case 8:
                                urlCat="dataLayer/callsWeb/catMotDesint.php";
                                break;
                        }
                        var htmlTable='';
                        $.ajax({
                            method: "POST",
                            url: urlCat,
                            data: {},
                            dataType: "JSON",
                            success: function (data) {
                                htmlTable = '<tr>';
                                htmlTable += '<th scope="row">Descripcion</th>';
                                htmlTable += '</tr>';
                                if (data.response.respuesta !== '' && 
                                    data.response.respuesta !== null && 
                                    typeof(data.response.respuesta) !== 'undefined') {
                                    data.response.respuesta.forEach(function(datosCatalogo, idx) {
                                        htmlTable += '<tr data-id="'+datosCatalogo.id+'">';
                                        htmlTable += '<th scope="row">'+datosCatalogo.desc+'</th>';
                                        htmlTable += '<th scope="row">';
                                        htmlTable += '<button id="btnDelRegCat" name="btnDelRegCat"  class="btn btn-danger"><i class="fa fa-times-circle"></i></button>';
                                        htmlTable += '</th>';
                                        htmlTable += '</tr>';
                                    });
                                    $('#reasonsText').append(htmlTable);
                                }
                            }
                        });
                    }
                }
                function insertarMotivoDeRechazo(){

                    var i=0;
                    var sizeReasons=reasons.length;
                    if(sizeReasons>0) {
                        $('#reasonsText').html('');
                        for(i=0;i<sizeReasons;i++) {
                            var motivoDeRechazo=reasons[i];
                            $.ajax({
                                method: "POST",
                                url: "dataLayer/callsWeb/InsertarCatalogoMotivosDeRechazo.php",
                                data: {inMotivoDeRechazo: motivoDeRechazo},
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.CodigoRespuesta == 1) {
                                        MostrarToast(1, "Exito", data.MensajeRespuesta);
                                    } else {
                                        MostrarToast(2, "Fallo al insertar motivo de catalogo", "Ocurrio un problema al momento de insertar el motivo de rechazo");
                                    }
                                }
                            });
                        }
                        reasons=[];
                        $('#modalCatalogs').modal('hide');
                    }else{
                        MostrarToast(2, "Aviso", "No hay motivos nuevos por insertar");
                    }
                }

                function eliminarMotivoDeRechazo(idMotivoRechazo){
                    $('#reasonsText').html('');
                    $.ajax({
                        method: "POST",
                        url: "dataLayer/callsWeb/EliminarMotivoDeRechazoDelCatalogo.php",
                        data: {idMotivoRechazo:idMotivoRechazo},
                        dataType: "JSON",
                        success: function (data) {
                           if(data.CodigoRespuesta==1){
                               MostrarToast(1,"Exito",data.MensajeRespuesta);
                           }else{
                               MostrarToast(2,"Fallo al eliminar motivo de catalogo","Ocurrio un problema al momento de eliminar el motivo de rechazo");
                           }
                        }
                    });
                    $('#modalCatalogs').modal('hide');
                }

                function closeCatalog () {
                    $('#modalCatalogs').modal('hide');
                    arrPlazos=[];
                    arrPagos=[];
                    list=[];
                    pageList=[];
                    $("#articulo").val('');
                    $("#description").val('');
                    $("#clasif").val('');
                    $("#financiamiento").val('');
                    $("#Precio").val('');
                    $("#catalogs").val(1);
                }

                function saveCatalog () {
                    var catalogType = $('#catalogs').val();

                    $.ajax({
                        method: "POST",
                        url: "dataLayer/callsWeb/updateCatalog.php",
                        data: { catalog : catalogType, reasons: reasons },
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            $('#modalCatalogs').modal('hide');

                            configurarToastCentrado();
                            //mostrarToast(1, "", "");
                        }
                    });
                }

                function obtenerCatalogoTiposDeContrato(){
                    $('#reasonsText').html('');
                    $.ajax({
                        method: "POST",
                        url: "dataLayer/callsWeb/ObtenerCatalogoTiposDeContrato.php",
                        data: {},
                        dataType: "JSON",
                        success: function (data) {
                            var i=0;
                            var sizeData=data.length;
                            list=data;
                            load();
                        }
                    });   
                }

                function makeList() {
                    numberOfPages = getNumberOfPages();
                }
                    
                function getNumberOfPages() {
                    return Math.ceil(list.length / numberPerPage);
                }

                function nextPage() {
                    currentPage += 1;
                    loadList();
                }

                function previousPage() {
                    currentPage -= 1;
                    loadList();
                }

                function firstPage() {
                    currentPage = 1;
                    loadList();
                }

                function lastPage() {
                    currentPage = numberOfPages;
                    loadList();
                }

                function loadList() {
                    var begin = ((currentPage - 1) * numberPerPage);
                    var end = begin + numberPerPage;

                    pageList = list.slice(begin, end);
                    console.log('pageList', pageList);
                    drawList();
                    check();
                }
                    
                function drawList() {
                    document.getElementById("reasonsText").innerHTML = "";
                    var sizeData=pageList.length;
                    if(sizeData > 0){
                        var htmlTable,clasif,claveArticuloContrato,financiamiento,
                            idCatalogoTiposDeContrato,idContrato,pagos,plazo,precio,
                            tipoDeContrato,selectPlazos, selectPagos;


                        htmlTable = '<tr>';
                        htmlTable += '<th>';
                        htmlTable += '<button id="first" name="first" onclick="firstPage()" value="first" class="btn btn-default"><i class="fa fa-arrow-circle-left "></i></button>';
                        
                        htmlTable += '&nbsp;<button id="previous" name="previous" onclick="previousPage()" value="previous" class="btn btn-default"><i class="fa fa-arrow-left"></i></button>';
                        
                        htmlTable += '&nbsp;<button id="next" name="next" onclick="nextPage()" value="next" class="btn btn-default"><i class="fa fa-arrow-right"></i></button>';
                        
                        htmlTable += '&nbsp;<button id="last" name="last" onclick="lastPage()" value="last" class="btn btn-default"><i class="fa fa-arrow-circle-right"></i></button>';
                        htmlTable += '</th>';
                        htmlTable += '</tr>';

                        htmlTable += '<tr>';
                        htmlTable += '<th scope="row">Contrato</th>';
                        htmlTable += '<th scope="row">Plazo</th>';
                        htmlTable += '<th scope="row">Pagos</th>';
                        htmlTable += '<th scope="row">Precios</th>';
                        htmlTable += '</tr>';
                        //load();
                        pageList.forEach(function(contrato) {
                            clasif=contrato.clasif;//:"ZM"
                            claveArticuloContrato=contrato.claveArticuloContrato;//:"Contrato(AYOPSA)"
                            financiamiento=contrato.financiamiento;//:"AYOPSA"
                            idCatalogoTiposDeContrato=contrato.idCatalogoTiposDeContrato;//:6
                            idContrato=contrato.idContrato;//:139
                            pagos=contrato.pagos;//:"460.10,239.16,154.51,123.07,108.71"
                            plazo=contrato.plazo;//:"6,13,24,36,48"
                            precio=contrato.precio.toFixed(2);//:2438.3200683594
                            tipoDeContrato=contrato.tipoDeContrato;//:"Contrato Nuevos Fraccionamientos"

                            pagos = pagos.split(",");
                            plazo = plazo.split(",");

                            selectPlazos ='<select id="plazos" class="form-group">';
                            plazo.forEach(function(plazos, idx) {
                                selectPlazos += '<option value="'+idx+'">'+plazos+'</option>';
                            })
                            selectPlazos +='</select>';

                            selectPagos ='<select id="pagos" class="form-group">';
                            pagos.forEach(function(pago, idx) {
                                selectPagos += '<option value="'+idx+'">'+pago+'</option>';
                            })
                            selectPagos +='</select>';
                            //creamos la tabla dinamicamente
                            htmlTable += '<tr data-id="'+idCatalogoTiposDeContrato+'">';
                            htmlTable += '<th scope="row">' + tipoDeContrato + '</th>';
                            htmlTable += '<th scope="row">' + selectPlazos + '</th>';
                            htmlTable += '<th scope="row">' + selectPagos + '</th>';
                            htmlTable += '<th scope="row">' + precio + '</th>';
                            htmlTable += '<th scope="row">';
                            htmlTable += '<button id="btnDelTipContrato" name="btnDelTipContrato"  class="btn btn-danger"><i class="fa fa-times-circle"></i></button>';
                            htmlTable += '</th>';
                            htmlTable += '</tr>';
                        });
                        $('#reasonsText').append(htmlTable);
                    }
                }

                function check() {
                    document.getElementById("next").disabled = currentPage == numberOfPages ? true : false;
                    document.getElementById("previous").disabled = currentPage == 1 ? true : false;
                    document.getElementById("first").disabled = currentPage == 1 ? true : false;
                    document.getElementById("last").disabled = currentPage == numberOfPages ? true : false;
                }

                function load() {
                    makeList();
                    loadList();
                }

                $(document).ready(function () {
                    var idUser = $('#inputIdUser').val();
                    JsNotificaciones.leerNotificacionesPorIdUsuario(idUser);
                });

                $('#closeModal').click(function () {
                    $('#modalCatalogs').modal('hide');
                    arrPlazos=[];
                    arrPagos=[];
                    list=[];
                    pageList=[];
                    $("#articulo").val('');
                    $("#description").val('');
                    $("#clasif").val('');
                    $("#financiamiento").val('');
                    $("#Precio").val('');
                    $("#catalogs").val(1);
                });

                $('#reasonsText').on('click', '#btnAgregarTipoContrato', function(e) {
                    e.preventDefault();
                    console.log('habilitamos el modal');
                    $('#modalAddTipoContrato').modal('show');
                });
                $('#btnAgregarTipoContrato:not(:disabled)').click(function (e) {
                    $('#btnAgregarTipoContrato').prop('disabled', true);
                    e.preventDefault();
                    var idArticulo=$("#idArticulo").val(),
                        articulo=$("#articulo").val(),
                        description=$("#description").val(),
                        Precio=$("#Precio").val();
                    if (arrPlazos.length >0 &&
                        arrPagos.length >0) {
                        if ((typeof(idArticulo) !== 'undefined' && idArticulo !== null && idArticulo !=='') &&
                            (typeof(articulo) !== 'undefined' && articulo !== null && articulo !=='') &&
                            (typeof(Precio) !== 'undefined' && Precio !== null && Precio !=='')) {
                            var plazos=arrPlazos.toString();
                            var pagos=arrPagos.toString();
                            arrPlazos=[];
                            arrPagos=[];
                            $.ajax({
                                method: "POST",
                                url: "dataLayer/callsWeb/InsertarCatalogoTipoContrato.php",
                                data: {idArticulo: idArticulo,
                                       articulo: articulo,
                                       description: description,
                                       Precio: Precio,
                                       plazos: plazos,
                                       pagos: pagos},
                                dataType: "JSON",
                                success: function (data) {
                                    console.log('data', data);
                                    if (data.status == "ERROR") {
                                        MostrarToast(2, "Falla en catalogos dinamicos", "Fallo en creacion de tipo de contrato");
                                    } else {
                                        if (data.code === '500' || data.code === 500) {
                                            MostrarToast(2, "Fallo en Creacion de Tipo de Contrato", data.result);
                                            configurarToastCentrado();
                                        }else if(data.code === '200' || data.code === 200){
                                            MostrarToast(1, "Tipo de Contrato creado", data.result);
                                            list=[];
                                            pageList=[];
                                            $("#articulo").val('');
                                            $("#description").val('');
                                            $("#Precio").val('');
                                            obtenerCatalogoTiposDeContrato();
                                        }
                                    }
                                }
                            });
                            $('#btnAgregarTipoContrato').prop('disabled', false);
                        }else{
                            MostrarToast(2, "Faltan algunos datos de capturar para crear el nuevo Tipo de contrato");
                            $('#btnAgregarTipoContrato').prop('disabled', false);
                        }
                    }else{
                        MostrarToast(2, "Es necesario capturar la mensualidad y el pago correspondiente");
                        $('#btnAgregarTipoContrato').prop('disabled', false);
                    }
                });


                $('#btnAgregarRegCat:not(:disabled)').click(function (e) {
                    $('#btnAgregarRegCat').prop('disabled', true);
                    e.preventDefault();
                    var idCatalogo=$("#catalogs").val(), tipoCatalogoReg, descripcionCat;
                    switch(parseInt(idCatalogo)) {
                        case 3:
                            descripcionCat=$("#tableAddAnomalias input").val();
                            break;
                        case 4:
                            descripcionCat=$("#tableAddNivViv input").val();
                            break;
                        case 5:
                            descripcionCat=$("#tableAddMatInst input").val();
                            break;
                        case 6:
                            descripcionCat=$("#tableAddMedidor input").val();
                            break;
                        case 7:
                            descripcionCat=$("#tableAddTuberia input").val();
                            break;
                        case 8:
                            descripcionCat=$("#tableAddMotDesint input").val();
                            break;
                    }
                    if (typeof(descripcionCat) !== 'undefined' && descripcionCat !== null && descripcionCat !=='') {
                        $.ajax({
                            method: "POST",
                            url: "dataLayer/callsWeb/actualizarCatalogo.php",
                            data: {tipoCatalogoReg: idCatalogo,description: descripcionCat},
                            dataType: "JSON",
                            success: function (data) {
                                console.log('data', data);
                                if (data.status == "ERROR") {
                                    MostrarToast(2, "Falla en catalogos dinamicos", "Fallo en creacion de tipo de contrato");
                                } else {
                                    if (data.code === '500' || data.code === 500) {
                                        MostrarToast(2, "Fallo la actualizacion del contrato");
                                        configurarToastCentrado();
                                    }else if(data.code === '200' || data.code === 200){
                                        switch(parseInt(idCatalogo)) {
                                            case 3:
                                                $("#tableAddAnomalias input").val('');
                                                MostrarToast(1, "Catalogo de Anomalias Actualizado");
                                                obtenerCatalogos(idCatalogo);
                                                break;
                                            case 4:
                                                $("#tableAddNivViv input").val('');
                                                MostrarToast(1, "Catalogo de Nivel de Vivienda Actualizado");
                                                obtenerCatalogos(idCatalogo);
                                                break;
                                            case 5:
                                                $("#tableAddMatInst input").val('');
                                                MostrarToast(1, "Catalogo de Material de instalacion Actualizado");
                                                obtenerCatalogos(idCatalogo);
                                                break;
                                            case 6:
                                                $("#tableAddMedidor input").val('');
                                                MostrarToast(1, "Catalogo de Medidor Actualizado");
                                                obtenerCatalogos(idCatalogo);
                                                break;
                                            case 7:
                                                $("#tableAddTuberia input").val('');
                                                MostrarToast(1, "Catalogo de Tuberia Actualizado");
                                                obtenerCatalogos(idCatalogo);
                                                break;
                                            case 8:
                                                $("#tableAddMotDesint input").val('');
                                                MostrarToast(1, "Catalogo Motivos de Desinteres Actualizado");
                                                obtenerCatalogos(idCatalogo);
                                                break;
                                        }
                                    }
                                }
                            }
                        });
                        $('#btnAgregarRegCat').prop('disabled', false);
                    }else{
                        MostrarToast(2, "El campo Descripcion se Encuentra Vacio");
                        $('#btnAgregarRegCat').prop('disabled', false);
                    }
                });

                $('#modalCatalogs').on('click', '#btnAgregarPago', function(e) {
                    e.preventDefault();
                    var pago=$("#pagos").val();
                    var plazo=$("#plazo").val();
                    if ((typeof(plazo) !== 'undefined' && plazo !== '' && plazo !== null) &&
                        (typeof(pago) !== 'undefined' && pago !== '' && pago !== null)) {
                        arrPlazos.push(plazo);
                        arrPagos.push(pago);
                    }else{
                        MostrarToast(2, "Es necesario capturar la mensualidad y el pago correspondiente");
                    }
                    $("#pagos").val('');
                    $("#plazo").val('');
                    pago='';
                    plazo='';
                });

                $('#reasonsText').on('click', '#btnDelTipContrato', function(e) {
                    console.log('this', this);
                    e.preventDefault();
                    var row=$(this).parents("tr");
                    var idContrato=row.data('id');
                    if (typeof(idContrato) !== 'undefined' && idContrato !== null && idContrato !== '') {
                        $.ajax({
                            method: "POST",
                            url: "dataLayer/callsWeb/eliminarTipoContrato.php",
                            data: {idContrato: idContrato},
                            dataType: "JSON",
                            success: function (data) {
                                console.log('data', data);
                                if (data.status == "ERROR") {
                                    MostrarToast(2, "Falla en catalogos dinamicos", "Fallo en eliminacion de tipo de contrato");
                                } else {
                                    if (data.code === '500' || data.code === 500) {
                                        MostrarToast(2, "Fallo en Creacion de Tipo de Contrato", data.result);
                                        configurarToastCentrado();
                                    }else if(data.code === '200' || data.code === 200){
                                        MostrarToast(1, "Tipo de Contrato eliminado", data.result);
                                        $("tr[data-id='"+idContrato+"']").remove();
                                    }
                                }
                            }
                        });
                    }
                });

                $('#reasonsText').on('click', '#btnDelRegCat', function(e) {
                    e.preventDefault();
                    var tipoCatalogo=$("#catalogs").val()
                    var row=$(this).parents("tr");
                    var idCatalog=row.data('id');
                    if (typeof(idCatalog) !== 'undefined' && idCatalog !== null && idCatalog !== '') {
                        $.ajax({
                            method: "POST",
                            url: "dataLayer/callsWeb/eliminarRegCatalogo.php",
                            data: {idCatalog: idCatalog, tipoCatalogo: tipoCatalogo},
                            dataType: "JSON",
                            success: function (data) {
                                if (data.status == "ERROR") {
                                    MostrarToast(2, "Falla en catalogos dinamicos", "Fallo en eliminacion");
                                } else {
                                    if (data.code === '500' || data.code === 500) {
                                        MostrarToast(2, "Fallo en Creacion de opcion de catalogo", data.result);
                                        configurarToastCentrado();
                                    }else if(data.code === '200' || data.code === 200){
                                        MostrarToast(1, "Registro eliminado");
                                        $("tr[data-id='"+idCatalog+"']").hide();
                                        $("tr[data-id='"+idCatalog+"']").remove();
                                    }
                                }
                            }
                        });
                    }
                });
            </script>