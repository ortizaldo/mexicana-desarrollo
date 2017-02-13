<?php include("header.php");
include_once 'dataLayer/DAO.php';
include_once 'dataLayer/libs/utils.php';
session_start();
?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="form-inline">
                    <label>Filtros</label>
                    <form id="consultaMapa" method="POST" action="">
                        <select class="form-control" id="txtCompany">
                            <option value="">Nombre de la agencia</option>
                        </select>
                        <select class="form-control" id="txtRole">
                            <option value="">Tipo de rol</option>
                        </select>
                        <select class="form-control" id="txtEmployee">
                            <option value="">Nombre del trabajador</option>
                        </select> &nbsp; <label class="text-capitalize">Fecha de:</label> &nbsp;
                        <input type='text' class="form-control" id="inputdate" name="dateReport"/>
                        <button type="button" id="btn_search" class="btn btn-success">
                            <span class="glyphicon glyphicon-calendar" style="color:#fff;"></span></button>
                       <!-- <a href="#" id="cleanAll">LIMPIAR TODO</a> -->
                        <button type="button" id="btn_download" class="btn btn-success">
                            <span class="glyphicon glyphicon-download-alt" style="color:#fff;"></span>
                        </button>
                    </form>
                </div>
            </header>
            <div class="panel-body">
                <div id="gmap_marker" class="gmaps"></div>
                <div id="gmap_trayectoria" class="gmaps" style='display:none'></div>
                <div id="gmap_vector" class="gmaps" style='display:none'></div>
            </div>
            <div id="modalVect" style="display: none">
                <div id="content">
                <ul id="listli" class="nav nav-tabs">
                    <li><a data-toggle="tab" href="#menu1">Vectores</a></li>
                    <li><a data-toggle="tab" href="#menu2">Trayectoria</a></li>
                </ul>
                <div class="tab-content ">
                    <div id="menu1" class="tab-pane fade in active">
                        <div class="row">
                            <div id="divUser" class="col-xs-3 col-md-6">
                                <h4>User</h4></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 col-md-6">Distancia Recorrida</div>
                            <div class="col-xs-3 col-md-3">Tiempos</div>
                        </div>
                        <div class="row">
                            <div id="divDistanceVector" class="col-xs-3 col-md-6"><b>0 KM</b></div>
                            <div id="divDateVector" class="col-xs-3 col-md-3"><b>0 min</b></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-md-4"><label>Reportes Asignados</label></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-md-3">Realizadas</div>
                            <div class="col-xs-6 col-md-3">En proceso</div>
                            <div class="col-xs-6 col-md-3">Rechazadas</div>
                            <div class="col-xs-6 col-md-3">Pendientes</div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-md-3 divReal"><b>0</b></div>
                            <div class="col-xs-6 col-md-3 divProces"><b>0</b></div>
                            <div class="col-xs-6 col-md-3 divRech"><b>0</b></div>
                            <div class="col-xs-6 col-md-3 divPendient"><b>0</b></div>
                        </div>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <div class="row">
                            <div id="divUserTrayectoria" class="col-xs-3 col-md-6">
                                <h4>User</h4></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 col-md-6">Distancia Recorrida</div>
                            <div class="col-xs-3 col-md-3">Tiempos</div>
                        </div>
                        <div class="row">
                            <div id="divDistanceTrayectoria" class="col-xs-3 col-md-6"><b>0 KM</b></div>
                            <div id="divDateTrayectoria" class="col-xs-3 col-md-3"><b>0 min</b></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-md-4"><label>Instalaciones</label></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-md-3">Realizadas</div>
                            <div class="col-xs-6 col-md-3">En proceso</div>
                            <div class="col-xs-6 col-md-3">Rechazadas</div>
                            <div class="col-xs-6 col-md-3">Pendientes</div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-md-3 divReal"><b>0</b></div>
                            <div class="col-xs-6 col-md-3 divProces"><b>0</b></div>
                            <div class="col-xs-6 col-md-3 divRech"><b>0</b></div>
                            <div class="col-xs-6 col-md-3 divPendient"><b>0</b></div>
                        </div>
                    </div>
                </div>
        </section>

    </div>
