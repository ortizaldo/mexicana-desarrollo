<?php include("header.php") ?>

<div class="row">
    <div class="col-lg-12" style="margin-bottom:20px;">
        <div class="form-inline">
            <button type="button" id="btnAddUser" class="btn btn-success btn-lg"><i class="fa fa-plus"></i>Agregar
                Usuario
            </button>
            <button type="button" id="btnNotification" name="btnNotification" class="btn btn-info btn-lg"><i
                    class="fa fa-user"></i>Enviar
                Notificaci&oacute;n
            </button>
            <label class="sr-only" for="searchUserField">B&uacute;squeda de Usuario</label>
            <input type="user" class="form-control" id="searchUserField" style="height: 47px; width: 275px;"
                   placeholder="Buscar Usuario">
            <button type="button" id="btnSearchUser" class="btn btn-info btn-lg" onclick="searchUser();"><i
                    class="fa fa-search"></i>Búsqueda de Usuario
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
                    <li class="active"><a href="#admins" data-toggle="tab">Administradores</a></li>
                    <li><a href="#agencies" data-toggle="tab">Agencias</a></li>
                    <li><a href="#employees" data-toggle="tab">Empleados</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="admins"></div>
                    <div class="tab-pane" id="agencies"></div>
                    <div class="tab-pane" id="employees"></div>
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
                <form class="cmxform" role="form" id="formNewUser">
                    <div><input type="text" id="txtid" hidden></div>
                    <div class="form-group">
                        <label>Nickname</label>
                        <input type="text" id="txtNickname" name="nickname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" id="txtEmail" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="txtPass" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Avatar</label>
                        <img id="imgAvatar" name="avatar" widht="256" height="256" src=""/>
                        <input type="file" id="inAvatarImg" name="avatarImg">
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <br/>
                        <label class="checkbox-inline"><input type="checkbox" id="chxCensista"
                                                              name="check">Censista</label>
                        <label class="checkbox-inline"><input type="checkbox" id="chxSalesman"
                                                              name="check">Vendedor</label>
                        <label class="checkbox-inline"><input type="checkbox" id="chxPlumber"
                                                              name="check">Plomero</label>
                        <label class="checkbox-inline"><input type="checkbox" id="chxInstaller" name="check">Instalador</label>
                    </div>
                    <div class="form-group">
                        <!--<Add company according the user>-->
                        <label>Agencia</label>
                        <select class="form-control" id="txtCompany">
                            <option value="1">Mexicana de Gas</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type=button id="btnCancel" class="btn btn-danger">Cancelar</button>
                <button type=button id="btnAdd" class="btn btn-success">Agregar</button>
            </div>
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

<div class="modal fade disable-scroll" id="modalNotification" name="modalNotification" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalNotificationClose" data-dismiss="modal"
                        aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleModal">Enviar notificacion a empleado</h4>
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


