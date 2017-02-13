function Usuario() {
    this.insertarUsuarioConImagen = InsertarUsuarioConImagen;
    this.insertarImagenUsuario = InsertarImagenUsuario;

    this.editarImagenUsuario = EditarImagenUsuario;
    this.editarUsuarioConImagen = EditarUsuarioConImagen;

    this.desactivarUsuario = DesactivarUsuario;
    this.eliminarUsuario = EliminarUsuaario;
}

var JsUsuario = new Usuario();
configurarToastCentrado();
function InsertarImagenUsuario() {
    var inAvatarImg=$('#inAvatarImg').val();
    /****SI LA IMAGEN DEL AVATAR ESTA VACIA PONEMOS LA DE DEFAULT DE MEXICANA*/
    if(inAvatarImg === null || inAvatarImg === "" || typeof(inAvatarImg) === "undefined") {
        //var urlImagen="../../assets/img/logoMexicana.png";
        var urlImagen = null;
        console.log('accedimos a insertarUsuarioConImagen');
        JsUsuario.insertarUsuarioConImagen(urlImagen);
    }else {
        $("#formAgregarNuevoUsuarioImagen").submit(function (e) {
            $.ajax({
                url: "dataLayer/callsWeb/InsertarImagenes.php",
                type: "POST",
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                    switch (data.CodigoRespuesta) {
                        case 1:
                            mostrarToast(1, "EXITO", data.MensajeRespuesta);
                            JsUsuario.insertarUsuarioConImagen(data.urlImagen);
                            break;
                        case 0:
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsUsuario.insertarUsuarioConImagen(data.urlImagen);
                            break;
                        default :
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsUsuario.insertarUsuarioConImagen(data.urlImagen);
                            break;
                    }
                }
            });
        });
    }
}

function InsertarUsuarioConImagen(urlImagen) {
    var urlDeLaImagen = urlImagen;
    $('#txtUrlImagen').val(urlDeLaImagen);
    var txtUrlImagen = $('#txtUrlImagen').val();
    var txtRol = $('#txtRol').val();
    var txtProfile = $('#txtProfile').val();
    var txtAdminsCompanyAlta = $('#txtAdminsCompanyAlta').val();
    var txtNickname = $('#txtNickname').val();
    var txtName = $('#txtName').val();
    var txtPassword = $('#txtPassword').val();
    var txtLastName = $('#txtLastName').val();
    var txtLastNameOp = $('#txtLastNameOp').val();
    var txtEmail = $('#txtEmail').val();
    var txtPhoneNumber = $('#txtPhoneNumber').val();
    var form=$("#formAgregarNuevoUsuarioImagen");
    var url=form[0].action;
    var dataForm=form.serialize();
    console.log('dataform',dataForm);
    var postData = {
        txtUrlImagen: txtUrlImagen,
        txtRol: txtRol,
        txtProfile: txtProfile,
        txtAdminsCompanyAlta: txtAdminsCompanyAlta,
        txtNickname: txtNickname,
        txtPassword: txtPassword,
        txtName: txtName,
        txtLastName: txtLastName,
        txtLastNameOp: txtLastNameOp,
        txtEmail: txtEmail,
        txtPhoneNumber: txtPhoneNumber
    };
    if(ValidarCampos(postData))
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "dataLayer/callsWeb/InsertarUsuario.php",
            data: dataForm,
            success: function (data, textStatus, jqXHR) {
                // alert(JSON.stringify(data))
                var mensajeResponse = data.MensajeRespuesta;
                if (mensajeResponse == null) {
                    mensajeResponse = "Ocurrio un problema al insertar el usuario";
                    mostrarToast(2, "AVISO", mensajeResponse);
                } else {
                    switch (data.CodigoRespuesta) {
                        case 1:
                            redireccionarAlTerminarToast(data.url);
                            mostrarToast(1, "EXITO", mensajeResponse);
                            break;
                        case 0:
                            mostrarToast(2, "AVISO", mensajeResponse);
                            break;
                        default:
                            break;
                    }

                }
                $('#modalform').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('errorThrown', errorThrown);
            },
        });
}

function EditarImagenUsuario() {
    var inAvatarImg=$('#inAvatarImg').val();
    /****SI LA IMAGEN DEL AVATAR ESTA VACIA ENTONCES NO DEBEMOS HACER EL PROCESO DE ACTUALIZAR LA IMAGEN*/
    if(inAvatarImg==null || inAvatarImg=="") {
        var urlImagen=$("#txtUrlImagen").val();
        JsUsuario.editarUsuarioConImagen(urlImagen);
    }else{
        $("#formAgregarNuevoUsuarioImagen").submit(function (e) {
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
                            mostrarToast(1, "EXITO", data.MensajeRespuesta);
                            JsUsuario.editarUsuarioConImagen(data.urlImagen);
                            break;
                        case 0:
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsUsuario.editarUsuarioConImagen(data.urlImagen);
                            break;
                        default :
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsUsuario.editarUsuarioConImagen(data.urlImagen);
                            break;
                    }
                }
            });
        });
    }
}

