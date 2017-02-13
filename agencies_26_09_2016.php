<?php include("header.php") ?>

<!-- Codigo -->
<div class="row">
    <div class="col-lg-12" style="margin-bottom:20px;">
        <?php
        if ($_SESSION["rol"] == "Agency") {
            echo '';
        } else {
            echo '<button type="button" id="btnAddUser" class="btn btn-lg btn-success"><i class="fa fa-plus">&nbsp;</i> Agregar Agencia</button>';
        } ?>
        <button type="button" id="btnNotification" class="btn btn-info btn-lg">
            <i class="fa fa-building">&nbsp;&nbsp;</i>Enviar notificaci&oacute;n a una agencia
        </button>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">
        <!-- tabs -->
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#agencies" data-toggle="tab">Agencias</a></li>
                <li class="hidden"><a href="#employees" data-toggle="tab">Empleados</a></li>
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
                <h4 class="modal-title" id="titleCompany">Usuario</h4>
            </div>
            <div class="modal-body">
                <form class="cmxform" id="formAgregarNuevaAgenciaImagen" name="formAgregarNuevaAgenciaImagen"
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

                    <form class="cmxform" id="formAgregarNuevaAgencia" name="formAgregarNuevaAgencia"
                          method="post" enctype="multipart/form-data"
                          onsubmit="return false;">
                        <div><input type="text" id="txtId" name="txtId" class="hidden"></div>
                        <div><input type="text" id="txtUrlImagen" name="txtUrlImagen" class="hidden"></div>

                        <div class="form-group">
                            <label class="red-color"><strong>Campos obligatorios *</strong></label>
                        </div>
                        <div class="form-group">
                            <label class="red-color">Nickname *</label>
                            <input type="text" id="txtNickname" name="txtNickname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Password *</label>
                            <input type="text" id="txtPassword" name="txtPassword" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Nombre responsable *</label>
                            <input type="text" id="txtName" name="txtName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Apellido paterno responsable *</label>
                            <input type="text" id="txtLastName" name="txtLastName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Apellido materno responsable *</label>
                            <input type="text" id="txtLastNameOp" name="txtLastNameOp" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="red-color">Email *</label>
                            <input type="text" id="txtEmail" name="txtEmail" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="red-color">Tel&eacute;fono *</label>
                            <input type="text" id="txtPhoneNumber" name="txtPhoneNumber" class="form-control">
                        </div>
                        <div class="form-group">
                            <!--<Add company according the user>-->
                            <label class="red-color">Tipo *</label>
                            <select class="form-control" id="txtTipoAgencia" name="txtTipoAgencia">
                                <option value="1">Instalacion</option>
                                <option value="2">PH</option>
                                <option value="3">Censo</option>
                                <option value="4">Promotor</option>
                                <option value="5">Financiera</option>
                                <option value="6">CallCenter</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <!--<Add company according the user>-->
                            <label class="red-color">Perfil de la agencia *</label>
                            <select class="form-control" id="txtPerfilAgencia" name="txtPerfilAgencia">
                                <option value=1>Cencista</option>
                                <option value=2>Venta</option>
                                <option value=3>Plomero</option>
                                <option value=4>Instalacion</option>
                                <option value=5>Censista & Venta</option>
                                <option value=6>Plomero & Venta</option>
                                <option value=7>Plomero & Venta & Censista</option>
                                <option value=8>Plomero & Venta & Censista & Instalador</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Número de referencia PH</label>
                            <input type="text" id="txtPhAgencia" name="txtPhAgencia" class="form-control">
                        </div>
            </div>
            <div class="modal-footer">
                <button type=button id="btnCancel" class="btn btn-danger" onclick="cerrarModal();">Cancelar</button>
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
                        <!--<input type="text" id="txtnotificationText" name="notificationTextField" class="form-control">-->
                        <textarea id="txtnotificationText" rows="4" cols="60" class="form-control"></textarea>
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