</div>

<?php include("footer.php") ?>

<link rel="stylesheet" href="assets/css/dcalendar.picker.min.css"/>
<script src="assets/js/dcalendar.picker.min.js"></script>
<!-- Abajo los js que incluimos -->
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0BQrlm-H4bGcdR6ad6t70roRtTEOvOOE&libraries=geometry,places">
</script>
<script src="assets/js/gmaps.js"></script>
<script src="assets/js/mapplace.js"></script>
<script src="assets/js/underscore.js"></script>
<script src="assets/js/clases/funciones.js"></script>
<!--<script src="assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>-->
</div>

<style>
    .gmaps {
        min-height: 500px !important;
    }

    #modalVect {
        position: absolute;
        background-color: white;
        bottom: 10px;
        left: 10px;
        z-index: 99;
        box-shadow: 10px 10px 5px grey;
    }
</style>

<script type="text/javascript">
    var user = "Usuario";
    var mapVector= new Maplace();
    var mapTrayectoria= new Maplace();
    var mapAgency= new Maplace();
    var GoogleMaps = function () {
        var mapMarker = function () {
            var map = new GMaps({
                div: '#gmap_marker',
                lat: -12.043333,
                lng: -77.028333
            });
            map.addMarker({
                lat: -12.043333,
                lng: -77.03,
                title: 'Lima',
                details: {
                    database_id: 42,
                    author: 'HPNeo'
                },
                click: function (e) {
                    if (console.log) console.log(e);
                    alert('You clicked in this marker');
                }
            });
            map.addMarker({
                lat: -12.042,
                lng: -77.028333,
                title: 'Marker with InfoWindow',
                infoWindow: {
                    content: contentString
                }
            });
            var x = new google.maps.LatLng(-12.043333, -77.028333);
            var stavanger = new google.maps.LatLng(-12.043333, -77.03);
            var amsterdam = new google.maps.LatLng(52.395715, 4.888916);
            var london = new google.maps.LatLng(51.508742, -0.120850);

            var myTrip = [stavanger, amsterdam, london];
            var flightPath = new google.maps.Polyline({
                path: myTrip,
                strokeColor: "#0000FF",
                strokeOpacity: 0.8,
                strokeWeight: 2
            });
            flightPath.setMap(map);
        }
        return {
            //main function to initiate map samples
            init: function () {
                //mapMarker();
                initMap();
                // loadCompanies()
            }
        };
    }();
    var map;
    var polylineTrack;
    var polylineVector;
    var markersArray = [];
    var markersArrayVectores = [];
    var locationArray = [];
    var path_track = [];
    var path_vector = [];
    var path_trackVector = [];
    var timesVector=[];
    var datosMarker = [];
    var times = [];
    var path_vector = [];
    //var points = []
    function initMap() {
        var myOptions = {
            zoom: 14,
            center: new google.maps.LatLng(25.6724702, -100.3398529),
            mapTypeId: google.maps.MapTypeId.TERRAIN
        }
        map = new google.maps.Map(document.getElementById("gmap_marker"), myOptions);
        // map.addListener('click', addLatLng);
    }
    function paintMapByPath(path) {
        polylineTrack = new google.maps.Polyline({
            path: path,
            strokeColor: "#00B5FF"
        });
        polylineTrack.setMap(map);
    }
    

    function loadRoute(employeeId) {
        var agencyId = $("#txtCompany").val();
        var roleId = $("#txtRole").val();
        var gmap_marker=$('#gmap_marker:visible').length;
        var gmap_trayectoria=$('#gmap_trayectoria:visible').length;
        var gmap_vector=$('#gmap_vector:visible').length;
        $('#modalVect').show();
        if (employeeId == null || employeeId == "") {
            employeeId = $("#txtEmployee").val();
        }
        var uniq=[], user;
        var date = moment($("#inputdate").val()).format('YYYY-MM-DD');
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/mapInfo.php",
            data: {
                employee: employeeId, date: date
            },
            dataType: "JSON",
            success: function (data) {
                times = [];
                locationArray=[];
                var iconStatus='assets/icons/start.png';
                _.each(data, function(entry, idx) {
                    user=entry.nickname;
                    if(idx === 0){
                        locationArray.push({lat: entry.latitude, 
                                            lon: entry.longitude,
                                            icon:iconStatus,
                                            //start:0,
                                            zoom:8, 
                                            Animation:"google.maps.Animation.BOUNCE"});
                    }else{
                        locationArray.push({lat: entry.latitude, 
                                            lon: entry.longitude, 
                                            zoom:8, 
                                            Animation:"google.maps.Animation.BOUNCE"});
                    }
                        
                    user = entry.nickname;
                    times.push(entry.created);
                    path_track.push(new google.maps.LatLng(entry.latitude, entry.longitude));
                })
            }
        });

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/mapInfoReport.php",
            data: {
                employee: employeeId, date: date
            },
            dataType: "JSON",
            success: function (data) {
                var st_pendientes = 0;
                var st_real = 0;
                var st_rech = 0;
                var st_process = 0;
                path_vector = [];
                var user;
                var iconStatus;
                var tipo;
                path_trackVector = [];
                timesVector = [];
                datosMarker = [];
                console.log('_.isUndefined(value)', _.isUndefined(data));
                console.log('_.isUndefined(value)', data.length);
                if (data.length > 0) {
                    _.each(data, function(entry, idx) {
                        if (parseInt(entry.idStatus) === 2 && parseInt(entry.rechazado) === 1) {
                            //rechazado
                            iconStatus='assets/img/rechazadoS.png';
                            st_rech++;
                        }else if(parseInt(entry.idStatus) === 2 && parseInt(entry.rechazado) !== 1){
                            //pendiente
                            iconStatus='assets/img/pendienteS.png';
                            st_pendientes++;
                        }else if (parseInt(entry.idStatus) === 4) {
                            //Proceso
                            iconStatus='assets/img/procesoS.png';
                            st_process++;
                        }else if(parseInt(entry.idStatus) === 3){
                            //Completo
                            iconStatus='assets/img/completo.png';
                            st_real++;
                        }
                        user=entry.nickname;
                        datosMarker.push({agreementNumberR:entry.agreementNumber,
                                          clientNameR:entry.clientName,
                                          coloniaR:entry.colonia,
                                          streetR:entry.street,
                                          betweenStreetsR:entry.betweenStreets,
                                          nseR:entry.nse,
                                          newStreetR:entry.newStreet,
                                          streetsR:entry.streets,
                                          coloniaTypeR:entry.coloniaType,
                                          marketTypeR:entry.marketType,
                                          innerNumberR:entry.innerNumber,
                                          outterNumberR:entry.outterNumber,
                                          streetR:entry.street,
                                          cpR:entry.cp,
                                          dot_latitudeR:entry.dot_latitude,
                                          dot_longitudeR:entry.dot_longitude,
                                          nicknameR:entry.nickname,
                                          idCitytR: entry.idCity,
                                          tipoReporteR:entry.tipoReporte
                                        });
                        //datos
                        path_vector.push({ lat: entry.dot_latitude, 
                                           lon: entry.dot_longitude,
                                           zoom:16,
                                           icon:iconStatus,
                                           animation:"google.maps.Animation.BOUNCE"});
                        timesVector.push(entry.created_at);
                        path_trackVector.push(new google.maps.LatLng(entry.dot_latitude, entry.dot_longitude));
                    });
                    paintMapByPoints_Vector(path_vector);
                    tipo='Vector';
                    totalTime(timesVector, tipo);
                    totalDistance(path_trackVector, tipo);
                    $("#divUser").html('<b>' + user + '</b>');
                    $("#divUserTrayectoria").html('<b>' + user + '</b>');
                    $(".divPendient").html('<b>' + st_pendientes + '</b>');
                    $(".divRech").html('<b>' + st_rech + '</b>');
                    $(".divReal").html('<b>' + st_real + '</b>');
                    $(".divProces").html('<b>' + st_process + '</b>');
                }else{
                    MostrarToast(2, "No hay coincidencias con su busqueda");
                    $('#btn_download').prop('disabled', false); 
                }
            }
        });
    }

    //load all employ from agency
    function loadEmploy_by_Agency() {
        var agencyId = $("#txtCompany").val();
        var roleId = $("#txtRole").val();
        var employeeId = $("#txtEmployee").val();
        var date = moment($("#inputdate").val()).format('YYYY-MM-DD');
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/reportsByAgency.php",
            data: {
                agency: agencyId, date: date
            },
            dataType: "JSON",
            success: function (data) {
                // console.log(date);
                console.log('data', data);
                var points = [];
                var listid = [];
                var image = 'assets/img/mapa.png';
                console.log('data', data);
                _.each(data, function(dato, index) {
                    points.push({ lat: dato.latitude, lon: dato.longitude, zoom:16, icon:image, animation:"google.maps.Animation.BOUNCE"});
                    listid.push({idEmp:dato.id, perfilEmp:dato.perfil, nicknameEmp:dato.nickname});
                });
                paintMapByPoints_(points, listid);
            }
        });
    }
    // Take way if get or not employee
    $('#btn_search').click(function () {
        var txtCompany=$('#txtCompany').val();
        var txtRole=$('#txtRole').val();
        var txtEmployee=$('#txtEmployee').val();
        var inputdate=$('#inputdate').val();
        if((typeof(txtCompany) !== 'undefined' && txtCompany !== null && txtCompany !== '') &&
           (typeof(inputdate) !== 'undefined' && inputdate !== null && inputdate !== '')){
            loadby();
        }else{
            MostrarToast(2, "Se encuentran campos vacios");
        }
    });
    function loadby() {
        var employeeId = $("#txtEmployee").val();
        if (parseInt(employeeId) !== 0 && employeeId !== null && typeof(employeeId) !== undefined) {
            console.log('parseInt(employeeId)', parseInt(employeeId));
            loadRoute();
        } else {
            loadEmploy_by_Agency();
        }
    }
    function paintMapByPoints_(points, listid) {
        var gmap_marker=$('#gmap_marker:visible').length;
        var gmap_trayectoria=$('#gmap_trayectoria:visible').length;
        var gmap_vector=$('#gmap_vector:visible').length;
        if (points.length <= 0) {
            listid=[];
            MostrarToast(2, "No se encuentran ubicaciones");
            $('#gmap_marker .wrap_controls').remove();
        }else{
            $('#modalVect').hide();
            $('#gmap_trayectoria').hide();
            $('#gmap_vector').hide();
            $('#gmap_marker').show();
            if(mapAgency.Loaded()){
                $('#gmap_marker .wrap_controls').remove();
                mapAgency.RemoveLocations(1);
                mapAgency.Load({
                    locations: points,
                    afterCreateMarker: function(index, location, marker) {
                        console.log('index', index);
                        var contentString="";
                            contentString += '<address>';
                            contentString += '<strong>Empleado: '+listid[index].nicknameEmp+' - '+listid[index].perfilEmp+'</strong><br>';
                            contentString += '</address>';
                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });
                        google.maps.event.addListener(marker, 'mouseover', function() {
                            infowindow.open(map, marker);
                        });
                        google.maps.event.addListener(marker, 'mouseout', function() {
                            infowindow.close();
                        });
                    },
                    afterShow: function(index, location, marker) {
                        loadRoute(listid[index].idEmp);
                        /*google.maps.event.addListenerOnce(marker, 'click', function() {
                            console.log('click2', listid[index].idEmp);
                            loadRoute(listid[index].idEmp);
                        });*/
                    },
                });
            }else{
                mapAgency.Load({
                    locations: points,
                    map_div: '#gmap_marker',
                    controls_title: 'Escoger una ubicacion:',
                    afterCreateMarker: function(index, location, marker) {
                        var contentString="";
                            contentString += '<address>';
                            contentString += '<strong>Empleado: '+listid[index].nicknameEmp+' - '+listid[index].perfilEmp+'</strong><br>';
                            contentString += '</address>';
                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });
                        google.maps.event.addListener(marker, 'mouseover', function() {
                            infowindow.open(map, marker);
                        });
                        google.maps.event.addListener(marker, 'mouseout', function() {
                            infowindow.close();
                        });
                    },
                    afterShow: function(index, location, marker) {
                        loadRoute(listid[index].idEmp);
                        /*google.maps.event.addListenerOnce(marker, 'click', function() {
                            console.log('click2', listid[index].idEmp);
                            loadRoute(listid[index].idEmp);
                        });*/
                    },
                });
            }
        }
    }

    function paintMapByPoints_Vector(points) {
        console.log('points', points);
        var contentString="";
        if (points.length > 0) {
            $('#gmap_trayectoria').hide();
            $('#gmap_vector').show();
            $('#gmap_marker').hide();
            console.log('mapVector', mapVector);
            if(mapVector.Loaded()){
                $('#gmap_vector .wrap_controls').remove();
                mapVector.RemoveLocations(1);
                mapVector.Load({
                    locations: points,
                    afterCreateMarker: function(index, location, marker) {
                        var contentString="";
                            contentString += '<address>';
                            contentString += '<strong>Cliente: '+datosMarker[index].clientNameR+' - '+datosMarker[index].tipoReporteR+'</strong><br>';
                            contentString += 'Colonia: '+datosMarker[index].coloniaR+' #'+datosMarker[index].outterNumberR+'<br>';
                            contentString += 'Calle: '+datosMarker[index].streetR+'<br>';
                            contentString += 'Ciudad, Provincia '+datosMarker[index].idCitytR+'<br>';
                            contentString += '</address>';
                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });
                        google.maps.event.addListener(marker, 'mouseover', function() {
                            infowindow.open(map, marker);
                        });
                        google.maps.event.addListener(marker, 'mouseout', function() {
                            infowindow.close();
                        });
                    },
                });
            }else{
                mapVector.Load({
                    locations: points,
                    map_div: '#gmap_vector',
                    ccontrols_div: '#controls-polyline',
                    controls_type: 'dropdown',
                    controls_on_map: true,
                    view_all_text: 'Start',
                    type: 'polyline',
                    afterCreateMarker: function(index, location, marker) {
                        var contentString="";
                            contentString += '<address>';
                            contentString += '<strong>Cliente: '+datosMarker[index].clientNameR+' - '+datosMarker[index].tipoReporteR+'</strong><br>';
                            contentString += 'Colonia: '+datosMarker[index].coloniaR+' #'+datosMarker[index].outterNumberR+'<br>';
                            contentString += 'Calle: '+datosMarker[index].streetR+'<br>';
                            contentString += 'Ciudad, Provincia '+datosMarker[index].idCitytR+'<br>';
                            contentString += '</address>';
                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });
                        google.maps.event.addListener(marker, 'mouseover', function() {
                            infowindow.open(map, marker);
                        });
                        google.maps.event.addListener(marker, 'mouseout', function() {
                            infowindow.close();
                        });
                    },
                });
            }
        }
    }

    function totalTime(points, tipo) {
        var totalmts = 0;
        if (points.length > 1) {
            for (i = 0; i < points.length - 1; i++) {
                totalmts = totalmts + timeDiffN(points[i], points[i + 1]); // text += cars[i] + "<br>";
            }
        }
        if (tipo === 'Trayectoria') {
            $("#divDateTrayectoria").html('<b>' + totalmts.toFixed(2) + " Horas" + '</b>');
        }else if (tipo === 'Vector'){
            $("#divDateVector").html('<b>' + totalmts.toFixed(2) + " Horas" + '</b>');
        }else if (tipo === 'Reporte'){
            var resultado=totalmts.toFixed(2);
            return resultado;
        }
    }
    function totalDistance(points, tipo) {
        //console.log('points', points);
        var totalmts = 0;
        if (points.length > 1) {
            for (i = 0; i < points.length - 1; i++) {
                totalmts = totalmts + distance(points[i].lat(), points[i].lng(), points[i + 1].lat(), points[i + 1].lng());
            }
        }
        if (tipo === 'Trayectoria') {
            $("#divDistanceTrayectoria").html('<b>' + totalmts.toFixed(3) + " KM" + '</b>');
        }else if (tipo === 'Vector') {
            $("#divDistanceVector").html('<b>' + totalmts.toFixed(3) + " KM" + '</b>');
        }else if (tipo === 'Reporte') {
            var resultado=totalmts.toFixed(3);
            return resultado;
        }
        
    }

    function timeDiffN(date1N, date2N) {

        var date1 = new Date(date1N);
        var date2 = new Date(date2N);
        var timeDiff = Math.abs(date2.getTime() - date1.getTime()) / 36e5;
        return timeDiff
    }


    function distance(lat1, lon1, lat2, lon2, unit) {
        var radlat1 = Math.PI * lat1 / 180
        var radlat2 = Math.PI * lat2 / 180
        var radlon1 = Math.PI * lon1 / 180
        var radlon2 = Math.PI * lon2 / 180
        var theta = lon1 - lon2
        var radtheta = Math.PI * theta / 180
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        dist = Math.acos(dist)
        dist = dist * 180 / Math.PI
        dist = dist * 60 * 1.1515
        if (unit == "K") {
            dist = dist * 1.609344
        }
        if (unit == "N") {
            dist = dist * 0.8684
        }
        return dist
    }

    function distance2(lat1, lon1, lat2, lon2) {
        var p = 0.017453292519943295; // Math.PI / 180
        var c = Math.cos;
        var a = 0.5 - c((lat2 - lat1) * p) / 2 +
            c(lat1 * p) * c(lat2 * p) *
            (1 - c((lon2 - lon1) * p)) / 2;

        return 12742 * Math.asin(Math.sqrt(a)); // 2 * R; R = 6371 km
    }

    function loadCompanies() {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/companiesName.php",
            dataType: "JSON",
            success: function (data) {
                for (var value in data)
                    $("#txtCompany").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
            }
        });
    }

    function loadRoles() {
        console.log('loadRoles');
        var agency=$("#txtCompany").val();
        if (agency !== '') {
            $.ajax({
                method: "GET",
                url: "dataLayer/callsWeb/loadRoles.php",
                data: {
                    agency:agency,
                },
                dataType: "JSON",
                success: function (data) {
                    $("#txtRole").html('');
                    $("#txtRole").append("<option value='0'>Seleccionar un Rol</option>");
                    _.each(data, function(rol, idx) {
                        $("#txtRole").append("<option value=" + rol.idProfile + ">" + rol.nameProfile + "</option>");
                    });
                }
            });
        } 
    }

    function loadUsers(agency, role) {
        var postParams;
        if (typeof(role) === undefined || role == null || role == 0) {
            postParams = {
               agency: agency,
            };
        } else {
            postParams = {
                agency: agency,
                role: role
            };
        }

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/usersByAgency.php",
            data: postParams,
            dataType: "JSON",
            success: function (data) {
                $("#txtEmployee").html('');
                $("#txtEmployee").append("<option value='0'>Seleccionar un Empleado</option>");
                var sizeData = data.length;
                if (sizeData > 0) {
                    _.each(data, function(users, idx) {
                        if (users.employee !== 'Pendiente de Asignar') {
                            $("#txtEmployee").append("<option value=" + users.id + ">" + users.employee + "</option>");    
                        }
                    });
                } else {
                    $("#txtEmployee").html('<option value="">No existen empleados</option>');
                }
            }
        });
    }
    $(document).on('click', '#cleanAll', function () {
        $("#txtCompany").html('<option value="0">Nombre de la agencia</option>');
        $("#txtRole").html('<option value="0">Tipo de rol</option>');
        $("#txtEmployee").html('<option value="0">Nombre del trabajador</option>');
        $("#inputdate").html('');
    });

    $(document).on('change', '#txtCompany', function () {
        var agency = $("#txtCompany").val();
        loadUsers(agency);
        loadRoles();
    });

    $(document).on('change', '#txtRole', function () {
        var agency = $("#txtCompany").val();
        if (agency > 0) {
            var role = $("#txtRole").val();
            loadUsers(agency, role);
        } else {
            console.log("No Agency Selected");
        }
    });

    $(document).ready(function () {
        jQuery(document).ready(function () {
            GoogleMaps.init();
        });

        $("#titleHeader").html("Ubicaciones");
        $("#subtitle-header").html("Detalle");

        $(".limenu").removeClass("active");
        $("#gomap").addClass("active");

        $('#inputdate').dcalendarpicker();

        loadCompanies();
        loadRoles();

        $('#listli li').on('click', function () {
            var tipo;
            if ($(this).text() == "Vectores") {
                tipo='Vector';
                $('#gmap_trayectoria').hide();
                $('#gmap_vector').show();
                $('#gmap_marker').hide();
                //totalTime(timesVector, tipo);
                
            } else if($(this).text() == "Trayectoria"){
                tipo='Trayectoria';
                $('#gmap_trayectoria').show();
                $('#gmap_vector').hide();
                $('#gmap_marker').hide();
                if(mapTrayectoria.Loaded()){
                    $('#gmap_vector .wrap_controls').remove();
                    mapTrayectoria.RemoveLocations(1);
                    mapTrayectoria.Load({locations: locationArray});
                }else{
                    mapTrayectoria.Load({
                        locations: locationArray,
                        map_div: '#gmap_trayectoria',
                        controls_div: '#controls-polyline',
                        controls_on_map: false,
                        type: 'polyline'
                    });
                }
                totalTime(times, tipo);
                totalDistance(path_track, tipo);
            }
        });
    });

    //generacion de excell
    $('#btn_download:not(:disabled)').click(function () {
        $('#btn_download').prop('disabled', true);
        var dateFrom='';
        if ($("#inputdate").val() !== '') {
            dateFrom = moment($("#inputdate").val()).format('YYYY-MM-DD');    
        }
        var inputIdUser = $('#txtEmployee').val();
        var txtCompany = $('#txtCompany').val();
        if (txtCompany !== '' && dateFrom !== '' && (inputIdUser !== '' && parseInt(inputIdUser) !== 0)) {
            console.log('no falta nada');
            generarExcel(dateFrom,inputIdUser);
        }else{
            MostrarToast(2, "Faltan datos por seleccionar");
            $('#btn_download').prop('disabled', false);
        }
    });
    function generarExcel(dateFrom,inputIdUser){
        if(inputIdUser !== '' && inputIdUser !== null && typeof(inputIdUser) !== 'undefined'){
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/reportExcelUbicaciones.php",
                data: {
                    employee:inputIdUser,
                    date:dateFrom,
                },
                dataType: "JSON",
                success: function (data) {
                    console.log('reporte ubicacion', data);
                    $('#btn_download').prop('disabled', false);
                    if (data.code === '500') {
                        MostrarToast(2, "Rango fechas erroneo", data.response);
                        $('#btn_download').prop('disabled', false);
                    }else{
                        var tipoReporte;
                        var arrObjDatos=[], myFailure, 
                            distancia=[],distanciaT=[], 
                            latitudC, longitudC, 
                            trackReport=[],trackReportT=[], 
                            tipo='Reporte', trayectoriaObj, timesV=[], tiempo=[],timesT=[], tiempoT=[];
                        var existeVectores=_.has(data, 'vectores');
                        if (existeVectores === true) {
                            _.each(data.vectores, function(dato, idx) {
                                if (data.vectores.length === 0) {
                                    //aqui no calculamos la distancia almacenamos la longitud y latitud en otras variables
                                    trackReport.push(new google.maps.LatLng(dato.dot_latitude, dato.dot_longitude));
                                    timesV.push(dato.created_at);
                                    
                                }else if(idx <= data.vectores.length){
                                    //aqui si
                                    trackReport.push(new google.maps.LatLng(dato.dot_latitude, dato.dot_longitude));
                                    timesV.push(dato.created_at);
                                    tiempo.push(totalTime(timesV, tipo));
                                    distancia.push(totalDistance(trackReport, tipo));
                                }
                                //agregamos al objeto el dato de 'distancia recorrida'
                                data.vectores[idx]['distanciaRecorrida']=distancia[idx];
                                data.vectores[idx]['tiempoRecorrido']=tiempo[idx];
                            });
                            _.each(data.trayectoria, function(datoT, idx) {
                                if (data.trayectoria.length === 0) {
                                    //aqui no calculamos la distancia almacenamos la longitud y latitud en otras variables
                                    trackReportT.push(new google.maps.LatLng(datoT.latitude, datoT.longitude));
                                    timesT.push(datoT.created);
                                }else if(idx <= data.trayectoria.length){
                                    //aqui si
                                    trackReportT.push(new google.maps.LatLng(datoT.latitude, datoT.longitude));
                                    distanciaT.push(totalDistance(trackReportT, tipo));
                                    timesT.push(datoT.created);
                                    tiempoT.push(totalTime(timesT, tipo));
                                }
                                data.trayectoria[idx]['distanciaRecorrida']=distanciaT[idx];
                                data.trayectoria[idx]['tiempoRecorrido']=tiempoT[idx];
                            });
                            //console.log('data.vectores', data.vectores);
                            var txtEmployee = $('#txtEmployee option:selected').text();
                            var txtCompany = $('#txtCompany option:selected').text();
                            crearExcel(data, txtCompany, txtEmployee, dateFrom);
                        }else{
                            MostrarToast(2, "No se encuentra informacion para exportar");
                            $('#btn_download').prop('disabled', false);
                        }
                    }
                        
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });
        }
    }
    function crearExcel(res, agency, empleado, dateFrom){
        if(res.vectores.length > 0 && res.trayectoria.length > 0){
            $.ajax({
                method: "POST",
                url: "dataLayer/callsWeb/createExcelUbicaciones.php",
                data: {
                    collection:res,
                    agency:agency,
                    empleado:empleado,
                    dateFrom:dateFrom
                },
                dataType: "JSON",
                success: function (data) {
                    console.log('data exp final', data);
                    $('#b_download').show();
                    var $a = $("<a>");
                    $a.attr("href",data.file);
                    $("#b_download").append($a);
                    var hoy=moment().format('DDMMYYYYhmmss');
                    $a.attr("download","ReporteUbicaciones_"+hoy+".xls");
                    $a[0].click();
                    $('#b_download').hide();
                    $('#b_download a').remove();
                    $('#btn_download').prop('disabled', false);
                    MostrarToast(1, "La exportacion se realizo correctamente");
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('textStatus', textStatus);
                }
            });
        }
    }
</script>