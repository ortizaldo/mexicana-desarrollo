function FormFunciones () {
    this.cargarEstatusPorTipo = CargarEstatusPorTipo;
}
var JsFormFunciones = new FormFunciones();

function CargarEstatusPorTipo(idTipo) {
    $('#txtStatus').empty().append('whatever');
    //alert(idTipo);
    $.ajax({
        method: "GET",
        url: "dataLayer/callsWeb/getReportStatus.php?idTipo="+idTipo,
        dataType: "JSON",
        success: function (data) {
            $('#txtStatus').append('<option value="">Todos los estatus</option>');
            for (elem in data) {
                $('#txtStatus').append('<option value="' + data[elem].ID + '">' + data[elem].description + '</option>');
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('request failed');
            console.log(textStatus);
        }
    });
}



