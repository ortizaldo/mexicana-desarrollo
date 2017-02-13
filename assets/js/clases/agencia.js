function Agencia() {

    this.obtenerInformacionDeAgencia = ObtenerInformacionDeAgencia;
    this.ingresarImagenAgencia = IngresarImagenAgencia;
    this.ingresarAgenciaConImagen = IngresarAgenciaConImagen;
    this.editarImagenAgencia = EditarImagenAgencia;
    this.editarAgenciaConImagen = EditarAgenciaConImagen;

    this.desactivarAgencia = DesactivarAgencia;
}

var JsAgencia = new Agencia();
var urlAgencies = "agencies.php";

function ObtenerInformacionDeAgencia(idUsuario) {

    var postData = {
        idUsuario: idUsuario
    };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/ObtenerInformacionDeAgencia.php",
        data: postData,
        beforeSend: function (jqXHR, settings) {

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('errorThrown', errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            //  alert(JSON.stringify(data))
            console.log('data agencies', data);
            var mensajeResponse = data.MensajeRespuesta;
            if (mensajeResponse == null) {
                mensajeResponse = "Ocurrio un problema al obtener la agencia";
                mostrarToast(2, "AVISO", mensajeResponse);
            } else {
                switch (data.CodigoRespuesta) {
                    case 1:
                        $('#imgAvatar').attr("src", data.photoUrl);
                        $('#inAvatarImg').val('');
                        $('#txtNickname').val(data.nickname);
                        $('#txtName').val(data.name);
                        $('#txtLastName').val(data.lastName);
                        $('#txtLastNameOp').val(data.lastNameOp);
                        $('#txtPassword').val(data.password);
                        $('#txtEmail').val(data.email);
                        $('#txtPhoneNumber').val(data.phoneNumber);

                        /**VALIDAMOS EL TIPO DE AGENCIA CON EL TEXTO, SI COINCIDE CON
                         * LOS TAGS ENTONCES PONEMOS SU VALUE CORRESPONDIENTE***/
                        var valueTipoAgencia = 1;
                        if (data.tipo == 'Instalacion') {
                            valueTipoAgencia = 1;
                        } else if (data.tipo == 'PH') {
                            valueTipoAgencia = 2;
                        } else if (data.tipo == 'Censo') {
                            valueTipoAgencia = 3;
                        } else if (data.tipo == 'Promotor') {
                            valueTipoAgencia = 4;
                        } else if (data.tipo == 'Financiera') {
                            valueTipoAgencia = 5;
                        } else if (data.tipo == 'CallCenter') {
                            valueTipoAgencia = 6;
                        } else {
                            valueTipoAgencia = data.tipo;
                        }
                        $('#txtTipoAgencia').val(valueTipoAgencia);
                        $('#txtPerfilAgencia').val(data.perfilAgencia);
                        $('#txtPhAgencia').val(data.numeroReferenciaPh);
                        $('#txtId').val(idUsuario);
                        $('#txtUrlImagen').val(data.photoUrl);

                        $('#modalform').modal('show');
                        $('#titleCompany').html('Editar Usuario');
                        $('#btnAdd').html('Actualizar');
                        $('#btnAdd').attr("onclick", "JsAgencia.editarImagenAgencia()");

                        break;
                    case 0:
                        mostrarToast(2, "AVISO", mensajeResponse);
                        break;
                    default:
                        break;
                }

            }
            $('#modalDelete').modal('hide');
        }
        ,
        complete: function (jqXHR, textStatus) {

        }
    });

}

function IngresarImagenAgencia() {
    var inAvatarImg = $('#inAvatarImg').val();
    /****SI LA IMAGEN DEL AVATAR ESTA VACIA ENTONCES NO DEBEMOS HACER EL PROCESO DE ACTUALIZAR LA IMAGEN*/
    if (inAvatarImg == null || inAvatarImg == "") {
        var urlImagen="../../assets/img/logoMexicana.png"
        JsAgencia.ingresarAgenciaConImagen(urlImagen);
    } else {
        $("#formAgregarNuevaAgenciaImagen").submit(function (e) {
            //alert('Entro al submit de las imagenes');
            /**VISTO EN
             *  https://www.formget.com/ajax-image-upload-php/***/
            $.ajax({
                url: "dataLayer/callsWeb/InsertarImagenes.php",
                type: "POST",
                dataType: "json",// Type of request to be send, called as method
                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData: false,        // To send DOMDocument or non processed data file it is set to false
                success: function (data)   // A function to be called if request succeeds
                {

                    //alert(data.MensajeRespuesta);
                    switch (data.CodigoRespuesta) {
                        case 1:
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(1, "EXITO", data.MensajeRespuesta);
                            JsAgencia.ingresarAgenciaConImagen(data.urlImagen);
                            break;
                        case 0:
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsAgencia.ingresarAgenciaConImagen(data.urlImagen);
                            break;
                        default :
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsAgencia.ingresarAgenciaConImagen(data.urlImagen);
                            break;
                    }
                }
            });
        });
    }
}

