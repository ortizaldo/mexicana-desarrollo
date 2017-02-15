<?php include("header.php") ?>
<?php require_once 'dataLayer/classes/estructuraCarpetas.php'; ?>

<?php  
$oEstructuraCarpetas = new EstructuraCarpetas();
$estatus_instalacion = $oEstructuraCarpetas->getEstatusInstalacion();

?>

<style>
    .modal {
        /* ... */
        overflow-y: scroll;
    }
</style>

<link rel="stylesheet" href="assets/css/dcalendar.picker.min.css" xmlns="http://www.w3.org/1999/html"/>
<link rel="stylesheet" href="assets/css/lightbox.css"/>
<script src="assets/js/dcalendar.picker.min.js"></script>
<script src="assets/js/jQueryRotate.js"></script>
<script src="assets/js/lightbox.js"></script>


<?php if($_SESSION['typeAgency']== 'Comercializadora' || $_SESSION['typeAgency'] == 'Instalacion' || $_SESSION['typeAgency'] == 'Instalacion y Comercializadora'): ?>
    <button type="button" id="btnAddVenta" class="btn btn-success btn-lg"><i class="fa fa-plus"></i> Crear nueva venta</button>
<?php endif; ?>
<br>

    
&nbsp;&nbsp;&nbsp;&nbsp;
<!--<button type="button" id="btnAddTask" class="btn btn-info btn-lg"><i class="glyphicon glyphicon-user"></i>
    Asignar tarea
</button><br/><br/> -->
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="form-inline">
                    <p><b>CONSULTAS</b></p>

                    <form method="POST"
                          action="dataLayer/callsWeb/downloadExcelForms.php">
                        <input id="inputIdUser" name="inputIdUser" type="text" class="hidden"
                               value="<?= $_SESSION["id"]; ?>"/>
                        <input id="inputNickUserLogg" name="inputNickUserLogg" type="text" class="hidden"
                               value=""/>
                        <label>Filtros</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <select class="form-control" id="txtType" name="txtType">
                            <option value="0">Todos los tipos</option>
                            <option value="1">Censo</option>
                            <option value="2">Plomer&iacute;a</option>
                            <option value="3">Venta</option>
                            <option value="4">Instalacion</option>
                            <option value="5">Segunda Venta</option>
                        </select>
                        <select class="form-control" id="txtStatus" name="txtStatus" onchange="buscarPorEstatus()">
                            <option value="0">Todos los estatus</option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label class="text-capitalize">Fecha de:</label>&nbsp;
                        <input type='text' class="form-control" id="dateFrom" name="dateFrom"/>

                        <label class="text-capitalize">hasta:</label>&nbsp;
                        <input type='text' class="form-control" id="dateTo" name="dateTo"/>

                        <button type="button" id="btnFiltrarPorFechas" name="btnFiltrarPorFechas"
                                class="btn btn-success" onclick="filtrarPorFechas()">
                            <i class="fa fa-search">&nbsp;Filtrar por fechas</i>
                        </button>
                        <button type="button" id="limpiarFiltros" class="btn btn-success">
                            <span class="glyphicon glyphicon-refresh" style="color:#fff;"></span>
                        </button>

                        <button type="submit" id="btn_download" class="btn btn-success">
                            <span class="fa fa-file-excel-o" style="color:#fff;"></span>
                        </button>
                        <button type="submit" id="b_download" class="btn btn-success" style="display: none">
                            <a href=""></a>
                        </button>
                    </form>
                </div>
            </header>

            <div class="panel-body">
                <table id="tablaReporte" name="tablaReporte" class="table responsive-data-table data-table">
                    <thead>
                        <?php 
                            if($_SESSION['typeAgency'] == 'Instalacion' || 
                               $_SESSION['typeAgency'] == 'Instalacion y Comercializadora'){ ?>
                                <tr>
                                    <th>
                                        <div class="checkboxAsignacionMasiva">
                                            <label>
                                                <input type="checkbox" class="seleccionarChecks" name="seleccionarChecks">
                                                <i class="fa fa-check-square-o" aria-hidden="true"></i> Habilitar <br> Opciones
                                            </label>
                                        </div>
                                        <div class="select" style="display: none">
                                            <label>
                                                <select id="asignarUsuario" class="form-control">
                                                </select>
                                            </label>
                                        </div>
                                    </th>
                                    <th>
                                        <button id="asignacionMasiva" 
                                                data-toggle="button" 
                                                style="width:144px" 
                                                class="btn btn-default active" 
                                                aria-pressed="true">
                                            <i class="fa fa-wrench"></i> Asignacion masiva <br> de Instalacion
                                        </button>
                                    </th>
                                </tr>
                        <?php 
                            }
                        ?>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Seleccionar Usuario</th>
                            <th>ID Cliente</th>
                            <th>Contrato</th>
                            <th>Tipo</th>
                            <th>Estatus</th>
                            <th>Municipio</th>
                            <th>Colonia</th>
                            <th>Calle</th>
                            <th>Usuario</th>
                            <th>Agencia</th>
                            <th>Fecha</th>
                            <th>Tarea</th>
                        </tr>
                    </thead>
                    <tbody id="bodyReport">
                    </tbody>
                </table>
            </div>
            <div id="tablaLoader" name="tablaLoader">
                <div class="loader"></div>
                <br>

                <p class="centrar"><strong>CARGANDO .....</strong></p>
            </div>
        </section>
    </div>
</div>

<div class="modal fade disable-scroll" id="modalform" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalFormClose" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleCompany">Nueva venta</h4>
            </div>
            <div class="modal-body">
                <form class="cmxform" role="form" id="formNewSale">
                    <div><input type="text" id="txtid" hidden></div>
                    <div class="row">
                        <div class="col-xs-6">
                            <!--<Add company according the user>-->
                            <label>Municipio</label>
                            <select class="form-control" id="txMun" required>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6" id="colonia">
                            <!--<Add company according the user>-->
                            <label>Colonia</label>
                            <select class="form-control" id="txtCol" required>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Calle</label>
                            <select class="form-control" name="street" id="txtStreet" required></select>
                        </div>
                        <div class="col-xs-6">
                            <!--<Add rol according the user>-->
                            <br/>
                            <h4>Asignar venta</h4>
                            <label>Agencia</label>
                        </div>
                        <div class="col-xs-6">
                            <!--<Add company according the user>-->
                            <label>Entre calles</label>
                            <select class="form-control" name="txtRoads" id="txtRoads"></select>
                            <!--<input type="text" id="txtRoads" name="middleStreet" class="form-control" placeholder="Nombre de calles"/>-->
                        </div>
                        <div class="col-xs-6">
                            <select class="form-control" id="txtAgency" required></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <label>N&uacute;mero</label>
                            <select class="form-control" name="txtNumber" id="txtNumber"></select>
                        </div>
                        <div class="col-xs-6">
                            <label>Empleado</label>
                            <select class="form-control" id="txtUsers" required></select>
                        </div>
                        <div class="col-xs-6">
                            <input name="txtnotificationText" id="txtnotificationText"
                                   value="Se ha generado una nueva venta para ti" class="hidden"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <label>C.P.</label>
                            <input type="text" id="txtCP" name="postalcode" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <br/>
                            <!--<Add company according the user>-->
                            <label>Nivel socioecon&oacute;mico(NSE)</label>
                            <select id="txtLevel">
                                <option value="C">C</option>
                                <option value="C++">C++</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                        </div>
                        <br/>

                        <div class="col-xs-6">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-danger" id="btnCancelSell">CANCELAR</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-success" id="btnCreateSell">CREAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade fade disable-scroll " id="secondSellModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalFormClose" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <!-- TODO AQUI VA EL EL MODAL DE LA SEGUNDA VENTA--->
                <h4 class="modal-title" id="titlesecondSellModal" tabindex="-1" role="dialog"></h4>
            </div>
            <div class="modal-body">
                <form class="cmxSecondSellForm" role="form" id="formSecondSell">
                    <div><input type="text" id="txtid" hidden></div>
                    <!-- tabs left -->
                    <div class="tabbable tabs-left">
                        <ul class="nav nav-tabs">
                            <li class="active" id="nextSellTab1"><a href="#datosTitular" data-toggle="tab">Datos del
                                    titular</a></li>
                            <li id="nextSellTab2"><a href="#agreementInformation" data-toggle="tab">Informaci&oacute;n
                                    del contrato</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="datosTitular">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="col-xs-6">
                                            <p>&nbsp;&nbsp;&nbsp;&nbsp;CHECKLIST</p>

                                            <div class="col-xs-6">
                                                <label>Consecutivo</label>
                                                <input type="hidden" name="inputUserSegundaVenta" id="inputUserSegundaVenta">
                                                <input type="text" id="txtConsecutive" name="consecutive"
                                                       class="form-control input-sm">
                                                <label>Pagar&eacute;</label>

                                                <div class="input-group">
                                                    <input type="text" id="txtNextSellPayment" name="nextSellPayment"
                                                           class="form-control input-sm">
                                                    <input type="hidden" id="financialService" name="financialService"
                                                           class="form-control input-sm">
                                                    <div id="segundaVentaFinanciada" name="segundaVentaFinanciada" class="input-group-addon">AYO</div>
                                                </div>
                                                <label>N&uacute;mero de contrato</label>
                                                <input type="text" id="txtAgreement" name="Agreement"
                                                       class="form-control input-sm">
                                                <br/>

                                                <p>DATOS DEL TITULAR</p>
                                                <label>Apellido paterno</label>
                                                <input type="text" id="txtLastName1" name="ClientLastName1"
                                                       class="form-control input-sm">
                                                <label>Nombre</label>
                                                <input type="text" id="txtName" name="ClientName"
                                                       class="form-control input-sm">
                                                <label>CURP</label>
                                                <input type="text" id="txtCURP" name="ClientCURP"
                                                       class="form-control input-sm">
                                                <label>Estado civil</label>
                                                <select class="form-control input-sm" id="txtEngagment">
                                                    <option value="1">Soltero(a)</option>
                                                    <option value="2">Casado(a)</option>
                                                    <option value="3">Viudo (a)</option>
                                                    <option value="4">Unión Libre</option>
                                                </select>
                                                <label>Identificaci&oacute;n</label>
                                                <input type="text" id="txtIdCard" name="ClientIDCard"
                                                       class="form-control input-sm">
                                            </div>
                                            <div class="col-xs-6">
                                                <label>Agencia</label>
                                                <input type="text" id="txtNextSellAgency" name="nextSellAgency"
                                                       class="form-control input-sm">
                                                <input type="hidden" id="idAgenciaHid" >
                                                <br/><br/><br/>
                                                <label>Fecha de solicitud </label>
                                                <input type="text" id="txtRequestDate" name="txtRequestDate"
                                                       class="form-control input-sm">
                                                <br/><br/>
                                                <label>Apellido materno</label>
                                                <input type="text" id="txtLastName2" name="ClientLastName2"
                                                       class="form-control input-sm" onkeypress="return soloLetras(event)">
                                                <label>RFC</label>
                                                <input type="text" id="txtRFC" name="ClientRFC"
                                                       class="form-control input-sm">
                                                <label>Correo</label>
                                                <input type="text" id="txtEmail" name="ClientEmail"
                                                       class="form-control input-sm">
                                                <label>Sexo</label>
                                                <select class="form-control input-sm" id="txtNextSellGender">
                                                    <option value="1">Femenino</option>
                                                    <option value="2">Masculino</option>
                                                </select>
                                                <label>Tipo de identificaci&oacute;n</label>
                                                <select class="form-control input-sm" id="txtNextSellIdentification">
                                                    <option value="1">IFE</option>
                                                    <option value="2">Licencia para conducir</option>
                                                    <option value="3">Pasaporte</option>
                                                    <option value="4">Cartilla militar</option>
                                                    <option value="5">Cedula Profesional</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="col-xs-6">
                                                <label>Fecha de Nacimiento</label>
                                                <input type="text" id="txtNextSellBithdate" name="NextSellBirthdate"
                                                       class="form-control input-sm">
                                                <br/>

                                                <p>DOMICILIO</p>
                                                <label>Estado</label>
                                                <select class="form-control input-sm" id="txtNextStepSaleState">
                                                    <option value="1">Nuevo Le&oacute;n</option>
                                                    <option value="1"></option>
                                                    <option value="1"></option>
                                                </select>
                                                <label>Colonia</label>
                                                <select class="form-control input-sm" id="txtNextStepSaleColonia">
                                                </select>
                                                <label>Numero</label>
                                                <select class="form-control input-sm" id="txtNextStepSaleStreetNumber">
                                                </select>
                                                <label>Tel&eacute;fono celular</label>
                                                <input type="text" id="txtNextSellCellularPhone"
                                                       name="NextSellCellularPhone" class="form-control input-sm" onkeypress="return validateSoloNumeros(event)">
                                                <br/>

                                                <p>EMPLEO</p>
                                                <label>Empresa</label>
                                                <input type="text" id="txtNextSellEnterprise" name="NextSellEnterprise"
                                                       class="form-control input-sm">
                                                <label>Puesto</label>
                                                <input type="text" id="txtNextSellPosition" name="NextSellPosition"
                                                       class="form-control input-sm">
                                                <label>Tel&eacute;fono</label>
                                                <input type="text" id="txtNextSellJobTelephone"
                                                       name="NextSellJobTelephone" class="form-control input-sm" 
                                                       onkeypress="return validateSoloNumeros(event)">
                                            </div>
                                            <div class="col-xs-6">
                                                <label>Pa&iacute;s de Nacimiento</label>
                                                <select class="form-control input-sm" id="txtNextSellCountry">
                                                    <option value="1">M&eacute;xico</option>
                                                </select>

                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>
                                                <label>Municipio</label>
                                                <select class="form-control input-sm" id="txtNextStepSaleCity">
                                                </select>
                                                <label>Calle</label>
                                                <select class="form-control input-sm" id="txtNextStepSaleStreet">
                                                </select>
                                                <label>Vive en casa</label>
                                                <select class="form-control input-sm" id="txtNextStepSaleInHome">
                                                    <option value="1">Propia pagada</option>
                                                    <option value="2">Propia pagando</option>
                                                    <option value="3">Rentada</option>
                                                </select>
                                                <input type="hidden" id="domHiddenCity" name="domHiddenCity"  value="" class="form-control input-sm" disabled>
                                                <input type="hidden" id="domHiddenStreet" name="domHiddenStreet"  value="" class="form-control input-sm" disabled>
                                                <input type="hidden" id="domHiddenColonia" name="domHiddenColonia"  value="" class="form-control input-sm" disabled>
                                                <input type="hidden" id="domHiddenStreetNumber" name="domHiddenStreetNumber"  value="" class="form-control input-sm" disabled>
                                                <label>Tel&eacute;fono de casa</label>
                                                <input type="text" id="txtNextSellPhone" name="txtNextSellPhone"
                                                       class="form-control input-sm" onkeypress="return validateSoloNumeros(event)">
                                                <br/><br/><br/><br/>
                                                <label>Direcci&oacute;n</label>
                                                <input type="text" id="txtNextSellJobLocation"
                                                       name="NextSellJobLocation" class="form-control input-sm" onkeypress="return soloLetras(event)">
                                                <label>Actividad/&Aacute;rea</label>
                                                <input type="text" id="txtNextSellJobActivity"
                                                       name="NextSellJobActivity" class="form-control input-sm" onkeypress="return soloLetras(event)">
                                                <br/>
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="btn btn-primary" id="addSecondSell" style="display: none">GUARDAR</button>
                                                        </td>
                                                        <td>
                                                            &nbsp;
                                                        </td>
                                                        <td>
                                                            <!-- <a class="example-image-link" id="imgRutaSolicitud" href="#" data-lightbox="example-solicitud" >
                                                                <img id="imgSolicitud" src="" alt="solicitud" height="100px" width="100px"/>
                                                            </a>-->
                                                            <a href="#pictureInformation" data-toggle="tab">Documentos</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="agreementInformation">
                                <div class="row">
                                    <div id="informacionFinanciamientoReferencias"
                                         name="informacionFinanciamientoReferencias" class="col-md-9">
                                        <p>&nbsp;&nbsp;&nbsp;&nbsp;FINANCIAMIENTO</p>

                                        <div class="col-xs-6">
                                            <label>Tipo de contrato</label>
                                            <select class="form-control input-sm"
                                                    id="txtNextStepSaleAgreegmentType">

                                            </select>
                                            <label>Plazo</label>
                                            <input type="hidden" id="plazos">
                                            <input type="hidden" id="articulodesc">
                                            <br/>
                                            <select class="form-control input-sm"
                                                    id="nextSellPaymentTime">

                                            </select>
                                            <label>RI</label>
                                            <input type="text" id="txtNextSellRI" name="nextSellRI"
                                                   class="form-control input-sm">
                                            <br/>

                                            <p>REFERENCIAS</p>
                                            <label>Nombre referencia 1</label>
                                            <input type="text" id="txtNombreRefrencia1" name="txtNombreRefrencia1"
                                                   class="form-control input-sm" onkeypress="return soloLetras(event)">
                                            <label>Tel&eacute;fono de trabajo referencia 1</label>
                                            <input type="text" id="txtTelefonoDeTrabajoReferencia1"
                                                   name="txtTelefonoDeTrabajoReferencia1" class="form-control input-sm" onkeypress="return validateSoloNumeros(event)">
                                            <br/>
                                            <label>Nombre Ref. 2</label>
                                            <input type="text" id="txtNombreRefrencia2" name="txtNombreRefrencia2"
                                                   class="form-control input-sm" onkeypress="return soloLetras(event)">
                                            <label>Tel&eacute;fono de trabajo referencia 2</label>
                                            <input type="text" id="txtTelefonoDeTrabajoReferencia2"
                                                   name="txtTelefonoDeTrabajoReferencia2" class="form-control input-sm" onkeypress="return validateSoloNumeros(event)">
                                        </div>
                                        <div class="col-xs-6">
                                            <label>Precio </label>
                                            <input type="text" id="txtNextSellPrice" name="nextSellAgency"
                                                   class="form-control input-sm" disabled="disabled">
                                            <label>Mensualidad</label>
                                            <select class="form-control input-sm" id="txtNextSellMonthlyCost"
                                                   name="nextSellMonthlyCost" disabled="disabled"></select>
                                            <label>Fecha RI</label>
                                            <input type="text" id="txtNextSellDateRI" name="nextSellDateRI"
                                                   class="form-control input-sm">
                                            <br/><br/>
                                            <label>Tel&eacute;fono particular referencia </label>
                                            <input type="text" id="txtTelefonoParticularRefrencia1"
                                                   name="txtTelefonoParticularRefrencia1" class="form-control input-sm" onkeypress="return validateSoloNumeros(event)">
                                            <label>Extensi&oacute;n referencia 1 </label>
                                            <input type="text" id="txtTelefonoTrabajoExtRefrencia1"
                                                   name="txtTelefonoTrabajoExtRefrencia1"
                                                   class="form-control input-sm" onkeypress="return validateSoloNumeros(event)">
                                            <br/>
                                            <label>Tel&eacute;fono particular referencia 2</label>
                                            <input type="text" id="txtTelefonoParticularReferencia2"
                                                   name="txtTelefonoParticularRefrencia2" class="form-control input-sm" onkeypress="return validateSoloNumeros(event)">
                                            <label>Extensi&oacute;n referencia 2</label>
                                            <input type="text" id="txtTelefonoTrabajoExtReferencia2"
                                                   name="txtTelefonoTrabajoExtRefrencia2"
                                                   class="form-control input-sm" onkeypress="return validateSoloNumeros(event)">
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-4">

                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane" id="pictureInformation">
                                <div class="row">
                                    <div id="detalleImagenes"
                                         name="detalleImagenes" class="col-md-9">
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <!-- /tabs -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade disable-scroll" id="taskForm" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalFormTaskClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleCompanyTask"></h4>
            </div>
            <div class="modal-body">
                <form class="cmxInstallationform" role="form" id="formInstallationSale">
                    <input type="text" id="txtTaskid" hidden/>

                    <div class="row">
                        <div class="col-xs-12">
                            <label>Asignación a Perfil</label>
                            <select class="form-control" id="txtUserProfile"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Agencia</label>
                            <select class="form-control" id="txtTaskAgency"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Empleado</label>
                            <select class="form-control" id="txtTaskEmployee"></select>
                        </div>
                    </div>
                    <br/>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-7">&nbsp;</div>
                            <div class="col-xs-5">
                                <input type="hidden" id="inpIDRep">
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-danger" id="btnCancelTaskAssign">CANCELAR</button>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-success" id="btnAssign">CREAR</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade fade disable-scroll" id="formsDetails" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnFormsDetailsClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleFormsDetails"></h4>
            </div>
            <div class="modal-body" id="formsDetailsBody" style="background-color: #e6e6e6 !important;"></div>
        </div>
    </div>
</div>

<div class="modal fade fade disable-scroll" id="formSlideShow" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnformSlideShowClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleformSlideShow"></h4>
            </div>
            <div class="modal-body" id="formSlideShowBody" style="background-color: #e6e6e6 !important;"></div>
        </div>
    </div>
</div>

<?php //include("footer.php") ?>
<?php include("footerDataTables.php") ?>

<script src="assets/js/dcalendar.picker.min.js"></script>
<script type="text/javascript" src="assets/js/clases/funciones.js"></script>
<script type="text/javascript" src="assets/js/clases/loadforms.js"></script>
<script type="text/javascript" src="assets/js/clases/callejero.js"></script>
<script type="text/javascript" src="assets/js/clases/callejero.js"></script>

