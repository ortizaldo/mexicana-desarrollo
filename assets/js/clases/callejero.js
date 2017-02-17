/************************************Load Cities, colonias, streets****************************************/
var citiesTemp;
function loadCities() {
    $.ajax({
        method: "GET",
        url: "dataLayer/callsWeb/siscomMunicipios.php",
        dataType: "JSON",
        success: function (data) {
            console.log('data', data);
            $('#txMun').html('');

            citiesTemp = data.ot_municipios;
            citiesTemp = citiesTemp.ot_municipiosRow;

            $('#txMun').append("<option value=''>SELECCIONAR</option>");

            for (var elem in citiesTemp) {
                if (citiesTemp[elem].nombre !== null) {
                    $('#txMun').append('<option value="' + citiesTemp[elem].idMunicipio + '">' + citiesTemp[elem].nombre + '</option>');
                }
            }
            sortSelectOptions('#txMun', true);
            var city = $('#txMun').val();
            //alert(city);
            loadColonias(city);
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function loadColonias(city) {
    console.log('_.isEmpty(city)', _.isEmpty(city));
    if (!_.isEmpty(city)) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/siscomColonias.php",
            data: {city: city},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                var tempData = [];
                tempData = data.ot_colonias;
                tempData = tempData.ot_coloniasRow;
                console.log('has col', _.has(data, "ot_colonias"));
                for (city in tempData) {
                    if (tempData[city].nombre !== null) {
                        $('#txtCol').append('<option value="' + tempData[city].idcolonia + '">' + tempData[city].nombre + '</option>');
                    }
                }
                sortSelectOptions('#txtCol', true);
            }
        });
    }else{
        $('#txtCol').html('');
        $('#txtCol').append("<option value=''>SELECCIONAR</option>");
    }
}
function getNumeros(street, direccionesarray) {
    numerosArray = [];
    for (elem in direccionesarray) {
        if (direccionesarray[elem].calle == street) {
            numerosArray.push(direccionesarray[elem].numero_exterior);
        }

    }

    return numerosArray;
}

function getEntreCalles(street, direccionesArray) {
    betweenStreetsArray = [];
    for (elem in direccionesArray) {
        if (direccionesArray[elem].calle == street) {
            betweenStreetsArray.push(direccionesArray[elem].entre_calles);
        }

    }

    return betweenStreetsArray;
}
var directions = [];
function loadStreets(city, colonia) {
    $.ajax({
        method: "POST",
        url: "dataLayer/callsWeb/siscomCalles.php",
        data: {city: city, colonia: colonia},
        dataType: "JSON",
        success: function (data) {
            //console.log(data);

            directions = data.ot_direcciones.ot_direccionesRow;
            if (directions === undefined) {
                configurarToastCentrado();
                MostrarToast(2, "Conflicto en Direcciones", "No se encontraron direcciones para la colonia seleccionada");
            }
            if (typeof(directions.length) !== 'undefined') {
                var streets = [];

                for (var elem in directions) {
                    streets.push(directions[elem].calle);

                }

                streets = deleteDuplicates(streets);

                $('#txtStreet').html('');

                  $('#txtStreet').append("<option value=''>SELECCIONAR</option>");

                for (var street in streets) {
                    if (streets[street] !== null && streets[street] !== "") {
                        $('#txtStreet').append('<option value="' + streets[street] + '">' + streets[street] + '</option>');
                    }
                }
            }else{
                $('#txtStreet').html('');
                $('#txtStreet').append("<option value=''>SELECCIONAR</option>");
                $('#txtStreet').append('<option value="' + directions.calle + '">' + directions.calle + '</option>');
            }
            sortSelectOptions('#txtStreet', true);
        }
    });
}

function sortSelectOptions(selector, skip_first) {
    var options = (skip_first) ? $(selector + ' option:not(:first)') : $(selector + ' option');
    var arr = options.map(function(_, o) { return { t: $(o).text(), v: o.value, s: $(o).prop('selected') }; }).get();
    arr.sort(function(o1, o2) {
      var t1 = o1.t.toLowerCase(), t2 = o2.t.toLowerCase();
      return t1 > t2 ? 1 : t1 < t2 ? -1 : 0;
    }); 
    options.each(function(i, o) {
        o.value = arr[i].v;
        $(o).text(arr[i].t);
        if (arr[i].s) {
            $(o).attr('selected', 'selected').prop('selected', true);
        } else {
            $(o).removeAttr('selected');
            $(o).prop('selected', false);
        }
    }); 
}
/************************************Load Cities, colonias, streets****************************************/