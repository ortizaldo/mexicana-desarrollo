<?php include("header.php") ?>

<!-- Codigo -->
<div class="row">
    <div class="col-lg-12" style="margin-bottom:20px;">
        <button type="button" id="btnAddUser" class="btn btn-lg btn-success"><i class="fa fa-plus">&nbsp;</i> Agregar Agencia
        </button>
        <button type="button" id="btnNotification" class="btn btn-info btn-lg"><i class="fa fa-building">&nbsp;&nbsp;</i>Enviar
            notificaci&oacute;n a una agencia
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <!-- tabs -->
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#agencies" data-toggle="tab">Agencias</a></li>
                <li><a href="#employees" data-toggle="tab">Empleados</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="agencies" name="agencies"></div>
                <div class="tab-pane" id="employees" name="employees"></div>
            </div>
        </div>
        <!-- /tabs -->
    </div>
</div>

<div class="modal fade disable-scroll" id="modalform" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalFormClose" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleCompany">Agregar Agencia</h4>
            </div>
            <div class="modal-body">
                <form class="cmxform" role="form" id="formNewUser">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="txtName" name="txtName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Correo</label>
                        <input type="text" id="txtMail" name="txtMail" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="txtPass" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Avatar</label>
                        <img id="imgAvatar" name="avatar" widht="300" height="256" src="assets/img/fotoweb.jpg" />
                        <input type="file" id="inAvatarImg" name="avatarImg">
                    </div>
                    <div class="form-group">
                        <!--<Add company according the user>-->
                        <label>Tipo</label>
                        <select class="form-control" id="txtType">
                            <option value="1">Instalación</option>
                            <option value="1">PH</option>
                            <option value="1">Censo</option>
                            <option value="1">Promotor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Número de referencia PH</label>
                        <input type="text" id="txtph" name="txtph" class="form-control">
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
                <h4 class="modal-title">Cancelar usuario</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label>¿Esta seguro de cancelar este usuario?</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type=button id="btnCancelUser" class="btn btn-danger">Sí, cancelar</button>
            </div>
        </div>
        <!-- modal content-->
    </div>
    <!-- modal-dialog-->
</div>
<!-- /.modal-->


<div class="modal fade disable-scroll" id="modalNotification" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnModalNotificationClose" data-dismiss="modal"
                        aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleModal">Enviar notificaci&oacute;n a una agencia</h4>
            </div>
            <div class="modal-body">
                <form class="cmxformNotification" role="form" id="formNewNotification" name="formNewNotification">
                    <div class="form-group">
                        <label>Mensaje</label>
                        <input type="text" id="txtnotificationText" name="notificationTextField" class="form-control">
                    </div>
                    <h4>DESTINATARIO</h4>

                    <div class="form-group">
                        <label>Agencia</label>
                        <select class="form-control" id="txtAgenciesCompanyNotify" name="txtAgenciesCompanyNotify">
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type=button id="btnNotificationCancel" class="btn btn-danger">Cancelar</button>
                <button type=button id="btnAddNotification" onclick="JsNotificaciones.insertarNotificacion();"
                        class="btn btn-success">Agregar
                </button>
            </div>
        </div>
        <!-- modal content-->
    </div>
    <!-- modal-dialog-->
</div>
<?php include("footer.php") ?>