<?php include("footer.php") ?>

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
            + "<table class='table table-striped custom-table table-hover'>"
            + "<thead>"
            + "<tr>"
            + "<th><i class='fa fa-bookmark-o'></i>Id</th>"
            + "<th><i class='fa fa-building'></i>Compañía</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'></i>Nickname</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'></i>Nombre</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'></i>Email</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-earphone'></i>N&uacute;mero Telef&oacute;nico</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-sort'></i>Rol</th>"
            + "<th><i class='glyphicon glyphicon-cog'></i> Acción</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody id='bodyData'>"
            + "</tbody>"
            + "</table>"
            + "</section>"
            + "</div>"
            + "</div>");

        $("#agencies").html("<div class='row'>"
            + "<div class='col-lg-12'>"
            + "<section class='panel'>"
            + "<table class='table table-striped custom-table table-hover'>"
            + "<thead>"
            + "<tr>"
            + "<th><i class='fa fa-bookmark-o'></i>Id</th>"
            + "<th><i class='fa fa-building'></i>Nombre</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'></i>Usuario</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-earphone'></i>N&uacute;mero Telef&oacute;nico</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-sort'></i>Tipo</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-earphone'></i>N&uacute;mero de Referencia PH</th>"
            + "<th><i class='glyphicon glyphicon-cog'></i> Acción</th>"
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
            + "<table class='table table-striped custom-table table-hover'>"
            + "<thead>"
            + "<tr>"
            + "<th><i class='fa fa-bookmark-o'></i>Id</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'></i>Nickname</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'></i>Nombre</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'></i>Apellido</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'></i>Email</th>"
            + "<th class='hidden-xs'><i class='fa fa-building'></i>Agencia</th>"
            + "<th class='hidden-xs'><i class='fa fa-users'></i>Perfil</th>"
            + "<th><i class='glyphicon glyphicon-cog'></i> Acción</th>"
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
        console.log(string_nickname);

        var temp = DoStuff.whitin(string_nickname);
        console.log(temp);

        $.ajax({
            method: "POST",
            //url: "/mexicana/newProjectStructure/dataLayer/callsWeb/searchUsersByParam.php",
            //url: "/mexicana/dataLayer/callsWeb/searchUsersByParam.php",
            url: "/dataLayer/callsWeb/searchUsersByParam.php",
            data: {value: temp, pivot: value},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                loadHeaders();

                var adminValues = data.Admins;
                var agenciesValues = data.Agencies;
                var employeesValues = data.Employees;

                for (var elem in adminValues) {

                    //console.log(adminValues[elem]);

                    var id = adminValues[elem].id;
                    var avatar = adminValues[elem].avatar;
                    var email = adminValues[elem].email;
                    var lastName = adminValues[elem].lastName;
                    var name = adminValues[elem].name;
                    var nickname = adminValues[elem].nickname;
                    var phoneNumber = adminValues[elem].phoneNumber;
                    var urlImage = adminValues[elem].photoUrl;
                    var rol = adminValues[elem].type;

                    $("#bodyData").append("<tr id=user" + id + ">" +
                            //"<td><a href='/newProjectStructure/user.php?id=" + id + "'>" + id + "</a></td>" +
                        "<td><a href='/mexicana/newProjectStructure/user.php?id=" + id + "'>" + id + "</a></td>" +
                        "<td class='hidden-xs'>" + nickname + "</td>" +
                        "<td>" + name + lastName + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" +
                        "<td>" + rol + "</td>" +
                        "<td class='hidden-xs'>Mexicana</td>" +
                        "<td class='hidden-xs'>" +
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ");'><i class='fa fa-pencil'></i></button>" +
                        "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                        "</td>" +
                        "</tr>");
                }

                for (var elem in agenciesValues) {

                    //console.log(agenciesValues[elem]);

                    var id = agenciesValues[elem].id;
                    var avatar = agenciesValues[elem].avatar;
                    var email = agenciesValues[elem].email;
                    var lastName = agenciesValues[elem].lastName;
                    var name = agenciesValues[elem].name;
                    var nickname = agenciesValues[elem].nickname;
                    var phoneNumber = agenciesValues[elem].phoneNumber;
                    var urlImage = agenciesValues[elem].photoUrl;
                    var rol = agenciesValues[elem].tipo;

                    $("#bodyDataAgencies").append("<tr id=user" + id + ">" +
                        "<td><a href='/mexicana/newProjectStructure/user.php?id=" + id + "'>" + id + "</a></td>" +
                        "<td class='hidden-xs'>Mexicana</td>" +
                        "<td class='hidden-xs'>" + nickname + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" +
                        "<td>" + rol + "</td>" +
                        "<td class='hidden-xs'>" +
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ");'><i class='fa fa-pencil'></i></button>" +
                        "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                        "</td>" +
                        "</tr>");
                }

                for (var elem in employeesValues) {
                    //console.log(employeesValues[elem]);

                    var employeeArray = employeesValues[elem];

                    var employeeId = employeeArray.id;
                    var agencyName = employeeArray.agency;
                    var employeeNickname = employeeArray.employee;
                    var employeeName = employeeArray.employeeName;
                    var employeeLastName = employeeArray.employeeLastName;
                    var employeeEmail = employeeArray.email;
                    var employeePhone = employeeArray.phone;
                    var employeeProfile = employeeArray.profile;

                    $("#bodyDataEmployees").append("<tr id=user" + employeeId + ">" +
                        "<td><a href='/mexicana/newProjectStructure/user.php?id=" + employeeId + "'>" + employeeId + "</a></td>" +
                        "<td class='hidden-xs'>" + agencyName + "</td>" +
                        "<td>" + employeeNickname + "</td>" +
                        "<td>" + employeeName + "</td>" +
                        "<td>" + employeeLastName + "</td>" +
                        "<td>" + employeeEmail + "</td>" +
                        "<td>" + employeePhone + "</td>" +
                        "<td>" + employeeProfile + "</td>" +
                        "<td class='hidden-xs'>" +
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + employeeId + ");'><i class='fa fa-pencil'></i></button>" +
                        "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                        "</td>" +
                        "</tr>");
                }
            }
        });
    }

    function createRow(name, mail, company) {
        if ($('#formNewUser').valid()) {
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
            cleanFields()
        }
    }

    function cleanFields() {
        $("#txtName").val('')
        $("#txtMail").val('')
    }

    function resetValidation() {
        form_user.resetForm();
    }

    var form_user = $("#formNewUser").validate({
        rules: {
            txtName: {
                required: true,
                minlength: 4
            },
            txtMail: {
                required: true,
                email: true
            }
        },
        messages: {
            txtName: {
                required: "Es necesario este campo",
                minlength: "El nombre debera ser mayor a tres caracteres"
            },
            txtMail: "Es necesario agregar un correo valido"
        }
    });

    function agenciesLoad() {
        $.ajax({
            method: "POST",
            url: "/mexicana/dataLayer/callsWeb/companiesName.php",
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                for (var value in data) {
                    console.log(data[value]);
                    $("#txtAdminsCompanyNotify").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
            },
            complete: function (XMLHttpRequest, status) {
            }
        });
    }

    $(document).on('change', '#txtAdminsCompanyNotify', function () {
        var agency = $("#txtAdminsCompanyNotify").val();
        //alert(agency);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/usersAgency.php",
            data: {
                agency: agency
            },
            dataType: "JSON",
            success: function (data) {
                //alert('se obtuvo los usuarios de la agencia');
                //alert(JSON.stringify(data))
                for (var value in data)
                    $("#txtAdminsEmployeeNotify").append("<option value=" + data[value].id + ">" + data[value].employee + "</option>");
            }
        });
    });