<div class="modal fade disable-scroll" id="modalAssingCity" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Municipio</h4>
            </div>
            <div class="modal-body" id="formNewNotification">
                <form role="form" id="formAssingCity">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="col-md-1">
                                &nbsp;
                            </div>
                            <div class="col-md-4">
                                <select multiple id="cities" class="form-group"></select>
                            </div>
                            <div class="col-md-3">
                                <img src="assets/icons/agregar.png" onclick="addCity();" height="24px" width="24px"/>
                                <br/>
                                <br/>
                                <img src="assets/icons/delete.png" onclick="substractCity();" height="24px"
                                     width="24px"/>
                            </div>
                            <div class="col-md-4">
                                <select multiple id="citiesAssigned" class="form-group"></select>
                            </div>
                        </div>
                    </div>
                </form>
                <br/>
                <br/>
                <br/>
                <br/>
                <input type="hidden" id='idAgency' name="idAgency" style="display: none;"  value=''/>
            </div>
            <div class="modal-footer">
                <button type=button id="btnCancel" class="btn btn-danger" onclick="cerrarModal();">CANCELAR</button>
                <!--<button type=button id="btnAssing" onclick="();" class="btn btn-success">GUARGAR</button>-->
                <button type=button id="btnAssing" class="btn btn-success">GUARGAR</button>
            </div>
        </div>
        <!-- modal content-->
    </div>
    <!-- modal-dialog-->
</div>
<!-- /.modal-->

<?php //include("footer.php") ?>
<?php include("footerDataTables.php") ?>