function IngresarAgenciaConImagen(urlImagen) {
    //alert(urlImagen);
    var urlDeLaImagen = urlImagen;
    $('#txtUrlImagen').val(urlDeLaImagen)
    var txtUrlImagen = $('#txtUrlImagen').val();

    var txtNickname = $('#txtNickname').val();
    var txtName = $('#txtName').val();
    var txtPassword = $('#txtPassword').val();
    var txtLastName = $('#txtLastName').val();
    var txtLastNameOp = $('#txtLastNameOp').val();
    var txtEmail = $('#txtEmail').val();
    var txtPhoneNumber = $('#txtPhoneNumber').val();
    var txtTipoAgencia = $("#txtTipoAgencia option:selected").text();
    var txtPerfilAgencia = $('#txtPerfilAgencia').val();
    var txtPhAgencia = $('#txtPhAgencia').val();


    var postData = {
        txtUrlImagen: txtUrlImagen,
        txtNickname: txtNickname,
        txtPassword: txtPassword,
        txtName: txtName,
        txtLastName: txtLastName,
        txtLastNameOp: txtLastNameOp,
        txtEmail: txtEmail,
        txtPhoneNumber: txtPhoneNumber,
        txtTipoAgencia: txtTipoAgencia,
        txtPerfilAgencia: txtPerfilAgencia,
        txtPhAgencia: txtPhAgencia
    };
    //console.log(JSON.stringify(postData));
    if(ValidarDatos(postData))
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "dataLayer/callsWeb/InsertarAgencia.php",
            data: postData,
            beforeSend: function (jqXHR, settings) {

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
            success: function (data, textStatus, jqXHR) {
                //alert(JSON.stringify(data))
                var mensajeResponse = data.MensajeRespuesta;
                if (mensajeResponse == null) {
                    mensajeResponse = "Ocurrio un problema al actualizar el Agencia";
                    redireccionarAlTerminarToast(urlAgencies);
                    mostrarToast(2, "AVISO", mensajeResponse);
                } else {
                    switch (data.CodigoRespuesta) {
                        case 1:
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(1, "EXITO", mensajeResponse);
                            break;
                        case 0:
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(2, "AVISO", mensajeResponse);
                            break;
                        default:
                            break;
                    }

                }
                $('#modalform').modal('hide');
            },
            complete: function (jqXHR, textStatus) {

            }
        });

}

function EditarImagenAgencia() {
    var inAvatarImg = $('#inAvatarImg').val();
    /****SI LA IMAGEN DEL AVATAR ESTA VACIA ENTONCES NO DEBEMOS HACER EL PROCESO DE ACTUALIZAR LA IMAGEN*/
    if (inAvatarImg == null || inAvatarImg == "") {
        var urlImagen = $("#txtUrlImagen").val();
        JsAgencia.editarAgenciaConImagen(urlImagen);
    } else {
        $("#formAgregarNuevaAgenciaImagen").submit(function (e) {
            //alert('Entro al submit de las imagenes');
            /**VISTO EN
             *  https://www.formget.com/ajax-image-upload-php/***/
            $.ajax({
                url: "dataLayer/callsWeb/InsertarImagenes.php",
                type: "POST",
                dataType: "json",// Type of request to be send, called as method
                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData: false,        // To send DOMDocument or non processed data file it is set to false
                success: function (data)   // A function to be called if request succeeds
                {

                    //alert(data.MensajeRespuesta);
                    switch (data.CodigoRespuesta) {
                        case 1:
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(1, "EXITO", data.MensajeRespuesta);
                            JsAgencia.editarAgenciaConImagen(data.urlImagen);
                            break;
                        case 0:
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsAgencia.editarAgenciaConImagen(data.urlImagen);
                            break;
                        default :
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsAgencia.editarAgenciaConImagen(data.urlImagen);
                            break;
                    }
                }
            });
        });
    }
}

