function Notificaciones() {
    this.leerNotificacionesPorIdUsuario = LeerNotificacionesPorIdUsuario;
    this.actualizarNotificacion = ActualizarNotificacion;
    this.actualizarTodasLAsNotificaciones=ActualizarTodasLasNotificaciones;
    this.insertarNotificacion = InsertarNotificacion;

    this.actionLeerNotificacionesDelUsuario = "1";
    this.actionActualizarTodasLasNotificaciones = "2";

}
var JsNotificaciones = new Notificaciones();

function LeerNotificacionesPorIdUsuario(idUser) {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/Notificaciones.php",
        data: {
            idUser: idUser,
            action: JsNotificaciones.actionLeerNotificacionesDelUsuario
        },
        success: function (data, textStatus, jqXHR) {
            switch (data.CodigoRespuesta) {
                case '1':
                    var i = 0;
                    var numNotif=0;
                    var listaNotificaciones = "";
                    var sizeNotifications = data.notifications.length;
                    $('#spanCampanaNotificaciones').html('');
                    if (sizeNotifications > 0) {

                        _.each(data.notifications, function(notif, idx) {
                            console.log('notificacion', notif);
                            if (parseInt(notif.status) === 1) {
                                //contamos
                                numNotif++;
                            }
                            var idNotification = notif.idNotification;
                            var message = notif.message;
                            var fechaNotificacion = notif.timeMod;
                            listaNotificaciones += '<a href="#" class="single-mail" '
                                + 'onclick="JsNotificaciones.actualizarNotificacion(' + idNotification + ',' + idUser + ');">'
                                + '<span class="icon bg-info">'
                                + '<i class="fa fa-envelope"></i>'
                                + '</span>' + message
                                + '<p><small>' + fechaNotificacion + '</small></p>'
                                + '</a>';
                        });
                        if (parseInt(numNotif) > 0) {
                            $('#spanCampanaNotificaciones').html(numNotif);
                            $('#h5MensajeNuevasNotificaciones').html('Tienes ' + numNotif + ' nuevas notificaciones');
                            $('#divDropdownMenuNotificaciones').html('' + '<div class="title-row">'
                                + '<h5 id="h5MensajeNuevasNotificaciones" name="h5MensajeNuevasNotificaciones"'
                                + 'class="title green">'
                                + 'Tienes ' + numNotif + ' nuevas notificaciones'
                                + '</h5>'
                                + '<a href="javascript:;" onclick="JsNotificaciones.actualizarTodasLAsNotificaciones('+idUser+')" class="btn-success btn-view-all">&nbsp;<i class="fa fa-check-square">&nbsp;Marcar todo visto &nbsp;</i></a>'
                                + '</div>'
                                + '<div class="notification-list-scroll sidebar">'
                                + '<div id="divListaNotificaciones"  name="divListaNotificaciones" class="notification-list mail-list not-list">'
                                + '</div>'
                                + '</div>'
                                + '');
                        }else if (parseInt(numNotif) <= 0){
                            $('#spanCampanaNotificaciones').html('');
                            $('#h5MensajeNuevasNotificaciones').html('');
                            $('#divDropdownMenuNotificaciones').html('' + '<div class="title-row">'
                                + '<h5 id="h5MensajeNuevasNotificaciones" name="h5MensajeNuevasNotificaciones"'
                                + 'class="title green">'
                                + 'No tienes nuevas notificaciones en tu bandeja'
                                + '</h5>'
                                + '<div class="notification-list-scroll sidebar">'
                                + '<div id="divListaNotificaciones"  name="divListaNotificaciones" class="notification-list mail-list not-list">'
                                + '</div>'
                                + '</div>'
                                + '');
                        }
                        $('#divListaNotificaciones').html(listaNotificaciones);
                    } else {
                        $('#spanCampanaNotificaciones').html('');
                        $('#h5MensajeNuevasNotificaciones').html('');
                        $('#divDropdownMenuNotificaciones').html('' + '<div class="title-row">'
                            + '<h5 id="h5MensajeNuevasNotificaciones" name="h5MensajeNuevasNotificaciones"'
                            + 'class="title green">'
                            + 'No tienes nuevas notificaciones en tu bandeja'
                            + '</h5>'
                            + '</div>'
                            + '');
                    }
                    break;
                case '0':
                    JsFunciones.mostrarToast(2,"Ocurrio un problema",data.Mensaje);
                    break;
            }
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}

function ActualizarNotificacion(idNotification, idUser) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/Notificaciones.php",
        data: {idNotification: idNotification},
        beforeSend: function (jqXHR, settings) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            //alert(JSON.stringify(data))
            switch (data.CodigoRespuesta) {
                case 1:
                    //alert(data.MensajeRespuesta);
                    JsNotificaciones.leerNotificacionesPorIdUsuario(idUser);
                    break;
                case 0:
                    //alert(data.MensajeRespuesta);
                    break;
            }
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}