<!--form validation-->
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">
    var cities = new Object;

    function loadCompanies() {
        $.ajax({
            method: "GET",
            url: "dataLayer/callsWeb/companiesName.php",
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

    function assingCity(id) {
        $('#idAgency').val(id);
        $("#cities").html('');
        $("#citiesAssigned").html('');

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/assignedMunicipios.php",
            data: {id: id},
            dataType: "JSON",
            success: function (data) {
                var sitiesTemp = data.ot_municipios;
                sitiesTemp = sitiesTemp.ot_municipiosRow;
                var citiesList = data.assigned;

                for (var elem in sitiesTemp) {
                    if (sitiesTemp[elem].nombre !== null) {
                        var item = '<option value="' + sitiesTemp[elem].idMunicipio + '">' + sitiesTemp[elem].nombre + '</option>';
                        var add = true;

                         $.each(citiesList, function(i, e){
                            if (sitiesTemp[elem].idMunicipio == e.city) {
                                add = false;
                            }
                        });
                        if(add)
                            $('#cities').append(item);
                    }
                }
                $.each(citiesList, function(i, e){
                     for (var elem in sitiesTemp) {
                        if (sitiesTemp[elem].idMunicipio == e.city) {
                            $('#citiesAssigned').append('<option value="' + sitiesTemp[elem].idMunicipio + '">' + sitiesTemp[elem].nombre + '</option>');
                        }
                    }
                });

                $('#modalAssingCity').modal('show');
            }
        });
        //$('#modalAssingCity').modal('show');
    }

    function sortSelect(selectToSort) {
        jQuery(selectToSort.options).sort(function (a, b) {
            return a.value > b.value ? 1 : -1;
        });
    }

    function addCity() {
        console.log('AddCity clicked');
        var cityVal;
        var cityText;
        //console.log($('#cities').val());

        //var optionValue = $('#cities').val();
        //var optionValue = [];
        //console.log(optionValue);
        //var optionText = $('#cities').text();
        //var optionText = [];
        //console.log(optionText);

        $("#cities option:selected").each(function () {
            //optionValue.push($( this ).val());
            cities.Val = $(this).val();
            //optionText.push($( this ).text());
            cities.Text = $(this).text();
        });

        //cities.push($('#cities').val());
        console.log(cities);

        for (var city in cities) {
            if (city == "Val") {
                cityVal = cities[city];
            } else if (city == "Text") {
                cityText = cities[city];
            }
            console.log(cityVal);
            if (cityVal !== undefined && cityText !== undefined) {
                $("#cities option[value='" + cityVal + "']").remove();
                $("#citiesAssigned").append('<option value="' + cityVal + '">' + cityText + '</option>');
            }
        }
        //$("#citiesAssigned").append('<option value="' + optionValue + '">'+optionText+'</option>');
    }

    function sortObject(obj) {
        var arr = [];
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                arr.push({
                    'key': prop,
                    'value': obj[prop]
                });
            }
        }
        arr.sort(function (a, b) {
            return a.value - b.value;
        });
        //arr.sort(function(a, b) { a.value.toLowerCase().localeCompare(b.value.toLowerCase()); }); //use this to sort as strings
        return arr; // returns array
    }

    function substractCity() {
        console.log('substractCity clicked');
        var cityVal;
        var cityText;
        var citiesSorted = [];

        $("#citiesAssigned option:selected").each(function () {
            //optionValue.push($( this ).val());
            cities.Val = $(this).val();
            //optionText.push($( this ).text());
            cities.Text = $(this).text();
        });

        console.log(cities);
        //cities = Object.keys(cities).sort(function(a,b){return cities[a].Val-cities[b].Val});
        //citiesSorted = sortObject(cities);
        //console.log(citiesSorted);

        for (var city in cities) {
            console.log(cities[city]);
            //console.log(citiesSorted[city].Val);
            if (city == "Val") {
                //cityVal = citiesSorted[city].value;
                cityVal = cities[city];
            } else if (city == "Text") {
                //cityText = citiesSorted[city].value;
                cityText = cities[city];
            }
            console.log(cityVal);
            if (cityVal !== undefined && cityText !== undefined) {
                $("#citiesAssigned option[value='" + cityVal + "']").remove();
                $("#cities").append('<option value="' + cityVal + '">' + cityText + '</option>');

            }
        }
        /*$("#cities option").sort(function(a, b) {
         console.log(a.id);
         console.log(b.id);
         return parseInt(a.id) - parseInt(b.id);
         }).each(function() {
         var elem = $(this);
         elem.remove();
         $(elem).appendTo("#cities");
         });*/
        var selectSorted = $('#cities');
        console.log(selectSorted);
        console.log(selectSorted.length);
        if (selectSorted.length > 0) {
            var $options = $('option', selectSorted);
            var arrVals = [];
            $options.each(function () {
                // push each option value and text into an array
                arrVals.push({
                    val: $(this).val(),
                    text: $(this).text()
                });
            });

            // sort the array by the value (change val to text to sort by text instead)
            arrVals.sort(function (a, b) {
                return a.val - b.val;
            });

            // loop through the sorted array and set the text/values to the options
            for (var i = 0, l = arrVals.length; i < l; i++) {
                $($options[i]).val(arrVals[i].val).text(arrVals[i].text);
            }
        }
    }

    function loadUsers() {
        $("#agencies").html("<div class='row'>"
            + "<div class='col-lg-12'>"
            + "<section class='panel'>"
            + "<table id='tablaAgencias' name='tablaAgencias' class='table responsive-data-table data-table'>"
            + "<thead>"
            + "<tr>"
            + "<th class='hidden'></th>"
            + "<th class='hidden-xs'><i class='fa fa-building'>&nbsp;</i>Agencia</th>"
            + "<th class='hidden-xs'><i class='fa fa-user'>&nbsp;</i>Nombre</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-envelope'>&nbsp;</i>Email</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-earphone'>&nbsp;</i>N&uacute;mero Telef&oacute;nico</th>"
            + "<th class='hidden-xs'><i class='glyphicon glyphicon-sort'>&nbsp;</i>Rol</th>"
            + "<th><i class='glyphicon glyphicon-cog'>&nbsp;</i> Acción</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody id='bodyDataAgencies' name='bodyDataAgencies'>"
            + "</tbody>"
            + "</table>"
            + '<div id="tablaLoader" name="tablaLoader">'
            + '<div class="loader"></div>'
            + '<br>'
            + '<p class="centrar"><strong>CARGANDO .....</strong></p>'
            + '</div>'
            + "</section>"
            + "</div>"
            + "</div>");

        $("#employees").html("<div class='row'>"
            + "<div class='col-lg-12'>"
            + "<section class='panel'>"
            + "<table id='tablaEmpleados' name='tablaEmpleados' class='table responsive-data-table data-table'>"
            + "<thead>"
            + "<tr>"
            + "<th class=''></th>"
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

        var rolUsuario = $("#inputRolUser").val();
        //alert(rolUsuario);
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/ObtenerAgencias.php",
            data: {},
            dataType: "JSON",
            success: function (data) {
                //alert(JSON.stringify(data));
                var i;
                var sizeAgenciasData = data.length;
                for (i = 0; i < sizeAgenciasData; i++) {
                    var id = data[i].id;
                    var email = data[i].email;
                    var lastName = data[i].lastName;
                    var name = data[i].name;
                    var nickname = data[i].nickname;
                    var phoneNumber = data[i].phoneNumber;
                    var rol = data[i].type;
                    var updated_at = data[i].updated_at;

                    var botonesAccion = "";
                    if (rolUsuario != 'Agency') {
                        botonesAccion = "<button type='button' class='btnEditUser btn btn-success btn-xs' onClick='edit(" + id + ");'><i class='fa fa-pencil'></i></button>" +
                            "<button type='button' class='btnDeleteUser btn btn-danger btn-xs'><i class='fa fa-trash-o '></i></button>";
                    } else {

                    }

                    if(rol == 'Instalacion'){
                        $("#bodyDataAgencies").append("<tr id=user" + id + ">" +
                            "<td class='hidden'>" + updated_at + "</td>" +
                            "<td class='hidden-xs'><a onclick='assingCity(" + id + ")' style='color: #69c2fe;'>" + nickname + "</a></td>" +
                            "<td>" + name + "&nbsp;" + lastName + "</td>" +
                            "<td>" + email + "</td>" +
                            "<td>" + phoneNumber + "</td>" +
                            "<td>" + rol + "</td>" +
                            "<td class='hidden-xs'>" +
                            botonesAccion +
                            "</td>" +
                            "</tr>");
                    }
                    else{
                        $("#bodyDataAgencies").append("<tr id=user" + id + ">" +
                            "<td class='hidden'>" + updated_at + "</td>" +
                            "<td class='hidden-xs'>" + nickname + "</td>" +
                            "<td>" + name + "&nbsp;" + lastName + "</td>" +
                            "<td>" + email + "</td>" +
                            "<td>" + phoneNumber + "</td>" +
                            "<td>" + rol + "</td>" +
                            "<td class='hidden-xs'>" +
                            botonesAccion +
                            "</td>" +
                            "</tr>");
                    }
                }

                $('#tablaLoader').html('');

                $("#tablaAgencias").DataTable().destroy();
                $("#tablaAgencias").DataTable({
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

    function saveUser(name, mail, pass, type, ph) {
        console.log(name);
        console.log(mail);
        console.log(pass);
        console.log(type);
        console.log(ph);
        console.log("Creating User");
        var password;
        password = makeid();
        //Ajax Request to add user to database
        $.ajax({
            method: "POST",
            url: "dataLayer/createAgencys.php",
            data: {
                name: name, email: mail, pass: password, tipo: type, ph: ph
            },
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                var xrow = '';
                xrow += '<tr>';
                xrow += '<td><a href="#">1</a></td>';
                xrow += '<td class="hidden-xs">' + company + '</td>';
                xrow += '<td>' + name + '</td>';
                xrow += '<td><span class="label label-success label-mini">Activo</span></td>';
                xrow += '<td class="hidden-xs"><button type="button" class="btnEditUser btn btn-success btn-xs"><i class="fa fa-pencil"></i></button> <button type="button" class="btnDeleteUser btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>';
                xrow += '</td>';
                xrow += '</tr>';
                console.log(xrow);

                $("#bodyData").append(xrow);
                $('#modalform').modal('hide');
                cleanFields();
            }
        });
    }

    $(document).ready(function () {

        var string_nickname = $("#nicknameZone").html();
        string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
        localStorage.setItem("id", string_nickname.trim());

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

    $(document).on("click", "#btnAssing", function () {
        var cities = "";
        var listCities = $("#citiesAssigned option");

        if(listCities.length == 0)
            return;

        $("#citiesAssigned option").each(function(i){
            cities += $(this).attr('value') + '|';
        });

        if(cities.length != 0)
            cities = cities.substr(0, cities.length -1);

        var user = $('#idAgency').val();
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/assignCities.php",
            data: {
                userAgency: user, cities: cities
            },
            dataType: "JSON",
            success: function (data) {
                console.log(data);
            }
        });

        cerrarModal();
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
        limpiarCamposFormularioModalAgregarEditarAgencia();
        //resetValidation();
        $('#modalform').modal('show');
        $('#titleCompany').html('Agregar Agencia');

        $('#btnAdd').html('Agregar');
        $('#btnAdd').attr("onclick", "JsAgencia.ingresarImagenAgencia()");

    });

    $('#btnAdd').click(function () {
        resetValidation();
        var xName = $('#txtName').val();
        var xMail = $('#txtMail').val();
        var xPass = $('#txtPass').val();
        var xType = $('#txtType').val();
        var xph = $('#txtph').val();
        createRow(xName, xMail, xPass, xType, xph);
    });

    /* $(document.body).on("click", ".btnEditUser", function () {
     rowPick = $(this).parent().parent();
     //resetValidation();
     limpiarCamposFormularioModalAgregarEditarAgencia();
     $('#modalform').modal('show');
     $('#titleCompany').html('Editar Usuario');
     $('#btnAdd').html('Guardar');
     });*/


    function cerrarModal() {
        $('#modalform').modal('hide');
        $('#modalAssingCity').modal('hide');
    }

    function edit(idUsuario) {
        //alert(idUsuario);
        //limpiarCamposFormularioModalAgregarEditarAgencia();
        JsAgencia.obtenerInformacionDeAgencia(idUsuario);

    }

    /*$('#btnCancel').click(function () {
     $('#modalform').modal('hide');
     });*/

    $(document.body).on("click", ".btnDeleteUser", function () {
        rowPick = $(this).parent().parent();
        $('#modalDelete').modal('show');
    });

    $('#btnCancelUser').click(function () {
        $('#modalDelete').modal('hide');
        rowPick.remove();
        rowPick = '';
    });


    $('#btnNotificationCancel').click(function () {
        $('#modalNotification').modal('hide');
        rowPick.remove();
        rowPick = '';
    });

    $('#btnModalFormClose').click(function () {
        cleanFields();
    });

    function createRow(name, mail, pass, type, ph) {
        if ($('#formNewUser').valid()) {
            console.log(name);
            console.log(mail);
            console.log(pass);
            console.log(type);
            console.log(ph);
            //createRow(xName, xMail,xPass,xType,xph)
            saveUser(name, mail, pass, type, ph);
            /*var xrow = ''
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
        $("#txtName").val('');
        $("#txtMail").val('');
    }

    function limpiarCamposFormularioModalAgregarEditarAgencia() {
        $('#imgAvatar').attr("src", 'assets/img/logoMexicana.png');
        $('#inAvatarImg').val('');
        $('#txtNickname').val('');
        $('#txtName').val('');
        $('#txtLastName').val('');
        $('#txtLastNameOp').val('');
        $('#txtEmail').val('');
        $('#txtPhoneNumber').val('');
        $('#txtPassword').val('');
        $('#txtTipoAgencia').val('1');
        $('#txtPhAgencia').val('');
        $('#txtUrlImagen').val('');
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
            method: "GET",
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
        //resetValidation();
        agenciesLoad();
        $('#modalNotification').modal('show');
        $('#titleCompany').html('Enviar Notificaci&oacute;n a una agencia');
        $('#btnAddNotificacion').html('Agregar');
        //cleanFields();
    });
</script>

<script type="text/javascript" src="assets/js/clases/agencia.js"></script>