function EditarUsuarioConImagen(urlImagen) {
    //alert(urlImagen);
    var urlDeLaImagen = urlImagen;
    $('#txtUrlImagen').val(urlDeLaImagen)
    var txtId = $('#txtId').val();
    var txtUrlImagen = $('#txtUrlImagen').val();
    var txtRol = $('#txtRol').val();
    var txtProfile = $('#txtProfile').val();
    var txtAdminsCompanyAlta = $('#txtAdminsCompanyAlta').val();
    var txtNickname = $('#txtNickname').val();
    var txtName = $('#txtName').val();
    var txtPassword = $('#txtPassword').val();
    var txtLastName = $('#txtLastName').val();
    var txtLastNameOp = $('#txtLastNameOp').val();
    var txtEmail = $('#txtEmail').val();
    var txtPhoneNumber = $('#txtPhoneNumber').val();

    var postData = {
        txtId:txtId,
        txtUrlImagen: txtUrlImagen,
        txtRol: txtRol,
        txtProfile: txtProfile,
        txtAdminsCompanyAlta: txtAdminsCompanyAlta,
        txtNickname: txtNickname,
        txtPassword: txtPassword,
        txtName: txtName,
        txtLastName: txtLastName,
        txtLastNameOp: txtLastNameOp,
        txtEmail: txtEmail,
        txtPhoneNumber: txtPhoneNumber
    };
    //console.log(JSON.stringify(postData));
    if(ValidarCampos(postData))
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "dataLayer/callsWeb/ActualizarUsuario.php",
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
                    mensajeResponse = "Ocurrio un problema al actualizar el usuario";
                    redireccionarAlTerminarToast(data.url);
                    mostrarToast(2, "AVISO", mensajeResponse);
                } else {
                    switch (data.CodigoRespuesta) {
                        case 1:
                            redireccionarAlTerminarToast(data.url);
                            mostrarToast(1, "EXITO", mensajeResponse);
                            break;
                        case 0:
                            redireccionarAlTerminarToast(data.url);
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

function DesactivarUsuario(idUsuario) {

    var postData = {
        idUsuario: idUsuario
    };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/DesactivarUsuario.php",
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
                mensajeResponse = "Ocurrio un problema al desactivar el usuario";
                mostrarToast(2, "AVISO", mensajeResponse);
            } else {
                switch (data.CodigoRespuesta) {
                    case 1:
                        redireccionarAlTerminarToast(data.url);
                        mostrarToast(1, "EXITO", mensajeResponse);
                        break;
                    case 0:
                        redireccionarAlTerminarToast(data.url);
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

function EliminarUsuaario(idUsuario)
{
    var postData = {
        idUsuario: idUsuario
    };
    /*$.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/EliminarUsuario.php",
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
                mensajeResponse = "Ocurrio un problema al eliminar el usuario";
                mostrarToast(2, "AVISO", mensajeResponse);
            } else {
                switch (data.CodigoRespuesta) {
                    case "OK":
                        redireccionarAlTerminarToast(data.url);
                        mostrarToast(1, "EXITO", mensajeResponse);
                        break;
                    case "BAD":
                        redireccionarAlTerminarToast(data.url);
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
    });*/
    mostrarToast(2, "AVISO", "Esta caracteristica actualmente no se encuentra disponible, favor de comunicarse con el Administrador del Sistema.");
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


function ValidarCampos(postData){
    if(
        postData.txtRol == "" ||
        postData.txtProfile == "" ||
        postData.txtAdminsCompanyAlta == "" ||
        postData.txtNickname == "" ||
        postData.txtPassword == "" ||
        postData.txtName == "" ||
        postData.txtLastName == "" ||
        postData.txtLastNameOp == "" ||
        postData.txtEmail == "" ||
        postData.txtPhoneNumber == "" ){
        console.log(JSON.stringify(postData));
        mostrarToast(2, "Error", "Faltan campos por capturar");
        return false;
    }else {

        postData.txtEmail=postData.txtEmail.toLowerCase();
        $('#txtEmail').val(postData.txtEmail);

        if(postData.txtEmail.indexOf('@', 0) == -1 || postData.txtEmail.indexOf('.', 0) == -1) {
            mostrarToast(2, "Error", "Formato de Email Inv&aacute;lido");
            return false;
        }

        return true;
    }
}




       
