<?php include("header.php") ?>

<div class="modal fade fade disable-scroll " id="secondSellModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalFormClose" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
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
                                                <input type="text" id="txtConsecutive" name="consecutive"
                                                       class="form-control input-sm">
                                                <label>Pagar&eacute;</label>

                                                <div class="input-group">
                                                    <input type="text" id="txtNextSellPayment" name="nextSellPayment"
                                                           class="form-control input-sm">

                                                    <div class="input-group-addon">AYO</div>
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
                                                    <option value="1">Soltero</option>
                                                    <option value="1">Casado</option>
                                                    <option value="1">Viuda</option>
                                                </select>
                                                <label>Identificaci&oacute;n</label>
                                                <input type="text" id="txtIdCard" name="ClientIDCard"
                                                       class="form-control input-sm">
                                            </div>
                                            <div class="col-xs-6">
                                                <label>Agencia</label>
                                                <input type="text" id="txtNextSellAgency" name="nextSellAgency"
                                                       class="form-control input-sm">
                                                <br/><br/><br/>
                                                <label>Fecha de solicitud </label>
                                                <input type="text" id="txtRequestDate" name="ClientRequestDate"
                                                       class="form-control input-sm">
                                                <br/><br/>
                                                <label>Apellido materno</label>
                                                <input type="text" id="txtLastName2" name="ClientLastName2"
                                                       class="form-control input-sm">
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
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="col-xs-6">
                                                <label>Fecha de Nacimiento</label>
                                                <input type="date" id="txtNextSellBithdate" name="NextSellBirthdate"
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
                                                    <option value="1">Centro</option>
                                                    <option value="1"></option>
                                                    <option value="1"></option>
                                                </select>
                                                <label>Vive en casa</label>
                                                <select class="form-control input-sm" id="txtNextStepSaleInHome">
                                                    <option value="1">S&iacute;</option>
                                                    <option value="2">No</option>
                                                </select>
                                                <label>Tel&eacute;fono celular</label>
                                                <input type="text" id="txtNextSellCellularPhone"
                                                       name="NextSellCellularPhone" class="form-control input-sm">
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
                                                       name="NextSellJobTelephone" class="form-control input-sm">
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
                                                    <option value="1">Monterrey</option>
                                                    <option value="1"></option>
                                                    <option value="1"></option>
                                                </select>
                                                <label>Calle</label>
                                                <select class="form-control input-sm" id="txtNextStepSaleStreet">
                                                    <option value="1">Centro</option>
                                                    <option value="1"></option>
                                                    <option value="1"></option>
                                                </select>
                                                <label>Tel&eacute;fono de casa</label>
                                                <input type="text" id="txtNextSellPhone" name="NextSellPhone"
                                                       class="form-control input-sm">
                                                <br/><br/><br/><br/>
                                                <label>Direcci&oacute;n</label>
                                                <input type="text" id="txtNextSellJobLocation"
                                                       name="NextSellJobLocation" class="form-control input-sm">
                                                <label>Actividad/&Aacute;rea</label>
                                                <input type="text" id="txtNextSellJobActivity"
                                                       name="NextSellJobActivity" class="form-control input-sm">
                                                <br/>
                                                <button class="btn btn-primary" onclick="secondSell();">ENVIAR</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="agreementInformation">
                                <div class="row">
                                    <div class="col-md-9">
                                        <p>&nbsp;&nbsp;&nbsp;&nbsp;FINANCIAMIENTO</p>

                                        <div class="col-xs-6">
                                            <label>Tipo de contrato</label>
                                            <select class="form-control input-sm"
                                                    id="txtNextStepSaleAgreegmentType">
                                                <option value="1">Contrato 1</option>
                                                <option value="2">Contrato 2</option>
                                            </select>
                                            <label>Plazo</label>
                                            <input type="text" id="txtNextSellPaymentTime"
                                                   name="nextSellPaymentTime" class="form-control input-sm">
                                            <label>RI</label>
                                            <input type="text" id="txtNextSellRI" name="nextSellRI"
                                                   class="form-control input-sm">
                                            <br/>

                                            <p>REFERENCIAS</p>
                                            <label>Nombre Ref. 1</label>
                                            <input type="text" id="txtNextSellReference1" name="nextSellReference1"
                                                   class="form-control input-sm">
                                            <label>Tel&eacute;fono de trabajo</label>
                                            <input type="text" id="txtNextSellReference1Telephone"
                                                   name="nextSellReference1Telephone" class="form-control input-sm">
                                            <br/>
                                            <label>Nombre Ref. 2</label>
                                            <input type="text" id="txtNextSellReference2" name="nextSellReference2"
                                                   class="form-control input-sm">
                                            <label>Tel&eacute;fono de trabajo</label>
                                            <input type="text" id="txtNextSellReference2Telephone"
                                                   name="nextSellReference2Telephone" class="form-control input-sm">
                                        </div>
                                        <div class="col-xs-6">
                                            <label>Precio</label>
                                            <input type="text" id="txtNextSellPrice" name="nextSellAgency"
                                                   class="form-control input-sm">
                                            <label>Mensualidad</label>
                                            <input type="text" id="txtNextSellMonthlyCost"
                                                   name="nextSellMonthlyCost" class="form-control input-sm">
                                            <label>Fecha RI</label>
                                            <input type="date" id="txtNextSellDateRI" name="nextSellDateRI"
                                                   class="form-control input-sm">
                                            <br/><br/>
                                            <label>Tel&eacute;fono particular</label>
                                            <input type="text" id="txtNextSellReferenceTelephone"
                                                   name="nextSellReferenceTelephone" class="form-control input-sm">
                                            <label>Extensi&oacute;n </label>
                                            <input type="text" id="txtNextSellReferenceTelephoneExt"
                                                   name="nextSellReferenceTelephoneExt"
                                                   class="form-control input-sm">
                                            <br/>
                                            <label>Tel&eacute;fono particular</label>
                                            <input type="text" id="txtNextSellReferenceTelephone2"
                                                   name="nextSellReferenceTelephone2" class="form-control input-sm">
                                            <label>Extensi&oacute;n </label>
                                            <input type="text" id="txtNextSellReferenceTelephoneExt2"
                                                   name="nextSellReferenceTelephoneExt2"
                                                   class="form-control input-sm">
                                        </div>
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
