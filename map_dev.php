<?php include("header.php");
include_once 'dataLayer/DAO.php';
include_once 'dataLayer/libs/utils.php';
//include_once 'dataLayer/libs/PHPExcel/Classes/PHPExcel.php';

session_start();

if (isset($_POST["dateReport"])) {
    $DB = new DAO();
    $conn = $DB->getConnect();
    print_r($conn);
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    print_r($objPHPExcel);

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Mexicana de Gas")
        ->setLastModifiedBy("Mexicana de Gas")
        ->setTitle("Reporte General de formularios creados al dia" . $dateSearch)
        ->setSubject("Reporte de formularios por empleados de la agencia")
        ->setDescription("Documento en formato xls creado en el sitio Web de Mexicana de Gas")
        ->setKeywords("office 2007 openxml excel reportes")
        ->setCategory("Reportes");

    $dateSearch = $_POST["dateReport"];
    $reportData = [];
    $returnData = [];

    $reportsMap = $conn->prepare("SELECT RP.id, RP.agreementNumber, RPT.name, status.description, CTY.name, RP.colonia,
                   RP.street, RP.innerNumber, RP.outterNumber, UsEMP.nickname, USAG.nickname, RP.created_at
            FROM report AS RP
            LEFT JOIN reportType AS RPT ON RPT.id = RP.idReportType
            INNER JOIN country AS CNT ON CNT.id = RP.idCountry
            INNER JOIN state AS ST ON ST.id = RP.idState
            INNER JOIN city AS CTY ON CTY.id = RP.idCity
            INNER JOIN user AS UsEMP ON RP.idEmployee = UsEMP.id
            INNER JOIN user AS UsCreator ON RP.idUserCreator = UsCreator.id
            INNER JOIN workflow_status_report AS WSR ON RP.id = WSR.idReport
            LEFT JOIN status ON status.id = WSR.idStatus
            INNER JOIN agency_employee AS AGEM ON RP.idEmployee = AGEM.idemployee
            LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency
            LEFT JOIN user AS USAG ON USAG.id = AG.idUser
            WHERE RP.created_at = DATE(?);");
    $reportsMap->bind_param('s', $dateSearch);

    if ($reportsMap->execute()) {
        $reportsMap->store_result();
        $reportsMap->bind_result($id, $agreementNumber, $reportType, $status, $city, $colonia, $street, $innerNumber, $outterNumber, $employee, $agency, $creationDate);

        while ($reportsMap->fetch()) {
            $reportData["ID"] = $id;
            $reportData["Contrato"] = $agreementNumber;
            $reportData["Tipo"] = $reportType;
            $reportData["Estatus"] = $status;
            $reportData["Municipio"] = $city;
            $reportData["Colonia"] = $colonia;
            $reportData["Calle"] = $street;
            $reportData["Numero"] = $innerNumber;
            $reportData["NumeroExterno"] = $outterNumber;
            $reportData["Empleado"] = $employee;
            $reportData["Agencia"] = $agency;
            $reportData["Fecha"] = $creationDate;
            $returnData[] = $reportData;
        }
    }

    // Create a first sheet
    echo date('H:i:s'), " Add data", EOL;
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', "ID");
    $objPHPExcel->getActiveSheet()->setCellValue('B1', "Contrato");
    $objPHPExcel->getActiveSheet()->setCellValue('C1', "Tipo");
    $objPHPExcel->getActiveSheet()->setCellValue('D1', "Estatus");
    $objPHPExcel->getActiveSheet()->setCellValue('E1', "Municipio");
    $objPHPExcel->getActiveSheet()->setCellValue('F1', "Colonia");
    $objPHPExcel->getActiveSheet()->setCellValue('G1', "Calle y Número");
    $objPHPExcel->getActiveSheet()->setCellValue('H1', "Empleado");
    $objPHPExcel->getActiveSheet()->setCellValue('I1', "Agencia");
    $objPHPExcel->getActiveSheet()->setCellValue('J1', "Fecha");

    // Hide "Phone" and "fax" column
    echo date('H:i:s'), " Hide 'Phone' and 'fax' columns", EOL;
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setVisible(false);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setVisible(false);


    // Set outline levels
    echo date('H:i:s'), " Set outline levels", EOL;
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setOutlineLevel(1)
        ->setVisible(false)
        ->setCollapsed(true);

    // Freeze panes
    echo date('H:i:s'), " Freeze panes", EOL;
    $objPHPExcel->getActiveSheet()->freezePane('A2');


    // Rows to repeat at top
    echo date('H:i:s'), " Rows to repeat at top", EOL;
    $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

    $i = 2;
    // Add data
    foreach ($returnData as $key => $value) {
        $streetNumber = "";
        if (isset($reportData["Numero"])) {
            $streetNumber = $value["Calle"] . $reportData["Numero"];
        } else {
            $streetNumber = $value["Calle"] . $reportData["NumeroExterno"];
        }

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value["ID"])
            ->setCellValue('B' . $i, $value["Contrato"])
            ->setCellValue('C' . $i, $value["Tipo"])
            ->setCellValue('D' . $i, $value["Estatus"])
            ->setCellValue('E' . $i, $value["Municipio"])
            ->setCellValue('F' . $i, $value["Colonia"])
            ->setCellValue('G' . $i, $streetNumber)
            ->setCellValue('H' . $i, $value["Empleado"])
            ->setCellValue('I' . $i, $value["Agencia"])
            ->setCellValue('J' . $i, $value["Fecha"]);
        $i++;
    }
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reporteFormularios.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
?>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">

                <div class="form-inline">
                    <label>Filtros</label>

                    <select class="form-control" id="txtCompany">
                        <option value="">Nombre de la agencia</option>
                    </select>
                    <select class="form-control" id="txtRole">
                        <option value="">Tipo de rol</option>
                    </select>
                    <select class="form-control" id="txtEmployee">
                        <option value="">Nombre del trabajador</option>
                    </select> &nbsp; <label class="text-capitalize">Fecha de:</label> &nbsp;
                    <!--<div class='input-group' id='datetimepickerFinal'>-->
                    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                        <input type='text' class="form-control" id="inputdate" name="dateReport"/>
                        <button onclick="loadby();" type="button" id="btn_search" class="btn btn-success">
                            <span class="glyphicon glyphicon-calendar" style="color:#fff;"></span></button>
                       <!-- <a href="#" id="cleanAll">LIMPIAR TODO</a> -->
                        <button type="submit" value="" type="button" id="btn_download" class="btn btn-success">
                            <span class="glyphicon glyphicon-download-alt" style="color:#fff;"></span>
                        </button>
                    </form>
                </div>

            </header>
            <div class="panel-body">
                <div id="gmap_marker" class="gmaps"></div>
            </div>
            <div id="modalVect">
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
                                <div id="divDistance" class="col-xs-3 col-md-6"><b>0 KM</b></div>
                                <div id="divDate" class="col-xs-3 col-md-3"><b>0 min</b></div>
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
                                <div id="divReal" class="col-xs-6 col-md-3"><b>0</b></div>
                                <div id="divProces" class="col-xs-6 col-md-3"><b>0</b></div>
                                <div id="divRech" class="col-xs-6 col-md-3"><b>0</b></div>
                                <div id="divPendient" class="col-xs-6 col-md-3"><b>0</b></div>
                            </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <div class="row">
                                <div id="divUser" class="col-xs-3 col-md-6">
                                    <h4>User</h4></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 col-md-6">Distancia Recorrida</div>
                                <div class="col-xs-3 col-md-3">Tiempos</div>
                            </div>
                            <div class="row">
                                <div id="divDistance" class="col-xs-3 col-md-6"><b>0 KM</b></div>
                                <div id="divDate" class="col-xs-3 col-md-3"><b>0 min</b></div>
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
                                <div id="divReal" class="col-xs-6 col-md-3"><b>0</b></div>
                                <div id="divProces" class="col-xs-6 col-md-3"><b>0</b></div>
                                <div id="divRech" class="col-xs-6 col-md-3"><b>0</b></div>
                                <div id="divPendient" class="col-xs-6 col-md-3"><b>0</b></div>
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
        src="http://maps.google.com/maps/api/js?key=AIzaSyCwfWoqM5jaW4y2m3cAbkOQxlGo4GEB0yI"></script>
<script src="assets/js/gmaps.js"></script>
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

    var contentString = '<div id="content">' +
        '<ul class="nav nav-tabs">' +
        '<li><a data-toggle="tab" href="#menu1">Vectores</a></li>' +
        '<li><a data-toggle="tab" href="#menu2">Trayectoria</a></li>' +
        '</ul>' +
        '<div class="tab-content ">' +
        '<div id="menu1" class="tab-pane fade in active">' +
        '<div class="row">' +
        '<div class="col-xs-3 col-md-6"><h4>' + user + '</h4></div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-3 col-md-6">Distancia Recorrida</div>' +
        '<div class="col-xs-3 col-md-3">Tiempos</div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-3 col-md-6">12 KM</div>' +
        '<div class="col-xs-3 col-md-3">30 min</div>' +
        '</div>' +
        '<div  class="row">' +
        '<div class="col-xs-4 col-md-4"><label>Instalaciones</label></div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-6 col-md-4">Realizadas</div>' +
        '<div class="col-xs-6 col-md-4">Rechazadas</div>' +
        '<div class="col-xs-6 col-md-4">Pendientes</div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-6 col-md-4">0</div>' +
        '<div class="col-xs-6 col-md-4">1</div>' +
        '<div class="col-xs-6 col-md-4">0</div>' +
        '</div>' +
        '</div>' +
        '<div id="menu2" class="tab-pane fade">' +
        '<div class="row">' +
        '<div class="col-xs-3 col-md-6"><h4>' + user + '</h4></div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-3 col-md-6">Distancia Recorrida</div>' +
        '<div class="col-xs-3 col-md-3">Tiempos</div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-3 col-md-6">12 KM</div>' +
        '<div class="col-xs-3 col-md-3">30 min</div>' +
        '</div>' +
        '<div  class="row">' +
        '<div class="col-xs-4 col-md-4"><label>Instalaciones</label></div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-6 col-md-4">Realizadas</div>' +
        '<div class="col-xs-6 col-md-4">Rechazadas</div>' +
        '<div class="col-xs-6 col-md-4">Pendientes</div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-xs-6 col-md-4">0</div>' +
        '<div class="col-xs-6 col-md-4">1</div>' +
        '<div class="col-xs-6 col-md-4">0</div>' +
        '</div>' +
        '</div>';

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
    var path_track = [];
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

    function paintMapByPath_V(path) {
        polylineVector = new google.maps.Polyline({
            path: path,
            strokeColor: "#00B5FF"
        });
    polylineVector.setMap(null);
        polylineVector.setMap(map);

    }

    /**
     convert date  format
     **/

    function convertDate(inputFormat) {
        function pad(s) {
            return (s < 10) ? '0' + s : s;
        }

        var d = new Date(inputFormat);
        return [d.getFullYear(), pad(d.getMonth() + 1), pad(d.getDate())].join('/');
    }

    function loadRoute(employeeId) {
        var agencyId = $("#txtCompany").val();
        var roleId = $("#txtRole").val();
        if (employeeId == null || employeeId == "") {
            employeeId = $("#txtEmployee").val();
        }

        var employeeId = $("#txtEmployee").val();
        var date = $("#inputdate").val();
        //employeeId = 775;
        date = convertDate(date);
        //console.log(date);

        //console.log("lo va a ejecutar");
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/mapInfo.php",
            data: {
                employee: employeeId, date: date
            },
            dataType: "JSON",
            success: function (data) {
                times = [];

                //path =[];
                deleteOverlays();
                if (polylineTrack) {
                    polylineTrack.setMap(null);
                    polylineTrack = null;
                }
        path_track   = [];
                paintMapByPath(path_track);
                for (var value in data) {
                    if (data[value].latitude == null) {

                    } else {

                        path_track.push({lat: data[value].latitude, lng: data[value].longitude})
                        //console.log(points);
                        user = data[value].nickname;
                        times.push(data[value].created);

                    }
                }

                if (polylineTrack != null) {
                    polylineTrack.setMap(null);
                }

                // paintMapByPath(path_track);
                $("#divUser").html('<b>' + user + '</b>');
                totalTime(times);
                totalDistance(points);
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

                for (elem in data) {
                    //console.log(data[elem]);
                }

                var st_pendientes = 0;
                var st_real = 0;
                var st_rech = 0;
                var st_process = 0;

                deleteOverlays_vector();
                /* if(polylineVector!=null){
                 polylineVector.setMap(null);
                 polylineVector  =null;
                 }*/
                 path_vector = [];
                for (var value in data) {

                    if (data[value].dot_latitude == null) {

                    } else {
                        path_vector.push(new google.maps.LatLng(data[value].dot_latitude, data[value].dot_longitude))
                        console.log(data[value]);


                        if (data[value].status == "Pendiente por Asignar") {
                            st_pendientes++;
                        }
                        if (data[value].status == "En proceso") {
                            st_process++;
                        }

                        if (data[value].status == "Rechazado") {
                            st_rech++;

                        }
                        if (data[value].status == "Finalizado") {
                            st_real++;
                        }
                    }
                    if (polylineVector != null) {
                        polylineVector.setMap(null);
                        polylineVector =null;
                    }
                    paintMapByPath_V(path_vector);
                    paintMapByPoints_Vector(path_vector);

                }

                $("#divUser").html('<b>' + user + '</b>');
                $("#divPendient").html('<b>' + st_pendientes + '</b>');
                $("#divRech").html('<b>' + st_rech + '</b>');
                $("#divReal").html('<b>' + st_real + '</b>');
                $("#divProces").html('<b>' + st_process + '</b>');


                //totalDistance(points);
            }
        });
    }


    //////////load all employ from agency

    function loadEmploy_by_Agency() {
        var agencyId = $("#txtCompany").val();
        var roleId = $("#txtRole").val();
        var employeeId = $("#txtEmployee").val();
        var date = $("#inputdate").val();
        agencyId = 1;
        date = convertDate(date);
        //console.log(date);
        deleteOverlays();
        deleteOverlays_vector();
        polylineTrack.setMap(null);
        polylineTrack = null;    
        polylineVector.setMap(null);
        polylineVector = null;
  

        //console.log("lo va a ejecutar");
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/reportsByAgency.php",
            data: {
                agency: agencyId, date: date
            },
            dataType: "JSON",
            success: function (data) {
                // console.log(date);
                points = [];
                listid = [];
                for (var value in data) {
                    if (data[value] == null) {

                    } else {
                        points.push(new google.maps.LatLng(data[value].latitude, data[value].longitude))
                        listid.push(data[value].id);
                    }
                }
                paintMapByPoints_(points, listid);

            }
        });


    }


    // Take way if get or not employee
    function loadby() {
        var employeeId = $("#txtEmployee").val();

        if (employeeId == "0") {
            loadEmploy_by_Agency();
        } else {
            loadRoute();
        }

    }


    ///


    function addLatLng(event) {
        var path = polyline.getPath();

        // Because path is an MVCArray, we can simply append a new coordinate
        // and it will automatically appear.
        var test = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng())
        path.push(test);

        // Add a new marker at the new plotted point on the polyline.
        /* var marker = new google.maps.Marker({
         position: event.latLng,
         title: '#' + path.getLength(),
         map: map
         });*/
    }


    ////load marks to users
    var image = 'assets/img/mapa.png';
    function paintMapByPoints_(points, listid) {
        var path = [];
        for (var i = 0; i < points.length; ++i) {
            var marker = new google.maps.Marker({
                map: map,
                icon: image,
                position: points[i],
                labelContent: "" + listid[i],
                title: "Hello world"
            });

            marker.addListener('click', function () {
                loadRoute(listid[i]);
            });
            marker.setMap(map);
            markersArray.push(marker);
            path.push(marker.position);
        }
    }

    /////Paint into the maps
    function paintMapByPoints_Vector(points) {
        var path = [];
        for (var i = 0; i < points.length; ++i) {
            var marker = new google.maps.Marker({
                map: map,
                position: points[i]
            });
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });
            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });
            //marker.setMap(map);   

            markersArrayVectores.push(marker);
            path.push(marker.position);
        }
    }


    // Removes the overlays from the map, but keeps them in the array
    function clearOverlays() {
        if (markersArray) {
            for (i in markersArray) {
                markersArray[i].setMap(null);
            }
        }
    }

    // Shows any overlays currently in the array
    function showOverlays() {
        if (markersArray) {
            for (i in markersArray) {
                markersArray[i].setMap(map);
            }
        }
    }

    // Deletes all markers in the array by removing references to them
    function deleteOverlays() {
        if (markersArray) {
            for (i in markersArray) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }
    }

    ///////// vectores
    // Removes the overlays from the map, but keeps them in the array
    function clearOverlays_vector() {
        if (markersArrayVectores) {
            for (i in markersArrayVectores) {
                markersArrayVectores[i].setMap(null);
            }
        }
    }

    // Shows any overlays currently in the array
    function showOverlays_vector() {
        if (markersArrayVectores) {
            for (i in markersArrayVectores) {
                markersArrayVectores[i].setMap(map);
            }
        }
    }

    // Deletes all markers in the array by removing references to them
    function deleteOverlays_vector() {
        if (markersArrayVectores) {
            for (i in markersArrayVectores) {
                markersArrayVectores[i].setMap(null);
            }
            markersArrayVectores.length = 0;
        }
    }


    //////Vectores

    /////////////Only markers

    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
    }

    // Shows any markers currently in the array.
    function showMarkers() {
        setMapOnAll(map);
    }

    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
        clearMarkers();
        markers = [];
    }
    ////////////////////


    function totalTime(points) {

        var totalmts = 0;
        if (points.length > 1) {
            for (i = 0; i < points.length - 1; i++) {
                totalmts = totalmts + timeDiffN(points[i], points[i + 1]); // text += cars[i] + "<br>";
            }
        }

        $("#divDate").html('<b>' + totalmts.toFixed(2) + " Horas" + '</b>');
    }

    function totalDistance(points) {

        var totalmts = 0;
        if (points.length > 1) {
            for (i = 0; i < points.length - 1; i++) {
                totalmts = totalmts + distance(points[i].lat, points[i].lng, points[i + 1].lat, points[i + 1].lng);
            }
        }

        $("#divDistance").html('<b>' + totalmts.toFixed(3) + " KM" + '</b>');
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

    function distance(lat1, lon1, lat2, lon2) {
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
            //url: "dataLayer/callsWeb/companiesName.php",
            dataType: "JSON",
            success: function (data) {
                for (var value in data)
                    $("#txtCompany").append("<option value=" + data[value].id + ">" + data[value].agency + "</option>");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                alert(textStatus);
            },
            complete: function (XMLHttpRequest, status) {
                //console.log(XMLHttpRequest);
                //console.log(status);
            }
        });
    }

    function loadRoles() {
        $("#txtRole").append("<option value='1'>Cencista</option>" + "<option value='2'>Cambaceo</option>" + "<option value='3'>Plomero</option>" + "<option value='4'>Instalador</option>");
    }

    function loadUsers(agency, role) {
        //console.log(agency);
        //console.log(role);
        //alert(agency + ' ' + role);
        var postParams;
        if (role == undefined || role == null || role == 0) {
            postParams ={
               agency:agency,
            } ;
        } else {
            postParams ={
                agency:agency,
                role:role
            } ;
        }

        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/usersByAgency.php",
            data: postParams,
            dataType: "JSON",
            success: function (data) {
                //console.log(data);
                //alert('fucking data =>' + data);
                $("#txtEmployee").html('');
                var sizeData = data.length;
                if (sizeData > 0) {
                    for (var value in data)
                        $("#txtEmployee").append("<option value=" + data[value].id + ">" + data[value].employee + "</option>");
                } else {
                    $("#txtEmployee").html('<option value="">No existen empleados</option>');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                alert(textStatus);
            },
            complete: function (XMLHttpRequest, status) {
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
        /*var string_nickname = $("#user_nickname").html();
         string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
         string_nickname = string_nickname.trim();*/

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
            //alert($(this).text());
            if ($(this).text() == "Vectores") {
                // clearOverlays_vector();
                if (polylineTrack != null) {
                    polylineTrack.setMap(null);
                    //polylineTrack =null;
                }
                var pathnew   =[];
                paintMapByPath(pathnew);
                //paintMapByPath_V(path_vector);
        polylineVector.setPath(path_vector)

            } else if ($(this).text() == "Trayectoria") {
                // clearOverlays();
                if (polylineVector != null) {
                    polylineVector.setMap(null);
                }
                //paintMapByPath(path_track);
                var pathnew   =[];
                paintMapByPath_V(pathnew);
                polylineTrack.setPath(path_track)
            }

        });

        //loadUsers();
    });
</script>
