<?php include("header.php") ?>

<div class="row">
    <div class="col-lg-12" style="margin-bottom:20px;">
        <div class="form-inline">
            <?php
            if (($_SESSION["rol"] == "Agency" && !(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter")) || $_SESSION["rol"] == "Admin") {
                echo '';
            } else {
                echo '<button type="button" id="btnAddUser" class="btn btn-success btn-lg"><i class="fa fa-plus"></i> &nbsp;Agregar Usuario</button>';
            } ?>
            <?php if(!(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter")): ?>
                <button type="button" id="btnNotification" class="btn btn-info btn-lg"><i class="fa fa-user">&nbsp;</i>Enviar
                    notificaci&oacute;n a un empleado
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">

    <div class="row">
        <div class="col-lg-12">
            <!-- tabs -->
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <?php
                    if (($_SESSION["rol"] == "Agency" && !(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter")) || $_SESSION["rol"] == "Admin") {
                        echo '
                        <li class="active"><a href="#agencies" data-toggle="tab">Agencias</a></li>
                        <li><a href="#employees" data-toggle="tab">Empleados</a></li>
                        ';
                    } else if(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter") {
                        echo '<li class="active"><a href="#admins" data-toggle="tab">Administradores</a></li>';
                    } else {
                        echo '
                        <li class="active"><a href="#admins" data-toggle="tab">Administradores</a></li>
                        <li><a href="#agencies" data-toggle="tab">Agencias</a></li>
                        <li><a href="#employees" data-toggle="tab">Empleados</a></li>
                        ';
                    } ?>
                </ul>
                <div class="tab-content">
                    <?php
                    if (($_SESSION["rol"] == "Agency" && !(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter")) || $_SESSION["rol"] == "Admin") {
                        echo '
                        <div class="tab-pane active" id="agencies"></div>
                         <div class="tab-pane" id="employees"></div>
                        ';
                    } else if(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter") {
                        echo '<div class="tab-pane active" id="admins"></div>';
                    } else {
                        echo '
                         <div class="tab-pane active" id="admins"></div>
                         <div class="tab-pane" id="agencies"></div>
                         <div class="tab-pane" id="employees"></div>
                        ';
                    } ?>

                </div>
            </div>
            <!-- /tabs -->
        </div>
    </div>
</div>

<div class="modal fade disable-scroll" id="modalform" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalFormClose" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleCompany">Usuario</h4>
            </div>
            <div class="modal-body">
                <form class="cmxform" id="formAgregarNuevoUsuarioImagen" name="formAgregarNuevoUsuarioImagen"
                      method="post" enctype="multipart/form-data"
                      onsubmit="return false;">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                        <div class="text-center">Fotografia de perfil</div>
                        <img id="imgAvatar" name="imgAvatar"
                             style="max-width: 128px; max-height: 128px; margin-bottom: 20px"
                             src="assets/img/logoMexicana.png"/>
                    </div>
                    <div class="col-sm-4">
                    </div>
                    <div class="form-group">
                        <input type="file" id="inAvatarImg" name="inAvatarImg">
                    </div>

                    <form class="cmxform" id="formAgregarNuevoUsuario" name="formAgregarNuevoUsuario"
                          method="post" enctype="multipart/form-data"
                          onsubmit="return false;">
                        <div><input type="text" id="txtId" name="txtId" class="hidden"></div>
                        <div><input type="text" id="txtUrlImagen" name="txtUrlImagen" class="hidden"></div>

                        <div class="form-group">
                            <label class="red-color"><strong>Campos obligatorios *</strong></label>
                        </div>
                        <div class="form-group">
                            <label id="lblRol" name="lblRol" class="red-color">Rol *</label>
                            <select class="form-control" id="txtRol" name="txtRol" >
                                <?php if(!(isset($_SESSION["typeAgency"]) && $_SESSION["typeAgency"] == "CallCenter")): ?>
                                    <option value="1">SuperAdministrador</option>
                                    <option value="2">Administrador</option>
                                    <!--     <option value="3">Agencia</option>
                                 <option value="4">Empleado</option>-->
                                <?php else: ?>
                                    <option value="2">Administrador</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group" id="profileSelect" name="profileSelect">
                        </div>
                        <div class="form-group" id="agenciaSelect" name="agenciaSelect">
                        </div>
                        <div class="form-group" id="agenciaTipoSelect" name="agenciaTipoSelect">
                        </div>
                        <div class="form-group" id="agenciaPlazoSelect" name="agenciaPlazoSelect">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Nickname *</label>
                            <input type="text" id="txtNickname" name="txtNickname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Password *</label>
                            <input type="password" id="txtPassword" name="txtPassword" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Nombre *</label>
                            <input type="text" id="txtName" name="txtName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Apellido Paterno *</label>
                            <input type="text" id="txtLastName" name="txtLastName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Apellido Materno *</label>
                            <input type="text" id="txtLastNameOp" name="txtLastNameOp" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Email *</label>
                            <input type="text" id="txtEmail" name="txtEmail" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">N&uacute;mero Telef&oacute;nico *</label>
                            <input type="text" id="txtPhoneNumber" name="txtPhoneNumber" class="form-control">
                        </div>
            </div>
            <div class="modal-footer">
                <button type=button id="btnCancel" class="btn btn-danger">Cancelar</button>
                <button type=submit id="btnAdd" class="btn btn-success">Agregar</button>
            </div>
            </form>
            </form>
        </div>
        <!-- modal content-->
    </div>
    <!-- modal-dialog-->
</div>
<!-- /.modal-->

<div class="modal fade disable-scroll" id="modalDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminar usuario</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label>¿Estás seguro que deseas eliminar este usuario?</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type=button id="btnCancelUser" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
        <!-- modal content-->
    </div>
    <!-- modal-dialog-->
</div>
<!-- modal -->

<div class="modal fade disable-scroll" id="modalNotification" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalNotificationClose" data-dismiss="modal"
                        aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleModal">Enviar notificaci&oacute;n a empleado</h4>
            </div>
            <div class="modal-body">
                <form class="cmxformNotification" role="form" id="formNewNotification" name="formNewNotification">
                    <div class="form-group">
                        <label>Mensaje</label>
                        <input type="text" id="txtnotificationText" name="txtnotificationText" class="form-control">
                    </div>
                    <h4>DESTINATARIO</h4>

                    <div class="form-group">
                        <label>Agencia</label>
                        <select class="form-control" id="txtAdminsCompanyNotify" name="txtAdminsCompanyNotify">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Empleado</label>
                        <select class="form-control" id="txtAdminsEmployeeNotify" name="txtAdminsEmployeeNotify">
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type=button id="btnNotificationCancel" class="btn btn-danger">Cancelar</button>
                <button type=button id="btnAddNotification" onclick="JsNotificaciones.insertarNotificacion()"
                        class="btn btn-success">Agregar
                </button>
            </div>
        </div>
        <!-- modal content-->
    </div>
    <!-- modal-dialog-->
</div>
<!-- /.modal-->

<?php include("footerDataTables.php") ?>

<!--form validation-->
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgAvatar').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#inAvatarImg").change(function () {
        readURL(this);
    });

    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < 8; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }

    function loadHeaders() {
        $("#admins").html("<div class='row'>"
            + "<div class='col-lg-12'>"
            + "<section class='panel'>"
            + "<table id='tablaAdministradores' name='tablaAdministradores' class='table  data-table'>"
            + "<thead>"
            + "<tr>"
            + "<th class='hidden'></th>"
            + "<th class='hidden-xs'><i class='fa fa-user-secret'>&nbsp;</i>Usuario</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'>&nbsp;</i>Nombre</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'>&nbsp;</i>Email</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-earphone'>&nbsp;</i>N&uacute;mero Telef&oacute;nico</th>"
            + "<th><i class='glyphicon glyphicon-cog'>Rol</i></th>"
            + "<th><i class='glyphicon glyphicon-cog'>&nbsp;</i></th>"
            + "<th>Acci&oacute;n</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody>"
            + "</tbody>"
            + "</table>"
            +'<div id="tablaLoader" name="tablaLoader">'
            +'<div class="loader"></div>'
            +'<br>'
            +'<p class="centrar"><strong>CARGANDO .....</strong></p>'
            +'</div>'
            + "</section>"
            + "</div>"
            + "</div>");

        $("#agencies").html("<div class='row'>"
            + "<div class='col-lg-12'>"
            + "<section class='panel'>"
            + "<table id='tablaAgencias' name='tablaAgencias' class='table responsive-data-table data-table'>"
            + "<thead>"
            + "<tr>"
            + "<th class='hidden'></th>"
            + "<th class='hidden-xs'><i class='fa fa-user'>&nbsp;</i>Usuario</th>"
            + "<th><i class='fa fa-building'>&nbsp;</i>Nombre</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'>&nbsp;</i>Email</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-earphone'>&nbsp;</i>N&uacute;mero Telef&oacute;nico</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-sort'>&nbsp;</i>Tipo Agencia"
            + "</th>"
            + "<th><i class='glyphicon glyphicon-cog'>&nbsp;</i> Acci&oacute;n</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody id='bodyDataAgencies'>"
            + "</tbody>"
            + "</table>"
            + "</section>"
            + "</div>"
            + "</div>");

        $("#employees").html("<div class='row'>"
            + "<div class='col-lg-12'>"
            + "<section class='panel'>"
            + "<table id='tablaEmpleados' name='tablaEmpleados' class='table responsive-data-table data-table'>"
            + "<thead>"
            + "<tr>"
            + "<th class='hidden'></th>"
            + "<th class='hidden-xs'><i class='fa fa-user-secret'>&nbsp;</i>Usuario</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'>&nbsp;</i>Nombre</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'></i>Email</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-phone'></i>N&uacute;mero de tel&eacute;fono</th>"
            + "<th class='hidden-xs'><i class='fa fa-users'>&nbsp;</i>Perfil</th>"
            + "<th class='hidden-xs'><i class='fa fa-building'>&nbsp;</i>Agencia</th>"
            + "<th><i class='glyphicon glyphicon-cog'>&nbsp;</i> Acci&oacute;n</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody id='bodyDataEmployees'>"
            + "</tbody>"
            + "</table>"
            + "</section>"
            + "</div>"
            + "</div>");
    }

    function searchUser() {
        var value = $("#searchUserField").val();
        var typeAgency = $("#typeAgency").val();
        var string_nickname = $("#nicknameZone").html();
        string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
        string_nickname = string_nickname.trim();
        //console.log(string_nickname);

        var temp = DoStuff.whitin(string_nickname);

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/searchUsersByParam.php",
            data: {value: temp, pivot: value},
            dataType: "JSON",
            success: function (data) {
                //console.log(data);
                loadHeaders();

                var adminValues = data.Admins;
                var agenciesValues = data.Agencies;
                var employeesValues = data.Employees;

                for (var elem in adminValues) {
                    var id = adminValues[elem].id;
                    var avatar = adminValues[elem].avatar;
                    var email = adminValues[elem].email;
                    var lastName = adminValues[elem].lastName;
                    var name = adminValues[elem].name;
                    var nickname = adminValues[elem].nickname;
                    var phoneNumber = adminValues[elem].phoneNumber;
                    var urlImage = adminValues[elem].photoUrl;
                    var rol = adminValues[elem].type;

                    var body = "<tr id=user" + id + ">" +
                        "<td><a href='user.php?id=" + id + "'>" + id + "</a></td>" +
                        "<td class='hidden-xs'>" + nickname + "</td>" +
                        "<td>" + name + lastName + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" +
                        "<td>" + rol + "</td>" +
                        "<td class='hidden-xs'>Mexicana</td>" +
                        "<td class='hidden-xs'>" ;
                    if(typeAgency != "" || typeAgency.toUpperCase() !== "CALLCENTER"){
                        body += "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",2);'><i class='fa fa-pencil'></i></button>" +
                        "&nbsp;" +
                        "<button type='button' class='btnDeleteUser btn btn-success btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>";
                    }
                    body += "</td></tr>";
                    $("#tablaAdministradores tbody").append(body);
                }
                /*DataTables instantiation.*/
                // $( "#tablaAdministradores").dataTable();

                for (var elem in agenciesValues) {
                    var id = agenciesValues[elem].id;
                    var avatar = agenciesValues[elem].avatar;
                    var email = agenciesValues[elem].email;
                    var lastName = agenciesValues[elem].lastName;
                    var name = agenciesValues[elem].name;
                    var nickname = agenciesValues[elem].nickname;
                    var phoneNumber = agenciesValues[elem].phoneNumber;
                    var urlImage = agenciesValues[elem].photoUrl;
                    var rol = agenciesValues[elem].tipo;
                    onsole.log('typeAgency', typeAgency);
                    var body = "<tr id=user" + id + ">" +
                        "<td><a href='/mexicana/newProjectStructure/user.php?id=" + id + "'>" + id + "</a></td>" +
                        "<td class='hidden-xs'>Mexicana</td>" +
                        "<td class='hidden-xs'>" + nickname + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" +
                        "<td>" + rol + "</td>" +
                        "<td class='hidden-xs'>" +
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",3);'><i class='fa fa-pencil'></i></button>" +
                        "&nbsp;" +
                        "<button type='button' class='btnDeleteUser btn btn-success btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>" + "</td>" +
                        "</td>" +
                        "</tr>";
                    $("#tablaAgencias tbody").append(body);
                }
                /*DataTables instantiation.*/
                //$( "#tablaAgencias").dataTable();

                for (var elem in employeesValues) {
                    var employeeArray = employeesValues[elem];
                    var employeeId = employeeArray.id;
                    var agencyName = employeeArray.agency;
                    var employeeNickname = employeeArray.employee;
                    var employeeName = employeeArray.employeeName;
                    var employeeLastName = employeeArray.employeeLastName;
                    var employeeEmail = employeeArray.email;
                    var employeePhone = employeeArray.phone;
                    var employeeProfile = employeeArray.profile;

                    var body = "<tr id=user" + employeeId + ">";
                        body += "<td><a href='/mexicana/newProjectStructure/user.php?id=" + employeeId + "'>" + employeeId + "</a></td>";
                        body += "<td class='hidden-xs'>" + agencyName + "</td>";
                        body += "<td>" + employeeNickname + "</td>";
                        body += "<td>" + employeeName + "</td>";
                        body += "<td>" + employeeLastName + "</td>";
                        body += "<td>" + employeeEmail + "</td>";
                        body += "<td>" + employeePhone + "</td>";
                        body += "<td>" + employeeProfile + "</td>";
                        body += "<td class='hidden-xs'>";
                        body +="<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",4);'><i class='fa fa-pencil'></i></button>";
                        body +="&nbsp;";
                        body +="<button type='button' class='btnDeleteUser btn btn-success btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>";
                        body +="</td>";
                        body +="</td>";
                        body +="</tr>";
                    $("#tablaEmpleados tbody").append(body);
                }
                /*DataTables instantiation.*/

                // $( "#tablaEmpleados").DataTable();
            }
        });
    }

    function loadUsers(nickname, param) {
        loadHeaders();
        var ROL_SUPERADMINISTRADOR = 1;
        var ROL_ADMINISTRADOR = 2;
        var ROL_AGENCIA = 3;
        var ROL_EMPLEADO = 4;
        var typeAgency = $("#typeAgency").val();

        /***CARGA DE LOS USUARIOS SUPERADMINISTRADORES Y ADMINISTRADORES**/

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/ObtenerUsuariosPorRol.php",
            data: {rolUsuarioABuscar: ROL_SUPERADMINISTRADOR},
            dataType: "JSON",
            success: function (data) {
                //var typeAgency = $("#typeAgency").val();
                //alert(JSON.stringify(data));
                var i;
                var sizeSuperAdministradores = data.length;
                for (i = 0; i < sizeSuperAdministradores; i++) {
                    var id = data[i].id;
                    var nickname = data[i].nickname;
                    var name = data[i].name;
                    var lastName = data[i].lastName;
                    var lastNameOp = data[i].lastNameOp;
                    var email = data[i].email;
                    var phoneNumber = data[i].phoneNumber;
                    var rol = data[i].type;
                    var updated_at = data[i].updated_at;
                    var active = data[i].active;
                    var switchActive;

                    if (active == 1) {
                        switchActive = "<input type='checkbox' id='switch' name='enabled' value='" + id + "' checked>";
                    } else if (active == 0) {
                        switchActive = "<input type='checkbox' id='switch' name='enabled' value='" + id + "'>";
                    }

                    var body = "<tr id=user" + id + ">";
                        body += "<td class='hidden'>" + updated_at + "</td>";
                        body += "<td class='hidden-xs'>" + nickname + "</td>";
                        body += "<td>" + name + " " + lastName + " " + lastNameOp + "</td>";
                        body += "<td>" + email + "</td>";
                        body += "<td>" + phoneNumber + "</td>";
                        body += "<td>" + rol + "</td>";
                        body += "<td>";
                        if(typeAgency.toUpperCase() !== "CALLCENTER"){
                            body += switchActive;
                        }
                        body += "</td>";
                        body += "<td class='hidden-xs'>" ;
                    if(typeAgency.toUpperCase() !== "CALLCENTER"){
                        body += "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",2);'><i class='fa fa-pencil'></i></button>" +
                        "&nbsp;" +
                        "<button type='button' class='btnDeleteUser btn btn-success btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>";
                    }
                    body += "</td></tr>";
                    $("#tablaAdministradores tbody").append(body);
                }

                //if enabled = true

                var options = {
                    size: 'mini'
                };
                $("[name='enabled']").bootstrapSwitch(options);

                $('#tablaLoader').html('');

                /*DataTables instantiation.*/
                $("#tablaAdministradores").DataTable().destroy();
                $("#tablaAdministradores").DataTable({
                    "order": [[0, 'desc']],
                    autoWidth: false,
                    searching: true,
                    "language": {
                        "lengthMenu": "Mostrar _MENU_",
                        "search": "<strong>Buscar usuario:   </strong>",
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

        /***CARGA DE LOS USUARIOS AGENCIA**/
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/ObtenerUsuariosPorRol.php",
            data: {rolUsuarioABuscar: ROL_AGENCIA},
            dataType: "JSON",
            success: function (data) {

                //alert(JSON.stringify(data));
                var i;
                var sizeAgencias = data.length;
                for (i = 0; i < sizeAgencias; i++) {
                    var email = data[i].email;
                    var id = data[i].id;
                    var lastName = data[i].lastName;
                    var lastNameOp = data[i].lastNameOp;
                    var name = data[i].name;
                    var nickname = data[i].nickname;
                    var phoneNumber = data[i].phoneNumber;
                    var tipoAgencia = data[i].type;
                    var updated_at = data[i].updated_at;

                    var body = "<tr id=user" + id + ">" +
                        "<td class='hidden'>" + updated_at + "</td>" +
                        "<td class='hidden-xs'>" + nickname + "</td>" +
                        "<td class='hidden-xs'>" + name + ' ' + lastName + ' ' + lastNameOp + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" +
                        "<td>" + tipoAgencia + "</td>" +
                        "<td class='hidden-xs'>" +
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",2);'><i class='fa fa-pencil'></i></button>" +
                        "&nbsp;" +
                        "<button type='button' class='btnDeleteUser btn btn-danger btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>" + "</td>" +
                        "</tr>";
                    $("#tablaAgencias tbody").append(body);
                }

                //if enabled = true

                var options = {
                    size: 'mini'
                };
                $("[name='enabled']").bootstrapSwitch(options);

                /*DataTables instantiation.*/
                $("#tablaAgencias").DataTable().destroy();
                $("#tablaAgencias").DataTable({
                    "order": [[0, 'desc']],
                    autoWidth: false,
                    searching: true,
                    "language": {
                        "lengthMenu": "Mostrar _MENU_",
                        "search": "<strong>Buscar agencia:   </strong>",
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

        /***CARGA DE LOS USUARIOS EMPLEADO**/
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/ObtenerUsuariosPorRol.php",
            data: {rolUsuarioABuscar: ROL_EMPLEADO},
            dataType: "JSON",
            success: function (data) {
                //alert(JSON.stringify(data));
                var i;
                var sizeSuperAdministradores = data.length;
                for (i = 0; i < sizeSuperAdministradores; i++) {
                    var active = data[i].active;
                    var avatar = data[i].avatar;
                    var email = data[i].email;
                    var id = data[i].id;
                    var lastName = data[i].lastName;
                    var lastNameOp = data[i].lastNameOp;
                    var name = data[i].name;
                    var nickname = data[i].nickname;
                    var phoneNumber = data[i].phoneNumber;
                    var type = data[i].type;
                    var perfilEmpleado = data[i].perfilEmpleado;
                    var agenciaDelEmpleado = data[i].agenciaDelEmpleado;
                    var updated_at = data[i].updated_at;

                    var switchActive;

                    if (active == 1) {
                        switchActive = "<input type='checkbox' id='switch' name='enabled' value='" + id + "' checked>";
                    } else if (active == 0) {
                        switchActive = "<input type='checkbox' id='switch' name='enabled' value='" + id + "'>";
                    }

                    var body = "<tr id=user" + id + ">" +
                        "<td class='hidden'>" + updated_at + "</td>" +
                        "<td class='hidden-xs'>" + nickname + "</td>" +
                        "<td class='hidden-xs'>" + name + ' ' + lastName + ' ' + lastNameOp + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" +
                        "<td>" + perfilEmpleado + "</td>" +
                        "<td>" + agenciaDelEmpleado + "</td>" + "<td class='hidden-xs'>" ;

                    if(typeAgency != "" && typeAgency != "CallCenter"){
                        body=body + "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",2);'><i class='fa fa-pencil'></i></button>" +
                            "&nbsp;" +
                            "<button type='button' class='btnDeleteUser btn btn-danger btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>";

                    }
                       body =body   + "</td>" + "</tr>";
                    $("#tablaEmpleados tbody").append(body);
                }

                //if enabled = true

                var options = {
                    size: 'mini'
                };
                $("[name='enabled']").bootstrapSwitch(options);

                /*DataTables instantiation.*/
                $("#tablaEmpleados").DataTable().destroy();
                $("#tablaEmpleados").DataTable({
                    "order": [[0, 'desc']],
                    autoWidth: false,
                    searching: true,
                    "language": {
                        "lengthMenu": "Mostrar _MENU_",
                        "search": "<strong>Buscar empleado:   </strong>",
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

    $(document).ready(function () {
        var rol;
        var string_nickname = $("#nicknameZone").html();
        string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
        string_nickname = string_nickname.trim();

        $("#titleHeader").html("Usuarios");
        $("#subtitle-header").html("Usuarios disponibles en el sistema");

        var parameter = "admins";
        loadUsers(string_nickname, parameter);
        //loadUsers(string_nickname, "agencies");
        //loadUsers(string_nickname, "employee");

        $(document).on('change', '#txtRol', function () {
            rol = $("#txtRol").val();

            $("#profileSelect").html('');
            $('#agenciaSelect').html('');
            $('#agenciaTipoSelect').html('');

            if (rol == 1) {
                $("#profileSelect").html('');
                $('#agenciaSelect').html('');
                $('#agenciaTipoSelect').html('');
            } else if (rol == 2) {
                $("#profileSelect").html('');
                $('#agenciaSelect').html('');
                $('#agenciaTipoSelect').html('');
            }
            else if (rol == 3) {
                $("#agenciaTipoSelect").append("<label class='red-color'>Tipo *</label>"
                    + "<select class='form-control' id='txtAgenciaTipo' name='txtAgenciaTipo'>"
                    + "<option value='Financiera'>Financiera</option>"
                    + "<option value='Comercializadora'>Comercializadora</option>"
                    + "<option value='Instaladora'>Instaladora</option>"
                    + "<option value='Censista'>Censista</option>"
                    + "</select>"
                    + "</div>"
                );

            }
            else if (rol == 4) {
                agenciesLoad();
                $("#profileSelect").append("<label class='red-color'>Perfil *</label>"
                    + "<select class='form-control' id='txtProfile'>"
                    + "<option value='1'>Cencista</option>"
                    + "<option value='2'>Cambaceo</option>"
                    + "<option value='3'>Plomero</option>"
                    + "<option value='4'>Instalador</option>"
                    + "</select>"
                    + "</div>"
                );

                $('#agenciaSelect').append("<label class='red-color'>Agencia *</label>"
                    + "<select class='form-control' id='txtAdminsCompanyAlta' name='txtAdminsCompanyAlta'>"
                    + "</select>"
                    + "</div>"
                );
            } else {

                $("#profileSelect").html('');
                $('#agenciaSelect').html('');
                $('#agenciaTipoSelect').html('');
            }
        });

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/companiesName.php",
            dataType: "JSON",
            success: function (data) {
                for (var value in data) {
                    $("#txtCompany").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                }
            }
        });

        $(document).on('click', '#btnShowAll', function () {
            var string_nickname = $("#nicknameZone").html();
            string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
            string_nickname = string_nickname.trim();

            $('#searchUserField').val('');
            loadUsers(string_nickname, "admins");
        });

        function editUser(id, nickname, email, pass, img, roles, agency) {
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/updateUser.php",
                data: {
                    id: id, nickname: nickname, email: email, pass: pass, img: img, roles: roles, agency: agency
                },
                dataType: "JSON",
                success: function (data) {
                    //console.log(data);
                }
            });
        }

        function saveUser(id, nickname, email, pass, img, roles, agency) {
            /*console.log(id);
             console.log(nickname);
             console.log(email);
             console.log(pass);
             console.log(img);
             console.log(roles);
             console.log(agency);
             console.log("Creating User");*/
            var password;
            //var passwordRetype;
            password = makeid();
            //Ajax Request to add user to database
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/ShortcutCreateUser.php",
                data: {agency: agency, nickname: nickname, email: email, pass: password, img: img, rol: roles},
                dataType: "JSON",
                success: function (data) {
                    //console.log("Function executed");
                    //console.log(data);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest);
                    alert(textStatus);
                },
                complete: function (XMLHttpRequest, status) {
                    console.log(XMLHttpRequest);
                    console.log(status);
                }
            });
        }

        var rowPick;
        $('#btnAddUser').click(function () {
            $('#modalform').modal('show');
            $('#titleCompany').html('Agregar Usuario');
            $('#btnAdd').html('Agregar');
            $('#btnAdd').attr("onclick", "JsUsuario.insertarImagenUsuario()");
            $("#txtRol").prop('disabled', false);
            $('#lblRol').show();
            $('#txtRol').show();
            limpiarCamposFormularioModalAgregarUsuario();
            agenciesLoadAgregarUsuario();
        });

        function agenciesLoad() {
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/companiesName.php",
                dataType: "JSON",
                success: function (data) {
                    //console.log(data);
                    for (var value in data) {
                        //console.log(data[value]);
                        $("#txtAdminsCompanyNotify").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                        $("#txtAdminsCompanyAlta").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");

                    }
                }
            });
        }

        function agenciesLoadAgregarUsuario() {
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/companiesName.php",
                dataType: "JSON",
                success: function (data) {
                    //console.log(data);
                    for (var value in data) {
                        //console.log(data[value]);
                        $("#txtAdminsCompanyAlta").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");

                    }
                }
            });
        }

        $('#btnNotification').click(function () {
            $('#modalNotification').modal('show');
            $('#titleCompany').html('Enviar Notificaci&oacute;n a un usuario');
            $('#btnAddNotificacion').html('Agregar');
        });


        window.edit = function (idUser, rol) {
            rowPick = $(this).parent().parent();

            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/theRealShowUser.php",
                data: {
                    id: idUser,
                    rol: rol
                },
                dataType: "JSON",
                success: function (data) {
                    console.log(data);

                    for (var elem in data) {
                        var id = data[elem].id;
                        var avatar = data[elem].avatar;
                        var email = data[elem].email;
                        var lastName = data[elem].lastName;
                        var lastNameOp = data[elem].lastNameOp;
                        var name = data[elem].name;
                        var nickname = data[elem].nickname;
                        var phoneNumber = data[elem].phoneNumber;
                        var birthdate = data[elem].birthdate;
                        var gender = data[elem].gender;
                        var idProfile = data[elem].idProfile;
                        var idAgency = data[elem].idAgency;

                        //var urlImage = data[elem].photoUrl;
                        //load photo to canvas
                    }

                    $('#imgAvatar').attr("src", avatar);
                    console.log(idUser);
                    $('input[name="txtId"]').attr('value', idUser);
                    $('input[name="txtUrlImagen"]').attr('value', avatar);
                    $('#txtRol').val(rol);
                    $("#txtRol").prop('disabled', true);
                    $('#lblRol').hide();
                    $('#txtRol').hide();
                    $('#txtNickname').val(nickname);
                    $('#txtName').val(name);
                    $('#txtLastName').val(lastName);
                    $('#txtLastNameOp').val(lastNameOp);
                    $('#txtEmail').val(email);
                    //$('#txtBirthDate').val(data.birthdate);
                    //$('#txtGender').val(data.gender);
                    $('#txtPhoneNumber').val(phoneNumber);

                    /*****TODO TENEMOS QUE CREAR LA CARGA DEPENDIENDO EL PERFIL QUE SE VAYA
                     * A ACTUALIZAR EL USUARIO PARA QUE CARGUE LOS DEMAS COMBOS
                     */

                    $("#profileSelect").html('');
                    $('#agenciaSelect').html('');
                    $('#agenciaTipoSelect').html('');

                    if (rol == 1) {

                        $("#profileSelect").html('');
                        $('#agenciaSelect').html('');
                        $('#agenciaTipoSelect').html('');
                    } else if (rol == 2) {

                        $("#profileSelect").html('');
                        $('#agenciaSelect').html('');
                        $('#agenciaTipoSelect').html('');
                    }
                    else if (rol == 3) {
                        $("#profileSelect").append("<label class='red-color'>Perfil *</label>"
                            + "<select class='form-control' id='txtProfile' name='txtProfile'>"
                            + "<option value='1'>Cencista</option>"
                            + "<option value='2'>Cambaceo</option>"
                            + "<option value='3'>Plomero</option>"
                            + "<option value='4'>Instalador</option>"
                            + "</select>"
                            + "</div>"
                        );

                        $('select[name="txtProfile"]').val(idProfile);

                    }
                    else if (rol == 4) {
                        agenciesLoad();
                        $("#profileSelect").append("<label class='red-color'>Perfil *</label>"
                            + "<select class='form-control' id='txtProfile' name='txtProfile'>"
                            + "<option value='1'>Cencista</option>"
                            + "<option value='2'>Cambaceo</option>"
                            + "<option value='3'>Plomero</option>"
                            + "<option value='4'>Instalador</option>"
                            + "</select>"
                            + "</div>"
                        );

                        $('#agenciaSelect').append("<label class='red-color'>Agencia *</label>"
                            + "<select class='form-control' id='txtAdminsCompanyAlta' name='txtAdminsCompanyAlta'>"
                            + "</select>"
                            + "</div>"
                        );

                        $('select[name="txtProfile"]').val(idProfile);
                        $('select[name="txtAdminsCompanyAlta"]').val(idAgency);
                    } else {
                        $("#profileSelect").html('');
                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    //alert(XMLHttpRequest);
                    //alert(textStatus);
                },
                complete: function (XMLHttpRequest, status) {
                    //console.log(XMLHttpRequest);
                    //console.log(status);
                    $('#titleCompany').html('Editar Usuario');
                    $('#btnAdd').html('Guardar');
                    $('#btnAdd').attr("onclick", "JsUsuario.editarImagenUsuario()");
                    $('#modalform').modal('show');
                }
            });
        }

        window.eliminar = function (id) {
            rowPick = $(this).parent().parent();
            $('#modalDelete').modal('show');
            $("#btnCancelUser").attr("onClick", "JsUsuario.eliminarUsuario(" + id + ")");
        }

        $('#btnCancel').click(function () {
            $('#modalform').modal('hide');
        });

        $('#btnModalFormClose').click(function () {
            $('#modalform').modal('hide');
        });
    });

    $(document).on('click', '#btnCancel', function () {
        $('#modalform').modal('hide');
    });

    $('#btnNotificationCancel').click(function () {
        $('#modalNotification').modal('hide');
    });

    $(document).on('change', '#txtAdminsCompanyNotify', function () {
        var agency = $("#txtAdminsCompanyNotify").val();
        alert(agency);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/usersAgency.php",
            data: {
                agency: agency
            },
            dataType: "JSON",
            success: function (data) {
                for (var value in data)
                    $("#txtAdminsEmployeeNotify").append("<option value=" + data[value].idUser + ">" + data[value].employee + "</option>");
            }
        });
    });

    function createRow(name, mail, company) {
        if ($('#formAgregarNuevoUsuario').valid()) {
            /*console.log(name)
             console.log(mail)
             console.log(company)*/
            var xrow = ''
            xrow += '<tr>'
            xrow += '<td><a href="#">1</a></td>'
            xrow += '<td class="hidden-xs">' + company + '</td>'
            xrow += '<td>' + name + '</td>'
            xrow += '<td><span class="label label-success label-mini">Activo</span></td>'
            xrow += '<td class="hidden-xs"><button type="button" class="btnEditUser btn btn-success btn-xs"><i class="fa fa-pencil"></i></button> <button type="button" class="btnDeleteUser btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>'
            xrow += '</td>'
            xrow += '</tr>'

            //console.log(xrow)

            $("#bodyData").append(xrow)
            $('#modalform').modal('hide')
        }
    }

    function agenciesLoad() {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/companiesName.php",
            dataType: "JSON",
            success: function (data) {
                //console.log(data);
                for (var value in data) {
                    //console.log(data[value]);
                    $("#txtAdminsCompanyNotify").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                    $("#txtAdminsCompanyAlta").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                }
                cargarEmpleadosParaNotificar();

            }
        });
    }

    function cargarEmpleadosParaNotificar() {
        var idAgencia =($("#txtAdminsCompanyNotify option:first").val());
        //alert(idAgencia);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/ObtenerEmpleadosDeAgenciaPorIdAgencia.php",
            data: {idAgencia:idAgencia},
            dataType: "JSON",
            success: function (data) {
                console.log(data );
                for (var value in data) {
                    console.log(data);
                    $("#txtAdminsEmployeeNotify").append("<option value=" + data[value].id + ">" + data[value].nickname + "</option>");
                }

                $('#modalNotification').modal('show');
                $('#titleCompany').html('Enviar Notificaci&oacute;n');
                $('#btnAddNotificacion').html('Enviar');
            }
        });
    }

    function limpiarCamposFormularioModalAgregarUsuario() {
        $("#profileSelect").html('');
        $('#agenciaSelect').html('');
        $('#agenciaTipoSelect').html('');
        $('#imgAvatar').attr("src", 'assets/img/logoMexicana.png');
        $('#txtRol').val('1');
        $('#txtProfile').val('1');
        $('#txtAdminsCompanyAlta').val('');
        $('#txtNickname').val('');
        $('#txtName').val('');
        $('#txtLastName').val('');
        $('#txtLastNameOp').val('');
        $('#txtEmail').val('');
        $('#txtPhoneNumber').val('');


    }

    $('#btnNotification').click(function () {
        $('#txtAdminsCompanyNotify').html('');
        agenciesLoad();
        $('#txtAdminsEmployeeNotify').html('');

    });
</script>
<script type="text/javascript" src="assets/js/clases/usuario.js"></script>
<script type="text/javascript" src="assets/js/clases/notificaciones.js"></script>

