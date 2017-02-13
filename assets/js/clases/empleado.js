function Empleado() {
    this.insertarEmpleadoConImagen = InsertarEmpleadoConImagen;
    this.insertarImagenEmpleado = InsertarImagenEmpleado;

    this.editarImagenEmpleado = EditarImagenEmpleado;
    this.editarEmpleadoConImagen = EditarEmpleadoConImagen;

    this.desactivarEmpleado = DesactivarEmpleado;
}

var JsEmpleado = new Empleado();
var urlEmpleadosDeAgencia="empleadosDeAgencia.php";
configurarToastCentrado();

/*
 $('#txtRol').val('3');
 $('#txtProfile').val('3');
 $('#txtAdminsCompanyAlta').val();
 $('#txtNickname').val('AgenciaMarioBros2');
 $('#txtName').val('MarioBros');
 $('#txtLastName').val('Bros');
 $('#txtLastNameOp').val('Bros');
 $('#txtEmail').val('mario@x.com.x');
 $('#txtPhoneNumber').val('8114978896');*/


function InsertarEmpleadoConImagen(urlImagen) {
    //alert(urlImagen);
    var urlDeLaImagen = urlImagen;
    $('#txtUrlImagen').val(urlDeLaImagen)
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
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/InsertarUsuario.php",
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
                mensajeResponse = "Ocurrio un problema al insertar el Empleado";
                mostrarToast(2, "AVISO", mensajeResponse);
            } else {
                switch (data.CodigoRespuesta) {
                    case 1:
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
        complete: function (jqXHR, textStatus) {

        }
    });

}

function InsertarImagenEmpleado() {
    var inAvatarImg=$('#inAvatarImg').val();
    /****SI LA IMAGEN DEL AVATAR ESTA VACIA PONEMOS LA DE DEFAULT DE MEXICANA*/
    if(inAvatarImg==null || inAvatarImg=="") {
        var urlImagen="../../assets/img/logoMexicana.png"
        JsEmpleado.insertarEmpleadoConImagen(urlImagen);
    }else {
        $("#formAgregarNuevoEmpleadoImagen").submit(function (e) {
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
                            redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                            mostrarToast(1, "EXITO", data.MensajeRespuesta);
                            JsEmpleado.insertarEmpleadoConImagen(data.urlImagen);
                            break;
                        case 0:
                            redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsEmpleado.insertarEmpleadoConImagen(data.urlImagen);
                            break;
                        default :
                            redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsEmpleado.insertarEmpleadoConImagen(data.urlImagen);
                            break;
                    }
                }
            });
        });
    }
}

function EditarImagenEmpleado() {
    var inAvatarImg=$('#inAvatarImg').val();
    /****SI LA IMAGEN DEL AVATAR ESTA VACIA ENTONCES NO DEBEMOS HACER EL PROCESO DE ACTUALIZAR LA IMAGEN*/
    if(inAvatarImg==null || inAvatarImg=="") {
        var urlImagen=$("#txtUrlImagen").val();
        JsEmpleado.editarEmpleadoConImagen(urlImagen);
    }else{
        $("#formAgregarNuevoEmpleadoImagen").submit(function (e) {
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
                            redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                            mostrarToast(1, "EXITO", data.MensajeRespuesta);
                            JsEmpleado.editarEmpleadoConImagen(data.urlImagen);
                            break;
                        case 0:
                            redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsEmpleado.editarEmpleadoConImagen(data.urlImagen);
                            break;
                        default :
                            redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                            mostrarToast(2, "AVISO", data.MensajeRespuesta);
                            JsEmpleado.editarEmpleadoConImagen(data.urlImagen);
                            break;
                    }
                }
            });
        });
    }
}

function EditarEmpleadoConImagen(urlImagen) {
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
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/ActualizarEmpleado.php",
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
                mensajeResponse = "Ocurrio un problema al actualizar el Empleado";
                redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                mostrarToast(2, "AVISO", mensajeResponse);
            } else {
                switch (data.CodigoRespuesta) {
                    case 1:
                        redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                        mostrarToast(1, "EXITO", mensajeResponse);
                        break;
                    case 0:
                        redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
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

function DesactivarEmpleado(idEmpleado) {

    var postData = {
        idEmpleado: idEmpleado
    };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/DesactivarEmpleado.php",
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
                mensajeResponse = "Ocurrio un problema al desactivar el Empleado";
                mostrarToast(2, "AVISO", mensajeResponse);
            } else {
                switch (data.CodigoRespuesta) {
                    case 1:
                        redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
                        mostrarToast(1, "EXITO", mensajeResponse);
                        break;
                    case 0:
                        redireccionarAlTerminarToast(urlEmpleadosDeAgencia);
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




