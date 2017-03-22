/************************************Load Cities, colonias, streets****************************************/
var citiesTemp;
function Cities(idCiudad,calle, num, colonia) {
    ////console.log('idCiudad', idCiudad);
    var domHiddenCity = $('#domHiddenCity').val();
    var domHiddenStreet = $('#domHiddenStreet').val();
    var domHiddenColonia = $('#domHiddenColonia').val();
    var domHiddenStreetNumber = $('#domHiddenStreetNumber').val();

    console.log('domHiddenCity', domHiddenCity);
    console.log('domHiddenStreet', domHiddenStreet);
    console.log('domHiddenColonia', domHiddenColonia);
    console.log('domHiddenStreetNumber', domHiddenStreetNumber);
    $.ajax({
        method: "GET",
        url: "dataLayer/callsWeb/siscomMunicipios.php",
        dataType: "JSON",
        success: function (data) {
            ////console.log('data loadCities', data);
            $('#txtNextStepSaleCity').html('');

            citiesTemp = data.ot_municipios;
            citiesTemp = citiesTemp.ot_municipiosRow;
            $('#txtNextStepSaleCity').append("<option value=''>SELECCIONAR</option>");

            for (var elem in citiesTemp) {
                if (citiesTemp[elem].nombre !== null) {
                    $('#txtNextStepSaleCity').append('<option value="' + citiesTemp[elem].idMunicipio + '">' + citiesTemp[elem].nombre + '</option>');
                }
            }
            var city = $('#txtNextStepSaleCity').val();
            //cambiamos el valor de el value correspondiente al valor que actualmente trae
            if ((typeof(domHiddenCity) !== 'undefined' && domHiddenCity !== null && domHiddenCity !== '') && 
                (typeof(domHiddenStreet) !== 'undefined' && domHiddenStreet !== null && domHiddenStreet !== '') &&
                (typeof(domHiddenColonia) !== 'undefined' && domHiddenColonia !== null && domHiddenColonia !== '')) {
                $('select#txtNextStepSaleCity').find('option').each(function() {
                    if ($(this).text() === domHiddenCity) {
                        $('#txtNextStepSaleCity').val($(this).val()).change();
                    }
                });
            }
        }, error: function (xhr, ajaxOptions, thrownError) {
            //console.log(xhr.status);
            //console.log(thrownError);
        }
    });
}
function Colonias(city) {
    var domHiddenCity = $('#domHiddenCity').val();
    var domHiddenStreet = $('#domHiddenStreet').val();
    var domHiddenColonia = $('#domHiddenColonia').val();
    var domHiddenStreetNumber = $('#domHiddenStreetNumber').val();
    if (city !== "" && parseInt(city) > 0) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/siscomColonias.php",
            data: {
                city: city
            },
            dataType: "JSON",
            success: function (data) {
                console.log('data colonias', data);
                if (typeof(data) !== 'undefined' && data !== null && data !== '') {
                    var tempData = [];
                    tempData = data.ot_colonias;
                    tempData = tempData.ot_coloniasRow;
                    $('#txtNextStepSaleColonia').html('');
                    for (city in tempData) {
                        if (tempData[city].nombre !== null) {
                            $('#txtNextStepSaleColonia').append('<option value="' + tempData[city].idcolonia + '">' + tempData[city].nombre + '</option>');
                        }
                    }
                    if (typeof(domHiddenColonia) !== 'undefined' && domHiddenColonia !== null && domHiddenColonia !== '') {
                        $('select#txtNextStepSaleColonia').find('option').each(function() {
                            if ($(this).text() === domHiddenColonia) {
                                var idColonia=$(this).val();
                                $('#txtNextStepSaleColonia').val($(this).val()).change();                            
                            }
                        });
                    }
                    Streets();
                }
            }
        });
    }
}
function Streets() {
    console.log('Streets');
    var domHiddenCity = $('#domHiddenCity').val();
    var domHiddenStreet = $('#domHiddenStreet').val();
    var domHiddenColonia = $('#domHiddenColonia').val();
    var domHiddenStreetNumber = $('#domHiddenStreetNumber').val();
    var colonia = $("#txtNextStepSaleColonia").val();
    var city = $("#txtNextStepSaleCity").val();
    $.ajax({
        method: "POST",
        url: "dataLayer/callsWeb/siscomCalles.php",
        data: {city: city, colonia: colonia, tipoDir:"libres"},
        dataType: "JSON",
        success: function (data) {
            console.log('data calles', data);
            var sizeData = data.length;
            window.arrayCalle = []; //arrayCalle
            window.arrayNumCalle = []; //arrayNumCalle
            window.arrayIDireccion = []; //arrayNumCalle
            var streets=[], direcciones="";
            var ot_direcciones=_.has(data, 'ot_direcciones');
            $('#txtNextStepSaleStreet').html('');
            $('#txtNextStepSaleStreetNumber').html('');
            if (ot_direcciones) {
                var ot_direccionesRow=_.has(data.ot_direcciones, 'ot_direccionesRow');
                if (ot_direccionesRow) {
                    $('#txtNextStepSaleStreet').append('<option value="">Seleccionar</option>');
                    console.log('data.ot_direcciones.ot_direccionesRow', data.ot_direcciones.ot_direccionesRow.length);
                    if (!_.isUndefined(data.ot_direcciones.ot_direccionesRow.length)) {
                        _.each(data.ot_direcciones.ot_direccionesRow, function (row, idx) {
                            //$('#plazos').val(data[i].plazo);
                            streets.push(row.calle);
                            streets = deleteDuplicates(streets);
                            window.arrayCalle[idx]=row.calle;
                            window.arrayNumCalle[idx]=row;
                            window.arrayIDireccion[idx]=row.id_direccion;
                            //$('#txtNextStepSaleStreetNumber').append('<option value="' + row.id_direccion + '">' + row.numero_exterior + '</option>');
                        });
                    }else{
                        direcciones = data.ot_direcciones.ot_direccionesRow;
                        streets.push(direcciones.calle);
                        window.arrayCalle[0]=direcciones.calle;
                        window.arrayNumCalle[0]=direcciones;
                        window.arrayIDireccion[0]=direcciones.id_direccion;
                    }
                    _.each(streets, function (street, idx) {
                        $('#txtNextStepSaleStreet').append('<option value="' + street + '">' + street + '</option>');
                    });
                }
            }
            $('select#txtNextStepSaleStreet').find('option').each(function() {
                if ($(this).text() === domHiddenStreet) {
                    $('#txtNextStepSaleStreet').val($(this).val());
                    _.each(window.arrayNumCalle, function (row, idx) {
                        if (row.calle === $('#txtNextStepSaleStreet').val()) {
                            $('#txtNextStepSaleStreetNumber').append('<option value="' + row.id_direccion + '">' + row.numero_exterior + '</option>');
                        }
                    });
                }
            });
            if ((typeof(domHiddenStreetNumber) !== 'undefined' && domHiddenStreetNumber !== '' && domHiddenStreetNumber !== null)) {
                var num2 = domHiddenStreetNumber;
                var res_num= num2.split("-");
                var num3=0;
                var res_num2=0;
                $('select#txtNextStepSaleStreetNumber').find('option').each(function() {
                    num3 = $(this).text();
                    res_num2= num3.split("-");
                    if (res_num.length === res_num2.length) {
                        if (res_num.length === 1) {
                            if ((res_num2[0] == res_num[0])) {
                                $('#txtNextStepSaleStreetNumber').val($(this).val()).change();
                            }
                        }else if (res_num.length === 2) {
                            if ((res_num2[0] == res_num[0]) && (res_num2[1] == res_num[1])) {
                                $('#txtNextStepSaleStreetNumber').val($(this).val()).change();
                            }
                        }
                    }
                });
            }
        }
    });
}