function ActualizarTodasLasNotificaciones(idUser) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/Notificaciones.php",
        data: {
            idUser: idUser,
            action: JsNotificaciones.actionActualizarTodasLasNotificaciones
        },
        beforeSend: function (jqXHR, settings) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            //alert(JSON.stringify(data))
            switch (data.CodigoRespuesta) {
                case 1:
                    //alert(data.MensajeRespuesta);
                    JsNotificaciones.leerNotificacionesPorIdUsuario(idUser);
                    break;
                case 0:
                    //alert(data.MensajeRespuesta);
                    break;
            }
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}

function InsertarNotificacion() {
    var idUser = '';
    var message = $('#txtnotificationText').val();
    var idEmployeeDesdeAdmins = $('#txtAdminsEmployeeNotify').val();
    var idAgencyDesdeAdmins = $('#txtAdminsCompanyNotify').val();

    var idAgencyDesdeAgency='';
    if ($('#txtAgenciesCompanyNotify')!=undefined)
        idAgencyDesdeAgency=$('#txtAgenciesCompanyNotify').val();
    else
        idAgencyDesdeAgency=$("#txtUsersNuevaVenta").val();

  //  var idAgencyDesdeAgency = $('#txtAgenciesCompanyNotify').val();
   // var idAgencyDesdeAgency=$("#txtUsersNuevaVenta").val();
    
    var idEmployeeDesdeForms = $("#txtUsers").val();
    var idAgencyDesdeForms = $("#txtAgency").val();

    //alert(idEmployeeDesdeAdmins + " " + idEmployeeDesdeForms+" "+idAgencyDesdeAdmins + " " + idAgencyDesdeAgency+" "+idAgencyDesdeForms);
    /**SI ID AGENCIA DESDE ADMINS ESTA VACIO, SIGNIFICA QUE EL VALOR VIENE DESDE AGENCY
     * SI NO ENTONCES SI ESTAMOS EN EL FORMULARIO DE AGENCIES Y TENEMOS
     * QUE TOMAR EL ID DE LA AGENCIA COMO IDUSER**/
    if (typeof idAgencyDesdeAgency === "undefined") {
        if (typeof idEmployeeDesdeAdmins === "undefined" ) {
	        idUser = idEmployeeDesdeForms;
        } else {
	        idUser = idEmployeeDesdeAdmins;
        }
    } else {
        idUser = idAgencyDesdeAgency;
    }

    //alert(idUser);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "v1/api/InsertarNotificacion.php",
        data: {
            message: message,
            idUser: idUser
        },
        beforeSend: function (jqXHR, settings) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
           // alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            //alert(JSON.stringify(data))
            switch (data.CodigoRespuesta) {
                case 1:
                    $('#modalNotification').modal('hide');
                    $('#txtnotificationText').html('');

                    mostrarToast(1, "EXITO", data.MensajeRespuesta);
                    break;
                case 0:
                    $('#modalNotification').modal('hide');
                    $('#txtnotificationText').html('');
                    mostrarToast(2, "AVISO", data.MensajeRespuesta);
                    break;
            }
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}

function notificacionPlomeriaSecondSell(idUsuario, $msg) {
    console.log('idUsuario notificaciones', idUsuario);
    if (idUsuario !== '' && idUsuario !== null && typeof(idUsuario) !== 'undefined') {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "v1/api/InsertarNotificacion.php",
            data: {
                message: $msg,
                idUser: idUsuario
            },
            beforeSend: function (jqXHR, settings) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('errorThrown', errorThrown);
            },
            success: function (data, textStatus, jqXHR) {
                console.log('data', data);
                switch (data.CodigoRespuesta) {
                    case 1:
                        $('#modalNotification').modal('hide');
                        $('#txtnotificationText').html('');

                        mostrarToast(1, "EXITO", data.MensajeRespuesta);
                        break;
                    case 0:
                        $('#modalNotification').modal('hide');
                        $('#txtnotificationText').html('');
                        mostrarToast(2, "AVISO", data.MensajeRespuesta);
                        break;
                }
            },
            complete: function (jqXHR, textStatus) {
            }
        });
    }
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