function EditarAgenciaConImagen(urlImagen) {
    //alert(urlImagen);
    var urlDeLaImagen = urlImagen;
    $('#txtUrlImagen').val(urlDeLaImagen)
    var txtId = $('#txtId').val();
    var txtUrlImagen = $('#txtUrlImagen').val();

    var txtNickname = $('#txtNickname').val();
    var txtName = $('#txtName').val();
    var txtPassword = $('#txtPassword').val();
    var txtLastName = $('#txtLastName').val();
    var txtLastNameOp = $('#txtLastNameOp').val();
    var txtEmail = $('#txtEmail').val();
    var txtPhoneNumber = $('#txtPhoneNumber').val();
    var txtTipoAgencia = $("#txtTipoAgencia option:selected").text();
    var txtPerfilAgencia = $('#txtPerfilAgencia').val();
    var txtPhAgencia = $('#txtPhAgencia').val();


    var postData = {
        txtId: txtId,
        txtUrlImagen: txtUrlImagen,
        txtNickname: txtNickname,
        txtPassword: txtPassword,
        txtName: txtName,
        txtLastName: txtLastName,
        txtLastNameOp: txtLastNameOp,
        txtEmail: txtEmail,
        txtPhoneNumber: txtPhoneNumber,
        txtTipoAgencia: txtTipoAgencia,
        txtPerfilAgencia: txtPerfilAgencia,
        txtPhAgencia: txtPhAgencia
    };
    //console.log(JSON.stringify(postData));
    if(ValidarDatos(postData))
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "dataLayer/callsWeb/ActualizarAgencia.php",
            data: postData,
            beforeSend: function (jqXHR, settings) {

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
            success: function (data, textStatus, jqXHR) {
                 //alert(JSON.stringify(data))
                var mensajeResponse = data.MensajeRespuesta;
                if (mensajeResponse == null) {
                    mensajeResponse = "Ocurrio un problema al actualizar el Agencia";
                    redireccionarAlTerminarToast(urlAgencies);
                    mostrarToast(2, "AVISO", mensajeResponse);
                } else {
                    switch (data.CodigoRespuesta) {
                        case 1:
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(1, "EXITO", mensajeResponse);
                            break;
                        case 0:
                            redireccionarAlTerminarToast(urlAgencies);
                            mostrarToast(2, "AVISO", mensajeResponse);
                            break;
                        default:
                            break;
                    }

                }
                $('#modalform').modal('hide');
            },
            complete: function (jqXHR, textStatus) {

            }
        });

}

function DesactivarAgencia(idUsuario) {

    var postData = {
        idUsuario: idUsuario
    };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/DesactivarAgencia.php",
        data: postData,
        beforeSend: function (jqXHR, settings) {

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            // alert(JSON.stringify(data))
            var mensajeResponse = data.MensajeRespuesta;
            if (mensajeResponse == null) {
                mensajeResponse = "Ocurrio un problema al desactivar el Agencia";
                mostrarToast(2, "AVISO", mensajeResponse);
            } else {
                switch (data.CodigoRespuesta) {
                    case 1:
                        redireccionarAlTerminarToast(urlAgencies);
                        mostrarToast(1, "EXITO", mensajeResponse);
                        break;
                    case 0:
                        redireccionarAlTerminarToast(urlAgencies);
                        mostrarToast(2, "AVISO", mensajeResponse);
                        break;
                    default:
                        break;
                }

            }
            $('#modalDelete').modal('hide');
        },
        complete: function (jqXHR, textStatus) {

        }
    });

}

function mostrarToast(tipoToast, titulo, mensaje) {
    switch (tipoToast) {
        case 1:
            toastr.success(mensaje, titulo);
            break;
        case 2:
            toastr.warning(mensaje, titulo);
            break;
        default :
            toastr.success(mensaje, titulo);
            break;

    }
}

function configurarToastCentrado() {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "positionClass": "toast-top-full-width",
        "onclick": null,
        "showDuration": "5000",
        "hideDuration": "5000",
        "timeOut": "5000",
        "extendedTimeOut": "5000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": "false"
    }
}

function redireccionarAlTerminarToast(url) {
    toastr.options.onHidden = function () {
        console.log("onHide");
        window.location.href = url;
    };
}

function ValidarDatos(postData){
    if( postData.txtUrlImagen == "" ||
        postData.txtNickname == "" ||
        postData.txtPassword == "" ||
        postData.txtName == "" ||
        postData.txtLastName == "" ||
        postData.txtLastNameOp == "" ||
        postData.txtEmail == "" ||
        postData.txtPhoneNumber == "" ||
        postData.txtTipoAgencia == "" ||
        postData.txtPerfilAgencia == "" )
    {
        mostrarToast(2, "Error", "Faltan datos por capturar");
        return false;
    }
    else
        return true;
}