<!--form validation-->
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">
    function loadCompanies() {
        $.ajax({
            method: "POST",
            //url: "/newProjectStructure/dataLayer/callsWeb/companiesName.php",
            url: "dataLayer/callsWeb/companiesName.php",
            //data: { nickname: string_nickname},
            dataType: "JSON",
            success: function (data) {
                //console.log('cargarAgencias '+ data);
                //console.log('cargarAgencias id '+ data[0].id);


                for (var value in data) {
                    $("#txtAgenciesCompany").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
                }
            }
        });
    }

    //" +id+","+name+","+lastName+","+name+","+email+","+phoneNumber+","+rol+","+urlImage+"
    function loadUsers(nickname, param) {
        $("#agencies").html("<div class='row'>"
            + "<div class='col-lg-12'>"
            + "<section class='panel'>"
            + "<table class='table table-striped custom-table table-hover'>"
            + "<thead>"
            + "<tr>"
            + "<th><i class='fa fa-bookmark-o'>&nbsp;</i>Id</th>"
                //+"<th><i class='fa fa-building'></i>Compañía</th>"
            + "<th class='hidden-xs'><i class='fa fa-building'>&nbsp;</i>Agencia</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'>&nbsp;</i>Nombre</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'>&nbsp;</i>Email</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-earphone'>&nbsp;</i>N&uacute;mero Telef&oacute;nico</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-sort'>&nbsp;</i>Rol</th>"
                //+"<th><i class='fa fa-bar-chart-o'></i> status</th>"
            + "<th><i class='glyphicon glyphicon-cog'>&nbsp;</i> Acción</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody id='bodyDataAgencies' name='bodyDataAgencies'>"
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
            + "<th><i class='fa fa-bookmark-o'>&nbsp;</i>Id</th>"
            + "<th class='hidden-xs'><i class='fa fa-user-secret'>&nbsp;</i>Alias</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'>&nbsp;</i>Nombre</th>"
            + "<th class='hidden-xs'><i class='fa fa-building'>&nbsp;</i>Agencia</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'>&nbsp;</i>Email</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-earphone'>&nbsp;</i>N&uacute;mero Telef&oacute;nico</th>"
            + "<th><i class='glyphicon glyphicon-cog'>&nbsp;</i> Acción</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody id='bodyDataEmployees' name='bodyDataEmployees'>"
            + "</tbody>"
            + "</table>"
            + "</section>"
            + "</div>"
            + "</div>");

        //console.log('nickname '+ nickname);
        //console.log('param '+ param);

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/showUsers.php",
            data: {nickname: nickname, param: 'agencies'},
            dataType: "JSON",
            success: function (data) {
                //console.log('cargarUsuarios '+ data);

                var tempAgencies = data.Agencies;
                var tempAgenciesSelect = data.AgenciesSelect;
                var tempEmployees = data.Employees;

                // console.log(tempAgencies);
                //  console.log(tempAgenciesSelect);
                //  console.log(tempEmployees);

                for (var agencies in tempAgencies) {
                    // console.log(tempAgencies[agencies]);

                    var id = tempAgencies[agencies].id;
                    var avatar = tempAgencies[agencies].avatar;
                    var email = tempAgencies[agencies].email;
                    var lastName = tempAgencies[agencies].lastName;
                    var name = tempAgencies[agencies].name;
                    var nickname = tempAgencies[agencies].nickname;
                    var phoneNumber = tempAgencies[agencies].phoneNumber;
                    var urlImage = tempAgencies[agencies].photoUrl;
                    var rol = tempAgencies[agencies].type;

                    $("#bodyDataAgencies").append("<tr id=user" + id + ">" +
                        "<td><a href='user.php?id=" + id + "'>" + id + "</a></td>" +
                            //"<td class='hidden-xs'>Mexicana</td>"+
                        "<td class='hidden-xs'>" + nickname + "</td>" +
                        "<td>" + name +"&nbsp;"+ lastName + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" +
                        "<td>" + rol + "</td>" +
                            //"<td><span class='label label-success label-mini'>Activo</span></td>"+
                        "<td class='hidden-xs'>" +
						//edit(id,email,name,type,ph,urlImage)
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit("+id+",\""+email+"\",\""+nickname+"\",\""+rol+"\",\""+"123123"+"\",\""+urlImage+"\")'><i class='fa fa-pencil'><i></button>" +
                        "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                        "</td>" +
                        "</tr>");

                }

                /*for (var value in data) {

                 var id = data[value].id;
                 var avatar = data[value].avatar;
                 var email = data[value].email;
                 var lastName = data[value].lastName;
                 var name = data[value].name;
                 var nickname = data[value].nickname;
                 var phoneNumber = data[value].phoneNumber;
                 var urlImage = data[value].photoUrl;
                 var rol = data[value].type;

                 $("#bodyDataAgencies").append("<tr id=user" + id + ">" +
                 "<td><a href='user.php?id=" + id + "'>" + id + "</a></td>" +
                 //"<td class='hidden-xs'>Mexicana</td>"+
                 "<td class='hidden-xs'>" + nickname + "</td>" +
                 "<td>" + name +"&nbsp;"+ lastName + "</td>" +
                 "<td>" + email + "</td>" +
                 "<td>" + phoneNumber + "</td>" +
                 "<td>" + rol + "</td>" +
                 //"<td><span class='label label-success label-mini'>Activo</span></td>"+
                 "<td class='hidden-xs'>" +
                 "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit("+id+");'><i class='fa fa-pencil'></i></button>" +
                 "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                 "</td>" +
                 "</tr>");
                 }*/
            }
        });

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/showUsers.php",
            data: {nickname: nickname, param: 'employees'},
            dataType: "JSON",
            success: function (data) {
                //console.log('cargarUsuarios '+ data);

                var tempAgencies = data.Agencies;
                var tempAgenciesSelect = data.AgenciesSelect;
                var tempEmployees = data.Employees;

                /* console.log(tempAgencies);
                 console.log(tempAgenciesSelect);
                 console.log(tempEmployees);*/

                for (var employees in tempEmployees) {
                    // console.log(tempEmployees[employees]);

                    var id = tempEmployees[employees].id;
                    var email = tempEmployees[employees].email;
                    var employee = tempEmployees[employees].employee;
                    var employeeName = tempEmployees[employees].employeeName;
                    var employeeLastName = tempEmployees[employees].employeeLastName;
                    var agency = tempEmployees[employees].agency;
                    var phoneNumber = tempEmployees[employees].phone;

                    $("#bodyDataEmployees").append("<tr id=user" + id + ">" +
                        "<td><a href='user.php?id=" + id + "'>" + id + "</a></td>" +
                            //"<td class='hidden-xs'>Mexicana</td>"+
                        "<td class='hidden-xs'>" + employee + "</td>" +
                        "<td>" + employeeName +"&nbsp;"+ employeeLastName + "</td>" +
                        "<td>" + agency + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" + "<td class='hidden-xs'>" +
                        "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit("+id+",\""+email+"\",\""+employee+"\")'><i class='fa fa-pencil'><i></button>" +
                        "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                        "</td>" +
                        "</tr>");
                }

               /* for (var value in data) {
                    var id = data[value].id;
                    var email = data[value].email;
                    var employee = data[value].employee;
                    var employeeName = data[value].employeeName;
                    var employeeLastName = data[value].employeeLastName;
                    var agency = data[value].agency;
                    var phoneNumber = data[value].phone;
                    //console.log(email.toString());
                    var stringEmail=String(email);
                    $("#bodyDataEmployees").append("<tr id=user" + id + ">" +
                        "<td><a href='/user.php?id=" + id + "'>" + id + "</a></td>" +
                            //"<td class='hidden-xs'>Mexicana</td>"+
                        "<td class='hidden-xs'>" + employee + "</td>" +
                        "<td>" + employeeName +"&nbsp;"+ employeeLastName + "</td>" +
                        "<td>" + agency + "</td>" +
                        "<td>" + email + "</td>" +
                        "<td>" + phoneNumber + "</td>" +
                        "<td class='hidden-xs'>" +
                        '<button type="button" class="btnEditUser btn btn-success btn-xs" onClick="edit('+id+',\""'+email+'\"")"><i class="fa fa-pencil"><i></button>' +
                        "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>" +
                        "</td>" +
                        "</tr>");
                }*/
            }
        });




    }

    function edit(id,email,name,type,ph,urlImage){
        //alert(id+" "+email)
        
           cleanFields()
         $('#modalform').modal('show')
         $('#txtName').val(name)
         $('#txtMail').val(email)
         $('#txtPass').hide()
         $('#txtType').val(type)
         $('#my_image').attr('src',urlImage);
		 readURLEdit(urlImage)
         $('#titleCompany').html('Editar Agencia')
         $('#btnAdd').html('Guardar');
        
    }
    /*function edit(id,name,lastName,name,email,phoneNumber,rol,urlImage){
     //alert(id);
     resetValidation()
     cleanFields()
     //        alert(rowPick);
     $('#modalform').modal('show')
     $('#txtName').val(name)
     $('#txtMail').val(email)
     $('#txtPass').val(rowPick.find().text())
     $('#txtType').val(rowPick.find(rol).text())

     $('#titleCompany').html('Editar Usuario')
     $('#btnAdd').html('Guardar');
     };*/

    function  saveUser(name, mail, pass, tipo, ph) {


        /* $nickname=$_POST["nickname"];
         $name=$_POST["name"];
         $lastName=$_POST["lastName"];
         $lastNameOp=$_POST["lastNameOp"];
         $email=$_POST["email"];
         $password=$_POST["password"];
         $passwordRetype=$_POST["passwordRetype"];
         $birthdate=$_POST["birthdate"];
         $gender=$_POST["gender"];
         $phoneNumber=$_POST["phoneNumber"];
         $typeAgency=$_POST["typeAgency"];
         $credit=$_POST["credit"];
         $profile=$_POST["profile"];*/
        console.log(name);
        console.log(mail);
        console.log(pass);
        console.log(tipo);
        console.log(ph);
        console.log("Creating User");
        var password;
        //var passwordRetype;
        password = makeid();
        //Ajax Request to add user to database
        $.ajax({
            method: "POST",
            //url: "/mex/dataLayer/callsWeb/ShortcutCreateUser.php",
            url: "dataLayer/createAgencys.php",
            data: {
                name: name,
                email: email,
                pass: pass,
                tipo: tipo,
                ph: ph,
            },
            dataType: "JSON",
            success: function (data) {
                //   console.log(data);
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
        });
    }


    $(document).ready(function () {
        //var string_nickname = $("#user_nickname").html();
        var string_nickname = $("#nicknameZone").html();
        string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
        string_nickname = string_nickname.trim();

        $("#titleHeader").html("Agencias");
        $("#subtitle-header").html("Agencias disponibles en el sistema");

        var parameter = "agencies";

        loadUsers(string_nickname, parameter);
        loadCompanies();

        /*$.ajax({
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
         });*/

    });

	    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgAvatar').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
     function readURLEdit(input) {
        if (input) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgAvatar').attr('src', e.target.result);
            }
            reader.readAsDataURL(input);
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

    $('#newform').click(function () {
        $('#modalform').modal('show');
    });

    var rowPick;
    $('#btnAddUser').click(function () {
        resetValidation();
        $('#modalform').modal('show');
        $('#titleCompany').html('Agregar Agencia');
        $('#btnAdd').html('Agregar');
        cleanFields();
    });

    $('#btnAdd').click(function () {
        resetValidation()
        var xName = $('#txtName').val()
        var xMail = $('#txtMail').val()
        var xPass = $('#txtPass').val()
        var xType = $('#txtType').val()
        var xph = $('#txtph').val()
        createRow(xName, xMail,xPass,xType,xph)
    });

    $(document.body).on("click", ".btnEditUser", function () {
        rowPick = $(this).parent().parent()
        /*  resetValidation()
         cleanFields()
         //        alert(rowPick);
         $('#modalform').modal('show')
         $('#txtName').val(rowPick.find(':nth-child(2)').text())
         $('#txtMail').val(rowPick.find(':nth-child(4)').text())
         $('#txtPass').val(rowPick.find(':nth-child(5)').text())
         $('#txtType').val(rowPick.find(':nth-child(6)').text())

         $('#titleCompany').html('Editar Usuario')
         $('#btnAdd').html('Guardar');*/
    });

    $('#btnCancel').click(function () {
        $('#modalform').modal('hide')
    });

    $(document.body).on("click", ".btnDeleteUser", function () {
        rowPick = $(this).parent().parent()
        $('#modalDelete').modal('show')
    });

    $('#btnCancelUser').click(function () {
        $('#modalDelete').modal('hide')
        rowPick.remove()
        rowPick = '';
    });


    $('#btnNotificationCancel').click(function () {
        $('#modalNotification').modal('hide')
        rowPick.remove()
        rowPick = '';
    });
    $('#btnModalFormClose').click(function () {
        cleanFields();
    });

    function createRow(name, mail, pass,type,ph) {
        if ($('#formNewUser').valid()) {
            console.log(name)
            console.log(mail)
            console.log(company)
            //        createRow(xName, xMail,xPass,xType,xph)
            saveUser(name, mail, pass, tipo, ph)
            /*
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
             cleanFields()*/
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
            url: "dataLayer/callsWeb/companiesName.php",
            dataType: "JSON",
            success: function (data) {
                //console.log(data);
                for (var value in data) {
                    //console.log(data[value]);
                    $("#txtAgenciesCompanyNotify").append("<option value=" + data[value].idUser + ">" + data[value].agency + "</option>");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(textStatus);
            },
            complete: function (XMLHttpRequest, status) {
            }
        });
    }


    $('#btnNotification').click(function () {
        resetValidation();
        agenciesLoad();
        $('#modalNotification').modal('show');
        $('#titleCompany').html('Enviar Notificaci&oacute;n a una agencia');
        $('#btnAddNotificacion').html('Agregar');
        cleanFields();
    });
</script>