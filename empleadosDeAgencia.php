<?php include("header.php") ?>

<div class="row">
    <div class="col-lg-12" style="margin-bottom:20px;">
        <input type="hidden" id="txtTipoAgencia">
        <div class="form-inline">
            <button type="button" id="btnAddUser" class="btn btn-success btn-lg"><i class="fa fa-plus">&nbsp;</i>Agregar
                empleado
            </button>
            <button type="button" id="btnNotification" class="btn btn-info btn-lg"><i class="fa fa-user">&nbsp;</i>Enviar
                notificaci&oacute;n a un empleado
            </button>
            <label class="sr-only" for="searchUserField">Buscar empleado</label>
            <input type="user" class="form-control" id="searchUserField" style="height: 47px; width: 275px;"
                   placeholder="Buscar empleado">
            <button type="button" id="btnSearchUser" class="btn btn-info btn-lg" onclick="searchUser();"><i
                    class="fa fa-search"></i>Buscar empleado
            </button>
            <button type="button" id="btnShowAll" class="btn btn-default btn-lg"><i class="fa fa-users"></i>Ver todos
            </button>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <!-- tabs -->
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active" ><a href="#employees" data-toggle="tab" >Empleados</a></li>
                </ul>
                <div class="tab-content ">
                    <div class="tab-pane active" id="employees"></div>
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
                        <div class="text-center">Fotograf&iacute;a de perfil</div>
                        <img id="imgAvatar" name="imgAvatar" widht="128" height="128"
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
                            <label id="lblRol" name="lblRol">Rol</label>
                            <select class="form-control" id="txtRol" name="txtRol">
                                <option value="4">Empleado</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label id="lblProfile" name="lblProfile">Perfil</label>
                            <select class="form-control" id="txtProfile" name="txtProfile">
                                <option value=1>Cencista</option>
                                <option value=2>Venta</option>
                                <option value=3>Plomero</option>
                                <option value=4>Instalacion</option>
                                <option value=5>Censista & Venta</option>
                                <option value=6>Plomero & Venta</option>
                                <option value=7>Plomero & Venta & Censista</option>
                                <option value=8>Plomero & Venta & Censista & Instalador</option>
                                <option value=9>Plomero & Instalador</option>
                            </select>
                        </div>
                        <div class="form-group" id="agenciaSelect" name="agenciaSelect">
                        </div>
                        <div class="form-group" id="agenciaTipoSelect" name="agenciaTipoSelect">
                        </div>
                        <div class="form-group" id="agenciaPlazoSelect" name="agenciaPlazoSelect">
                        </div>
                        <div class="form-group">
                            <label>Nickname</label>
                            <input type="text" id="txtNickname" name="txtNickname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" id="txtPassword" name="txtPassword" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" id="txtName" name="txtName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Apellido Paterno</label>
                            <input type="text" id="txtLastName" name="txtLastName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Apellido Materno</label>
                            <input type="text" id="txtLastNameOp" name="txtLastNameOp" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" id="txtEmail" name="txtEmail" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>N&uacute;mero Telef&oacute;nico</label>
                            <input type="text" id="txtPhoneNumber" name="txtPhoneNumber" class="form-control">
                        </div>
            </div>
            <div class="modal-footer">
                <button type=button id="btnCancel" class="btn btn-danger" onclick="cerrarModal()">Cancelar</button>
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
                <h4 class="modal-title">Eliminar empleado</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label>¿Estás seguro que deseas eliminar este empleado?</label>
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

        $("#employees").html("<div class='row'>"
            + "<div class='col-lg-12'>"
            + "<section class='panel'>"
            + "<table id='tablaEmpleados' name='tablaEmpleados' class='table responsive-data-table data-table'>"
            + "<thead>"
            + "<tr>"
            + "<th class='hidden-xs'><i class=''>&nbsp;</i>Id</th>"
            + "<th class='hidden-xs'><i class='fa fa-user-secret'>&nbsp;</i>Nickname</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'>&nbsp;</i>Nombre</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'>&nbsp;</i>Apellido</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'></i>Email</th>"
            + "<th class='hidden-xs'><i class='fa fa-users'>&nbsp;</i>Perfil</th>"
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

        var string_nickname = $("#nicknameZone").html();
        string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
        string_nickname = string_nickname.trim();
        //string_nickname = localStorage.getItem("nickname");
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
                        "<td class='hidden-xs'>" +
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",2);'><i class='fa fa-pencil'></i></button>" +
                        "&nbsp;" +
                        "<button type='button' class='btnDeleteUser btn btn-success btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>" + "</td>" +
                        "</tr>";
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

                    var body = "<tr id=user" + employeeId + ">" +
                        "<td><a href='/mexicana/newProjectStructure/user.php?id=" + employeeId + "'>" + employeeId + "</a></td>" +
                        "<td class='hidden-xs'>" + agencyName + "</td>" +
                        "<td>" + employeeNickname + "</td>" +
                        "<td>" + employeeName + "</td>" +
                        "<td>" + employeeLastName + "</td>" +
                        "<td>" + employeeEmail + "</td>" +
                        "<td>" + employeePhone + "</td>" +
                        "<td>" + employeeProfile + "</td>" +
                        "<td class='hidden-xs'>" +
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",4);'><i class='fa fa-pencil'></i></button>" +
                        "&nbsp;" +
                        "<button type='button' class='btnDeleteUser btn btn-success btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>" + "</td>" +
                        "</td>" +
                        "</tr>";
                    $("#tablaEmpleados tbody").append(body);
                }
                /*DataTables instantiation.*/

                // $( "#tablaEmpleados").DataTable();
            }
        });
    }

    function loadAgency(idAgencia){
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/ObtenerAgenciaPorUsuario.php",
            dataType: "JSON",
            data: {idAgencia: idAgencia},
            success: function (data) {
                $("#txtTipoAgencia").empty();
                $("#txtTipoAgencia").val(data[0].tipo);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });
    }

    $(document).ready(function () {
        var idAgencia = $("#inputIdUser").val();

        $("#titleHeader").html("Empleados");
        $("#subtitle-header").html("Empleados disponibles en el sistema");

        loadAgency(idAgencia);

        loadUsers(idAgencia);

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
                $("#agenciaTipoSelect").append("<label>Tipo</label>"
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
                $("#profileSelect").append("<label>Perfil</label>"
                    + "<select class='form-control' id='txtProfile'>"
                    + "<option value='1'>Cencista</option>"
                    + "<option value='2'>Cambaceo</option>"
                    + "<option value='3'>Plomero</option>"
                    + "<option value='4'>Instalador</option>"
                    + "</select>"
                    + "</div>"
                );

                $('#agenciaSelect').append("<label>Agencia</label>"
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
                $("#txtCompany").empty();
                for (var value in data) {
                    $("#txtCompany").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                }
            }
        });

        $(document).on('click', '#btnShowAll', function () {
            var idAgencia = $("#inputIdUser").val();

            $('#searchUserField').val('');
            loadUsers(idAgencia);
        });

        function loadUsers(idAgencia) {
            loadHeaders();
            // alert(idAgencia);
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/ObtenerEmpleadosDeAgencia.php",
                data: {idAgencia: idAgencia},
                dataType: "JSON",
                success: function (data) {
                    console.log('dataEmpleados', data);
                    // alert(data);
                    var i=0;
                    var sizeEmpleados=data.length;
                    if (sizeEmpleados > 0) {
                        data.forEach(function(entry) {
                            //console.log('entry', entry);
                            if (entry.nickname !== 'Pendiente de Asignar') {
                                var id = entry.id;
                                var nickname = entry.nickname;
                                var name = entry.name;
                                var lastName = entry.lastName;
                                var lastNameOp = entry.lastNameOp;
                                var email = entry.email;
                                var idRol = entry.idRol;


                                var body = "<tr id=user" + id + ">" +
                                    "<td class='hidden-xs'>" + id + "</td>" +
                                    "<td>" + nickname + "</td>" +
                                    "<td>" + name + "</td>" +
                                    "<td>" + lastName + " " +lastNameOp+"</td>" +
                                    "<td>" + email + "</td>" +
                                    "<td>" + "Empleado" + "</td>" +
                                    "<td class='hidden-xs'>" +
                                    "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ",4);'><i class='fa fa-pencil'></i></button>" +
                                    "&nbsp;" +
                                    "<button type='button' class='btnDeleteUser btn btn-success btn-xs' onClick='eliminar(" + id + ");'><i class='fa fa-trash-o'></i></button>" + "</td>" +
                                    "</td>" +
                                    "</tr>";
                                $("#tablaEmpleados tbody").append(body);
                            }
                        });
                    }
                    /*DataTables instantiation.*/
                    $("#tablaEmpleados").DataTable().destroy();
                    $("#tablaEmpleados").DataTable({
                        "order": [[0, 'desc']],
                        autoWidth: false,
                        searching: false,
                        "language": {
                            "lengthMenu": "Mostrar _MENU_",
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



        var rowPick;
        $('#btnAddUser').click(function () {
            $('#agenciaSelect').html('');
            $('#txtAdminsCompanyNotify').html('');
            $('#modalform').modal('show');
            $('#titleCompany').html('Agregar empleado');
            $('#btnAdd').html('Agregar');
            $('#btnAdd').attr("onclick", "JsUsuario.insertarImagenUsuario()");
            $("#txtRol").prop('disabled', false);
            $('#lblRol').show();
            $('#txtRol').show();
            limpiarCamposFormularioModalAgregarUsuario();
            agenciesLoad();
        });


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

                    if($('#txtTipoAgencia').val()=="Comercializadora"){

                        $("#txtProfile").html("<option value='1'>Cencista</option>"
                            + "<option value='2'>Venta</option>"
                            + "<option value='3'>Plomero</option>"
                            + " <option value='5'>Censista &amp; Venta</option>"
                            + " <option value='6'>Plomero &amp; Venta</option>"
                            + "<option value='7'>Plomero &amp; Venta &amp; Censista</option>");
                    }


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
                        $("#profileSelect").append("<label>Perfil</label>"
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
                        $("#profileSelect").append("<label>Perfil</label>"
                            + "<select class='form-control' id='txtProfile' name='txtProfile'>"
                            + "<option value='1'>Cencista</option>"
                            + "<option value='2'>Cambaceo</option>"
                            + "<option value='3'>Plomero</option>"
                            + "<option value='4'>Instalador</option>"
                            + "</select>"
                            + "</div>"
                        );

                        $('#agenciaSelect').append("<label>Agencia</label>"
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
            $('#modalform').modal('hide')
        });

        $('#btnModalFormClose').click(function () {

        });
    });

    $('#btnNotificationCancel').click(function () {
        $('#modalNotification').modal('hide');
    });

    $(document).on('change', '#txtAdminsCompanyNotify', function () {
        var agency = $("#txtAdminsCompanyNotify").val();
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/usersAgency.php",
            data: {
                agency: agency
            },
            dataType: "JSON",
            success: function (data) {
                $("#txtAdminsEmployeeNotify").empty();
                for (var value in data)
                    $("#txtAdminsEmployeeNotify").append("<option value=" + data[value].idUser + ">" + data[value].employee + "</option>");
            }
        });
    });


    function cerrarModal() {
        $('#modalform').modal('hide');
    }


    function createRow(name, mail, company) {
        if ($('#formAgregarNuevoUsuario').valid()) {
            console.log(name)
            console.log(mail)
            console.log(company)
            var xrow = ''
            xrow += '<tr>'
            xrow += '<td><a href="#">1</a></td>'
            xrow += '<td class="hidden-xs">' + company + '</td>'
            xrow += '<td>' + name + '</td>'
            xrow += '<td><span class="label label-success label-mini">Activo</span></td>'
            xrow += '<td class="hidden-xs"><button type="button" class="btnEditUser btn btn-success btn-xs"><i class="fa fa-pencil"></i></button> <button type="button" class="btnDeleteUser btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>'
            xrow += '</td>'
            xrow += '</tr>'

            console.log(xrow)

            $("#bodyData").append(xrow)
            $('#modalform').modal('hide')
        }
    }



    function agenciesLoad() {
        var idUser=$("#inputIdUser").val();
        //alert(idUser);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/AgenciaNombre.php",
            data: {idUser:idUser},
            dataType: "JSON",
            success: function (data) {
                //alert(JSON.stringify(data));
                $("#txtAdminsCompanyAlta").empty();
                $("#txtAdminsCompanyAlta").empty();
                console.log(data);
                $.each(data, function(k, data){
                    $("#txtAdminsCompanyAlta").append("<option value=" + data.id + ">" + data.nickname + "</option>");
                    $("#txtAdminsCompanyNotify").append("<option value=" + data.id + ">" + data.nickname + "</option>");
                });
               
            }
        });
    }

    function cargarEmpleadosParaNotificar() {
        var idAgencia = $("#inputIdUser").val();
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/ObtenerEmpleadosDeAgencia.php",
            data: {idAgencia:idAgencia},
            dataType: "JSON",
            success: function (data) {
                console.log(data );
                for (var value in data) {
                    console.log(data);
                    $("#txtAdminsEmployeeNotify").append("<option value=" + data[value].id + ">" + data[value].nickname + "</option>");
                }
            }
        });
    }

    function limpiarCamposFormularioModalAgregarUsuario() {
        $('#imgAvatar').attr("src", 'assets/img/logoMexicana.png');
        $('#txtRol').val('4');
        $('#txtProfile').val('1');
        $('#txtNickname').val('');
        $('#txtName').val('');
        $('#txtLastName').val('');
        $('#txtLastNameOp').val('');
        $('#txtEmail').val('');
        $('#txtPhoneNumber').val('');

        if($('#txtTipoAgencia').val()=="Comercializadora"){

            $("#txtProfile").html("<option value='1'>Cencista</option>"
                + "<option value='2'>Venta</option>"
                + "<option value='3'>Plomero</option>"
                + " <option value='5'>Censista &amp; Venta</option>"
                + " <option value='6'>Plomero &amp; Venta</option>"
                + "<option value='7'>Plomero &amp; Venta &amp; Censista</option>");
        }


        $('#agenciaSelect').append("<label>Agencia</label>"
            + "<select class='form-control' id='txtAdminsCompanyAlta' name='txtAdminsCompanyAlta'>"
            + "</select>"
            + "</div>"
        );
        $('#txtAdminsCompanyNotify').html('');
    }



    $('#btnNotification').click(function () {
        $("#txtnotificationText").val('');
        $('#txtAdminsCompanyNotify').html('');
        agenciesLoad();
        $('#txtAdminsEmployeeNotify').html('');
        cargarEmpleadosParaNotificar();
        $('#modalNotification').modal('show');
        $('#titleCompany').html('Enviar Notificaci&oacute;n');
        $('#btnAddNotificacion').html('Agregar');
    });
</script>
<script type="text/javascript" src="assets/js/clases/usuario.js"></script>
<script type="text/javascript" src="assets/js/clases/notificaciones.js"></script>