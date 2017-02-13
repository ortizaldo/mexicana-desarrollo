function CargaVenta () {
    this.cargaAgenciasConRolVenta = CargaAgenciasConRolVenta;
    this.cargaEmpleadosDeAgenciaConRolVenta = CargaEmpleadosDeAgenciaConRolVenta;
    this.enviarValidacionSegundaVenta=EnviarValidacionSegundaVenta;
}
var JsCargaVenta = new CargaVenta();

function CargaAgenciasConRolVenta() {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/ObtenerAgenciasPorProfile.php",
        data: {profile:"Venta"},
        beforeSend: function (jqXHR, settings) {

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
        var sizeData=data.length;
            var i=0;
            for(i=0;i<sizeData;i++) {
                var idAgencia=data[i].id;
                var nicknameAgencia=data[i].nickname;

                $("#txtAgencyNuevaVenta").append(
                    '<option value="' + idAgencia + '">' + nicknameAgencia + '</option>'
                );

                $("#txtAgencyNuevaVenta").attr("onChange", "JsCargaVenta.cargaEmpleadosDeAgenciaConRolVenta()" );

            }

        },
        complete: function (jqXHR, textStatus) {

        }
    });
}

function CargaEmpleadosDeAgenciaConRolVenta() {
    $('#txtUsersNuevaVenta').empty().append('whatever');

    var idAgencia=$("#txtAgencyNuevaVenta option:selected").val();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "dataLayer/callsWeb/ObtenerEmpleadosDeLaAgenciaPorProfile.php",
        data: {profile:"Venta",idAgencia:idAgencia},
        beforeSend: function (jqXHR, settings) {

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            var sizeData=data.length;
            var i=0;
            for(i=0;i<sizeData;i++) {

                $("#txtUsersNuevaVenta").append(
                    '<option value="' + data[i].id + '">' + data[i].nickname + '</option>'
                );
            }

        },
        complete: function (jqXHR, textStatus) {

        }
    });
}

function EnviarValidacionSegundaVenta(){
        /**todo formulario de segunda venta**/
        var id = $('#txtConsecutive').val();
        var Consecutive = $('#txtConsecutive').val();
        var agency = $('#txtNextSellAgency').val();
        var NextSellPayment = $('#txtNextSellPayment').val();
        var Agreement = $('#txtAgreement').val();
        var RequestDate = $('txtRequestDate').val();
        var LastName1 = $('#txtLastName1').val();
        var LastName2 = $('#txtLastName2').val();
        var Name = $('#txtName').val();
        var RFC = $('#txtRFC').val();
        var CURP = $('#txtCURP').val();
        var Email = $('#txtEmail').val();
        var Engagment =$("#txtEngagment option:selected").text();
        var NextSellGender =$("#txtNextSellGender option:selected").text();
        var IdCard = $('#txtIdCard').val();
        var NextSellIdentification =$("#txtNextSellIdentification option:selected").text();
        var NextSellBirthdate = $('#NextSellBirthdate').val();
        var NextSellCountry = $('#txtNextSellCountry').val();
        var NextStepSaleState =$("#txtNextStepSaleState option:selected").text();
        var NextStepSaleCity =$("#txtNextStepSaleCity option:selected").text();
        var NextStepSaleColonia =$("#txtNextStepSaleColonia option:selected").text();
        var NextStepSaleStreet =$("#txtNextStepSaleStreet option:selected").text();
        var NextStepSaleInHome = $('#txtNextStepSaleInHome').val();
        var NextSellPhone = $('#NextSellPhone').val();
        var NextSellCellularPhone = $('#txtNextSellCellularPhone').val();
        var NextSellEnterprise = $('#txtNextSellEnterprise').val();
        var NextSellPosition = $('#txtNextSellPosition').val();
        var NextSellJobTelephone = $('#txtNextSellJobTelephone').val();
        var NextSellJobLocation = $('#txtNextSellJobLocation').val();
        var NextSellJobActivity = $('#txtNextSellJobActivity').val();
        var NextStepSaleAgreegmentType = $('#txtNextStepSaleAgreegmentType').val();
        var NextSellPrice = $('#txtNextSellPrice').val();
        var NextSellPaymentTime = $('#txtNextSellPaymentTime').val();
        var NextSellMonthlyCost = $('#txtNextSellMonthlyCost').val();
        var NextSellRI = $('#txtNextSellRI').val();
        var NextSellDateRI = $('#txtNextSellDateRI').val();

        var estaVacioAlgo = validarQueLaSegundaVentaNoEsteVacia();
        if (estaVacioAlgo==true) {}
        else {
            //alert("Todo es correcto");

            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/secondSell.php",
                data: {
                    idReport: id,
                    Consecutive: Consecutive,
                    Agency: agency,
                    NextSellPayment: NextSellPayment,
                    Agreement: Agreement,
                    LastName1: LastName1,
                    Name: Name,
                    CURP: CURP,
                    Engagment: Engagment,
                    IdCard: IdCard,
                    NextSellAgency: agency,
                    RequestDate: RequestDate,
                    LastName2: LastName2,
                    RFC: RFC,
                    Email: Email,
                    NextSellGender: NextSellGender,
                    NextSellIdentification: NextSellIdentification,
                    NextSellBirthdate: NextSellBirthdate,
                    NextStepSaleState: NextStepSaleState,
                    NextStepSaleColonia: NextStepSaleColonia,
                    NextStepSaleInHome: NextStepSaleInHome,
                    NextSellCellularPhone: NextSellCellularPhone,
                    NextSellEnterprise: NextSellEnterprise,
                    NextSellPosition: NextSellPosition,
                    NextSellJobTelephone: NextSellJobTelephone,
                    NextSellCountry: NextSellCountry,
                    NextStepSaleCity: NextStepSaleCity,
                    NextStepSaleStreet: NextStepSaleStreet,
                    NextSellPhone: NextSellPhone,
                    NextSellJobLocation: NextSellJobLocation,
                    NextSellJobActivity: NextSellJobActivity,
                    NextStepSaleAgreegmentType: NextStepSaleAgreegmentType,
                    NextSellPrice: NextSellPrice,
                    NextSellPaymentTime: NextSellPaymentTime,
                    NextSellMonthlyCost: NextSellMonthlyCost,
                    NextSellRI: NextSellRI,
                    NextSellDateRI: NextSellDateRI
                },
                dataType: "JSON",
                beforeSend: function() {


                },
                success: function (data) {
                    alert(data);
                    var code=data.code;

                    if(code=="200") {
                        console.log(data);
                        $('#secondSellModal').modal('hide');
                        MostrarToast(1, "Segunda venta almacenada", "La creación de la segunda venta se realizó con éxito");
                        //loadMain();
                    }else{
                        MostrarToast(2, "Error", "Ocurrio un problema al momento de crear la segunda venta");
                    }
                }, error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                    MostrarToast(2, "Error", "Ocurrio un problema al momento de crear la segunda venta");
                }
            });
        }
}