<script type="text/javascript">
    var base_url= "http://siscomcmg.com/uploads/";
    var string_nickname = $("#nicknameZone").html();
    
   
    ESTATUS_INSTALACION = <?php echo $estatus_instalacion; ?>;
    
    string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
    localStorage.setItem("id", string_nickname.trim());
    string_nickname = string_nickname.trim();
    var carouselProof,carouselRequest,carouselAnnouncement,carouselIdentification,carouselDebtor,carouselAgreement;
    var sellFormToValidate = "";
    var reasons = [];
    var rotate_angle = 0;
    var rotate_angle_izq = 0;
    $(document).ready(function () {
        $('#titleHeader').html('Consultas generales');
        $('#subtitle-header').html('Detalle');

        $('.limenu').removeClass('active');
        $('#gogeneral').addClass('active');

        loadMain();
        loadAgencies(localStorage.getItem("id"));
        loadStatus();
        loadProfile();

        $('#dateFrom').dcalendarpicker({format: "yyyy-mm-dd"});
        $('#dateTo').dcalendarpicker({format: "yyyy-mm-dd"});


    });

    function loadMain() {
        $('#inputdate').dcalendarpicker();
        var userLogged = localStorage.getItem("id");
        var idUser = $("#inputIdUser").val();
        $('#inputNickUserLogg').val(userLogged);
        var tipoAgencia = $("#typeAgency").val()
        //alert(idUser);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/loadForms.php",
            dataType: "JSON",
            data: {idUsuario: idUser, tipoAgencia:tipoAgencia},
            success: function (data) {
                console.log('reportes', data);
                $('#bodyReport').html('');
                var sizeData = data.length;
                if (sizeData > 0) {
                    var sizeReportes = data.length;
                    construirProcesosReporte(0, sizeReportes, data);
                } else {
                    $("#tablaLoader").html('');
                }
            }
        });
    }
    /*+++++++++++++++++++++++++++++++Delete Duplicates++++++++++++++++++++++++++++++*/
    function deleteDuplicates(arrayToClean) {
        var uniqueVals = [];
        $.each(arrayToClean, function (i, el) {
            if ($.inArray(el, uniqueVals) === -1) uniqueVals.push(el);
        });
        return uniqueVals;
    }
    /*+++++++++++++++++++++++++++++++Delete Duplicates++++++++++++++++++++++++++++++*/

    /*--------------------------------Main--------------------------------*/
    function showSecondSell(id, type, IDUs) {
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/validateAsignSecondSell.php",
            data: {idReport: id},
            dataType: "JSON",
            success: function (data) {
                var existeKey=_.has(data[0], 'validacionSegundaVenta');
                var idUser = $("#inputIdUser").val();
                var type = "Segunda Venta";
                $("#txtid").val(id);
                if (existeKey === true && parseInt(data[0].validacionSegundaVenta) > 40) {
                    //ejecutamos la carga del formulario
                    loadSecondSellForm(id,type,idUser);
                }else if (existeKey === true && parseInt(data[0].validacionSegundaVenta) === 40) {
                    //mandamos alerta de que no se puede cargar el formulario
                    MostrarToast(2, "Formulario aun no disponible", "No se puede visualizar el formulario hasta que se realice en movil");
                    return false;
                }else if(existeKey === false){
                    //ejecutamos la carga del formulario
                    loadSecondSellForm(id,type,idUser);
                }
            }
        });
    }

    function loadSecondSellForm(id,type,idUser) {
        //cargamos el formulario de segundaVenta
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/loadForm.php",
            data: {form: id, type: "Segunda Venta",idUsuario:idUser},
            dataType: "JSON",
            success: function (data) {
                console.log('data showSecondSell', data);
                var repCons,agreementNumber,nomCliente,apPat,apMat,colonia,
                    street,betweenStreets,idCountry,idState,idCity,idFormulario,
                    payment,financialService,fechaSol,nombreFinanciera='',IDEmp,
                    nicknameEmp,idAgencia,descAgencia, outterNumber;

                repCons=data[0].repCons;
                agreementNumber=data[0].agreementNumber;
                nomCliente=data[0].nomCliente;
                apPat=data[0].apPat;
                apMat=data[0].apMat;
                colonia=data[0].colonia;
                street=data[0].street;
                betweenStreets=data[0].betweenStreets;
                idCountry=data[0].idCountry;
                outterNumber=data[0].outterNumber;
                idState=data[0].idState;
                idCity=data[0].idCity;
                idFormulario=data[0].idFormulario;
                payment=data[0].payment;
                financialService=data[0].financialService;
                fechaSol=data[0].fechaSol;
                IDEmp=data[0].IDEmp;
                nicknameEmp=data[0].nicknameEmp;
                idAgencia=data[0].idAgencia;
                descAgencia=data[0].descAgencia;
                idUserAssigned=data[0].idUserAssigned;
                estatusAsignacionInstalacion=data[0].estatusAsignacionInstalacion;
                if (financialService == 1) {
                    nombreFinanciera = "AYO";
                } else {
                    nombreFinanciera = "MEX";
                }
                $('#domHiddenCity').val(idCity)
                $('#domHiddenStreet').val(street)
                $('#domHiddenColonia').val(colonia)
                $('#domHiddenStreetNumber').val(outterNumber)
                
                var domHiddenCity = $('#domHiddenCity').val();
                var domHiddenStreet = $('#domHiddenStreet').val();
                var domHiddenColonia = $('#domHiddenColonia').val();
                var domHiddenStreetNumber = $('#domHiddenStreetNumber').val();

                if((typeof(domHiddenCity) !== 'undefined' && domHiddenCity !== null && domHiddenCity !== '') &&
                   (typeof(domHiddenStreet) !== 'undefined' && domHiddenStreet !== null && domHiddenStreet !== '') &&
                   (typeof(domHiddenStreetNumber) !== 'undefined' && domHiddenStreetNumber !== null && domHiddenStreetNumber !== '') &&
                   (typeof(domHiddenColonia) !== 'undefined' && domHiddenColonia !== null && domHiddenColonia !== '')){
                    $.getScript( "assets/js/clases/domSegVenta.js" )
                    .done(function( script, textStatus ) {
                        Cities(domHiddenCity,domHiddenStreet,domHiddenStreetNumber, domHiddenColonia);
                    })
                    .fail(function( jqxhr, settings, exception ) {
                        $( "div.log" ).text( "Triggered ajaxError handler." );
                    });
                }
                /**LLENAMOS LA INFORMACION DE LA PRIMERA VENTA EN LA SEGUNDA VENTA**/
                /**TODO AQUI VA LA CARGA DE LA INFO DE LA SEGUNDA VENTA RJR*/
                var existeFormSegVta=_.has(data, 'formSegVta');
                if (existeFormSegVta === true) {
                    console.log('data.formSegVta', data.formSegVta);
                    if (data.formSegVta.length > 0) {
                        nomCliente=data.formSegVta[0].clientName_agrr;
                        apPat=data.formSegVta[0].clientlastName_agrr;
                        apMat=data.formSegVta[0].clientlastName2_agrr;
                        $("#txtNextSellPayment").val(data.formSegVta[0].payment_agrr);
                        //datos del titular
                        $("#txtNextSellCellularPhone").val(data.formSegVta[0].celullarTelephone_agrr);
                        $("#txtNextSellBithdate").val(data.formSegVta[0].clientBirthDate_agrr);
                        $("#txtCURP").val(data.formSegVta[0].clientCURP_agrr);
                        $("#txtEmail").val(data.formSegVta[0].clientEmail_agrr);
                        //data.formSegVta[0].clientIdNumber_agrr;
                        $("#txtNextSellJobActivity").val(data.formSegVta[0].clientJobActivity_agrr);
                        $("#txtNextSellEnterprise").val(data.formSegVta[0].clientJobEnterprise_agrr);
                        $("#txtNextSellJobLocation").val(data.formSegVta[0].clientJobLocation_agrr);
                        $("#txtNextSellPosition").val(data.formSegVta[0].clientJobRange_agrr);
                        $("#txtNextSellJobTelephone").val(data.formSegVta[0].clientJobTelephone_agrr);
                        $("#txtRFC").val(data.formSegVta[0].clientRFC_agrr);
                        //$("#txtEngagment").html(data.formSegVta[0].clientRelationship_agrr);
                        $('select#txtEngagment').find('option').each(function() {
                            if ($(this).text() === data.formSegVta[0].clientRelationship_agrr) {
                                $('#txtEngagment').val($(this).val());
                            }
                        });
                        $('select#txtNextSellGender').find('option').each(function() {
                            if ($(this).text() === data.formSegVta[0].clientgender_agrr) {
                                $('#txtNextSellGender').val($(this).val()).change();
                            }
                        });
                        $('select#txtNextSellIdentification').find('option').each(function() {
                            if ($(this).text() === data.formSegVta[0].identificationType_agrr) {
                                $('#txtNextSellIdentification').val($(this).val()).change();
                            }
                        });
                        $('select#txtNextSellCountry').find('option').each(function() {
                            if ($(this).text() === data.formSegVta[0].clientBirthCountry_agrr) {
                                $('#txtNextSellCountry').val($(this).val()).change();
                            }
                        });

                        $('select#txtNextStepSaleInHome').find('option').each(function() {
                            if ($(this).text() === data.formSegVta[0].inHome_agrr) {
                                $('#txtNextStepSaleInHome').val($(this).val()).change();
                            }
                        });
                        //$("#txtNextSellGender").html(data.formSegVta[0].clientgender_agrr);
                        $("#txtNextSellPhone").val(data.formSegVta[0].homeTelephone_agrr);
                        $("#txtIdCard").val(data.formSegVta[0].clientIdNumber_agrr);
                        var cont=1;
                        if(typeof(data.formSegVta[0].referencias) !== 'undefined'){
                            data.formSegVta[0].referencias.forEach(function(entry) {
                                if(cont === 1){
                                    $("#txtNombreRefrencia1").val(entry.name);
                                    $("#txtTelefonoDeTrabajoReferencia1").val(entry.jobTelephone);
                                    $("#txtTelefonoParticularRefrencia1").val(entry.telephone);
                                    $("#txtTelefonoTrabajoExtRefrencia1").val(entry.ext);
                                }else if(cont === 2){
                                    $("#txtNombreRefrencia2").val(entry.name);
                                    $("#txtTelefonoDeTrabajoReferencia2").val(entry.jobTelephone);
                                    $("#txtTelefonoParticularReferencia2").val(entry.telephone);
                                    $("#txtTelefonoTrabajoExtReferencia2").val(entry.ext);
                                }
                                cont++;
                            });
                        }
                        $('#txtNextSellDateRI').dcalendarpicker().val(data.formSegVta[0].agreementRiDate_agrr);
                        $("#txtNextSellRI").val(data.formSegVta[0].agreementRi_agrr);
                        var idClienteGenerado=data.formSegVta[0].idClienteGenerado;
                        if (typeof(idClienteGenerado) !== 'undefined' && idClienteGenerado !== '' && idClienteGenerado !== null) {
                            $('#addSecondSell').prop('disabled', true).text('SEGUNDA VENTA ENVIADA');
                        }else{
                            $('#addSecondSell').prop('disabled', false).text('ENVIAR');
                        }
                        var options = $('#txtNextStepSaleAgreegmentType option');
                        //var str=data.formSegVta[0].agreementType_agrr;
                        var idArt=data.formSegVta[0].idArt;
                        var agreementExpires_agrr=data.formSegVta[0].agreementExpires_agrr;
                        cargarTiposDeContrato(idArt, agreementExpires_agrr);
                    }
                }else{
                    var res='';
                    var pagoMes='';
                    cargarTiposDeContrato(res, pagoMes);
                }
                var zero = true,first = true,second = true,third = true,fourth = true,fifth = true,numElem = 1,elementsProof = "",itemsProof = "",elementsProofOP = "",itemsProofOP = "",elementsIdentification = "",itemsIdentification = "",elementsIdentificationOP = "",itemsIdentificationOP = "",elementsRequest = "",itemsRequest = "",elementsRequestOP = "",itemsRequestOP = "",elementsDebtor = "",itemsDebtor = "",elementsDebtorOP = "",itemsDebtorOP = "",elementsAnnouncement = "",itemsAnnouncement = "",elementsAnnouncementOP = "",itemsAnnouncementOP = "",elementsAgreement = "",itemsAgreement = "",elementsAgreementOP = "",itemsAgreementOP = "", res,nameFoto,htmlImagenes, nombreArchivo, urlArch;
                var existenImagenes=_.has(data, 'imgSolicitud');
                if (existenImagenes === true) {
                    if (data.imgSolicitud.length > 0) {
                        //var base_url= "http://siscomcmg.com/uploads/";
                        data.imgSolicitud.forEach(function(entry, idx) {
                            nameFoto=entry.name;
                            res = nameFoto.split("_");
                            res=res[0];
                            if (res == 'solicitud') {
                                if (zero) {
                                    elementsRequest += '<li data-target="#myCarousel2" data-slide-to="' + entry.name + '" class="active"></li>';

                                    itemsRequest += '<div class="item active">';
                                    itemsRequest += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-solicitud" >';
                                    itemsRequest += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="solicitud" height="256px" width="256px" style="" />';
                                    itemsRequest += '</a>';
                                    itemsRequest += '</div>';

                                    elementsRequestOP += '<li data-target="#myCarousel2OP" data-slide-to="' + entry.name + '" class="active"></li>';

                                    itemsRequestOP += '<div class="item active">';
                                    itemsRequestOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-solicitud" >';
                                    itemsRequestOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="solicitud" height="1024px" width="1248px" style="" />';
                                    itemsRequestOP += '</a>';
                                    itemsRequestOP += '</div>';

                                    zero = false;
                                } else {
                                    elementsRequest += '<li data-target="#myCarousel2" data-slide-to="' + entry.name + '"></li>';

                                    itemsRequest += '<div class="item">';
                                    itemsRequest += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-solicitud" >';
                                    itemsRequest += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="solicitud" height="256px" width="256px" style="" />';
                                    itemsRequest += '</a>';
                                    itemsRequest += '</div>';

                                    elementsRequestOP += '<li data-target="#myCarousel2OP" data-slide-to="' + entry.name + '"></li>';

                                    itemsRequestOP += '<div class="item">';
                                    itemsRequestOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-solicitud" >';
                                    itemsRequestOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="solicitud" height="1024px" width="1248px" style="" />';
                                    itemsRequestOP += '</a>';
                                    itemsRequestOP += '</div>';
                                }
                                numElem++;
                            } else if (res == 'aviso') {
                                if (first) {
                                    numElem = 1;
                                    elementsAnnouncement += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAnnouncement += '<div class="item active">';
                                    itemsAnnouncement += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-aviso" >';
                                    //itemsAnnouncement += '<img src="' + base_url + entry.name + '" alt="avisoprivacidad" height="256px" width="256px" />';
                                    itemsAnnouncement += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="avisoprivacidad" height="256px" width="256px" style="" />';
                                    itemsAnnouncement += '</a>';
                                    itemsAnnouncement += '</div>';

                                    elementsAnnouncementOP += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAnnouncementOP += '<div class="item active">';
                                    itemsAnnouncementOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-aviso" >';
                                    itemsAnnouncementOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="avisoprivacidad" height="256px" width="256px" style="" />';
                                    itemsAnnouncementOP += '</a>';
                                    itemsAnnouncementOP += '</div>';

                                    first = false;
                                } else {
                                    elementsAnnouncement += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '"></li>';

                                    itemsAnnouncement += '<div class="item">';
                                    itemsAnnouncement += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-aviso" >';
                                    itemsAnnouncement += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="avisoprivacidad" height="256px" width="256px" style="" />';
                                    itemsAnnouncement += '</a>';
                                     itemsAnnouncement += '</div>';

                                    elementsAnnouncementOP += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAnnouncementOP += '<div class="item">';
                                    itemsAnnouncementOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-aviso" >';
                                    itemsAnnouncementOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="avisoprivacidad" height="256px" width="256px" style="" />';
                                    itemsAnnouncementOP += '</a>';
                                    itemsAnnouncementOP += '</div>';
                                }
                                numElem++;
                            } else if (res == 'identificacion') {
                                if (second) {
                                    numElem = 1;
                                    elementsIdentification += '<li data-target="#myCarousel4" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsIdentification += '<div class="item active">';
                                    itemsIdentification += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-identificacion" >';
                                    itemsIdentification += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="identificacion" height="256px" width="256px" style="" />';
                                    itemsIdentification += '</a>';
                                    itemsIdentification += '</div>';

                                    elementsIdentificationOP += '<li data-target="#myCarousel4OP" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsIdentificationOP += '<div class="item active">';
                                    itemsIdentificationOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-identificacion" >';
                                    itemsIdentificationOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="identificacion" height="1024px" width="1248px" style="" />';
                                    itemsIdentificationOP += '</a>';
                                    itemsIdentificationOP += '</div>';

                                    second = false;
                                } else {
                                    elementsIdentification += '<li data-target="#myCarousel4" data-slide-to="' + numElem + '"></li>';

                                    itemsIdentification += '<div class="item">';
                                    itemsIdentification += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-identificacion" >';
                                    itemsIdentification += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="identificacion" height="256px" width="256px" style="" />';
                                    itemsIdentification += '</a>';
                                    itemsIdentification += '</div>';

                                    elementsIdentificationOP += '<li data-target="#myCarousel4OP" data-slide-to="' + numElem + '"></li>';

                                    itemsIdentificationOP += '<div class="item">';
                                    itemsIdentificationOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-identificacion" >';
                                    itemsIdentificationOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="identificacion" height="1024px" width="1248px" style="" />';
                                    itemsIdentificationOP += '</a>';
                                    itemsIdentificationOP += '</div>';
                                }
                                numElem++;
                                //} else if (FileType == 'foto_comprobante') {
                            } else if (res == 'comprobante') {
                                if (third) {
                                    numElem = 1;
                                    elementsProof += '<li data-target="#myCarousel" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsProof += '<div class="item active">';
                                    itemsProof += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-comprobante" >';
                                    itemsProof += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="comprobante" height="256px" width="256px" style="" />';
                                    itemsProof += '</a>';
                                    itemsProof += '</div>';

                                    elementsProofOP += '<li data-target="#myCarouselOP" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsProofOP += '<div class="item active">';
                                    itemsProofOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-comprobante" >';
                                    itemsProofOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="comprobante" height="1024px" width="1248px" style="" />';
                                    itemsProofOP += '</a>';
                                    itemsProofOP += '</div>';

                                    third = false;
                                } else {
                                    elementsProof += '<li data-target="#myCarousel" data-slide-to="' + numElem + '"></li>';

                                    itemsProof += '<div class="item">';
                                    itemsProof += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-comprobante" >';
                                    itemsProof += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="comprobante" height="256px" width="256px" style="" />';
                                    itemsProof += '</a>';
                                    itemsProof += '</div>';

                                    elementsProofOP += '<li data-target="#myCarouselOP" data-slide-to="' + numElem + '"></li>';

                                    itemsProofOP += '<div class="item">';
                                    itemsProofOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-comprobante" >';
                                    itemsProofOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="comprobante" height="1024px" width="1248px" style="" />';
                                    itemsProofOP += '</a>';
                                    itemsProofOP += '</div>';
                                }
                                numElem++;
                                //} else if (FileType == 'foto_solicitud') {
                            } else if (res == 'contrato') {
                                if (fourth) {
                                    numElem = 1;
                                    elementsAgreement += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAgreement += '<div class="item active">';
                                    itemsAgreement += '<a class="example-image-link" href="' +(getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name))  + '" data-lightbox="example-contrato" >';
                                    itemsAgreement += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="contrato" height="256px" width="256px" style="" />';
                                    itemsAgreement += '</a>';
                                    itemsAgreement += '</div>';

                                    elementsAgreementOP += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAgreementOP += '<div class="item active">';
                                    itemsAgreementOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-contrato" >';
                                    itemsAgreementOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="contrato" height="1024px" width="1248px" style="" />';
                                    itemsAgreementOP += '</a>';
                                    itemsAgreementOP += '</div>';

                                    fourth = false;
                                } else {
                                    elementsAgreement += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '"></li>';

                                    itemsAgreement += '<div class="item">';
                                    itemsAgreement += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-contrato" >';
                                    itemsAgreement += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="contrato" height="256px" width="256px" style="" />';
                                    itemsAgreement += '</a>';
                                    itemsAgreement += '</div>';

                                    elementsAgreementOP += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '"></li>';

                                    itemsAgreementOP += '<div class="item">';
                                    itemsAgreementOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-contrato" >';
                                    itemsAgreementOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="contrato" height="1024px" width="1248px" style="" />';
                                    itemsAgreementOP += '</a>';
                                    itemsAgreementOP += '</div>';
                                }
                                numElem++;
                                //} else if (FileType == 'foto_pagare') {
                            } else if (res == 'pagare') {
                                if (fifth) {
                                    numElem = 1;
                                    elementsDebtor += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsDebtor += '<div class="item active">';
                                    itemsDebtor += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-pagare" >';
                                    itemsDebtor += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="pagare" height="256px" width="256px" />';
                                    itemsDebtor += '</a>';
                                    itemsDebtor += '</div>';

                                    elementsDebtorOP += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsDebtorOP += '<div class="item active">';
                                    itemsDebtorOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-pagare" >';
                                    itemsDebtorOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="pagare" height="1024px" width="1248px" style="" />';
                                    itemsDebtorOP += '</a>';
                                    itemsDebtorOP += '</div>';

                                    fifth = false;
                                } else {
                                    elementsDebtor += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '"></li>';

                                    itemsDebtor += '<div class="item">';
                                    itemsDebtor += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-pagare" >';
                                    itemsDebtor += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="pagare" height="256px" width="256px" />';
                                    itemsDebtor += '</a>';
                                    itemsDebtor += '</div>';

                                    elementsDebtorOP += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '"></li>';

                                    itemsDebtorOP += '<div class="item">';
                                    itemsDebtorOP += '<a class="example-image-link" href="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" data-lightbox="example-pagare" >';
                                    itemsDebtorOP += '<img src="' + (getRutaVenta(estatusAsignacionInstalacion,financialService,fechaSol, agreementNumber,entry.name)) + '" alt="pagare" height="1024px" width="1248px" style="" />';
                                    itemsDebtorOP += '</a>';
                                    itemsDebtorOP += '</div>';
                                }
                                numElem++;
                            }
                        });

                        carouselProof = '<div id="myCarouselOP" class="carousel slide"><div class="carousel-inner">' + itemsProofOP + '</div>' + '<a class="left carousel-control" href="#myCarouselOP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarouselOP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselRequest = '<div id="myCarousel2OP" class="carousel slide"><div class="carousel-inner">' + itemsRequestOP + '</div>' + '<a class="left carousel-control" href="#myCarousel2OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselAnnouncement = '<div id="myCarousel3OP" class="carousel slide"><div class="carousel-inner">' + itemsAnnouncementOP + '</div>' + '<a class="left carousel-control" href="#myCarousel3OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselIdentification = '<div id="myCarousel4OP" class="carousel slide"><div class="carousel-inner">' + itemsIdentificationOP + '</div>' + '<a class="left carousel-control" href="#myCarousel4OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselDebtor = '<div id="myCarousel5OP" class="carousel slide"><div class="carousel-inner">' + itemsDebtorOP + '</div>' + '<a class="left carousel-control" href="#myCarousel5OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel5OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselAgreement = '<div id="myCarousel6OP" class="carousel slide"><div class="carousel-inner">' + itemsAgreementOP + '</div>' + '<a class="left carousel-control" href="#myCarousel6OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel6OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';

                        htmlImagenes ='<div class="col-xs-6">';
                        htmlImagenes += '<label>Comprobante de domicilio</label>' + '<br/>';
                        htmlImagenes += '<a onclick="showImages(1);"><div id="myCarousel" class="carousel slide"><div class="carousel-inner">' + itemsProof + '</div>' + '<a class="left carousel-control" href="#myCarousel" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>';

                        htmlImagenes += '<label>Solicitud</label>' + '<br/>';
                        htmlImagenes += '<a onclick="showImages(2);"><div id="myCarousel2" class="carousel slide"><div class="carousel-inner">' + itemsRequest + '</div>' + '<a class="left carousel-control" href="#myCarousel2" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>';

                        htmlImagenes += '<label>Aviso de privacidad</label>' + '<br/>';
                        htmlImagenes += '<a onclick="showImages(3);"><div id="myCarousel3" class="carousel slide"><div class="carousel-inner">' + itemsAnnouncement + '</div>' + '<a class="left carousel-control" href="#myCarousel3" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>';

                        htmlImagenes += '</div>';

                        htmlImagenes += '<div class="col-xs-6">';

                        htmlImagenes += '<label>Identificaci&oacute;n</label>' + '<br/>';
                        htmlImagenes += '<a onclick="showImages(4);"><div id="myCarousel4" class="carousel slide"><div class="carousel-inner">' + itemsIdentification + '</div>' + '<a class="left carousel-control" href="#myCarousel4" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>';
                        htmlImagenes += '<label>Pagar&eacute;</label>' + '<br/>';
                        htmlImagenes += '<a onclick="showImages(5);"><div id="myCarousel5" class="carousel slide"><div class="carousel-inner">' + itemsDebtor + '</div>' + '<a class="left carousel-control" href="#myCarousel5" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel5" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                        htmlImagenes += '<label>Contrato</label>' + '<br/>';
                        htmlImagenes += '<a onclick="showImages(6);"><div id="myCarousel6" class="carousel slide"><div class="carousel-inner">' + itemsAgreement + '</div>' + '<a class="left carousel-control" href="#myCarousel6" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel6" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                        + '</div>';
                    }
                    $('#detalleImagenes').html('');
                    $('#detalleImagenes').append(htmlImagenes);
                }

                $("#segundaVentaFinanciada").html(nombreFinanciera);
                $("#financialService").val(financialService);
                $("#idAgenciaHid").val(idAgencia);
                $("#txtConsecutive").prop('disabled', true).val(idFormulario);
                $("#txtNextSellAgency").prop('disabled', true).val(descAgencia);
                $("#txtAgreement").prop('disabled', true).val(agreementNumber);
                $("#txtRequestDate").prop('disabled', true).val(fechaSol);
                $("#txtName").val(nomCliente);
                $("#txtLastName1").val(apPat);
                $("#txtLastName2").val(apMat);
                $("#inputUserSegundaVenta").val(idUserAssigned);
                $('#txtNextStepSaleColonia').empty().append('whatever');
                $('#txtNextStepSaleStreet').empty().append('whatever');
                $('#txtNextStepSaleCity').empty().append('whatever');

                $('#txtRequestDate').dcalendarpicker({format: "yyyy-mm-dd"});
                $('#txtNextSellDateRI').dcalendarpicker({format: "yyyy-mm-dd"});
                $('#txtNextSellBithdate').dcalendarpicker({format: "yyyy-mm-dd"});
                var userLogged = localStorage.getItem("id");
                if (userLogged === 'SuperAdmin') {
                    $('#addSecondSell').text('ENVIAR').show();
                }else{
                    $('#addSecondSell').text('GUARDAR').show();
                }
                /*if (data[0].validacionSegundaVenta !== '' && 
                    data[0].validacionSegundaVenta !== null  && 
                    typeof(data[0].validacionSegundaVenta) !== 'undefined') {
                }else{
                   MostrarToast(2, 'Este reporte ya se encuentra asignado'); 
                }*/
                /***ESTABLECEMOS EL NUMERO DE REFERENCIAS EN 2 POR DEFAULT*/
                window.numeroReferenciaActual = 3;
            }
        });
        $('#titlesecondSellModal').html('');
        $('#titlesecondSellModal').append("Captura de Segunda Venta");
        $('#secondSellModal').modal('show');
    }

    $('.lb-rotder').click(function(){
        rotate_angle = ( rotate_angle == 360 ) ? 0 : rotate_angle + 10;
        $('.lb-outerContainer').rotate({ angle : rotate_angle });
    });

    $('.lb-rotizq').click(function(){
        rotate_angle = ( rotate_angle == 360 ) ? 0 : rotate_angle - 10;
        $('.lb-outerContainer').rotate({ angle : rotate_angle });
    });
    
    function validateSoloNumeros(e){
        var key = window.Event ? e.which : e.keyCode;
        return ((key >= 48 && key <= 57) || (key==8));
    }
    function soloLetras(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key).toLowerCase();
       letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
    //btnValidarVenta
    function cargarTiposDeContrato(idArt, pagoMes) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/ObtenerCatalogoTiposDeContrato.php",
            data: {},
            dataType: "JSON",
            success: function (data) {
                //alert(data);
                console.log('data contrato', data);
                var sizeData = data.length;
                window.arrayPrecio = []; //precio
                window.arrayPlazo = []; //plazo
                window.arrayPagos = []; //pagos
                var i = 0;
                //Math.round(num * 100) / 100
                $('#txtNextStepSaleAgreegmentType').empty().append('whatever');
                $("#txtNextStepSaleAgreegmentType").append(
                    '<option value="0">Selecciona un tipo</option>'
                );
                if (sizeData > 0) {
                    for (i = 0; i < sizeData; i++) {
                        $("#txtNextStepSaleAgreegmentType").append(
                            '<option value="' + data[i].idContrato + '">' + data[i].tipoDeContrato + '</option>'
                        );
                        //$('#plazos').val(data[i].plazo);
                        window.arrayPrecio[data[i].idContrato]=data[i].precio;
                        window.arrayPlazo[data[i].idContrato]=data[i].plazo;
                        window.arrayPagos[data[i].idContrato]=data[i].pagos;
                    }

                    if ((typeof(idArt) !== 'undefined' && idArt !== null && idArt !== '') &&
                        (typeof(pagoMes) !== 'undefined' && pagoMes !== null && pagoMes !== '')) {
                        var tipoContrato;
                        $('select#txtNextStepSaleAgreegmentType').find('option').each(function() {
                            if (parseInt($(this).val()) === parseInt(idArt)) {
                                $('#txtNextStepSaleAgreegmentType').val($(this).val());
                                calculoTipoDeContratoEnFormularioSegundaVenta($(this).val(), pagoMes);
                            }
                        });
                    }
                } else {
                }
            }
        });
    }

    $("#txtNextStepSaleAgreegmentType").on("change", function (event) {
        var idCatalogo = $("#txtNextStepSaleAgreegmentType").val();
        calculoTipoDeContratoEnFormularioSegundaVenta(idCatalogo);
    });
    $("#nextSellPaymentTime").on("change", function (event) {
        var idPlazo = $("#nextSellPaymentTime").val();
        $('#txtNextSellMonthlyCost').val(idPlazo).change();
    });

    $(document).on('change', '#txtNextStepSaleCity', function () {
        var city = $("#txtNextStepSaleCity").val();
        $("#txtNextStepSaleColonia").html("");
        $("#txtNextStepSaleStreet").html("");
        $("#txtNextStepSaleStreetNumber").html("");
        console.log('city', city);
        if (parseInt(city) !== 0) {
            $.getScript( "assets/js/clases/domSegVenta.js" )
            .done(function( script, textStatus ) {
                Colonias(city);
            })
            .fail(function( jqxhr, settings, exception ) {
                $( "div.log" ).text( "Triggered ajaxError handler." );
            });
            //loadColonias(city);
        } else {
            alert("No Hay Ciudad Seleccionada");
        }
    });
    
    $("#txtNextStepSaleColonia").on("change", function (event) {
        $("#txtNextStepSaleStreetNumber").html("");
        var idCity = $("#txtNextStepSaleCity").val();
        var idColonia = $("#txtNextStepSaleColonia").val();
        if (parseInt(idCity) !== 0 && parseInt(idColonia) !== 0) {
            $.getScript( "assets/js/clases/domSegVenta.js" )
            .done(function( script, textStatus ) {
                Streets();
            })
            .fail(function( jqxhr, settings, exception ) {
                $( "div.log" ).text( "Triggered ajaxError handler." );
            });
        } else {
            alert("No Hay Ciudad Seleccionada");
        }
    });
    
    $("#txtNextStepSaleStreet").on("change", function (event) {
        //console.log('txtNextStepSaleColonia change', event);
        if (window.arrayNumCalle.length > 0) {
            var txtNextStepSaleStreet = $("#txtNextStepSaleStreet").val();
            $('#txtNextStepSaleStreetNumber').html('');
            _.each(window.arrayNumCalle, function (row, idx) {
                if (row.calle === txtNextStepSaleStreet) {
                    $('#txtNextStepSaleStreetNumber').append('<option value="' + row.id_direccion + '">' + row.numero_exterior + '</option>');
                }
            });
        }
    });

    function calculoTipoDeContratoEnFormularioSegundaVenta(idCatalogo, pMes) {
        $("#txtNextSellPrice").val(window.arrayPrecio[idCatalogo].toFixed(2));
        var plazosArr = new Array();
        var pagosArr = new Array();
        plazosArr = window.arrayPlazo[idCatalogo].split(",");
        pagosArr=window.arrayPagos[idCatalogo].split(",");
        $('#txtNextSellMonthlyCost').empty().append('whatever');
        $('#nextSellPaymentTime').empty().append('whatever');
        for (a in plazosArr ) {
            $("#nextSellPaymentTime").append(
                '<option value="'+a+'">'+plazosArr[a]+'</option>'
            );
        }
        if (typeof(pMes) !== 'undefined' && pMes !== null && pMes !== '') {
            $('select#nextSellPaymentTime').find('option').each(function() {
                if ($(this).text() === pMes) {
                    console.log($(this).val());
                    $('#nextSellPaymentTime').val($(this).val()).change();
                }
            });
        }
        for (b in pagosArr ) {
            $("#txtNextSellMonthlyCost").append(
                '<option value="'+b+'">'+pagosArr[b]+'</option>'
            );
        }
        var selectPMes=$( "#nextSellPaymentTime option:selected" ).val();
        if (typeof(selectPMes) !== 'undefined' && selectPMes !== null && selectPMes !== '') {
            $('select#txtNextSellMonthlyCost').find('option').each(function() {
                if ($(this).val() === selectPMes) {
                    console.log($(this).val());
                    $('#txtNextSellMonthlyCost').val($(this).val()).change();
                }
            });
        }
    }

    /*function agregarMasReferencias() {
        var posicionReferencia = window.numeroReferenciaActual;
        var masreferencias =
            '<div class="col-xs-6">' +
            '<label>Nombre referencia ' + posicionReferencia + '</label>' +
            '<input type="text" id="txtNextSellReference' + posicionReferencia + '" name="nextSellReference' + posicionReferencia + '"' +
            'class="form-control input-sm">' +
            '<label>Tel&eacute;fono de trabajo referencia ' + posicionReferencia + '</label>' +
            '<input type="text" id="txtNextSellReference1Telephone"' +
            'name="nextSellReference1Telephone" class="form-control input-sm">' +
            '<br/>' +
            '</div>' +

            '<div class="col-xs-6">' +
            '<br/><br/>' +
            '<label>Tel&eacute;fono particular referencia ' + posicionReferencia + '</label>' +
            '<input type="text" id="txtNextSellReferenceTelephone"' +
            'name="nextSellReferenceTelephone" class="form-control input-sm">' +
            '<label>Extensi&oacute;n referencia' + posicionReferencia + ' </label>' +
            '<input type="text" id="txtNextSellReferenceTelephoneExt"' +
            'name="nextSellReferenceTelephoneExt"' +
            'class="form-control input-sm">' +
            '<br/>' +
            '</div>';

        $("#informacionFinanciamientoReferencias").append(masreferencias);
        window.numeroReferenciaActual = window.numeroReferenciaActual + 1;
    }*/

    function buscarPorTipo() {
        
        var estatus = [
           
                '<option>EN PROCESO</option>' +
                '<option>COMPLETO</option>',
            
                '<option>EN PROCESO</option>' +
                '<option>COMPLETO</option>' +
                '<option>REAGENDADA</option>' ,
            
                '<option>POR ASIGNAR</option>' +
                '<option>EN PROCESO</option>' +
                '<option>PENDIENTE</option>' +
                 '<option>RECHAZADO</option>' +
                '<option>CAPTURA COMPLETADA</option>' +
                '<option>VALIDADO POR AYOPSA</option>' +
                '<option>VALIDADO POR MEXICANA</option>' +
                '<option>VALIDACIONES COMPLETAS</option>' 
                ,
        
                '<option>EN PROCESO</option>' +
                '<option>COMPLETO</option>' +
                '<option>REAGENDADA</option>' ,
        
        
                '<option>EN PROCESO</option>' +
                '<option>COMPLETO</option>' +
                '<option>REAGENDADA</option>' 
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
        var table = $('#tablaReporte').DataTable();

        if (status != 'Todos los estatus') {
            table
                .columns(5)
                .search('^'+status+'$', true)
                .draw();
        } else {
            table.search( '' ).columns(4).search( '' ).draw();
        }

    }

    
 
    // #myInput is a <input type="text"> element
    $('#txtType').on( 'change', function () {
        var estatus = [
           
                '<option>EN PROCESO</option>' +
                '<option>COMPLETO</option>',
            
                '<option>EN PROCESO</option>' +
                '<option>COMPLETO</option>' +
                '<option>REAGENDADA</option>' ,
            
                '<option>POR ASIGNAR</option>' +
                '<option>EN PROCESO</option>' +
                '<option>PENDIENTE</option>' +
                 '<option>RECHAZADO</option>' +
                '<option>CAPTURA COMPLETADA</option>' +
                '<option>VALIDADO POR AYOPSA</option>' +
                '<option>VALIDADO POR MEXICANA</option>' +
                '<option>VALIDACIONES COMPLETAS</option>' 
                ,
        
                '<option>EN PROCESO</option>' +
                '<option>COMPLETO</option>' +
                '<option>INSTALACION ENVIADA</option>' +
                '<option>REAGENDADA</option>' ,
        
        
                '<option>EN PROCESO</option>' +
                '<option>COMPLETO</option>' +
                '<option>REVISION SEGUNDA CAPTURA</option>' +
                '<option>REAGENDADA</option>' 
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

        var table = $('#tablaReporte').DataTable();
        var buscar= $("#txtType option:selected").text();
        console.log('this.value', this.value);
        if (parseInt(this.value) > 0) {
            console.log('tds');
            if (parseInt(this.value) === 2) {
                buscar = 'Plomero';
            } 
            table.columns(4).search('^'+buscar+'$', true).draw();
        }else if (parseInt(this.value) === 0) {
            console.log('tods');
            table.search('').columns().search( '' ).draw();
        }
    } );
    var arrBoton=[]
    function construirProcesosReporte(posicionActual, totalPosiciones, dataLoadFormI) {
        var tipoAgencia = $("#typeAgency").val(),
            body = "",
            idClienteGenerado,
            idReporte,
            tipoReporte,
            status,
            dataLoadForm,
            id,
            contrato,
            municipio,
            colonia,
            calle,
            numeroD,
            usuario,
            agencia,
            fecha,
            promise,
            permisosDelProceso,
            estatusColores,
            botonAsignarTarea,
            idReportType;
            procesosReporte = "";
        var arrObjDatos=[],myFailure;
        console.log('dataLoadFormI', dataLoadFormI);
        _.each(dataLoadFormI, function(data, idx) {
            idClienteGenerado = data.idClienteGenerado;
            idClienteGenerado=(idClienteGenerado === '' || typeof(idClienteGenerado) === 'undefined' || idClienteGenerado === null) ? '' : idClienteGenerado;
            idReporte = data.Id;
            tipoReporte = data.Tipo;
            status = data.Status;
            idReportType = data.idReportType;
            body += '<tr data-id="'+idReporte+'" id="form' + idReporte + '">' +
                    '<td class="permisos">' + data.html.permisosDelProceso + '</td>';
            if (data.Status === "EN PROCESO" && data.Usuario === "Pendiente de Asignar" && 
                (localStorage.getItem("id") !== "SuperAdmin" && localStorage.getItem("id") !== "AYOPSA" && localStorage.getItem("id") !== "CallCenter")) {
                body += '<td>';
                    body += '<div class="checkbox" data-id="'+idReporte+'" style="display:none"> ';
                        body += '<label>';
                            body += '<input type="checkbox" class="asignarUsuario" name="asignarUsuario" data-id="'+idReporte+'">';
                            body += '<i class="fa fa-user" aria-hidden="true"></i>';
                        body += '</label>';
                    body += '</div>';
                body += '</td>' ;
            }else{
                body += '<td>';
                body += '</td>' ;
            }
            body += '<td class="idCliente">' + idClienteGenerado + '</td>';
            body +='<td class="contrato">' + data.Contrato + '</td>';
            body += '<td class="tipoReporte">' + data.Tipo + '</td>';
            body += '<td class="status">' + data.html.estatusColores + '</td>';
            body += '<td class="mun">' + data.Municipio + '</td>';
            body += '<td class="col">' + data.Colonia + '</td>';
            body += '<td class="calle">' + data.Calle+' - Num: '+data.Numero + '</td>';
            body += '<td class="usuario">' + data.Usuario + '</td>';
            body += '<td class="agencia">' + data.Agencia + '</td>';
            body += '<td class="fecha">' + data.Fecha + '</td>' ;
            if(tipoAgencia != "CallCenter"){
                body += '<td>' + data.html.botonAsignarTarea + '</td></tr>';
            }else{
                body += '<td></td></tr>';
            }
        });
        $("#tablaReporte tbody").append(body);
        $('#tablaLoader').html('');
        $("#tablaReporte").DataTable().destroy();
        $("#tablaReporte").DataTable({
            "order": [[11, 'desc']],
            "columnDefs": [
                {"orderable": true, "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]}
            ],
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
            },
            "deferRender": true
        });
        /*var checkboxes = $("#tablaReporte input[type=checkbox][name=asignarUsuario]");
        console.log('checkboxes', checkboxes);
        $(checkboxes).on("click", function() {
            console.log('checkbox');
            var checkedState = this.checked
            var row="", idRPTr=0;
            row=$(this).parents("tr");
            idRPTr=row.data('id');
            console.log('checkedState', checkedState);
            if (checkedState) {
                loadEmployeesInstallation(idRPTr)
            }else{
                $("#asignarUsuario-"+idRPTr).hide();
            }
        });*/
    }

    $('#tablaReporte').on('click', '.seleccionarChecks:not(:disabled)', function(e) {
        var checkboxes = $("#tablaReporte input[type=checkbox][name=asignarUsuario]");
        if (checkboxes.length > 0) {
            var isChecked = $('input.seleccionarChecks').is(':checked');
            if (isChecked) {
                var row="", idRPTr=0;
                $("#tablaReporte .checkbox").show();
                loadEmployeesInstallation(localStorage.getItem("id"));
            }else{
                $("#tablaReporte .checkbox").hide();
                $(".select").hide();
            }
        }
    });

    function loadEmployeesInstallation(agencia) {
        console.log('reportID', agencia);
        if (agencia !== "") {
            $.ajax({
                method: "GET",
                url: "dataLayer/callsWeb/getDInstalacionMasiva.php",
                data: {
                    'agencia':localStorage.getItem("id"),
                },
                dataType: "JSON",
                success: function (data) {
                    console.log('loadEmployeesInstallation', data);
                    if(data.length > 0){
                        tipo=data[0].tipo;
                        $("#asignarUsuario").empty().append('whatever');
                        $("#asignarUsuario").append($('<option>', {
                            value: 0,
                            text: "Seleccionar"
                        }));
                        _.each(data, function (row, idx) {
                            $("#asignarUsuario").append('<option value="' + row.IDEmp + '">' + row.nicknameEmp + '</option>');
                        });
                        $(".select").show();
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    //alert('request failed');
                }
            });
        }
    }

    $('#tablaReporte').on('click', '#asignacionMasiva:not(:disabled)', function(e) {
        e.preventDefault();
        console.log('e', e);
        $("#asignacionMasiva").prop("disabled", true);
        var $checkboxes = $("#tablaReporte input[type=checkbox][name=asignarUsuario]");
        var row="", idRPTr=0, idEmployee = 0, arrInstalaciones=[];
        $checkboxes.each(function() {
            if (this.checked === true) {
                row=$(this).parents("tr");
                idRPTr=row.data('id');
                idEmployee = $("#asignarUsuario").val();
                if (parseInt(idEmployee) > 0) {
                    msg = 'Se ha asignado una solicitud de Instalacion';
                    employeeProfile = '4';
                    arrInstalaciones.push({
                        'agencia':localStorage.getItem("id"),
                        'empleado':idEmployee,
                        'idReporte':idRPTr,
                    });
                }
            }
        });
        if (arrInstalaciones.length > 0) {
            generarAsignacionMasiva(arrInstalaciones);
        }else{
            MostrarToast(2, 'Info', "No se han seleccionado Instalaciones para asignar..");
            $("#asignacionMasiva").prop("disabled", false);
        }
    });

    function generarAsignacionMasiva(arrInstalaciones){
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/assignReportMasivo.php",
            dataType: "JSON",
            data: {
                arrInstalaciones: arrInstalaciones
            },
            success: function (data) {
                console.log('data', data);
                var contador=0
                var msg =  "Se ha asignado una instalacion";
                _.each(data, function (row) {
                    if (parseInt(row.code) === 200) {
                        $.getScript( "assets/js/clases/notificaciones.js" )
                        .done(function( script, textStatus ) {
                            notificacionPlomeriaSecondSell(row.idEmpleado,msg);
                            console.log('textStatus', textStatus);
                        })
                        .fail(function( jqxhr, settings, exception ) {
                            $( "div.log" ).text( "Triggered ajaxError handler." );
                        });
                        contador++;
                    }
                });
                console.log('contador', contador);
                if (contador > 0) {
                    var texto = "Se asignaron "+contador+" Instalaciones";
                    MostrarToast(1, texto, data.result);
                    configurarToastCentrado();
                    //loadMain();
                    window.location='forms.php';
                    $("#asignacionMasiva").prop("disabled", false);
                }
            }, error: function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.status);
                //alert(thrownError);
            }
        });
    }

    function filtrarPorFechas() {
        var fechainicial = moment($('#dateFrom').val()).format("YYYY-MM-DD HH:mm:ss");
        var fechafinal = moment($('#dateTo').val()).format("YYYY-MM-DD HH:mm:ss");
        var oTable = $("#tablaReporte").dataTable();
        if (fechainicial === "" && fechafinal === "") {
            MostrarToast(2, "Rango fechas erroneo", "Las fechas no pueden ir vacias");
        }
        else if (fechainicial != "" && fechafinal === "") {
            MostrarToast(2, "Rango fechas erroneo", "La fecha final no puede ir vacia");
        }
        else if (fechafinal != "" && fechainicial === "") {
            MostrarToast(2, "Rango fechas erroneo", "La fecha inicial no puede ir vacia");
        }
        else if (fechainicial > fechafinal) {
            MostrarToast(2, "Rango fechas erroneo", "La fecha inicial no puede ser mayor a la fecha final");
        } else if (fechafinal < fechainicial) {
            MostrarToast(2, "Rango fechas erroneo", "La fecha final no puede ser mayor a la fecha final");
        } else {
            oTable.fnDraw();
        }

    }
    //ESTE PLUGIN SE INVOCA AL MOMENTO DE ESTAR INSERTANDO LA INFORMACION DE LA TABLA
    $.fn.dataTableExt.afnFiltering.push(
        
        function (settings, data, iDataIndex) {
            var iFini = $('#dateFrom').val();
            var iFfin = $('#dateTo').val();
            var fecData = moment(data[11]).format('YYYY-MM-DD');
            if((typeof(iFini) !== 'undefined' && iFini !== null && iFini !== '')){
            if ((moment(fecData).isSame(iFini) || moment(fecData).isAfter(iFini)) && 
                (moment(fecData).isSame(iFfin) || moment(fecData).isBefore(iFfin)) ) {
                return true;
            }else {
                return false;
            }
            }else{
                return true;
            }
        }
    );

    
    function asignarTarea(id) {
        console.log('localStorage.getItem(id)', localStorage.getItem("id"));
        var nicknamAgencia=localStorage.getItem("id");
        var idReport = 0;
        idReport = id;
        $('#taskForm').modal('show');
        var profile='';
        
        //validamos si la agencia es solamente instaladora o si es instaladora comercializadora
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/getTipoAgencia.php",
            data: {nicknamAgencia: nicknamAgencia},
            dataType: "JSON",
            success: function (data) {
                console.log('getTipoAgencia', data);
                $('#titleCompanyTask').html('Asignar Tarea');
                $('#btnAssign').html('ASIGNAR');
                $('#inpIDRep').val(idReport);
                if (data.response[0].tipoAgencia === 'Instalacion' || data.response[0].tipoAgencia === 'Instalacion y Comercializadora') {
                    //cargamos la informacion de instalacion
                    loadInstalacion(idReport, profile);
                }else{
                    //generamos el proceso normal del modal
                    loadPlomeria(idReport, profile);
                }
            }
        });
    }
    function loadPlomeria(reportID, profile) {
        var tipo, nicknamAgencia=localStorage.getItem("id");
        //loadProfile();
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/getDataPlomeria.php",
            data: {
                id: reportID,
            },
            dataType: "JSON",
            success: function (data) {
                //obtenemos los datos de el reporte con plomeria
                if(data.length > 0){
                    //seteamos los inputs
                    tipo=data[0].tipo;
                    if(tipo === 'Plomero'){
                        console.log('data plom', data);
                        //$('#txtUserProfile').prop('disabled', true);
                        $('#txtUserProfile').html('');
                        $('#txtUserProfile').append('<option value="0">----Selecciona----</option><option value="2">Segunda Venta</option>');

                        $('#formInstallationSale').trigger("reset");
                        $('#txtTaskAgency').empty().append('whatever');
                        $('#txtTaskEmployee').empty().append('whatever');
                        $('#txtUserProfile').prop('disabled', false);
                        $("#txtUserProfile").val(2);
                        $("#txtUserProfile").change();
                        $('#txtTaskAgency').append($('<option>', {
                            value: data[0].idAgencia,
                            text: data[0].descAgencia
                        }));
                        $('#txtTaskEmployee').append($('<option>', {
                            value: data[0].IDEmp,
                            text: data[0].nicknameEmp
                        }));

                        //loadInstalacion(idReport, profile);

                        $.ajax({
                            method: "GET",
                            url: "dataLayer/callsWeb/getTipoAgencia.php",
                            data: {nicknamAgencia: nicknamAgencia},
                            dataType: "JSON",
                            success: function (data) {
                                console.log('getTipoAgencia', data);
                                $('#titleCompanyTask').html('Asignar Tarea');
                                $('#btnAssign').html('ASIGNAR');
                                $('#inpIDRep').val(reportID);
                                if (data.response[0].tipoAgencia === 'Instalacion y Comercializadora') {
                                    setTimeout(function(){ 
                                        loadInstalacion(reportID, profile); 
                                    }, 500);
                                }
                            }
                        });
                    }else{
                        $('#btnAssign').show();
                        obtenerAgencias(profile);
                    }
                }else{
                    if (profile !== '') {
                        $('#btnAssign').show();
                        obtenerAgencias(profile);
                    }
                }
                // console.log('data plom', data);
                return data;
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('request failed');
                console.log(textStatus);
            }
        });
    }

    function loadInstalacion(reportID, profile) {
        var tipo;
        //loadProfile();
        console.log('loadInstalacion');
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/getDataInstalacion.php",
            data: {
                id: reportID,
            },
            dataType: "JSON",
            success: function (data) {
                console.log('data instlacion', data);
                if(data.length > 0){
                    tipo=data[0].tipo;
                    console.log('data instlacion', data);
                    $('#txtUserProfile').empty().append('whatever');
                    $('#txtUserProfile').append($('<option>', {
                        value: "4",
                        text: "Instalación"
                    }));
                    $('#formInstallationSale').trigger("reset");
                    $('#txtTaskAgency').empty().append('whatever');
                    $('#txtTaskEmployee').empty().append('whatever');
                    $('#txtUserProfile').prop('disabled', false);
                    $('#txtTaskAgency').append($('<option>', {
                        value: data[0].idAgencia,
                        text: data[0].descAgencia
                    }));

                    for (var value in data)
                    {
                        $('#txtTaskEmployee').append($('<option>', {
                            value: data[value].IDEmp,
                            text: data[value].nicknameEmp
                        }));
                        console.log('data[value].nicknameEmp', data[value].nicknameEmp);
                        if (data[value].nickname2 !== 'Pendiente de Asignar') {
                            $('#btnAssign').hide();
                        }else{
                            $('#btnAssign').show();
                        }
                    }
                    
                }
                return data;
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('request failed');
                console.log(textStatus);
            }
        });
    }

    $('#btnCancelTaskAssign').click(function () {
        $('#formInstallationSale').trigger("reset");
        $('#txtTaskAgency').empty().append('whatever');
        $('#txtTaskEmployee').empty().append('whatever');
        $('#txtUserProfile').prop('disabled', false);
        $('#btnAssign').show();
    });
    $('#pictureInformation').click(function () {
        $('#detalleImagenes').prop('style', 'height:800px');
        $('#nextSellTab1').removeClass("active");
    });

    $("#btnModalFormClose").click(function (){
        console.log('btnModalFormClose');
        $(':input','#agreementInformation')
         .not(':button, :submit, :reset, :hidden')
         .val('')
         .removeAttr('checked')
         .removeAttr('selected');

        $(':input','#formSecondSell')
         .not(':button, :submit, :reset, :hidden')
         .val('')
         .removeAttr('checked')
         .removeAttr('selected');
    });
    function tareaDeshabilitada() {
        MostrarToast(2, 'Tarea deshabilitada', 'No es posible asignarle una tarea a este reporte.');
    }

    function mensajeToastFormularioAunNoDisponible() {
        //configurarToastCentrado();
        MostrarToast(2, "Formulario aun no disponible", "Todavia no se puede visualizar este formulario hasta completar los procesos anteriores.");
    }

    function mensajeToastBotonTareaAsignarNoDisponible() {
        //configurarToastCentrado();
        MostrarToast(2, "Asignar tarea no disponible", "No es posible asignar una tarea para este proceso.");
    }

    function loadStatus() {

        $('#txtStatus').html('');
        $('#txtStatus').append('<option>Todos los estatus</option>');
    }

    function loadProfile() {
        console.log('loadProfile');
        $('#txtUserProfile').html('');
        $('#txtUserProfile').append('<option value="0">----Selecciona----</option>' +
            '<option value="1">Plomer&iacute;a</option><option value="2">Segunda Venta</option>');
    }



    /**BOTONES QUE VALIDAN LOS CHECKBOX PARA SABER SI YA TODOS FUERON PRESIONADOS***/
    function checarRechazados() {

        var trustedHome;
        var requestImage;
        var privacyAdvice;
        var identificationImage;
        var payerImage;
        var agreegmentImage;
        var financieraValidate;

        if ($("#trustedImage").is(':checked')) {
            trustedHome = true;
        } else {
            trustedHome = false;
        }
        if ($("#requestImage").is(':checked')) {
            requestImage = true;
        } else {
            requestImage = false;
        }
        if ($("#privacyImage").is(':checked')) {
            privacyAdvice = true;
        } else {
            privacyAdvice = false;
        }
        if ($("#identificationImage").is(':checked')) {
            identificationImage = true;
        } else {
            identificationImage = false;
        }
        if ($("#payerImage").is(':checked')) {
            payerImage = true;
        } else {
            payerImage = false;
        }
        if ($("#agreegmentImage").is(':checked')) {
            agreegmentImage = true;
        } else {
            agreegmentImage = false;
        }
        if ($("#financieraValidate").is(':checked')) {
            financieraValidate = true;
        } else {
            financieraValidate = false;
        }
        // alert(trustedHome + requestImage + privacyAdvice + identificationImage + payerImage + agreegmentImage);
        if (trustedHome && requestImage && privacyAdvice && identificationImage && payerImage && agreegmentImage && financieraValidate) {
            console.log('todos');
            $("#divMotivosRechazo").addClass('hidden');
        } else {
            console.log('no todos');
            $("#divMotivosRechazo").removeClass('hidden');
        }
    }


    $('#limpiarFiltros').click(function () {
        window.location.replace("forms.php");

    });

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
    /*--------------------------------Main--------------------------------*/



    /*--------------------------------Load and Send Sell--------------------------------*/
    $('#btnAddVenta').click(function () {
        $('#titleCompany').html('Nueva Venta');
        $("#txtStreet").val('');
        $("#txtRoads").val('');
        $("#txtAddressNew").val('');
        $("#txtNumber").val('');
        $("#txtLevel").val('');
        loadCities();
        $('#modalform').modal('show');
    });
    $('#btnCancelSell').click(function () {
        $('#modalform').modal('hide');
        $('#titleCompany').html('');
        $("#txtStreet").val('');
        $("#txtRoads").val('');
        $("#txtAddressNew").val('');
        $("#txtNumber").val('');
        $("#txtLevel").val('');
        $("#txtCP").val('');
        $("#txtRole").val('');
    });

    $('#btnCreateSell').click(function () {
        var city = $("#txMun option:selected").text();
        if (city == "") {
            //Activar toast para el aviso de llenado en los campos correspondientes
        }
        var col = $("#txtCol option:selected").text();
        var street = $("#txtStreet option:selected").text();
        var roads = $("#txtRoads option:selected").text();
        var number = $("#txtNumber option:selected").text();
        number = number.trim();

        if (street == null || street == "") {
            configurarToastCentrado();
            MostrarToast(2, "Ingresar Dirección", "Para crear una venta es necesario ingresar la Calle del Domicilio.");
        } else {
            var level = $("#txtLevel").val();
            var addressNew = $("#txtAddressNew").val();
            var middleStreet = $("#txtMiddleStreet").val();
            var agency = $("#txtAgency").val();
            var cp = $("#txtCP").val();
            var users = $("#txtUsers").val();

            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/createReport.php",
                data: {
                    id: localStorage.getItem("id"), city: city,
                    col: col, street: street,
                    roads: roads, number: number,
                    level: level, agency: agency,
                    cp: cp, users: users
                },
                dataType: "JSON",
                success: function (data) {
                    JsNotificaciones.insertarNotificacion();
                    $("#txMun").html('');
                    $("#txtCol").html('');
                    $("#txtStreet").html('');
                    $("#txtRoads").html('');
                    $("#txtNumber").html('');
                    $("#txtAgency").html();
                    $("#txtCP").html();
                    $('#modalform').modal('hide');
                    configurarToastCentrado();
                    MostrarToast(1, "Venta Creada", "La creación de Venta y notificación de la asignación de la misma se realizó con éxito");
                   window.location='forms.php';
                    //loadMain(); 
                }, error: function (response) {
                    //alert(xhr.status);
            }
            });
        }
    });

    function secondSell() {
        //deshabilitamos el boton de enviar 
        var id = $('#txtid').val(),
            Consecutive = $("input[name='consecutive']").val(),
            agency = $("#idAgenciaHid").val(),            
            NextSellPayment = $("input[name='nextSellPayment']").val(),
            Agreement = $("input[name='Agreement']").val(),
            RequestDate = $("input[name='txtRequestDate']").val(),
            LastName1 = $("input[name='ClientLastName1']").val(),
            LastName2 = $("input[name='ClientLastName2']").val(),
            Name = $("input[name='ClientName']").val(),
            RFC = $("input[name='ClientRFC']").val(),
            CURP = $("input[name='ClientCURP']").val(),
            Email = $("input[name='ClientEmail']").val(),        
            Engagment = $("#txtEngagment option:selected").html(),
            NextSellGender = $("#txtNextSellGender option:selected").html(),
            IdCard = $('#txtIdCard').val(),
            NextSellIdentification = $("#txtNextSellIdentification option:selected").html(),
            NextSellBirthdate = $("input[name='NextSellBirthdate']").val(),
            NextSellCountry = $("#txtNextSellCountry option:selected").html(),
            NextStepSaleState = $('#txtNextStepSaleState option:selected').html(),
            NextStepSaleCity = $('#txtNextStepSaleCity option:selected').html(),
            NextStepSaleColonia = $('#txtNextStepSaleColonia option:selected').html(),
            NextStepSaleStreet = $('#txtNextStepSaleStreet option:selected').html(),
            NextStepSaleInHome = $('#txtNextStepSaleInHome option:selected').html(),            
            NextSellPhone = $("input[name='txtNextSellPhone']").val(),
            NextSellCellularPhone = $("input[name='NextSellCellularPhone']").val(),
            NextSellEnterprise = $("input[name='NextSellEnterprise']").val(),
            NextSellPosition = $("input[name='NextSellPosition']").val(),
            NextSellJobTelephone = $("input[name='NextSellJobTelephone']").val(),
            NextSellJobLocation = $("input[name='NextSellJobLocation']").val(),
            NextSellJobActivity = $("input[name='NextSellJobActivity']").val(),
            txtNextStepSaleStreetNumber = $("#txtNextStepSaleStreetNumber option:selected").text(),
            outterNumber = $("#txtNextStepSaleStreetNumber").val(),
            NextStepSaleAgreegmentType = $("#txtNextStepSaleAgreegmentType option:selected").html(),
            idArticulo = parseInt($("#txtNextStepSaleAgreegmentType").val()),
            NextSellPrice = $("#txtNextSellPrice").val(),
            NextSellPaymentTime = parseInt($("#nextSellPaymentTime option:selected").html()),
            NextSellMonthlyCost = parseFloat($("select[name='nextSellMonthlyCost'] option:selected").html()),
            NextSellRI = $("input[name='nextSellRI']").val(),
            NextSellDateRI = $("input[name='nextSellDateRI']").val(),
            idEmployee=$("#inputUserSegundaVenta").val(),
            txtNombreRefrencia1= $("input[name='txtNombreRefrencia1'").val(),
            txtfinancialService= $("#financialService").val(),
            txtTelefonoDeTrabajoReferencia1= $("input[name='txtTelefonoDeTrabajoReferencia1'").val(),
            txtTelefonoParticularRefrencia1= $("input[name='txtTelefonoParticularRefrencia1'").val(),
            txtTelefonoTrabajoExtRefrencia1= $("input[name='txtTelefonoTrabajoExtRefrencia1'").val(),
            txtNombreRefrencia2= $("input[name='txtNombreRefrencia2'").val(),
            txtTelefonoDeTrabajoReferencia2= $("input[name='txtTelefonoDeTrabajoReferencia2'").val(),
            txtTelefonoParticularRefrencia2= $("input[name='txtTelefonoParticularRefrencia2'").val(),
            inputNickUserLogg=$('#inputNickUserLogg').val();
            txtTelefonoTrabajoExtRefrencia2= $("input[name='txtTelefonoTrabajoExtRefrencia2'").val();

            var references1 = {txtNombreRefrencia1:txtNombreRefrencia1, 
                               txtTelefonoDeTrabajoReferencia1:txtTelefonoDeTrabajoReferencia1, 
                               txtTelefonoParticularRefrencia1:txtTelefonoParticularRefrencia1, 
                               txtTelefonoTrabajoExtRefrencia1:txtTelefonoTrabajoExtRefrencia1};
            var references2 = {txtNombreRefrencia2:txtNombreRefrencia2, 
                               txtTelefonoDeTrabajoReferencia2:txtTelefonoDeTrabajoReferencia2, 
                               txtTelefonoParticularRefrencia2:txtTelefonoParticularRefrencia2, 
                               txtTelefonoTrabajoExtRefrencia2:txtTelefonoTrabajoExtRefrencia2};
            
            var arrReferences=[references1,references2];
        var estaVacioAlgo = validarQueLaSegundaVentaNoEsteVacia();
        if (estaVacioAlgo !== true) {
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/secondSell.php",
                data: {
                    idReport: id,
                    idEmployee: idEmployee,
                    Consecutive: Consecutive,
                    Agency: agency,
                    NextSellPayment: NextSellPayment,
                    Agreement: Agreement,
                    LastName1: LastName1,
                    Name: Name,
                    CURP: CURP,
                    Engagment: Engagment,
                    IdCard: IdCard,
                    NextSellAgency: agency,
                    RequestDate: RequestDate,
                    LastName2: LastName2,
                    RFC: RFC,
                    Email: Email,
                    NextSellGender: NextSellGender,
                    NextSellIdentification: NextSellIdentification,
                    NextSellBirthdate: NextSellBirthdate,
                    NextStepSaleState: NextStepSaleState,
                    NextStepSaleColonia: NextStepSaleColonia,
                    NextStepSaleInHome: NextStepSaleInHome,
                    NextSellCellularPhone: NextSellCellularPhone,
                    NextSellEnterprise: NextSellEnterprise,
                    NextSellPosition: NextSellPosition,
                    NextSellJobTelephone: NextSellJobTelephone,
                    NextSellCountry: NextSellCountry,
                    NextStepSaleCity: NextStepSaleCity,
                    NextStepSaleStreet: NextStepSaleStreet,
                    NextSellPhone: NextSellPhone,
                    NextSellJobLocation: NextSellJobLocation,
                    NextSellJobActivity: NextSellJobActivity,
                    NextStepSaleAgreegmentType: NextStepSaleAgreegmentType,
                    idArticulo: idArticulo,
                    NextSellPrice: NextSellPrice,
                    NextSellPaymentTime: NextSellPaymentTime,
                    NextSellMonthlyCost: NextSellMonthlyCost,
                    NextSellRI: NextSellRI,
                    NextSellDateRI: NextSellDateRI,
                    References:arrReferences,
                    outterNumber:outterNumber,
                    financialService:txtfinancialService,
                    inputNickUserLogg:inputNickUserLogg,
                    txtNextStepSaleStreetNumber:txtNextStepSaleStreetNumber
                },
                dataType: "JSON",
                success: function (data) {
                    $('#addSecondSell').prop('disabled', false);
                    if (_.has(data, 'faultstring')) {
                        MostrarToast(2, "Error al generar el contrato", data.faultstring);
                        //data.ip_contrato = 22223;
                        //asignarInstalacion(id, idEmployee, data.ip_contrato, agency);
                        $('#addSecondSell').prop('disabled', false);
                    }else{
                        if (data.code === '500' && data.status === 'ERROR') {
                            MostrarToast(2, "Error al generar el contrato", data.response);
                            $('#addSecondSell').prop('disabled', false);
                        }else if(data.code === '200'){
                            MostrarToast(1, "Exito", data.response);
                            configurarToastCentrado();
                            $(':input','#agreementInformation')
                             .not(':button, :submit, :reset, :hidden')
                             .val('')
                             .removeAttr('checked')
                             .removeAttr('selected');

                            $(':input','#formSecondSell')
                             .not(':button, :submit, :reset, :hidden')
                             .val('')
                             .removeAttr('checked')
                             .removeAttr('selected');
                            $('#agreementInformation').find('input:text').val('');
                            $('#agreementInformation').find('select').val('');
                            $('#datosTitular').find('select').val('');
                            $('#datosTitular').find('input:text').val('');
                            $('#secondSellModal').modal('hide');
                            $('#addSecondSell').prop('disabled', false);
                            loadMain();
                        }else{
                            //generamos la instalacion y almacenamos los datos de idCliente
                            if((typeof(data.ip_contrato) !== 'undefined' && data.ip_contrato !== null && data.ip_contrato !== '') &&
                                parseInt(data.ip_contrato) > 0){
                                //validamos si el usuario que creo la plomeria es un plomero instalador
                                asignarInstalacion(id, idEmployee, data.ip_contrato, agency);
                                MostrarToast(1, "Info", 'Segunda Venta actualizada con exito - '+data.ip_contrato+' - '+data.op_message);
                                configurarToastCentrado();
                                $('#agreementInformation').find('input:text').val('');
                                $('#agreementInformation').find('select').val('');
                                $('#datosTitular').find('select').val('');
                                $('#datosTitular').find('input:text').val('');
                                $(':input','#formSecondSell')
                                 .not(':button, :submit, :reset, :hidden')
                                 .val('')
                                 .removeAttr('checked')
                                 .removeAttr('selected');
                                $('#secondSellModal').modal('hide');
                                $('#addSecondSell').prop('disabled', false);
                                loadMain();
                            }else{
                                $('#agreementInformation').find('input:text').val('');
                                $('#agreementInformation').find('select').val('');
                                $('#datosTitular').find('select').val('');
                                $('#datosTitular').find('input:text').val('');
                                MostrarToast(2, "Info", data.op_message+' - '+'No se genero ID Cliente');
                                $('#addSecondSell').prop('disabled', false);
                            }
                        }
                    }
                    //$('#secondSellModal').modal('hide');
                }, error: function (xhr, ajaxOptions, thrownError) {
                    console.log('xhr.status', xhr.status);
                    console.log('thrownError', thrownError);
                }
            });
        }
    }
    function asignarInstalacion(idReport, employee, idCliente, idAgencia){
        console.log('idReport', idReport);
        var employeeProfile = 3;
        var msg='';
        msg='Se ha asignado una solicitud de Instalacion';
        generarAsignacion(idCliente,employee,idReport,employeeProfile,msg, idAgencia);
    }
    function validarQueLaSegundaVentaNoEsteVacia() {
        var mensajeQueEstaVacio = "",
            estaVacio = false,
            id = $('#txtid').val(),
            Consecutive = $("input[name='consecutive']").val(),
            agency = $('#txtNextSellAgency').val(),
            NextSellPayment = $("input[name='nextSellPayment']").val(),
            Agreement = $("input[name='Agreement']").val(),
            LastName1 = $("input[name='ClientLastName1']").val(),
            LastName2 = $("input[name='ClientLastName2']").val(),
            Name = $("input[name='ClientName']").val(),
            RFC = $("input[name='ClientRFC']").val(),
            CURP = $("input[name='ClientCURP']").val(),
            Email = $("input[name='ClientEmail']").val(),
            Engagment = $('#txtEngagment').text(),
            txtRequestDate = $('#txtRequestDate').text(),
            txtNextSellPhone = $("input[name='txtNextSellPhone']").val(),
            NextSellGender = $('#txtNextSellGender').text(),
            IdCard = $('#txtIdCard').val(),
            NextSellIdentification = $('#txtNextSellIdentification').text(),
            NextSellBirthdate = $("input[name='NextSellBirthdate']").val(),
            NextSellCountry = $('#txtNextSellCountry').val(),
            NextStepSaleState = $('#txtNextStepSaleState').val(),
            NextStepSaleCity = $('#txtNextStepSaleCity').val(),
            NextStepSaleColonia = $('#txtNextStepSaleColonia').val(),
            NextStepSaleStreet = $('#txtNextStepSaleStreet').val(),
            txtNextStepSaleStreetNumber = $("#txtNextStepSaleStreetNumber").val(),
            NextStepSaleInHome = $('#txtNextStepSaleInHome').val(),
            NextSellCellularPhone = $('#txtNextSellCellularPhone').val(),
            NextSellEnterprise = $('#txtNextSellEnterprise').val(),
            NextSellPosition = $('#txtNextSellPosition').val(),
            NextSellJobTelephone = $("input[name='NextSellJobTelephone']").val(),
            NextSellJobLocation = $("input[name='NextSellJobLocation']").val(),
            NextSellJobActivity = $("input[name='NextSellJobActivity']").val(),
            NextStepSaleAgreegmentType = $('#txtNextStepSaleAgreegmentType').val(),
            NextSellPrice = $("input[name='nextSellAgency']").val(),
            NextSellPaymentTime = parseInt($("#nextSellPaymentTime option:selected").html()),
            NextSellMonthlyCost = parseFloat($("select[name='nextSellMonthlyCost'] option:selected").html()),
            NextSellRI = $("input[name='nextSellRI']").val(),
            NextSellDateRI = $("input[name='nextSellDateRI']").val(),
            txtNextSellReference1=$("#txtNextSellReference1").val(),
            txtNextSellReferenceTelephone1=$("#txtNextSellReferenceTelephone1").val(),
            txtTelefonoDeTrabajoReferencia1=$("#txtTelefonoDeTrabajoReferencia1").val(),
            txtNextSellReferenceTelephone1=$("#txtNextSellReferenceTelephone1").val(),
            txtNextSellReference1=$("#txtNextSellReference1").val();

        if (agency === '' || typeof(agency) === 'undefined' || agency === null) {
            mensajeQueEstaVacio = "La agencia no puede ir vacia";
            estaVacio = true;
        }else if (NextSellPayment === '' || typeof(NextSellPayment) === 'undefined' || NextSellPayment === null) {
            mensajeQueEstaVacio = "El pagare no puede ir vacio";
            estaVacio = true;
        }else if(Agreement === '' || typeof(Agreement) === 'undefined' || Agreement === null) {
            mensajeQueEstaVacio = "El numero de contrato no puede ir vacio";
            estaVacio = true;
        }else if(LastName1 === '' || typeof(LastName1) === 'undefined' || LastName1 === null) {
            mensajeQueEstaVacio = "El apellido paterno no puede ir vacio";
            estaVacio = true;
        }else if(LastName2 === '' || typeof(LastName2) === 'undefined' || LastName2 === null) {
            mensajeQueEstaVacio = "El apellido materno no puede ir vacio";
            estaVacio = true;
        }else if(Name === '' || typeof(Name) === 'undefined' || Name === null) {
            mensajeQueEstaVacio = "El nombre no puede ir vacio";
            estaVacio = true;
        }else if(RFC === '' || typeof(RFC) === 'undefined' || RFC === null) {
            mensajeQueEstaVacio = "El rfc no puede ir vacio";
            estaVacio = true;
        }else if(CURP === '' || typeof(CURP) === 'undefined' || CURP === null) {
            mensajeQueEstaVacio = "El curp no puede ir vacio";
            estaVacio = true;
        }else if(Email === '' || typeof(Email) === 'undefined' || Email === null) {
            mensajeQueEstaVacio = "El email no puede ir vacio";
            estaVacio = true;
        }else if(Engagment === '' || typeof(Engagment) === 'undefined' || Engagment === null) {
            mensajeQueEstaVacio = "El engagment no puede ir vacio";
            estaVacio = true
        }else if(IdCard === '' || typeof(IdCard) === 'undefined' || IdCard === null) {
            mensajeQueEstaVacio = "El numero de identificacion no puede ir vacio";
            estaVacio = true;
        }else if(NextSellBirthdate === '' || typeof(NextSellBirthdate) === 'undefined' || NextSellBirthdate === null) {
            mensajeQueEstaVacio = "La fecha de nacimiento no puede ir vacia";
            estaVacio = true;
        }else if(NextSellCellularPhone === '' || typeof(NextSellCellularPhone) === 'undefined' || NextSellCellularPhone === null) {
            mensajeQueEstaVacio = "El numero celular no puede ir vacio";
            estaVacio = true;
        }else if(NextSellEnterprise === '' || typeof(NextSellEnterprise) === 'undefined' || NextSellEnterprise === null) {
            mensajeQueEstaVacio = "El nombre de la empresa no puede ir vacio";
            estaVacio = true;
        }else if(NextSellPosition === '' || typeof(NextSellPosition) === 'undefined' || NextSellPosition === null) {
            mensajeQueEstaVacio = "El puesto no puede ir vacio";
            estaVacio = true;
        }else if(NextSellJobTelephone === '' || typeof(NextSellJobTelephone) === 'undefined' || NextSellJobTelephone === null) {
            mensajeQueEstaVacio = "El telefono de la empresa no puede ir vacio";
            estaVacio = true;
        }else if(NextSellJobLocation === '' || typeof(NextSellJobLocation) === 'undefined' || NextSellJobLocation === null) {
            mensajeQueEstaVacio = "La direccion de la empresa no puede ir vacia";
            estaVacio = true;
        }else if(NextSellJobActivity === '' || typeof(NextSellJobActivity) === 'undefined' || NextSellJobActivity === null) {
            mensajeQueEstaVacio = "La actividad o area no puede ir vacia";
            estaVacio = true;
        }else if(NextSellPrice === '' || typeof(NextSellPrice) === 'undefined' || NextSellPrice === null) {
            mensajeQueEstaVacio = "El precio de tipo de contrato no puede ir vacio";
            estaVacio = true;
        }else if(NextSellPaymentTime === '' || typeof(NextSellPaymentTime) === 'undefined' || NextSellPaymentTime === null) {
            mensajeQueEstaVacio = "El precio del contrato";
            estaVacio = true;
        }else if(NextSellMonthlyCost === '' || typeof(NextSellMonthlyCost) === 'undefined' || NextSellMonthlyCost === null) {
            mensajeQueEstaVacio = "El costo mensual no puede ir vacio";
            estaVacio = true;
        }else if(NextSellRI === '' || typeof(NextSellRI) === 'undefined' || NextSellRI === null) {
            mensajeQueEstaVacio = "El RI no puede ir vacio";
            estaVacio = true;
        }else if(NextSellDateRI === '' || typeof(NextSellDateRI) === 'undefined' || NextSellDateRI === null) {
            mensajeQueEstaVacio = "La fecha del RI no puede ir vacia";
            estaVacio = true;
        }else if (NextSellCountry === '' || typeof(NextSellCountry) === 'undefined' || NextSellCountry === null) {
            mensajeQueEstaVacio = "El campo Pais no puede ir vacio";
            estaVacio = true;
        }else if (NextStepSaleState === '' || typeof(NextStepSaleState) === 'undefined' || NextStepSaleState === null) {
            mensajeQueEstaVacio = "El campo Estado no puede ir vacio";
            estaVacio = true;
        }else if (NextStepSaleCity === '' || typeof(NextStepSaleCity) === 'undefined' || NextStepSaleCity === null) {
            mensajeQueEstaVacio = "El campo Ciudad no puede ir vacio";
            estaVacio = true;
        }else if (NextStepSaleColonia === '' || typeof(NextStepSaleColonia) === 'undefined' || NextStepSaleColonia === null) {
            mensajeQueEstaVacio = "El campo Colonia no puede ir vacio";
            estaVacio = true;
        }else if (NextStepSaleStreet === '' || typeof(NextStepSaleStreet) === 'undefined' || NextStepSaleStreet === null) {
            mensajeQueEstaVacio = "El campo Calle no puede ir vacio";
            estaVacio = true;
        }else if (NextStepSaleStreet === '' || typeof(NextStepSaleStreet) === 'undefined' || NextStepSaleStreet === null) {
            mensajeQueEstaVacio = "El campo Calle no puede ir vacio";
            estaVacio = true;
        }
        if(estaVacio === true) {
            MostrarToast(2, "Aviso", mensajeQueEstaVacio);
            $('#addSecondSell').prop('disabled', false);
        }
        return estaVacio;
    }

    $('#btnAddTask').click(function () {
        if (reportsSelected.length > 0) {
            $('#taskForm').modal('show');
            $('#titleCompanyTask').html('Asignar Tarea');
            $('#btnAssign').html('Asignar');
        } else {
            MostrarToast(2, "Asignación de tarea", "Primero debe marcar las tareas para asignar");
        }
    });

    function agencyEmployeeTask(agency, profile) {
        console.log(agency);
        console.log(profile);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/loadUserFromProfile.php",
            data: {profile: profile, agency: agency},
            dataType: "JSON",
            success: function (data) {
                console.log('data_agencyEmployeeTask', data);
                $('#txtTaskEmployee').html('');
                for (var agency in data) {
                    $('#txtTaskEmployee').append('<option value="' + data[agency].employeeUserID + '">' + data[agency].nickname + '</option>');
                }
            }
        });
    }

    $(document).on('change', '#txtUserProfile', function () {
        var profile = $("#txtUserProfile").val();
        profile = parseInt(profile);
        var idReport=$('#inpIDRep').val();
        if (profile === 0) {
            loadAgenciesTask(localStorage.getItem("id"));
        }else {
            if (profile === 1) {
                var resDataPlom=loadPlomeria(idReport, profile);
            }else if (profile === 2) {
                //validamos si ya se asigno la segunda venta
                validateSecondSell(idReport);
            }
        }
    });
    function validateSecondSell(idReport){
        var tipo;
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/validateSegundaVenta.php",
            data: {
                id: idReport,
            },
            dataType: "JSON",
            success: function (data) {
                //obtenemos los datos de el reporte con plomeria
                if(data.length > 0){
                    $('#btnAssign').hide();
                    $('#txtTaskAgency').empty().append('whatever');
                    $('#txtTaskEmployee').empty().append('whatever');
                    $('#txtTaskAgency').append($('<option>', {
                        value: data[0].idAgencia,
                        text: data[0].descAgencia
                    }));
                    $('#txtTaskEmployee').append($('<option>', {
                        value: data[0].IDEmp,
                        text: data[0].nicknameEmp
                    }));

                }else{
                    loadSecondSell(idReport);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(textStatus);
            }
        });
    }
    function loadSecondSell(idReport){
        var tipo;
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/getDataSecondSell.php",
            data: {
                id: idReport,
            },
            dataType: "JSON",
            success: function (data) {
                //obtenemos los datos de el reporte con plomeria
                if(data.length > 0){
                    $('#btnAssign').show();
                    $('#txtTaskAgency').empty().append('whatever');
                    $('#txtTaskEmployee').empty().append('whatever');
                    $('#txtTaskAgency').append($('<option>', {
                        value: data[0].idAgencia,
                        text: data[0].descAgencia
                    }));
                    $('#txtTaskEmployee').append($('<option>', {
                        value: data[0].IDEmp,
                        text: data[0].nicknameEmp
                    }));
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(textStatus);
            }
        });
    }
    function obtenerAgencias(profile){
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/loadUserFromProfile.php",
            data: {profile: profile},
            dataType: "JSON",
            success: function (data) {
                console.log('data_txtUserProfile', data);
                $('#txtTaskAgency').html('');
                for (var agency in data) {
                    //if (data[agency] !== null && data[agency] !== "") {
                    $('#txtTaskAgency').append('<option value="' + data[agency].agencyId + '">' + data[agency].nickname + '</option>');
                    //}
                }
                var agencyId = $('#txtTaskAgency').val();
                //alert(city);
                agencyEmployeeTask(agencyId, profile);
            }
        });
    }

    $(document).on('change', '#txtTaskAgency', function () {
        var profile = $("#txtUserProfile").val();
        if (parseInt(profile) === 0) {
            loadAgenciesTask(localStorage.getItem("id"));
        } else {
            var agencyId = $("#txtTaskAgency").val();
            agencyEmployeeTask(agencyId, profile);
        }
    });

    $('#btnAssign:not(:disabled)').click(function () {
        $('#btnAssign').prop('disabled', true);
        var employeeToAssing = parseInt($('#txtTaskEmployee').val());
        var employeeProfile = $('#txtUserProfile').val();
        var idReport=parseInt($('#inpIDRep').val());
        var msg='';
        console.log('employeeProfile', employeeProfile);
        report = idReport;
        if (employeeProfile === '1') {
            msg='Se ha asignado una solicitud de Plomería';
            generarAsignacion(localStorage.getItem("id"),employeeToAssing,report,employeeProfile,msg);
        }else if(parseInt(employeeProfile) === 2){
            //agrregamos validacion para no asignar segunda venta.
            msg='Se ha asignado una solicitud de Segunda Venta';
            generarAsignacion(localStorage.getItem("id"),employeeToAssing,report,employeeProfile,msg);
        }else if(employeeProfile === '4' ) {
            //agrregamos validacion para no asignar segunda venta.
            msg = 'Se ha asignado una solicitud de Instalacion';
            employeeProfile = '4';
            generarAsignacion(localStorage.getItem("id"), employeeToAssing, report, employeeProfile, msg);
        }
    });
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
        var inputIdUser=$('#inputIdUser').val();
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
                generarExcel(dateFrom,dateTo,inputIdUser);
            }
        }else{
            generarExcel(dateFrom,dateTo,inputIdUser);
        }
    });
    function generarExcel(dateFrom,dateTo,inputIdUser){
        if(inputIdUser !== '' && inputIdUser !== null && typeof(inputIdUser) !== 'undefined'){
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/downloadExcelForms.php",
                data: {
                    dateFrom:dateFrom,
                    dateTo:dateTo,
                    inputIdUser:inputIdUser,
                },
                dataType: "JSON",
                success: function (data) {
                    console.log('data', data);
                    if (data.code === '500') {
                        MostrarToast(2, "Rango fechas erroneo", data.response);
                        $('#btn_download').prop('disabled', false);
                    }else{
                        var tipoReporte;
                        var arrObjDatos=[], myFailure;
                        _.each(data, function(dato, index) {
                            if(index === 0){
                                console.log('data',dato);
                                switch(parseInt(data[index].idReportType)) {
                                    case 1:
                                        tipoReporte='Censo';
                                        break;
                                    case 2:
                                        tipoReporte='Venta';
                                        break;
                                    case 3:
                                        tipoReporte='Plomero';
                                        break;
                                    case 4:
                                        tipoReporte='Instalacion';
                                        break;
                                    case 5:
                                        tipoReporte='Segunda Venta';
                                        break;
                                }
                                arrObjDatos.push($.ajax({
                                    method: "POST",
                                    url: "dataLayer/callsWeb/loadFormExcel.php",
                                    data: {
                                        collection:dato,
                                        form:data[index].id,
                                        type:tipoReporte,
                                        idUsuario:data[index].idUserAssigned,
                                    },
                                    async: false,
                                    dataType: "JSON"
                                }));
                            }
                        });
                        $.when(arrObjDatos).then(mySuccessFunction, myFailure);
                    }
                        
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(textStatus);
                    $("#btn_download").notify("Ocurrio un problema al generar el archivo", "error");
                }
            });
        }
    }
    function mySuccessFunction(res){
        var arrDatos=[], csv;
        _.each(res, function(data, idx) {
            arrDatos.push(data.responseJSON);
        });
        console.log('arrDatos', arrDatos);
        //$('#btn_download').prop('disabled', false);
        /*if(arrDatos.length > 0){
            $("#btn_download").notify("El archivo termino de generarse correctamente", "success");
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/createExcel.php",
                data: {
                    collection:arrDatos,
                },
                //async: false,
                dataType: "JSON",
                success: function (data) {
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
                }
            });
        }*/
    }
    $('#addSecondSell:not(:disabled)').click(function () {
        $('#addSecondSell').prop('disabled', true);
        //validamos si el pagare coincide conforme al contrato
        var IDContrato=$('#txtNextStepSaleAgreegmentType').val(), resGetContrato, tipoSolicitud=$('#segundaVentaFinanciada').text();
        if (IDContrato !== '' || IDContrato !== 0) {
            //resGetContrato=buscarContrato(IDContrato);
            $.ajax({
                method: "GET",
                url: "dataLayer/callsWeb/buscarContratos.php",
                dataType: "JSON",
                data: {
                    idContrato:IDContrato
                },
                //async: false
                success: function (data) {
                    console.log('data', data);
                    if (data.CodigoRespuesta !== 1) {
                        MostrarToast(2, data.MensajeRespuesta);
                        configurarToastCentrado();
                        $('#addSecondSell').prop('disabled', false);
                    }else{
                        if ((data.response[0].financiamiento === 'AYOPSA' && tipoSolicitud === 'MEX')) {
                            MostrarToast(2, "El contrato seleccionado no corresponde a una financiera..");
                            configurarToastCentrado();
                            $('#addSecondSell').prop('disabled', false);
                        }else if((data.response[0].financiamiento === 'MEXGAS' && tipoSolicitud === 'AYO')){
                            MostrarToast(2, "El contrato seleccionado no corresponde a Mexicana..");
                            configurarToastCentrado();
                            $('#addSecondSell').prop('disabled', false);
                        }else if((data.response[0].financiamiento === 'AYOPSA' && tipoSolicitud === 'AYO') ||
                                 (data.response[0].financiamiento === 'MEXGAS' && tipoSolicitud === 'MEX')){
                            secondSell();
                        }                        
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(textStatus);
                },
                complete: function (XMLHttpRequest, status) {
                }
            });
        }else{
            MostrarToast(2, "No se ha seleccionado un contrato");
            configurarToastCentrado();
            $('#addSecondSell').prop('disabled', false);
        }
        //secondSell();
    });

    $('#formsDetailsBody').on('click', '#sendInstalacion:not(:disabled)', function(e) { 
        e.preventDefault(); 
        $('#sendInstalacion').prop('disabled', true);
        var idRep=parseInt($('#formsDetailsBody .row').attr('data-id'));
        if (idRep !== '' || idRep > 0) {
            //resGetContrato=buscarContrato(IDContrato);
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/sendDataInstalacion.php",
                dataType: "JSON",
                data: {
                    idReport:idRep
                },
                //async: false
                success: function (data) {
                    console.log('data', data);
                    //"result":"0","p_mensaje":"AMCO1781919"
                    if((typeof(data.p_mensaje) !== 'undefined' && data.p_mensaje !== null && data.p_mensaje !== '') &&
                        parseInt(data.result) === 0){
                        $.ajax({
                            method: "POST",
                            url: "dataLayer/callsWeb/updateDataInstalacion.php",
                            dataType: "JSON",
                            data: {
                                numInstalacionGen:data.p_mensaje,
                                idReport:idRep,
                            },
                            //async: false
                            success: function (data) {
                                if(parseInt(data.code) === 200){
                                    MostrarToast(1, "Envio de Instalacion Exitosa", data.result);
                                    configurarToastCentrado();
                                    $('#sendInstalacion').html(data.result);
                                    $('#formsDetails').modal('hide');
                                    window.location='forms.php';
                                }
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log(textStatus);
                            },
                            complete: function (XMLHttpRequest, status) {
                            }
                        });     
                    }else{
                        MostrarToast(2, "Info", data.p_mensaje);
                        $('#sendInstalacion').prop('disabled', false);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(textStatus);
                },
                complete: function (XMLHttpRequest, status) {
                }
            });
        }else{
            MostrarToast(2, "No se ha seleccionado un contrato");
            configurarToastCentrado();
            $('#sendInstalacion').prop('disabled', false);
        }
        //secondSell();
    });

    $('#formsDetailsBody').on('change', '#editPlomeria:not(:disabled)', function(e) {
        e.preventDefault();
        var isChecked=$("#editPlomeria").is(':checked');
        if (isChecked === true) {
            $("#savePlomeria").show();
            $("#formsDetailsBody #colorTapon").show();
            $("#formsDetailsBody #riMenor").show();
            $("#formsDetailsBody #reqTuberia").show();
            $("#formsDetailsBody #numTomasSel").show();
            $("#formsDetailsBody #savePlomeria").show();
            $("#formsDetailsBody #textTapon").hide();
            $("#formsDetailsBody #riMText").hide();
            $("#formsDetailsBody #tubText").hide();
            $("#formsDetailsBody #numTomas").hide();
            $("#formsDetails .toggle").show();
            $(".imagenInstTub").show();
            $(".imagenEtiqueta").show();
        }else{
            $("#formsDetailsBody #textTapon").show();
            $("#formsDetailsBody #riMText").show();
            $("#formsDetailsBody #tubText").show();
            $("#formsDetailsBody #numTomas").show();
            $("#formsDetailsBody #colorTapon").hide();
            $("#formsDetailsBody #riMenor").hide();
            $("#formsDetailsBody #reqTuberia").hide();
            $("#formsDetailsBody #numTomasSel").hide();
            $("#formsDetailsBody #savePlomeria").hide();
            $("#formsDetails .toggle").hide();
            $("#savePlomeria").hide();
            $(".imagenEtiqueta").hide();
            $(".imagenInstTub").hide();
        }   
    });

    $('#formsDetailsBody').on('change', '#editVenta:not(:disabled)', function(e) {
        e.preventDefault();
        console.log('editVenta', e);
        var isChecked=$("#editVenta").is(':checked');
        if (isChecked === true) {
            $('.carousel').carousel({
                interval: false
            }); 
            $('.imagenComprobante').show();
            $('.imagenSolicitud').show();
            $('.imagenAviso').show();
            $('.imagenID').show();
            $('.imagenPagare').show();
            $('.imagenContrato').show();
        }else{
            $('.carousel').carousel({
                interval: true
            }); 
            $('.imagenComprobante').hide();
            $('.imagenSolicitud').hide();
            $('.imagenAviso').hide();
            $('.imagenID').hide();
            $('.imagenPagare').hide();
            $('.imagenContrato').hide();
        }   
    });

    $('#formsDetailsBody').on('change', '#editInstall:not(:disabled)', function(e) {
        e.preventDefault();
        var isChecked=$("#editInstall").is(':checked');
        if (isChecked === true) {
            $("#sendInstalacion").hide();
            $("#saveInstalacion").show();
            $(".imagenCuadroMed").show();
            $("#formsDetailsBody .eliminaMaterial").show();
            $("#formsDetailsBody .materialSelect").show();
            $("#formsDetailsBody .cantMaterial").show();
            $("#formsDetailsBody .divBtnMat").show();
            loadMaterial();
        }else{
            $("#sendInstalacion").show();
            $("#saveInstalacion").hide();
            $(".imagenCuadroMed").hide();
            $("#formsDetailsBody .eliminaMaterial").hide();
            $("#formsDetailsBody .materialSelect").hide();
            $("#formsDetailsBody .cantMaterial").hide();
            $("#formsDetailsBody .divBtnMat").hide();
        }
            
    });

    $('#formsDetailsBody').on('click', '#addMaterial:not(:disabled)', function(e) {
        e.preventDefault();
        $("#addMaterial").prop('disabled', true);
        var isChecked=$("#editInstall").is(':checked');
        if (isChecked === true) {
            console.log('$(this)', $("#addMaterial").attr("data-id"));
            var dataID=parseInt($("#addMaterial").attr("data-id"));
            var materialSelect=$("#materialSelect option:selected").html();
            var materialSelectVal=parseInt($("#materialSelect").val());
            var cantidadMaterial=$("#cantidadMaterial").val();
            if (!_.isEmpty(cantidadMaterial) && parseInt(materialSelectVal) !== 0) {
                insertMaterialInst(dataID, cantidadMaterial, materialSelect);
            }else{
                $("#addMaterial").notify(
                    "Faltan datos por capturar", "error"
                );
                $("#addMaterial").prop('disabled', false);
            }
            //loadMaterialInst(dataID);
        }
    });

    $('#formsDetailsBody').on('click', '#addMaterial:not(:disabled)', function(e) {
        e.preventDefault();
        $("#addMaterial").prop('disabled', true);
        var isChecked=$("#editInstall").is(':checked');
        if (isChecked === true) {
            console.log('$(this)', $("#addMaterial").attr("data-id"));
            var dataID=parseInt($("#addMaterial").attr("data-id"));
            var materialSelect=$("#materialSelect option:selected").html();
            var materialSelectVal=parseInt($("#materialSelect").val());
            var cantidadMaterial=$("#cantidadMaterial").val();
            if (!_.isEmpty(cantidadMaterial) && parseInt(materialSelectVal) !== 0) {
                insertMaterialInst(dataID, cantidadMaterial, materialSelect);
            }else{
                $("#addMaterial").notify(
                    "Faltan datos por capturar", "error"
                );
                $("#addMaterial").prop('disabled', false);
            }
            //loadMaterialInst(dataID);
        }
    });

    $("#formsDetailsBody").on('change', '#imagenInstTub:not(:disabled)',  (function(e) {
        e.preventDefault();
        var file = this.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            $("#imagenInstTub").notify(
                "El archivo seleccionado no es permitido", "error"
            );
            return false;
        } else {
            $("#uploadimageInstTub").notify(
                "Cargando imagen", "info"
            );
            var filesLoad=this.files[0];
            var idReporte=parseInt($("#reporteID").val());
            var lengthInstTub=$('div.active a[data-lightbox="example-tuberia"]').length;
            var imgInstTub="";
            var res_imgInstTub="";
            if (lengthInstTub > 0) {
                imgInstTub= $('div.active a[data-lightbox="example-tuberia"]').attr("href");
                res_imgInstTub= imgInstTub.split("/");
                imgInstTub=res_imgInstTub[9];
            }

            var formData = new FormData();
            var month=moment().month();
            var year=moment().year();
            formData.append("reportID", idReporte);
            formData.append("tipoImg", "tuberia");
            formData.append("imgInstTub", imgInstTub);
            formData.append(
                "PostImage", 
                document.getElementById("imagenInstTub").files[0]
            ); 
            $.ajax({
                url: "dataLayer/callsWeb/uploadImage.php", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data:formData, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                dataType: "JSON",
                success: function(data){
                    var respnseCode=parseInt(data.code);
                    console.log('respnseCode', respnseCode);
                    if (respnseCode === 200) {
                        $("#uploadimageInstTub").notify(
                            "La imagen se cargo con exito", "success"
                        );
                        imageIsLoadedTuberia(data.responseRuta, data.responseImg);
                    }else if (respnseCode === 500){
                        $("#uploadimageInstTub").notify(
                            data.response, "error"
                        );
                    }
                }
            });
        }   
    }));

    $("#formsDetailsBody").on('change', '#imagenEtiqueta:not(:disabled)',  (function(e) {
        e.preventDefault();
        var file = this.files[0];
        var imagefile = file.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            $("#imagenEtiqueta").notify(
                "El archivo seleccionado no es permitido", "error"
            );
            return false;
        } else {
            $("#uploadimageEti").notify(
                "Cargando imagen", "info"
            );
            var filesLoad=this.files[0];
            var idReporte=parseInt($("#reporteID").val());
            var lengthEtiqueta=$('div.active a[data-lightbox="example-pie"]').length;
            var imgEtiqueta="";
            var res_imgEtiqueta="";
            if (lengthEtiqueta > 0) {
                imgEtiqueta= $('div.active a[data-lightbox="example-pie"]').attr("href");
                res_imgEtiqueta= imgEtiqueta.split("/");
                imgEtiqueta=res_imgEtiqueta[9];
            }
                
            var formData = new FormData();
            var month=moment().month();
            var year=moment().year();
            formData.append("reportID", idReporte);
            formData.append("tipoImg", "pie");
            formData.append("imgEtiqueta", imgEtiqueta);
            formData.append(
                "PostImage", 
                document.getElementById("imagenEtiqueta").files[0]
            ); 
            $.ajax({
                url: "dataLayer/callsWeb/uploadImage.php", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data:formData, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                dataType: "JSON",
                success: function(data){
                    console.log('data', data);
                    var respnseCode=parseInt(data.code);

                    if (respnseCode === 200) {
                        $("#uploadimageEti").notify(
                            "La imagen se cargo con exito", "success"
                        );
                        imageIsLoadedPieDerecho(data.responseRuta, data.responseImg);
                    }else if (respnseCode === 500){
                        $("#uploadimageEti").notify(
                            data.response, "error"
                        );
                    }
                }
            });
        }   
    }));

    $("#formsDetailsBody").on('click', '#delImagenInstTub:not(:disabled)',  (function(e) {
        e.preventDefault();
        var idReporte=parseInt($("#reporteID").val());
        var lengthInstTub=$('div.active a[data-lightbox="example-tuberia"]').length;
        var imgInstTub="";
        var res_imgInstTub="";
        if (lengthInstTub > 0) {
            imgInstTub= $('div.active a[data-lightbox="example-tuberia"]').attr("href");
            res_imgInstTub= imgInstTub.split("/");
            //imgEtiqueta=res_imgEtiqueta[11];
            imgInstTub=res_imgInstTub[9];
        }
        var tipoImg="tuberia";
        if (imgInstTub !== "") {
            $.ajax({
                url: "dataLayer/callsWeb/deleteImage.php", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data:{
                    filenameImg:imgInstTub,
                    tipoImg:tipoImg,
                    idReporte:idReporte
                }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                dataType: "JSON",
                success: function(data){
                    console.log('data', data);
                    var respnseCode=parseInt(data.code);
                    if (respnseCode === 200) {
                        $("#uploadimageInstTub").notify(
                            "La imagen se elimino con exito", "success"
                        );
                        imageIsLoadedTuberia(data.responseRuta, data.responseImg);
                    }else if (respnseCode === 500){
                        $("#uploadimageInstTub").notify(
                            data.response, "error"
                        );
                    }
                }
            });
        }else{
            $("#uploadimageInstTub").notify(
                "No hay imagen por eliminar", "error"
            );
        }  
    }));

    $("#formsDetailsBody").on('click', '#delImagenEtiqueta:not(:disabled)',  (function(e) {
        e.preventDefault();
        var idReporte=parseInt($("#reporteID").val());
        var lengthEtiqueta=$('div.active a[data-lightbox="example-pie"]').length;
        var imgEtiqueta="";
        var res_imgEtiqueta="";
        if (lengthEtiqueta > 0) {
            imgEtiqueta= $('div.active a[data-lightbox="example-pie"]').attr("href");
            res_imgEtiqueta= imgEtiqueta.split("/");
            //imgEtiqueta=res_imgEtiqueta[11];
            imgEtiqueta=res_imgEtiqueta[9];
        }
        var tipoImg="pie";
        if (imgEtiqueta !== "") {
            $.ajax({
                url: "dataLayer/callsWeb/deleteImage.php", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data:{
                    filenameImg:imgEtiqueta,
                    tipoImg:tipoImg,
                    idReporte:idReporte
                }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                dataType: "JSON",
                success: function(data){
                    console.log('data', data);
                    var respnseCode=parseInt(data.code);
                    if (respnseCode === 200) {
                        $("#uploadimageEti").notify(
                            "La imagen se elimino con exito", "success"
                        );
                        imageIsLoadedPieDerecho(data.responseRuta, data.responseImg);
                    }else if (respnseCode === 500){
                        $("#uploadimageEti").notify(
                            data.response, "error"
                        );
                    }
                }
            });
        }else{
            $("#uploadimageEti").notify(
                "No hay imagen por eliminar", "warning"
            );
        }
            
    }));

    $("#formsDetailsBody").on('change', '#imagenComprobante:not(:disabled)',  (function(e) {
        e.preventDefault();
        var file = this.files[0];
        var tipo = 'comprobante';
        uploadImageVentas(file, tipo);
    }));

    $("#formsDetailsBody").on('change', '#imagenID:not(:disabled)',  (function(e) {
        e.preventDefault();
        var file = this.files[0];
        var tipo = 'identificacion';
        uploadImageVentas(file, tipo);
    }));

    $("#formsDetailsBody").on('change', '#imagenSolicitud:not(:disabled)',  (function(e) {
        e.preventDefault();
        var file = this.files[0];
        var tipo = 'solicitud';
        uploadImageVentas(file, tipo);
    }));

    $("#formsDetailsBody").on('change', '#imagenPagare:not(:disabled)',  (function(e) {
        e.preventDefault();
        var file = this.files[0];
        var tipo = 'pagare';
        uploadImageVentas(file, tipo);
    }));

    $("#formsDetailsBody").on('change', '#imagenAviso:not(:disabled)',  (function(e) {
        e.preventDefault();
        var file = this.files[0];
        var tipo = 'aviso';
        uploadImageVentas(file, tipo);
    }));

    $("#formsDetailsBody").on('change', '#imagenContrato:not(:disabled)',  (function(e) {
        e.preventDefault();
        var file = this.files[0];
        var tipo = 'contrato';
        uploadImageVentas(file, tipo);
    }));

    $("#formsDetailsBody").on('change', '#imagenCuadroMed:not(:disabled)',  (function(e) {
        console.log('prueba');
        e.preventDefault();
        var file = this.files[0];
        var tipo = 'cuadro';
        uploadImageVentas(file, tipo);
    }));

    $("#formsDetailsBody").on('click', '#delImagenComprobante:not(:disabled)',  (function(e) {
        e.preventDefault();
        var tipo = 'comprobante';
        var elementImage =$('div.active a[data-lightbox="example-comprobante"]');
        var lengthImageActive=elementImage.length;
        console.log('lengthImageActive', lengthImageActive);
        if (lengthImageActive > 0) {
            var nombre = $('div.active a[data-lightbox="example-comprobante"]').attr("href");
            var arrNombre = nombre.split('/');
            if (arrNombre.length > 6 ) {
                deleteImageVentas(tipo);
            }else if(arrNombre.length === 0){
                $("#uploadimageComp").notify(
                    "No existe una imagen para eliminar", "error"
                );
            }else if(arrNombre.length === 6){
                $("#uploadimageComp").notify(
                    "Esta imagen no se permite eliminar", "error"
                );
            }
        }
    }));

    $("#formsDetailsBody").on('click', '#delImagenID:not(:disabled)',  (function(e) {
        console.log(e);
        e.preventDefault();
        var tipo = 'identificacion';
        var elementImage =$('div.active a[data-lightbox="example-identificacion"]');
        var lengthImageActive=elementImage.length;
        if (lengthImageActive > 0) {
            var nombre = $('div.active a[data-lightbox="example-identificacion"]').attr("href");
            var arrNombre = nombre.split('/');
            if (arrNombre.length > 6 ) {
                deleteImageVentas(tipo);
            }else if(arrNombre.length === 0){
                $("#uploadimageID").notify(
                    "No existe una imagen para eliminar", "error"
                );
            }else if(arrNombre.length === 6){
                $("#uploadimageID").notify(
                    "Esta imagen no se permite eliminar", "error"
                );
            }
        }
    }));

    $("#formsDetailsBody").on('click', '#delImagenSolicitud:not(:disabled)',  (function(e) {
        e.preventDefault();
        var tipo = 'solicitud';
        var elementImage =$('div.active a[data-lightbox="example-solicitud"]');
        var lengthImageActive=elementImage.length;
        if (lengthImageActive > 0) {
            var nombre = $('div.active a[data-lightbox="example-solicitud"]').attr("href");
            var arrNombre = nombre.split('/');
            if (arrNombre.length > 6 ) {
                deleteImageVentas(tipo);
            }else if(arrNombre.length === 0){
                $("#uploadimageSolicitud").notify(
                    "No existe una imagen para eliminar", "error"
                );
            }else if(arrNombre.length === 6){
                $("#uploadimageSolicitud").notify(
                    "Esta imagen no se permite eliminar", "error"
                );
            }
        }
    }));

    $("#formsDetailsBody").on('click', '#delImagenPagare:not(:disabled)',  (function(e) {
        e.preventDefault();
        var tipo = 'pagare';
        elementImage =$('div.active a[data-lightbox="example-pagare"]');
        var lengthImageActive=elementImage.length;
        if (lengthImageActive > 0) {
            var nombre = $('div.active a[data-lightbox="example-pagare"]').attr("href");
            var arrNombre = nombre.split('/');
            if (arrNombre.length > 6 ) {
                deleteImageVentas(tipo);
            }else if(arrNombre.length === 0){
                $("#uploadimagePagare").notify(
                    "No existe una imagen para eliminar", "error"
                );
            }else if(arrNombre.length === 6){
                $("#uploadimagePagare").notify(
                    "Esta imagen no se permite eliminar", "error"
                );
            }
        }
    }));

    $("#formsDetailsBody").on('click', '#delImagenAviso:not(:disabled)',  (function(e) {
        e.preventDefault();
        var tipo = 'aviso';
        var elementImage =$('div.active a[data-lightbox="example-aviso"]');
        var lengthImageActive=elementImage.length;
        if (lengthImageActive > 0) {
            var nombre = $('div.active a[data-lightbox="example-aviso"]').attr("href");
            var arrNombre = nombre.split('/');
            if (arrNombre.length > 6 ) {
                deleteImageVentas(tipo);
            }else if(arrNombre.length === 0){
                $("#uploadimageAviso").notify(
                    "No existe una imagen para eliminar", "error"
                );
            }else if(arrNombre.length === 6){
                $("#uploadimageAviso").notify(
                    "Esta imagen no se permite eliminar", "error"
                );
            }
        }
    }));

    $("#formsDetailsBody").on('click', '#delImagenContrato:not(:disabled)',  (function(e) {
        e.preventDefault();
        var tipo = 'contrato';
        var elementImage =$('div.active a[data-lightbox="example-contrato"]');
        if (lengthImageActive > 0) {
            var nombre = $('div.active a[data-lightbox="example-contrato"]').attr("href");
            var arrNombre = nombre.split('/');
            if (arrNombre.length > 6 ) {
                deleteImageVentas(tipo);
            }else if(arrNombre.length === 0){
                $("#uploadimageContrato").notify(
                    "No existe una imagen para eliminar", "error"
                );
            }else if(arrNombre.length === 6){
                $("#uploadimageContrato").notify(
                    "Esta imagen no se permite eliminar", "error"
                );
            }
        }
    }));

    $("#formsDetailsBody").on('click', '#delImagenCuadroMed:not(:disabled)',  (function(e) {
        e.preventDefault();
        var tipo = 'cuadro';
        elementImage =$('div.active a[data-lightbox="example-cuadro"]');
        if (lengthImageActive > 0) {
            var nombre = $('div.active a[data-lightbox="example-cuadro"]').attr("href");
            var arrNombre = nombre.split('/');
            if (arrNombre.length > 6 ) {
                deleteImageVentas(tipo);
            }else if(arrNombre.length === 0){
                $("#uploadimageCM").notify(
                    "No existe una imagen para eliminar", "error"
                );
            }else if(arrNombre.length === 6){
                $("#uploadimageCM").notify(
                    "Esta imagen no se permite eliminar", "error"
                );
            }
        }
    }));

    function uploadImageVentas(fileImage, typeImage) {
        //generalizamos el codigo para no repetirlo
        var imagefile = fileImage.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
            switch(typeImage) {
                case 'comprobante':
                    $("#uploadimageComp").notify(
                        "El archivo seleccionado no es permitido", "error"
                    );
                    break;
                case 'identificacion':
                    $("#uploadimageID").notify(
                        "El archivo seleccionado no es permitido", "error"
                    );
                    break;
                case 'solicitud':
                    $("#uploadimageSolicitud").notify(
                        "El archivo seleccionado no es permitido", "error"
                    );
                    break;
                case 'aviso':
                    $("#uploadimageAviso").notify(
                        "El archivo seleccionado no es permitido", "error"
                    );
                    break;
                case 'contrato':
                    $("#uploadimageContrato").notify(
                        "El archivo seleccionado no es permitido", "error"
                    );
                    break;
                case 'pagare':
                    $("#uploadimagePagare").notify(
                        "El archivo seleccionado no es permitido", "error"
                    );
                    break;
                case 'cuadro':
                    $("#uploadimageCM").notify(
                        "El archivo seleccionado no es permitido", "error"
                    );
                    break;
            }
            return false;
        } else {
            var lengthImageActive=0;
            var elementImage=""
            switch(typeImage) {
                case "comprobante":
                    $("#uploadimageComp").notify(
                        "Cargando imagen", "info"
                    );
                    elementImage =$('div.active a[data-lightbox="example-comprobante"]');
                    break;
                case "identificacion":
                    $("#uploadimageID").notify(
                        "Cargando imagen", "info"
                    );
                    elementImage =$('div.active a[data-lightbox="example-identificacion"]');
                    break;
                case "solicitud":
                    $("#uploadimageSolicitud").notify(
                        "Cargando imagen", "info"
                    );
                    elementImage =$('div.active a[data-lightbox="example-solicitud"]');
                    break;
                case "aviso":
                    $("#uploadimageAviso").notify(
                        "Cargando imagen", "info"
                    );
                    elementImage =$('div.active a[data-lightbox="example-aviso"]');
                    break;
                case "contrato":
                    $("#uploadimageContrato").notify(
                        "Cargando imagen", "info"
                    );
                    elementImage =$('div.active a[data-lightbox="example-contrato"]');
                    break;
                case "pagare":
                    $("#uploadimagePagare").notify(
                        "Cargando imagen", "info"
                    );
                    elementImage =$('div.active a[data-lightbox="example-pagare"]');
                    break;
                case 'cuadro':
                    $("#uploadimageCM").notify(
                        "Cargando imagen", "info"
                    );
                    elementImage =$('div.active a[data-lightbox="example-cuadro"]');
                    break;
            }
            //var filesLoad=this.files[0];
            var idReporte=parseInt($("#consecutive").val());
            lengthImageActive=elementImage.length;
            var nameImage="";
            var arrayNameImage="";
            if (lengthImageActive > 0) {
                nameImage= elementImage.attr("href");
                arrayNameImage= nameImage.split("/");
                nameImage=arrayNameImage[10];
            }
            //$('div.active a[data-lightbox="example-solicitud"]').attr("href").split("/")
            var formData = new FormData();
            var month=moment().month();
            var year=moment().year();
            if (typeImage === 'cuadro') {
                var statusInstalacion=parseInt($("#statusInstalacion").val());
                var idReporte=parseInt($("#idReporteF").val());
                formData.append("reportID", idReporte);
                formData.append("tipoImg", typeImage);
                formData.append("nameImage", nameImage);
                formData.append("statusInstalacion", statusInstalacion);
                formData.append(
                    "PostImage", 
                    fileImage
                ); 
            }else{
                var financialServiceVal=parseInt($("#financialServiceVal").val());
                formData.append("reportID", idReporte);
                formData.append("tipoImg", typeImage);
                formData.append("nameImage", nameImage);
                formData.append("financialServiceVal", financialServiceVal);
                formData.append(
                    "PostImage", 
                    fileImage
                );
            }
                
            $.ajax({
                url: "dataLayer/callsWeb/uploadImage.php", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data:formData, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                dataType: "JSON",
                success: function(data){
                    var respnseCode=parseInt(data.code);
                    if (respnseCode === 200) {
                        switch(typeImage) {
                            case 'comprobante':
                                $("#uploadimageComp").notify(
                                    "La imagen se cargo con exito", "success"
                                );
                                break;
                            case 'identificacion':
                                $("#uploadimageID").notify(
                                    "La imagen se cargo con exito", "success"
                                );
                                break;
                            case 'solicitud':
                                $("#uploadimageSolicitud").notify(
                                    "La imagen se cargo con exito", "success"
                                );
                                break;
                            case 'aviso':
                                $("#uploadimageAviso").notify(
                                    "La imagen se cargo con exito", "success"
                                );
                                break;
                            case 'contrato':
                                $("#uploadimageContrato").notify(
                                    "La imagen se cargo con exito", "success"
                                );
                                break;
                            case 'pagare':
                                $("#uploadimagePagare").notify(
                                    "La imagen se cargo con exito", "success"
                                );
                                break;
                            case 'cuadro':
                                $("#uploadimageCM").notify(
                                    "La imagen se cargo con exito", "success"
                                );
                                break;
                        }
                        imageIsLoadedVentas(data.responseRuta, data.responseImg, typeImage);
                    }else if (respnseCode === 500){
                        switch(typeImage) {
                            case 'comprobante':
                                $("#uploadimageComp").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'identificacion':
                                $("#uploadimageID").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'solicitud':
                                $("#uploadimageSolicitud").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'aviso':
                                $("#uploadimageAviso").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'contrato':
                                $("#uploadimageContrato").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'pagare':
                                $("#uploadimagePagare").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'cuadro':
                                $("#uploadimageCM").notify(
                                    data.response, "error"
                                );
                                break;
                        }
                    }
                }
            });
        }
    }

    function deleteImageVentas(typeImage) {
        var idReporte=parseInt($("#consecutive").val());
        var lengthImageActive=0;
        var elementImage=""
        switch(typeImage) {
            case "comprobante":
                $("#uploadimageComp").notify(
                    "Cargando imagen", "info"
                );
                elementImage =$('div.active a[data-lightbox="example-comprobante"]');
                break;
            case "identificacion":
                $("#uploadimageID").notify(
                    "Eliminando imagen", "info"
                );
                elementImage =$('div.active a[data-lightbox="example-identificacion"]');
                break;
            case "solicitud":
                $("#uploadimageSolicitud").notify(
                    "Eliminando imagen", "info"
                );
                elementImage =$('div.active a[data-lightbox="example-solicitud"]');
                break;
            case "aviso":
                $("#uploadimageAviso").notify(
                    "Eliminando imagen", "info"
                );
                elementImage =$('div.active a[data-lightbox="example-aviso"]');
                break;
            case "contrato":
                $("#uploadimageContrato").notify(
                    "Eliminando imagen", "info"
                );
                elementImage =$('div.active a[data-lightbox="example-contrato"]');
                break;
            case "pagare":
                $("#uploadimagePagare").notify(
                    "Eliminando imagen", "info"
                );
                elementImage =$('div.active a[data-lightbox="example-pagare"]');
                break;
            case 'cuadro':
                $("#uploadimageCM").notify(
                    "Eliminando imagen", "info"
                );
                elementImage =$('div.active a[data-lightbox="example-cuadro"]');
                break;
        }
        //var filesLoad=this.files[0];
        var idReporte=parseInt($("#consecutive").val());
        if(typeImage === 'cuadro'){
            idReporte=parseInt($("#idReporteF").val());
        }
        lengthImageActive=elementImage.length;
        var nameImage="";
        var res_imgComprobante="";
        if (lengthImageActive > 0) {
            nameImage= elementImage.attr("href");
            arrayNameImage= nameImage.split("/");
            nameImage=arrayNameImage[10];
            if (typeImage === 'cuadro') {
                nameImage=arrayNameImage[9];    
            }
            //nameImage=arrayNameImage[11];
        }
        console.log('nameImage', nameImage);
        var financialServiceVal=parseInt($("#financialServiceVal").val());
        if (nameImage !== "") {
            $.ajax({
                url: "dataLayer/callsWeb/deleteImage.php", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data:{
                    filenameImg:nameImage,
                    tipoImg:typeImage,
                    idReporte:idReporte,
                    financialServiceVal:financialServiceVal
                }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                dataType: "JSON",
                success: function(data){
                    console.log('data', data);
                    var respnseCode=parseInt(data.code);
                    if (respnseCode === 200) {
                        switch(typeImage) {
                            case 'comprobante':
                                $("#uploadimageComp").notify(
                                    "La imagen se elimino con exito", "success"
                                );
                                break;
                            case 'identificacion':
                                $("#uploadimageID").notify(
                                    "La imagen se elimino con exito", "success"
                                );
                                break;
                            case 'solicitud':
                                $("#uploadimageSolicitud").notify(
                                    "La imagen se elimino con exito", "success"
                                );
                                break;
                            case 'aviso':
                                $("#uploadimageAviso").notify(
                                    "La imagen se elimino con exito", "success"
                                );
                                break;
                            case 'contrato':
                                $("#uploadimageContrato").notify(
                                    "La imagen se elimino con exito", "success"
                                );
                                break;
                            case 'pagare':
                                $("#uploadimagePagare").notify(
                                    "La imagen se elimino con exito", "success"
                                );
                                break;
                            case 'cuadro':
                                $("#uploadimageCM").notify(
                                    "La imagen se elimino con exito", "success"
                                );
                                break;
                        }
                        imageIsLoadedVentas(data.responseRuta, data.responseImg, typeImage);
                    }else if (respnseCode === 500){
                        switch(typeImage) {
                            case 'comprobante':
                                $("#uploadimageComp").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'identificacion':
                                $("#uploadimageID").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'solicitud':
                                $("#uploadimageSolicitud").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'aviso':
                                $("#uploadimageAviso").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'contrato':
                                $("#uploadimageContrato").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'pagare':
                                $("#uploadimagePagare").notify(
                                    data.response, "error"
                                );
                                break;
                            case 'cuadro':
                                $("#uploadimageCM").notify(
                                    data.response, "error"
                                );
                                break;
                        }
                    }
                }
            });
        }else{
            switch(typeImage) {
                case 'comprobante':
                $("#uploadimageComp").notify(
                    'No existe imagen por eliminar', "error"
                );
                break;
                case "identificacion":
                    $("#uploadimageID").notify(
                        'No existe imagen por eliminar', "error"
                    );
                    break;
                case "solicitud":
                    $("#uploadimageSolicitud").notify(
                        'No existe imagen por eliminar', "error"
                    );
                    break;
                case "aviso":
                    $("#uploadimageAviso").notify(
                        'No existe imagen por eliminar', "error"
                    );
                    break;
                case "contrato":
                    $("#uploadimageContrato").notify(
                        'No existe imagen por eliminar', "error"
                    );
                    break;
                case "pagare":
                    $("#uploadimagePagare").notify(
                        'No existe imagen por eliminar', "error"
                    );
                    break;
                case 'cuadro':
                    $("#uploadimageCM").notify(
                        'No existe imagen por eliminar', "error"
                    );
                    break;
            }
        } 
    }
    
    function imageIsLoadedVentas(responseRuta,arrImages, tipoImage) {
        var existeKImg=_.has(arrImages, "arrIMG");
        if (existeKImg === true && responseRuta !== "") {
            var nombreImg1,nombreImg2, elementsTuberia, itemsTuberia, elementsTuberiaOP, itemsTuberiaOP, elementsPieDerecho,itemsPieDer,elementsPieDerechoOP,itemsPieDerOP;
                var zero = true;
                var first = true;
                var numElem = 1;
                var elementsProof = "";
                var itemsProof = "";
                var elementsProofOP = "";
                var itemsProofOP = "";
                var carrusel="";
                var carruselOP="";
                var lightboxCar="";
                var idCarrusel="";
                switch(tipoImage) {
                    case 'comprobante':
                        carrusel = $("#myCarousel");
                        carruselOP = $("#myCarouselOP");
                        lightboxCar = $("a[data-lightbox='example-comprobante']");
                        idCarrusel= {normal:"#myCarousel", normalOp:"#myCarouselOP"};
                        break;
                    case 'identificacion':
                        carrusel = $("#myCarousel4");
                        carruselOP = $("#myCarousel4OP");
                        lightboxCar = $("a[data-lightbox='example-identificacion']");
                        idCarrusel= {normal:"#myCarousel4", normalOp:"#myCarousel4OP"};
                        break;
                    case 'solicitud':
                        carrusel = $("#myCarousel2");
                        carruselOP = $("#myCarousel2OP");
                        lightboxCar = $("a[data-lightbox='example-solicitud']");
                        idCarrusel= {normal:"#myCarousel2", normalOp:"#myCarousel2OP"};
                        break;
                    case 'aviso':
                        carrusel = $("#myCarousel3");
                        carruselOP = $("#myCarousel3");
                        lightboxCar = $("a[data-lightbox='example-aviso']");
                        idCarrusel= {normal:"#myCarousel3", normalOp:"#myCarousel3"};
                        break;
                    case 'contrato':
                        carrusel = $("#myCarousel6");
                        carruselOP = $("#myCarousel6");
                        lightboxCar = $("a[data-lightbox='example-contrato']");
                        idCarrusel= {normal:"#myCarousel6", normalOp:"#myCarousel6"};
                        break;
                    case 'pagare':
                        carrusel = $("#myCarousel5");
                        carruselOP = $("#myCarousel5");
                        lightboxCar = $("a[data-lightbox='example-pagare']");
                        idCarrusel= {normal:"#myCarousel5", normalOp:"#myCarousel5"};
                        break;
                    case 'cuadro':
                        carrusel = $("#myCarousel3");
                        carruselOP = $("#myCarousel3");
                        lightboxCar = $("a[data-lightbox='example-cuadro']");
                        idCarrusel= {normal:"#myCarousel3", normalOp:"#myCarousel3"};
                        break;
                }

                carrusel.html("");
                carruselOP.html("");
                //lightboxCar.attr('href', "");
                if (existeKImg === true > 0) {
                    _.each(arrImages.arrIMG, function (dataImg, idx) {
                        var FileType = getFileType(dataImg.nameIMG);
                        var urlFinal = responseRuta + dataImg.nameIMG;
                        if (first) {
                            numElem = 1;
                            elementsProof += '<li data-target="'+idCarrusel.normal+'" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsProof += '<div class="item active">';
                            itemsProof += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-' +  tipoImage + '" >';
                            itemsProof += '<img src="' +  urlFinal + '" alt="' +  tipoImage + '" height="256px" width="256px" style="" />';
                            itemsProof += '</a>';
                            itemsProof += '</div>';

                            elementsProofOP += '<li data-target="'+idCarrusel.normalOp+'" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsProofOP += '<div class="item active">';
                            itemsProofOP += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-' +  tipoImage + '" >';
                            itemsProofOP += '<img src="' +  urlFinal + '" alt="' +  tipoImage + '" height="1024px" width="1248px" style="" />';
                            itemsProofOP += '</a>';
                            itemsProofOP += '</div>';

                            first = false;
                        } else {
                            elementsProof += '<li data-target="'+idCarrusel.normal+'" data-slide-to="' + numElem + '"></li>';

                            itemsProof += '<div class="item">';
                            itemsProof += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-' +  tipoImage + '" >';
                            itemsProof += '<img src="' +  urlFinal + '" alt="' +  tipoImage + '" height="256px" width="256px" style="" />';
                            itemsProof += '</a>';
                            itemsProof += '</div>';

                            elementsProofOP += '<li data-target="'+idCarrusel.normalOp+'" data-slide-to="' + numElem + '"></li>';

                            itemsProofOP += '<div class="item">';
                            itemsProofOP += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-' +  tipoImage + '" >';
                            itemsProofOP += '<img src="' +  urlFinal + '" alt="' +  tipoImage + '" height="1024px" width="1248px" style="" />';
                            itemsProofOP += '</a>';
                            itemsProofOP += '</div>';
                        }
                        numElem++;
                    })
                }

                var carouselProof = '<div id="'+idCarrusel.normalOp+'" class="carousel slide"><div class="carousel-inner">' + itemsProofOP + '</div>' + '<a class="left carousel-control" href="'+idCarrusel.normalOp+'" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="'+idCarrusel.normalOp+'" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                $('#formSlideShowBody').append(carouselProof);
                var htmlAppendComprobante = '<div class="carousel-inner">' + itemsProof + '</div>' + '<a class="left carousel-control" href="'+idCarrusel.normal+'" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="'+idCarrusel.normal+'" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>';
                carrusel.append(htmlAppendComprobante);
        }else{
            switch(tipoImage) {
                case 'comprobante':
                    $("#myCarousel").html("");
                    $("#myCarouselOP").html("");
                    break;
                case 'identificacion':
                    $("#myCarousel4").html("");
                    $("#myCarousel4OP").html("");
                    break;
                case 'solicitud':
                    $("#myCarousel2").html("");
                    $("#myCarousel2OP").html("");
                    break;
                case 'aviso':
                    $("#myCarousel3").html("");
                    break;
                case 'contrato':
                    $("#myCarousel6").html("");
                    break;
                case 'pagare':
                    $("#myCarousel5").html("");
                    break;
            }
        }
        carrusel = null;
        carruselOP = null;
        idCarrusel = null;
    };

    function imageIsLoadedTuberia(responseRuta,arrImages) {
        console.log('imageIsLoadedTuberia', arrImages);
        var existeKImg=_.has(arrImages, "arrIMG");
        if (existeKImg === true && responseRuta !== "") {
            console.log('imageIsLoadedTuberia', arrImages);
            var nombreImg1,nombreImg2, elementsTuberia, itemsTuberia, elementsTuberiaOP, itemsTuberiaOP, elementsPieDerecho,itemsPieDer,elementsPieDerechoOP,itemsPieDerOP;
                var zero = true;
                var first = true;
                var numElem = 1;
                var elementsTuberia = "";
                var itemsTuberia = "";
                var elementsTuberiaOP = "";
                var itemsTuberiaOP = "";
                var elementsPieDerecho = "";
                var itemsPieDer = "";
                var elementsPieDerechoOP = "";
                var itemsPieDerOP = "";
                $("#myCarousel2").html("");
                $("#myCarousel2OP").html("");
                $("a[data-lightbox='example-tuberia']").removeAttr("href");
                if (existeKImg === true > 0) {
                    _.each(arrImages.arrIMG, function (dataImg, idx) {
                        console.log('dataImg', dataImg);
                        var FileType = getFileType(dataImg.nameIMG);
                        var urlFinal = responseRuta + dataImg.nameIMG;
                        //if (FileType == 'foto_tuberia') {
                        if (FileType == 'tuberia') {
                            
                            if (zero) {
                                elementsTuberia += '<li data-target="#myCarousel2" data-slide-to="' + dataImg.nameIMG + '" class="active"></li>';

                                itemsTuberia += '<div class="item active">';
                                itemsTuberia += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-tuberia" >';
                                //itemsTuberia += '<img src="' +  urlFinal + '" alt="tuberia" height="256px" width="256px" />';
                                itemsTuberia += '<img src="' +  urlFinal + '" alt="tuberia" height="256px" width="256px" style="" />';
                                itemsTuberia += '</a>';
                                itemsTuberia += '</div>';

                                elementsTuberiaOP += '<li data-target="#myCarousel2OP" data-slide-to="' + dataImg.nameIMG + '" class="active"></li>';

                                itemsTuberiaOP += '<div class="item active">';
                                itemsTuberiaOP += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-tuberia" >';
                                itemsTuberiaOP += '<img src="' +  urlFinal + '" alt="tuberia" height="1024px" width="1248px" style="" />';
                                itemsTuberiaOP += '</a>';
                                itemsTuberiaOP += '</div>';

                                zero = false;
                            } else {
                                elementsTuberia += '<li data-target="#myCarousel2" data-slide-to="' + dataImg.nameIMG + '"></li>';

                                itemsTuberia += '<div class="item">';
                                itemsTuberia += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-tuberia" >';
                                itemsTuberia += '<img src="' +  urlFinal + '" alt="tuberia" height="256px" width="256px" style="" />';
                                itemsTuberia += '</a>';
                                itemsTuberia += '</div>';

                                elementsTuberiaOP += '<li data-target="#myCarousel2OP" data-slide-to="' + dataImg.nameIMG + '"></li>';

                                itemsTuberiaOP += '<div class="item">';
                                itemsTuberiaOP += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-tuberia" >';
                                itemsTuberiaOP += '<img src="' +  urlFinal + '" alt="tuberia" height="1024px" width="1248px" style="" />';
                                itemsTuberiaOP += '</a>';
                                itemsTuberiaOP += '</div>';
                            }
                            numElem++;
                            //} else if (FileType == 'foto_pie_derecho') {

                        }
                    })
                }
                var carouselTuberia = '<div id="myCarousel2OP" class="carousel slide"><div class="carousel-inner">' + itemsTuberiaOP + '</div>' + '<a class="left carousel-control" href="#myCarousel2OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                $('#formSlideShowBody').append(carouselTuberia);
                var htmlAppendTuberia = '<div class="carousel-inner">' + itemsTuberia + '</div>' + '<a class="left carousel-control" href="#myCarousel2" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div><br/><br/></a>';
                $('#myCarousel2').append(htmlAppendTuberia);
        }else{
            $("#myCarousel2").html("");
            $("#myCarousel2OP").html("");
        }
    };

    function imageIsLoadedPieDerecho(responseRuta,arrImages) {
        console.log('imageIsLoadedPieDerecho');
        var existeKImg=_.has(arrImages, "arrIMG");
        if (existeKImg === true && responseRuta !== "") {
            console.log('imageIsLoadedPieDerecho');
            var nombreImg1,nombreImg2, elementsTuberia, itemsTuberia, elementsTuberiaOP, itemsTuberiaOP, elementsPieDerecho,itemsPieDer,elementsPieDerechoOP,itemsPieDerOP;
                var zero = true;
                var first = true;
                var numElem = 1;
                var elementsTuberia = "";
                var itemsTuberia = "";
                var elementsTuberiaOP = "";
                var itemsTuberiaOP = "";
                var elementsPieDerecho = "";
                var itemsPieDer = "";
                var elementsPieDerechoOP = "";
                var itemsPieDerOP = "";
                $("#myCarousel3").html("");
                $("#myCarousel3OP").html("");
                $("a[data-lightbox='example-pie']").removeAttr("href");
                if (existeKImg === true) {
                    _.each(arrImages.arrIMG, function (dataImg, idx) {
                        //var FileType = getFileType(dataImg.nameIMG);
                        var urlFinal = responseRuta + dataImg.nameIMG;
                        console.log('responseRuta',responseRuta);
                        //if (FileType == 'foto_tuberia') {
                        if (first) {
                            numElem = 1;
                            elementsPieDerecho += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsPieDer += '<div class="item active">';
                            itemsPieDer += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-pie" >';
                            //itemsPieDer += '<img src="' +  urlFinal + '" alt="pie_derecho" height="256px" width="256px" />';
                            itemsPieDer += '<img src="' +  urlFinal + '" alt="pie_derecho" height="256px" width="256px" style="" />';
                            itemsPieDer += '</a>';
                            itemsPieDer += '</div>';

                            elementsPieDerechoOP += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsPieDerOP += '<div class="item active">';
                            itemsPieDerOP += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-pie" >';
                            itemsPieDerOP += '<img src="' +  urlFinal + '" alt="pie_derecho" height="256px" width="256px" style="" />';
                            itemsPieDerOP += '</a>';
                            itemsPieDerOP += '</div>';

                            first = false;
                        } else {
                            elementsPieDerecho += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '"></li>';

                            itemsPieDer += '<div class="item">';
                            itemsPieDer += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-pie" >';
                            itemsPieDer += '<img src="' +  urlFinal + '" alt="pie_derecho" height="256px" width="256px" style="" />';
                            itemsPieDer += '</a>';
                            itemsPieDer += '</div>';

                            elementsPieDerechoOP += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsPieDerOP += '<div class="item">';
                            itemsPieDerOP += '<a class="example-image-link" href="' +  urlFinal + '" data-lightbox="example-pie" >';
                            itemsPieDerOP += '<img src="' +  urlFinal + '" alt="pie_derecho" height="256px" width="256px" style="" />';
                            itemsPieDerOP += '</a>';
                            itemsPieDerOP += '</div>';
                        }
                        numElem++;
                    })
                }
                 var carouselPieDer = '<div id="myCarousel3OP" class="carousel slide"><div class="carousel-inner">' + itemsPieDerOP + '</div>' + '<a class="left carousel-control" href="#myCarousel3OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                $('#formSlideShowBody').append(carouselPieDer);
                 var htmlAppendPie = '<div class="carousel-inner">' + itemsPieDer + '</div>' + '<a class="left carousel-control" href="#myCarousel3" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div><br/><br/></a>';
                $('#myCarousel3').append(htmlAppendPie);
        }else{
            $("#myCarousel3").html("");
            $("#myCarousel3OP").html("");
        }
    };

    $('#formsDetailsBody').on('click', '.eliminaMaterial:not(:disabled)', function(e) {
        e.preventDefault();
        //console.log('$(".eliminaMaterial").attr("data-id")', $(".eliminaMaterial").attr("data-id"));
        $(".eliminaMaterial").prop('disabled', true);
        var isChecked=$("#editInstall").is(':checked');
        if (isChecked === true) {
            var dataID=parseInt($(this).attr("data-id"));
            console.log('dataID', dataID)
            if (dataID > 0) {
                console.log('entre');
                deleteMaterialInst(dataID);
            }
        }
    });

    $('#formsDetailsBody').on('click', '#saveInstalacion:not(:disabled)', function(e) {
        e.preventDefault();
        //console.log('$(".eliminaMaterial").attr("data-id")', $(".eliminaMaterial").attr("data-id"));
        $("#saveInstalacion").prop('disabled', true);
        var isChecked=$("#editInstall").is(':checked');
        if (isChecked === true) {
            var dataID=parseInt($("#addMaterial").attr("data-id"));
            if (dataID > 0) {
                console.log('entre');
                var serialNumber=$("#serialNumber").val();
                var measurement=$("#measurement").val();
                updateFormInstallation(dataID, serialNumber, measurement);
            }
        }
    });

    $('#formsDetailsBody').on('click', '#savePlomeria:not(:disabled)', function(e) {
        e.preventDefault();
        $("#savePlomeria").prop('disabled', true);
        var isChecked=$("#editPlomeria").is(':checked');
        if (isChecked === true) {
            var dataID=parseInt($("#consecutiveID").val());
            if (dataID > 0) {
                var colorTapon = $("#colorTapon").is(":checked");
                var riMenor = $("#riMenor").is(":checked");
                var reqTuberia = $("#reqTuberia").is(":checked");
                var numTomas = $("#numTomasSel option:selected").val();
                colorTapon = (colorTapon === true ) ? 1 : 0;
                reqTuberia = (reqTuberia === true ) ? 1 : 0;
                riMenor = (riMenor === true ) ? 1 : 0;
                updateFormPlumber(dataID, colorTapon, riMenor, reqTuberia, numTomas);
            }
        }
    });

    function updateFormPlumber(dataID, colorTapon, riMenor, reqTuberia, numTomas) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/updateFormPlumber.php",
            dataType: "JSON",
            data: {
                dataID:dataID, 
                colorTapon:colorTapon, 
                riMenor:riMenor, 
                reqTuberia:reqTuberia,
                numTomas:numTomas,
                tipoSentencia: 'update'
            },
            success: function (data) {
                if (data.code === '500' || data.code === 500) {
                    $("#addMaterial").notify(
                        "Ocurrio un error al actualizar la informacion", "error"
                    );
                }else if(data.code === '200' || data.code === 200){
                    //$("#numTomasSel").val(0);
                    $("#savePlomeria").notify(
                        "La informacion se actualizo correctamente", "success"
                    );
                    $("#savePlomeria").prop('disabled', false);
                }
            }
        });
    }

    function updateFormInstallation(idFI, serialNumber, measurement) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/insertarMaterial.php",
            dataType: "JSON",
            data: {
                idFI: idFI,
                serialNumber: serialNumber,
                measurement: measurement,
                tipoSentencia: 'update'
            },
            success: function (data) {
                console.log('data', data);
                if (data.code === '500' || data.code === 500) {
                    $("#addMaterial").notify(
                        "Ocurrio un error al actualizar la informacion", "error"
                    );
                }else if(data.code === '200' || data.code === 200){
                    $("#materialSelect").val(0);
                    $("#cantidadMaterial").val('');
                    $("#saveInstalacion").notify(
                        "La informacion se actualizo correctamente", "success"
                    );
                    $("#saveInstalacion").prop('disabled', false);
                }
            }
        });
    }

    function loadMaterial() {
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/catInstalacion.php",
            dataType: "JSON",
            success: function (data) {
                if (_.has(data, 'response')) {
                    $('#materialSelect').html('');
                    $('#materialSelect').append('<option value="0">Todos los tipos</option>');
                    _.each(data.response.respuesta, function (material, idx) {
                        $('#materialSelect').append('<option value="' + material.id + '">' + material.desc + '</option>');
                    });
                }
            }
        });
    }


    function insertMaterialInst(idFI, qty, material) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/insertarMaterial.php",
            dataType: "JSON",
            data: {
                idFI: idFI,
                qty: qty,
                material: material,
                tipoSentencia: 'insert'
            },
            success: function (data) {
                console.log('data', data);
                if (data.code === '500' || data.code === 500) {
                    $("#addMaterial").notify(
                        "Ocurrio un error al insertar un material", "error"
                    );
                }else if(data.code === '200' || data.code === 200){
                    $("#materialSelect").val(0);
                    $("#cantidadMaterial").val('');
                    $("#addMaterial").notify(
                        "La informacion se agrego correctamente", "success"
                    );
                    loadMaterialInst(idFI);
                    $("#addMaterial").prop('disabled', false);
                }
            }
        });
    }

    function deleteMaterialInst(idFI) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/insertarMaterial.php",
            dataType: "JSON",
            data: {
                idFI: idFI,
                tipoSentencia: 'delete'
            },
            success: function (data) {
                console.log('data', data);
                if (data.code === '500' || data.code === 500) {
                    $("#addMaterial").notify(
                        "Ocurrio un error al eliminar un material", "error"
                    );
                }else if(data.code === '200' || data.code === 200){
                    $("#addMaterial").notify(
                        "La informacion se elimino correctamente", "success"
                    );
                    var dataID=parseInt($("#addMaterial").attr("data-id"));
                    loadMaterialInst(dataID);
                    $(".eliminaMaterial").prop('disabled', false);
                }
            }
        });
    }

    function loadMaterialInst(idFI) {
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/getDInstall.php",
            dataType: "JSON",
            data: {
                idFI: idFI
            },
            success: function (data) {
                console.log('data', data);
                var contador, detailsHtml;
                if (_.has(data, 'response')) {
                    $('#detailsMat').html('');
                    _.each(data.response.respuesta, function (material, idx) {
                        contador= parseInt(idx) + parseInt(1);
                        detailsHtml+="<tr>" + "<th scope='row' >" + contador + "</th>";
                        detailsHtml+="<td>" + material.material + "</td>";
                        detailsHtml+='<td><input class="form-control" data-id="' + material.id + '" type="text" value="' + material.qty + '"></td><td><span><button type="button" class="eliminaMaterial btn btn-sm" data-id="' + material.id + '"><i class="fa fa-times" aria-hidden="true"><label>Eliminar</label></i></span></button></td></tr>';
                    });

                    $('#detailsMat').append(detailsHtml);
                }
            }
        });
    }

    function generarAsignacion(id,employeeToAssing,report,employeeProfile,msg, agency){
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/assingReport.php",
            dataType: "JSON",
            data: {
                param: id, employee: employeeToAssing, reports: report, profile: employeeProfile, agency: agency
            },
            success: function (data) {
                if (data.status == "ERROR") {
                    MostrarToast(2, "Fallo en Asignacion", "Fallo en el proceso Asignacion de Instalacion. Favor de revisar si existe una agencia instaladora para la ciudad de esta venta.");
                } else {
                    if (data.code === '500' || data.code === 500) {
                        MostrarToast(2, "Fallo en Asignacion", data.result);
                        $('#taskForm').modal('hide');
                        configurarToastCentrado();
                        $('#formInstallationSale').trigger("reset");
                        $('#txtTaskAgency').empty().append('whatever');
                        $('#txtTaskEmployee').empty().append('whatever');
                        $('#btnAssign').prop('disabled', false);
                    }else if(data.code === '200' || data.code === 200){
                        MostrarToast(1, "Asignación Finalizada", data.result);
                        $.getScript( "assets/js/clases/notificaciones.js" )
                        .done(function( script, textStatus ) {
                            notificacionPlomeriaSecondSell(employeeToAssing,msg);
                            console.log('textStatus', textStatus);
                        })
                        .fail(function( jqxhr, settings, exception ) {
                            $( "div.log" ).text( "Triggered ajaxError handler." );
                        });
                        $('#taskForm').modal('hide');
                        configurarToastCentrado();
                        //loadMain();
                        window.location='forms.php';
                        $('#formInstallationSale').trigger("reset");
                        $('#txtTaskAgency').empty().append('whatever');
                        $('#txtTaskEmployee').empty().append('whatever');
                        $('#btnAssign').prop('disabled', false);
                    }else if(data.code === '100' || data.code === 100){
                        MostrarToast(1, "Asignación Finalizada", data.result);
                        window.location='forms.php';
                    }
                }

            }, error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
    $(document).on('change', '#txtCompanyGeneral', function () {
        var agency = $("#txtCompanyGeneral").val();
        loadUsersGeneral(agency);
    });
    /*--------------------------------Load and Send Sell--------------------------------*/

    /*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!Get Colonias & Streets!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/
    $(document).on('change', '#txMun', function () {
        var city = $("#txMun").val();
        //var colonia = $("#txtCol").val();
        //if (colonia !== "") {
        $("#txtCol").html('');
        $("#txtStreet").html('');
        $("#txtRoads").html('');
        $("#txtNumber").html('');
        $("#txtCP").html('');
        //}
        if (city > 0) {
            loadColonias(city);
        } else {
            alert("No Hay Ciudad Seleccionada");
        }
    });

    $(document).on('change', '#txtCol', function () {
        var city = $("#txMun").val();
        var colonia = $("#txtCol").val();
        var streetLoaded = $("#txtStreet").val();

        $("#txtRoads").html('');
        $("#txtNumber").html('');
        $("#txtCP").html('');
        //}
        if (colonia > 0) {
            loadStreets(city, colonia);
        } else {
            colonia("No Hay Municipio/Colonia Seleccionados");
        }
    });

    $(document).on('change', '#txtStreet', function () {
        //directions
        //var colonia = $("#txtCol").val();
        var streetSelected = $("#txtStreet").val();
        var roads = [];
        var numbers = [];
        $("#txtRoads").html('');
        $("#txtNumber").html('');
        console.log(directions);
        roads = getEntreCalles(streetSelected, directions);
        roads = deleteDuplicates(roads);
        console.log(roads);

        $('#txtRoads').append("<option value=''>SELECCIONAR</option>");

        for (var road in roads) {
            if (roads[road] !== null && roads[road] !== "") {
                $('#txtRoads').append('<option value="' + roads[road] + '">' + roads[road] + '</option>');
            }
        }
        numbers = getNumeros(streetSelected, directions);
        numbers = deleteDuplicates(numbers);
        console.log(numbers);

        $('#txtNumber').append("<option value=''>SELECCIONAR</option>");

        for (var number in numbers) {
            if (numbers[number] !== null && numbers[number] !== "") {
                $('#txtNumber').append('<option value="' + numbers[number] + '">' + numbers[number] + '</option>');
            }
        }

    });

    /*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!Get Colonias & Streets!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/

    /*-----------------------------------Load Companies, Users-----------------------------------*/
    function loadCompanies() {
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/companiesName.php",
            dataType: "JSON",
            success: function (data) {
                for (var value in data) {
                    $("#txtCompany").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                    $("#txtCompanyGeneral").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                }
            }
        });
    }

    function loadUsers(elem, param, agency) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/showUsers.php",
            data: {elem: elem, param: param, agency: agency},
            dataType: "JSON",
            success: function (data) {
                console.log('dataEmployes', data);
                var agency = data.selectedAgency;
                var agenciesName = data.Agencies;
                var employeesName = data.Employees;
                $('#txtUsers').html('');
                $('#txtTaskEmployee').html('');
                if (employeesName !== null || employeesName !== NaN) {
                    for (var valueEmployee in employeesName) {
                            $('#txtUsers').append('<option value="' + employeesName[valueEmployee].id + '">' + employeesName[valueEmployee].employee + '</option>');
                            //$('#txtTaskEmployee').append('<option value="' + employeesName[valueEmployee].id + '">' + employeesName[valueEmployee].employee + '</option>')
                    }
                }
            }
        });
    }

    function loadUsersGeneral(elem) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/usersByAgency.php",
            data: {agency: elem},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                for (var value in data)
                    $("#txtEmployeeGeneral").append("<option value=" + data[value].id + ">" + data[value].employee + "</option>");
            }
        });
    }

    function loadAgencies(param) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/companiesName.php",
            data: {param: param},
            dataType: "JSON",
            success: function (data) {
                //console.log(data);
                $('#txtAgency').html('');
                //$('#txtTaskAgency').html('');
                for (var arg in data) {
                    $('#txtAgency').append('<option value="' + data[arg].id + '">' + data[arg].agency + '</option>');
                    //$('#txtTaskAgency').append('<option value="' + data[arg].id + '">' + data[arg].agency + '</option>');
                }
                var idAgency = $('#txtAgency').val();
                loadEmployees(idAgency);
            }
        });
    }

    function loadEmployeesTask(agency) {
        console.log(agency);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/usersByAgency.php",
            data: {agency: agency},
            dataType: "JSON",
            success: function (data) {
                $('#txtTaskEmployee').html('');
                for (var value in data)
                    $("#txtTaskEmployee").append("<option value=" + data[value].id + ">" + data[value].employee + "</option>");
            }
        });
    }

    function loadAgenciesTask(param) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/companiesName.php",
            data: {param: param},
            dataType: "JSON",
            success: function (data) {
                //console.log(data);
                $('#txtTaskAgency').html('');
                //$('#txtTaskAgency').html('');
                for (var arg in data) {
                    $('#txtTaskAgency').append('<option value="' + data[arg].id + '">' + data[arg].agency + '</option>');
                    //$('#txtTaskAgency').append('<option value="' + data[arg].id + '">' + data[arg].agency + '</option>');
                }
                var idAgencyTaskSelected = $('#txtTaskAgency').val();

                loadEmployeesTask(idAgencyTaskSelected);
            }
        });
    }


    var allReasons = [];
    function addReason() {
        var reasonText;
        $("#rejectedReasons option:selected").each(function () {
            var reasons = {};
            //console.log('rejectedReasons');
            reasons.Val = $(this).val();
            reasons.Text = $(this).text();
            reasonText = $(this).text();
            //console.log(reasons.Val);
            //console.log(reasonText);
            //console.log(reasons);
            console.log(allReasons);

        var band = true;

        for (elem in allReasons) {
            if (allReasons[elem].Text == reasonText) {
                band = false;
            } 
        }

        if(band){
            allReasons.push(reasons);
            $('#tableReasons').append('<tr style="background-color: white;"><td>' + reasonText + ' </td> <td><button class="btn btn-danger" onclick="elimiarReason('+reasons.Val+', this);"><i class="fa fa-times-circle" ></i></button></td></tr>');
        } else {
            MostrarToast(2, "Motivo de Rechazo Repetido", "Debe seleccionar un motivo de rechazo diferente a los de la lista.");
        }

/*


            if (allReasons.length == 0) {
                allReasons.push(reasons);
                $('#tableReasons').append('<tr style="background-color: white;"><td>' + reasonText + '</td></tr>');
            } else {
                for (elem in allReasons) {
                    //console.log(allReasons[elem]);
                    //console.log(allReasons[elem].Text);
                    //console.log(reasonText);

                    if (allReasons[elem].Text !== reasonText) {
                        allReasons.push(reasons);
                        $('#tableReasons').append('<tr style="background-color: white;"><td>' + reasonText + '</td></tr>');
                    } else {
                        reasons = "";
                        reasonText = "";
                        MostrarToast(2, "Motivo de Rechazo Repetido", "Debe seleccionar un motivo de rechazo diferente a los de la lista.");
                    }
                }
            }
*/




        });
        //console.log(allReasons);
    }
    
    
    function elimiarReason(idRazon, element)
    { 
        var aRazones = allReasons;
        var reasons={};
        
        allReasons = [];
        for (elem in aRazones) {
            if (aRazones[elem].Val != idRazon) {
                reasons.Val = aRazones[elem].Val;
                reasons.Text = aRazones[elem].Text;                
                allReasons.push(reasons);
            } 
        }
        
        $(element).parent().parent().remove();
    }


    function sellValidation() {
        $('#formsDetailsBody').html('');
        $('#formsDetailsBody').append(sellFormToValidate);
        $('.imagenComprobante').hide();
        $('.imagenSolicitud').hide();
        $('.imagenAviso').hide();
        $('.imagenID').hide();
        $('.imagenPagare').hide();
        $('.imagenContrato').hide();
        $('#editVenta').hide();
        allReasons = [];

        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/RejectedReasons.php",
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                for (var reason in data) {
                    console.log(data[reason]);
                    $("#rejectedReasons").append("<option value=" + data[reason].id + ">" + data[reason].reason + "</option>");
                }
            }
        });
    }
    //---- boton btnSendF -------
    $(document).on('click', '#btnSendSell', function () {
        var idForm = $('#formID').val();
        var id = localStorage.getItem("id");
        if (idForm == undefined) {
            mostrarToast(2, "Venta Invalida", "Error al cargar la venta, contacte a su administrador de sistemas.");
        } else {
            var trustedHome = ($("#trustedImage").is(':checked')) ? true : false,
                requestImage = ($("#requestImage").is(':checked')) ? true : false,
                privacyAdvice = ($("#privacyImage").is(':checked')) ? true : false,
                identificationImage = ($("#identificationImage").is(':checked')) ? true : false,
                payerImage = ($("#payerImage").is(':checked')) ? true : false,
                agreegmentImage = ($("#agreegmentImage").is(':checked')) ? true : false,
                financieraValidate = ($("#financieraValidate").is(':checked')) ? true : false,
                validado = 0,
                validacionEstatus,
                idUsuario = $("#inputIdUser").val(),//se usa para obtener el valor del usuario que esta loggeado
                idReporte = $('#idReporte').val();

            if (trustedHome && requestImage && privacyAdvice && identificationImage 
                && payerImage && agreegmentImage && financieraValidate) {
            
                validado = 1;
                validacionEstatus = (financialService.value === 'AYOPSA') ? 21 : 22;
            } else {
                validado =  0;
                validacionEstatus = (financialService.value === 'AYOPSA') ? 23 : 24;
            }

            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/createAgreement2.php",
                data: {
                    form: idForm,
                    id: id,
                    reasons: allReasons,
                    trustedHome: trustedHome,
                    requestImage: requestImage,
                    privacyAdvice: privacyAdvice,
                    identificationImage: identificationImage,
                    payerImage: payerImage,
                    agreegmentImage: agreegmentImage,
                    financieraValidate: financieraValidate,
                    idReporte:idReporte,
                    validacionEstatus:validacionEstatus
                },
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    allReasons = [];
                    
                    if(validado == 0){
                        if(data.status == "OK"){
                            $('#formsDetails').modal('hide');
                            configurarToastCentrado();
                            MostrarToast(1, "Rechazo Exitoso", data.response);
                            
                            window.location='forms.php';
                        }

                        if(data.status == "BAD"){
                            configurarToastCentrado();
                            MostrarToast(2, "Alerta Rechazo", data.response);
                        }
                    }else{
                        $.ajax({
                            method: "POST",
                            url: "dataLayer/callsWeb/CambiarValidacionDeVentaPorIdAgencia.php",
                            data: {
                                idUsuario: idUsuario,
                                validacionEstatus: validacionEstatus,
                                idReporte: idReporte
                            },
                            dataType: "JSON",
                            success: function (data) {
                                console.log(data);

                                var codigoRespuesta = data.CodigoRespuesta;
                                var mensajeRespuesta = data.MensajeRespuesta;
                                if (codigoRespuesta == 1) {
                                    $('#formsDetails').modal('hide');
                                    configurarToastCentrado();
                                    if (validacionEstatus != 23) {
                                        MostrarToast(1, "Venta Verificada", "La verificación de la venta se realizó con exito");
                                    } else {
                                        MostrarToast(2, "Venta Rechazada", "La venta fue rechazada por no cumplir con las fotografias correctas");
                                    }

                                    window.location='forms.php';

                                } else {
                                    $('#formsDetails').modal('hide');
                                    configurarToastCentrado();
                                    MostrarToast(2, "Venta no validada", mensajeRespuesta);
                                }
                            }
                        });    
                    }                    
                }
            });


        }
    });

    $(document).on('click', '#btnCancelTaskAssign', function () {
        $('#taskForm').modal('hide');
    });

    $(document).on('change', '#txtAgency', function () {
        var agency = $("#txtAgency").val();
        $("#txtUsers").html();
        if (agency > 0) {
            loadEmployees(agency, "Venta");
        } else {
            alert("No Agency Selected");
        }
    });

    function loadEmployees(agency, param) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/usersByAgency.php",
            data: {agency: agency, role: param},
            dataType: "JSON",
            success: function (data) {
                for (var value in data) {
                    var profile=data[value].profile;
                    if (data[value].employee.indexOf("Pendiente") == -1 && profile.indexOf("venta")>-1)
                        $("#txtUsers").append("<option value=" + data[value].id + ">" + data[value].employee + "</option>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
               // alert('Error en cargar los empleados de la agencia ' + errorThrown);
                // $('#tablaLoader').html('');
                //MostrarToast(2, 'Error', 'Ocurrio un problema al momento de obtener los registros de reportes');
            },
        });
    }
    $('#cleanAll').click(function () {
        $('#txtQty').html('');
        $('#txtQty').append('<option value="10">10</option>+<option value="25">25</option>+<option value="50">50</option>+<option value="100">100</option>');
        $("#txtCompany").html('');
        $("#txtCompanyGeneral").html('');
        $('#txMun').html('');
        $('#txtAgency').html('');
        $('#txtTaskAgency').html('');
        $('#txtStatus').html('');
        $('#txtType').html('');
        $('#txtType').append('<option value="0">Tipo</option><option value="1">Censo</option><option value="2">Venta</option><option value="3">Plomer&iacute;a</option><option value="4">Instalacion</option><option value="5">Segunda Venta</option>');

        loadCompanies();
        loadCities();
        loadAgencies(localStorage.getItem("id"));
        loadStatus();
        //$('#dateFrom').html('');
        //$('#dateTo').html('');
        $('#dateFrom').val('');
        $('#dateTo').val('');
    });

    function searchReports() {
        var limit = $('#txtQty').val();
        var type = $('#txtType').val();
        var status = $('#txtStatus').val();
        var dateFrom = $('#dateFrom').val();
        dateFrom = formatDate(dateFrom);
        var dateTo = $('#dateTo').val();
        dateTo = formatDate(dateTo);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/searchForms.php",
            data: {limit: limit, type: type, status: status, dateFrom: dateFrom, dateTo: dateTo},
            dataType: "JSON",
            success: function (data) {
                $('#bodyReport').html('');
                for (var report in data) {
                    //alert(type);
                    $('#bodyReport').append('<tr id="form' + data[report].Id + '">' +
                        '<td>' +
                        '<button id="btnCensos" name="btnCensos" data-toggle="button" style="width: 50px;" class="btn btn-success censosHabilitado" onclick="loadForm(' + data[report].Id + ',\'' + data[report].Tipo + '\');"><i class="fa fa-file-text-o"></i></button>' +
                        '</td>' + '<td>' + data[report].Contrato + '</td>' + '<td>' + data[report].Tipo + '</td>' +
                        '<td><span class="label label-info">' + data[report].Status + '</span></td>' +
                        '<td>' + data[report].Municipio + '</td>' +
                        '<td>' + data[report].Colonia + '</td><td>' + data[report].Calle + '</td>' +
                        '<td>' + data[report].Usuario + '</td>' +
                        '<td>' + data[report].Agencia + '</td>' +
                        '<td>' + data[report].Fecha + '</td></tr>');
                }
            }
        });
    }
    
    
    function mensajeCallejeroNoLlenado(){
        MostrarToast(2, "Alerta", "EL callejero no ha sido llenado");
    }
    function mensajeVentaCancelada(){
        MostrarToast(2, "Alerta", "La venta se encuentra cancelada");
    }

</script>

<script type="text/javascript" src="assets/js/clases/notificaciones.js"></script>
<script type="text/javascript" src="assets/js/clases/segundaVenta.js"></script>
<script type="text/javascript" src="assets/js/moment.min.js"></script>
<script type="text/javascript" src="assets/js/notify.js"></script>
<script src="assets/js/underscore.js"></script>
<!--<script src="assets/js/bootstrap.js"></script>-->
