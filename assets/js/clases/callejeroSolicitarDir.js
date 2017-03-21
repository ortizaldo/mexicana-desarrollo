/************************************Load Cities, colonias, streets****************************************/
var citiesTemp;
function loadCities() {
    $.ajax({
        method: "GET",
        url: "dataLayer/callsWeb/siscomMunicipios.php",
        dataType: "JSON",
        success: function (data) {
            console.log('data', data);
            var ot_municipios = _.has(data, 'ot_municipios');
            if (ot_municipios) {
                var ot_municipiosRow = _.has(data.ot_municipios, 'ot_municipiosRow');
                if (ot_municipiosRow) {
                    $("#txMun").html("");
                    var options="";
                    options = '<option value=""></option>';
                    _.each(data.ot_municipios.ot_municipiosRow, function (row, idx) {
                        options += '<option value="'+row.idMunicipio+'">'+row.nombre+'</option>';
                    });
                    $('#txMun').html('');
                    $('#txMun').select2({
                        placeholder: "Seleccionar un Municipio",
                        allowClear: true,
                    });
                    $("#txMun").append(options);
                    sortSelectOptions('#txMun', true);
                }
            }else{
                console.log('no entrao en el primer if');
            }
            //loadColonias(city);
        }, error: function (xhr, ajaxOptions, thrownError) {
            console.log('xhr.status', xhr.status);
            console.log('thrownError', thrownError);
        }
    });
}

function loadColonias(city) {
    if (!_.isEmpty(city)) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/siscomColonias.php",
            data: {city: city},
            dataType: "JSON",
            success: function (data) {
                var ot_colonias = _.has(data, "ot_colonias");
                if (ot_colonias) {
                    var ot_coloniasRow = _.has(data.ot_colonias, "ot_coloniasRow");
                    if (ot_coloniasRow) {
                        $("#txtCol").html("");
                        var options="";
                        options = '<option value=""></option>';
                        _.each(data.ot_colonias.ot_coloniasRow, function (row, idx) {
                            options += '<option value="'+row.idcolonia+'">'+row.nombre+'</option>';
                        });
                        $('#txtCol').html('');
                        $('#txtCol').select2({
                            placeholder: "Seleccionar una Colonia",
                            allowClear: true,
                        });
                        $("#txtCol").append(options);
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

function getIDireccion(street, direccionesarray) {
    console.log('direccionesarray', direccionesarray);
    var idDirArray = [];
    var idDDirArray = {};
    for (elem in direccionesarray) {
        if (direccionesarray[elem].calle == street) {
            idDDirArray = ({'id_direccion': direccionesarray[elem].id_direccion, 'numero_exterior': direccionesarray[elem].numero_exterior});
            idDirArray.push(idDDirArray);
        }

    }

    return idDirArray;
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
    if (!_.isEmpty(city) && !_.isEmpty(colonia)) {
        var tipoDir = "todas";
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/siscomCalles.php",
            data: {city: city, colonia: colonia, tipoDir: tipoDir},
            dataType: "JSON",
            success: function (data) {
                var ot_direcciones = _.has(data, 'ot_direcciones');
                if (ot_direcciones) {
                    var ot_direccionesRow = _.has(data.ot_direcciones, 'ot_direccionesRow');
                    if (ot_direccionesRow) {
                        if (!_.isUndefined(data.ot_direcciones.ot_direccionesRow.length)) {
                            directions = data.ot_direcciones.ot_direccionesRow;
                            localStorage.setItem("direcciones", directions);
                            var streets = [];
                            _.each(data.ot_direcciones.ot_direccionesRow, function (rStreet, idx) {
                                streets.push(rStreet.calle);
                            });
                            streets = deleteDuplicates(streets);
                            var options = '<option value=""></option>';
                            _.each(streets, function (calle, idx) {
                                options += '<option value="'+calle+'">'+calle+'</option>';
                            });
                            $('#street').html('');
                            $('#street').select2({
                                placeholder: "Seleccionar una Calle",
                                allowClear: true,
                            });
                            $("#street").append(options);
                        }else{
                            //calle: "PECES"entre_calles: ""id_direccion: "254027"numero_exterior: "304-4"
                            var calle = data.ot_direcciones.ot_direccionesRow.calle;
                            var entre_calles = data.ot_direcciones.ot_direccionesRow.entre_calles;
                            var numero_exterior = data.ot_direcciones.ot_direccionesRow.numero_exterior;
                            directions = data.ot_direcciones.ot_direccionesRow;
                            $('#street').html('');
                            $('#street').select2({
                                placeholder: "Seleccionar una Calle",
                                allowClear: true,
                            });
                            $('#street').append("<option value=''></option>");
                            $('#street').append('<option>' + directions.calle + '</option>');
                        }
                    }else{
                        configurarToastCentrado();
                        MostrarToast(2, "Conflicto en Direcciones", "No se encontraron direcciones para la colonia seleccionada");
                    }
                }
                sortSelectOptions('#street', true);
            }
        });
    }
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