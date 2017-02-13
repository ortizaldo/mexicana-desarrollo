function SegundaVenta() {
    this.insertarSegundaVenta = InsertarSegundaVenta;
    this.obtenerCatalogoTiposContrato = ObtenerTiposContrato;

}
var JsSegundaVenta = new SegundaVenta();

function InsertarSegundaVenta(idUser) {
    /***CHECKLIST***/
    txtConsecutive = $("#txtConsecutive").val();
    txtNextSellPayment = $("#txtNextSellPayment").val();
    txtAgreement = $("#txtAgreement").val();
    txtNextSellAgency = $("#txtNextSellAgency").val();
    txtRequestDate = $("#txtRequestDate").val();

    /***DATOS DEL TITULAR***/
    txtLastName1 = $("#txtLastName1").val();
    txtName = $("#txtName").val();
    txtCURP = $("#txtCURP").val();
    txtEngagment = $("#txtEngagment").val();
    txtIdCard = $("#txtIdCard").val();
    txtLastName2 = $("#txtLastName2").val();
    txtRFC = $("#txtRFC").val();
    txtEmail = $("#txtEmail").val();
    txtNextSellGender = $("#txtNextSellGender").val();
    txtNextSellIdentification = $("#txtNextSellIdentification").val();
    txtNextSellBirthdate = $("#txtNextSellBirthdate").val();

    /***DOMICILIO**/
    txtNextStepSaleState = $("#txtNextStepSaleState").val();
    txtNextStepSaleColonia = $("#txtNextStepSaleColonia").val();
    txtNextStepSaleInHome = $("#txtNextStepSaleInHome").val();
    txtNextSellCellularPhone = $("#txtNextSellCellularPhone").val();
    txtNextSellCountry = $("#txtNextSellCountry").val();
    txtNextStepSaleCity = $("#txtNextStepSaleCity").val();
    txtNextStepSaleStreet = $("#txtNextStepSaleStreet").val();
    txtNextSellPhone = $("#txtNextSellPhone").val();

    /**EMPLEO***/
    txtNextSellEnterprise = $("#txtNextSellEnterprise").val();
    txtNextSellPosition = $("#txtNextSellPosition").val();
    txtNextSellJobTelephone = $("#txtNextSellJobTelephone").val();
    txtNextSellJobLocation = $("#txtNextSellJobLocation").val();
    txtNextSellJobActivity = $("#txtNextSellJobActivity").val();
    txtNextSellCellularPhone = $("#txtNextSellCellularPhone").val();
    txtNextSellCellularPhone = $("#txtNextSellCellularPhone").val();

    /****FINANCIAMIENTO**/
    txtNextStepSaleAgreegmentType = $("#txtNextStepSaleAgreegmentType").val();
    txtNextSellPaymentTime = $("#txtNextSellPaymentTime").val();
    txtNextSellRI = $("#txtNextSellRI").val();
    txtNextStepSaleAgreegmentType = $("#txtNextStepSaleAgreegmentType").val();
    txtNextSellPrice = $("#txtNextSellPrice").val();
    txtNextSellMonthlyCost = $("#txtNextSellMonthlyCost").val();
    txtNextSellDateRI = $("#txtNextSellDateRI").val();

    /****REFERENCIAS ESTAS SON DINAMICAS E INCREMENTAN CONFORME LOS VALORES**/
    txtNextSellReference1 = $("#txtNextSellReference1").val();
    txtNextSellReference1Telephone = $("#txtNextSellReference1Telephone").val();
    txtNextSellReferenceTelephone = $("#txtNextSellReferenceTelephone").val();
    txtNextSellReferenceTelephoneExt = $("#txtNextSellReferenceTelephoneExt").val();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/Notificaciones.php",
        data: {
            idUser: idUser,
            action: JsNotificaciones.actionLeerNotificacionesDelUsuario
        },
        beforeSend: function (jqXHR, settings) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            //alert(JSON.stringify(data))
            switch (data.CodigoRespuesta) {
                case '1':
                    //alert(data.MensajeRespuesta);
                    var i = 0;
                    var listaNotificaciones = "";
                    //alert(JSON.stringify(data.notifications));
                    var sizeNotifications = data.notifications.length;
                    /**PRIMERO CREAMOS LA VISTA DINAMICA PARA DESPUES HACER EL LLENADO DE LAS NOTIFICACIONES**/
                    $('#spanCampanaNotificaciones').html('');
                    if (sizeNotifications > 0) {
                        $('#spanCampanaNotificaciones').html(sizeNotifications);
                        $('#h5MensajeNuevasNotificaciones').html('Tienes ' + sizeNotifications + ' nuevas notificaciones');
                        $('#divDropdownMenuNotificaciones').html('' + '<div class="title-row">'
                            + '<h5 id="h5MensajeNuevasNotificaciones" name="h5MensajeNuevasNotificaciones"'
                            + 'class="title green">'
                            + 'Tienes ' + sizeNotifications + ' nuevas notificaciones'
                            + '</h5>'
                            + '<a href="javascript:;" onclick="JsNotificaciones.actualizarTodasLAsNotificaciones(' + idUser + ')" class="btn-success btn-view-all">&nbsp;<i class="fa fa-check-square">&nbsp;Marcar todo visto &nbsp;</i></a>'
                            + '</div>'
                            + '<div class="notification-list-scroll sidebar">'
                            + '<div id="divListaNotificaciones"  name="divListaNotificaciones" class="notification-list mail-list not-list">'
                            + '</div>'
                            + '</div>'
                            + '');

                        /**DESPUES HACEMOS EL RECORRIDO DE LA LISTA DE NOTIFICACIONES PARA DESPLEGARLA EN LA LISTA***/
                        for (i = 0; i < sizeNotifications; i++) {
                            var idNotification = data.notifications[i].idNotification;
                            var message = data.notifications[i].message;
                            var fechaNotificacion = data.notifications[i].timeMod;
                            listaNotificaciones += '<a href="#" class="single-mail" '
                                + 'onclick="JsNotificaciones.actualizarNotificacion(' + idNotification + ',' + idUser + ');">'
                                + '<span class="icon bg-info">'
                                + '<i class="fa fa-envelope"></i>'
                                + '</span>' + message
                                + '<p><small>' + fechaNotificacion + '</small></p>'
                                + '</a>';
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
                    JsFunciones.mostrarToast(2, "Ocurrio un problema", data.Mensaje);
                    break;
            }
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}

function ObtenerTiposContrato() {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/ObtenerCatalogoTiposDeContrato.php",
        data: {},
        beforeSend: function (jqXHR, settings) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            //alert(JSON.stringify(data))
            switch (data.CodigoRespuesta) {
                case '1':
                    var i = 0;
                    var sizeData = data.length;
                    for (i = 0; i < sizeData; i++) {
                        $("#txtNextStepSaleAgreegmentType").append("<option value=" + data[i].idCatalogoTiposDeContrato + ">" + data[i].tipoDeContrato + "</option>");
                    }
                    $("#txtNextStepSaleAgreegmentType").val($("#target option:first").val());
                    $("#txtNextSellPaymentTime").val(data[0].plazo);
                    $("#txtNextSellPrice").val(data[0].precio);
                    $("#txtNextSellMonthlyCost").val(data[0].pagos);
                    break;
                case '0':

                    break;
            }
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}