</script>


<script type="text/javascript" src="assets/js/clases/notificaciones.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var rol;

        var string_nickname = $("#nicknameZone").html();
        string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
        string_nickname = string_nickname.trim();
        //string_nickname = localStorage.getItem("nickname");
        console.log(string_nickname);

        $("#titleHeader").html("Usuarios");
        $("#subtitle-header").html("Detalle de mis Usuarios");

        var parameter = "admins";
        loadUsers(string_nickname, parameter);

        $(document).on('change', '#txtRol', function () {
            rol = $("#txtRol").val();

            if (rol == 4) {
                $("#profileSelect").append("<label>Perfil</label>"
                    + "<select class='form-control' id='txtProfile'>"
                    + "<option value='1'>Cencista</option>"
                    + "<option value='2'>Cambaceo</option>"
                    + "<option value='3'>Plomero</option>"
                    + "<option value='4'>Instalador</option>"
                    + "</select>"
                    + "</div>");
            } else {
                $("#profileSelect").html('');
            }
        });

        $.ajax({
            method: "POST",
            //url: "/mexicana/dataLayer/callsWeb/companiesName.php",
            url: "dataLayer/callsWeb/companiesName.php",
            dataType: "JSON",
            success: function (data) {
                //console.log(data);
                for (var value in data) {
                    //console.log(data[value]);
                    $("#txtCompany").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                }
            }
        });

        $(document).on('click', '#btnShowAll', function () {
            var string_nickname = $("#nicknameZone").html();
            console.log(string_nickname);
            string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
            string_nickname = string_nickname.trim();

            $('#searchUserField').val('');
            loadUsers(string_nickname, "admins");
        });

        function loadUsers(nickname, param) {
            loadHeaders();

            $.ajax({
                method: "POST",
                //url: "/dataLayer/callsWeb/showUsers.php",
                url: "dataLayer/callsWeb/showUsers.php",
                data: {nickname: nickname, param: param},
                dataType: "JSON",
                success: function (data) {
                    console.log(data);

                    console.log(data.Admins);
                    console.log(data.Agencies);
                    console.log(data.Employees);

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

                        $("#bodyData").append("<tr id=user" + id + ">" +
                            "<td><a href='/mexicana/newProjectStructure/user.php?id=" + id + "'>" + id + "</a></td>" +
                            "<td class='hidden-xs'>Mexicana</td>" +
                            "<td class='hidden-xs'>" + nickname + "</td>" +
                            "<td>" + name + lastName + "</td>" +
                            "<td>" + email + "</td>" +
                            "<td>" + phoneNumber + "</td>" +
                            "<td>" + rol + "</td>" +
                            "<td class='hidden-xs'>" +
                            "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ");'><i class='fa fa-pencil'></i></button>" +
                            "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                            "</td>" +
                            "</tr>");
                    }

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

                        $("#bodyDataAgencies").append("<tr id=user" + id + ">" +
                            "<td><a href='/mexicana/newProjectStructure/user.php?id=" + id + "'>" + id + "</a></td>" +
                                //"<td class='hidden-xs'>Mexicana</td>" +
                            "<td class='hidden-xs'>" + nickname + "</td>" +
                            "<td>" + email + "</td>" +
                            "<td>" + phoneNumber + "</td>" +
                            "<td>" + rol + "</td>" +
                            "<td class='hidden-xs'>" +
                            "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ");'><i class='fa fa-pencil'></i></button>" +
                            "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                            "</td>" +
                            "</tr>");
                    }

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

                        $("#bodyDataEmployees").append("<tr id=user" + employeeId + ">" +
                            "<td><a href='/mexicana/newProjectStructure/user.php?id=" + employeeId + "'>" + employeeId + "</a></td>" +
                            "<td>" + employeeNickname + "</td>" +
                            "<td>" + employeeName + "</td>" +
                            "<td>" + employeeLastName + "</td>" +
                            "<td>" + employeeEmail + "</td>" +
                            "<td>" + agencyName + "</td>" +
                            "<td class='hidden-xs'>" + employeeProfile + "</td>" +
                            "<td class='hidden-xs'>" +
                            "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + employeeId + ");'><i class='fa fa-pencil'></i></button>" +
                            "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                            "</td>" +
                            "</tr>");
                    }
                }
            });
        }

        function editUser(id, nickname, email, pass, img, roles, agency) {
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/updateUser.php",
                data: {
                    id: id, nickname: nickname, email: email, pass: pass, img: img, roles: roles, agency: agency
                },
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    //$.('#').close();
                }
            });
        }

        function saveUser(id, nickname, email, pass, img, roles, agency) {
            console.log(id);
            console.log(nickname);
            console.log(email);
            console.log(pass);
            console.log(img);
            console.log(roles);
            console.log(agency);
            console.log("Creating User");
            var password;
            //var passwordRetype;
            password = makeid();
            //Ajax Request to add user to database
            $.ajax({
                method: "POST",
                //url: "/mex/dataLayer/callsWeb/ShortcutCreateUser.php",
                url: "dataLayer/callsWeb/ShortcutCreateUser.php",
                data: {
                    agency: agency,
                    nickname: nickname,
                    email: email,
                    pass: password,
                    img: img,
                    rol: roles,
                    agency: agency,
                },
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                }
            });
        }

        var rowPick;
        $('#btnAddUser').click(function () {
            resetValidation();
            $('#modalform').modal('show');
            $('#titleCompany').html('Agregar Usuario');
            $('#btnAdd').html('Agregar');
            cleanFields();
        });

        function agenciesLoad() {
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/companiesName.php",
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    for (var value in data) {
                        console.log(data[value]);
                        $("#txtAdminsCompanyNotify").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                    }
                }
            });
        }


        $('#btnAdd').click(function () {
            resetValidation();

            var xRoles = [];

            var buttonType = $('#btnAdd').text();
            var xId = $("#txtid").val();
            var xNickname = $('#txtNickname').val();
            var xEmail = $('#txtEmail').val();
            var xPass = $('#txtPass').val();
            //var xImg = $('#imgAvatar').val();
            var xImg = document.getElementById('imgAvatar').src;
            xImg = xImg.substr(xImg.indexOf(",") + 1);

            if ($('#chxCensista').is(":checked")) {
                xRoles.push("Censista");
            } else if ($('#chxSalesman').is(":checked")) {
                xRoles.push("Vendedor");
            } else if ($('#chxPlumber').is(":checked")) {
                xRoles.push("Plomero");
            } else if ($('#chxInstaller').is(":checked")) {
                xRoles.push("Instalador");
            }
            var xCompany = $("#txtCompany").val();

            $("#txtid").val("");
            $("#txtNickname").val("");
            $("#txtEmail").val("");
            $("#txtPass").val("");
            $("#imgAvatar").val("");
            $("#txtCompany").val("");

            if (buttonType == "Guardar") {
                editUser(xId, xNickname, xEmail, xPass, xImg, xRoles, xCompany);
            } else if (buttonType == "Agregar") {
                saveUser(xId, xNickname, xEmail, xPass, xImg, xRoles, xCompany);
            }
            //createRow(xName, xEmail, xCompany);

            $('#modalform').modal('hide');
            cleanFields();
        });

        window.edit = function (id) {
            rowPick = $(this).parent().parent();
            resetValidation();
            cleanFields();

            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/showUser.php",
                data: {id: id},
                dataType: "JSON",
                success: function (data) {
                    console.log(data);

                    for (var elem in data) {

                        console.log(data[elem]);

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

                        //var urlImage = data[elem].photoUrl;
                        //load photo to canvas
                    }

                    $('#txtid').val(id);
                    $('#txtNickname').val(nickname);
                    $('#txtName').val(name);
                    $('#txtLastName').val(lastName);
                    $('#txtLastNameOp').val(lastNameOp);
                    $('#txtEmail').val(email);
                    //$('#txtBirthDate').val(data.birthdate);
                    //$('#txtGender').val(data.gender);
                    $('#txtPhoneNumber').val(phoneNumber);
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

                    $('#modalform').modal('show');
                }
            });
        }

        $('#btnCancel').click(function () {
            $('#modalform').modal('hide')
        });

        $(document.body).on("click", ".btnDeleteUser", function () {
            rowPick = $(this).parent().parent()
            $('#modalDelete').modal('show')
        });

        $('#btnCancelUser').click(function () {
            $('#modalDelete').modal('hide');

            //Do ajax to set user status active to 0

            //rowPick.remove();
            //rowPick = '';
        });

        $('#btnModalFormClose').click(function () {
            cleanFields();
        });


        $('#btnNotification').click(function () {
            resetValidation();
            agenciesLoad();
            $('#modalNotification').modal('show');
            $('#titleCompany').html('Enviar Notificaci&oacute;n');
            $('#btnAddNotificacion').html('Agregar');
            cleanFields();
        });
    });
</script>