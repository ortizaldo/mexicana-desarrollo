<?php include_once "../DAO.php";
include_once "../libs/utils.php";

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$DB = new DAO();
$conn = $DB->getConnect();

$username;
$data = []; $result = [];

$idSession;
$city;
$col;
$street;
$roads;
$number;
$cp;
$level;
$addressNew;
$middleStreet;
$agency;
$users;

$idEmployee;

if ( isset($_POST['city']) && isset($_POST['col']) && isset($_POST['agency']) && isset($_POST['users'])) {
    $idSession = $_POST['id'];
    $city = $_POST['city'];
    $col = $_POST['col'];
    $street = $_POST['street'];
    $roads = $_POST['roads'];
    $number = $_POST['number'];
    $cp = $_POST['cp'];
    $level  = $_POST['level'];
    $addressNew = $_POST['addressNew'];
    $middleStreet = $_POST['middleStreet'];
    $agency = $_POST['agency'];
    $users = $_POST['users'] ;

    if ($cp == null || !isset($cp)) $cp=00000;


    //$agreementNumber Consumir de webservice de mexicana
    $agreementNumber = "-";
    $outterNumber = $number;
    //venta
    $idReportType = 2;
    //Flujo numero 1
    $idWorkflow = 1;
    //Pendiente por asignar
    $idStatus = 4;
    $name = "Venta";
    $description = "Venta";

    $getEmployeeID = $conn->prepare("SELECT id, idProfile FROM `employee` WHERE `idUser` = ?;");
    $getEmployeeID->bind_param('i', $users);

    $getEmployeeID->store_result();
    $getEmployeeID->bind_result($id, $idProfile);

    if( $getEmployeeID->execute() ) {
        while ($getEmployeeID->fetch()) {
            $idEmployee = $id;
        }
    }

    /*var_dump($agreementNumber);
    var_dump($col);
    var_dump($street);
    var_dump($roads);
    var_dump($outterNumber);
    var_dump($outterNumber);
    var_dump($street);
    var_dump($city);
    var_dump($cp);
    var_dump($users);
    var_dump($idReportType);
    var_dump($idSession);*/

    /* Revisa si el empleado a asignar la tarea tiene el rol de instalación en caso contrario devuelve los
     * parámetros para desplegar un dialogo de alerta notificando que no se puede asignar la tarea a esa agencia.
     */
    //$idProfile;

    $prospect=1;
    $uninteresting="";
    $motivosDesinteres="";
    $comments="";
    $owner=0;
    $name="";
    $lastName="";
    $lastNameOp="";
    $payment="";
    $financialService="";
    $requestNumber=0;
    $meeting="2016-01-01";
    $idFormulario=0;
    $latitude=0.00;
    $longitude=0.00;



    $insertFormSells = $conn->prepare("INSERT INTO `form_sells` (prospect, uninteresting, motivosDesinteres,comments,owner,name,lastName,lastNameOp,payment,financialService,requestNumber,meeting,idFormulario,latitude,longitude,created_at,updated_at,active)  VAlUES (1,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW(),1);");
    $insertFormSells->bind_param('sssissssiisidd',$uninteresting,$motivosDesinteres,$comments,$owner,$name,$lastName,$lastNameOp,$payment,$financialService,$requestNumber,$meeting,$idFormulario,$latitude,$longitude);
    $insertFormSells->execute();
    $idFormSells = $insertFormSells->insert_id;


    $insertReport = $conn->prepare("INSERT INTO `report` (agreementNumber, colonia, street, betweenStreets, innerNumber, outterNumber, idCity, cp, idEmployee, idReportType,idSolicitud, idUserCreator, created_at, updated_at) VAlUES (?,?,?,?,?,?,?,?,?,?,?,?, NOW(), NOW());");
    $insertReport->bind_param('ssssssssiiii', $agreementNumber, $col, $street, $roads, $outterNumber, $outterNumber, $city, $cp, $idEmployee, $idReportType, $idFormSells,$idSession);
    if ($insertReport->execute()) {
        //var_dump("Creación de venta correcto");
        $idReport = $insertReport->insert_id;
        
        /* AGREGADO PARA INSERTAR EN HISTORY REPORT */
        
        $insertHistory = "INSERT INTO `reportHistory`(`idReport`,`idFormSell`,`idReportType`,`idUserAssigned`,`idStatusReport`,`updated_at`,`created_at`)VALUES(?,?,?,?,?,NOW(),NOW())";
        $insertHistory = $conn->prepare($insertHistory);
        $insertHistory->bind_param("iiiii",$idReport,$idFormSells,$idReportType,  $idEmployee, $idStatus);
        $insertHistory->execute();
        
        /* FIN AGREGADO */
        
        //var_dump($idReport);
        $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report` (idWorkflow, idStatus, idReport) VAlUES (?,?,?);");
        $insertStatusReport->bind_param('iii', $idWorkflow, $idStatus, $idReport);
        $insertStatusReport->execute();



        $insertReportAssignedStatus = $conn->prepare("INSERT INTO `report_AssignedStatus` (`idReport`, `idStatus`, `created_at`, `updated_at`) VAlUES (?, ?, NOW(), NOW());");
        $insertReportAssignedStatus->bind_param('ii', $idReport, $idStatus);
        if ($insertReportAssignedStatus->execute()){

            $statusReport = 60; $statusCensus = 0; $statusSells = 4; $setStatusNoSell = 0;$statusVentaEnProceso=20;
            $insertInEstatusContrato = $conn->prepare("INSERT INTO tEstatusContrato(`idReporte`, `estatusReporte`, `estatusCenso`, `estatusVenta`,
                                              `idEmpleadoParaVenta`, `asignadoMexicana`, `asignadoAyopsa`, `validadoMexicana`, `validadoAyopsa`, `phEstatus`, `idEmpleadoPhAsignado`, `asignacionSegundaVenta`,
                                              `idEmpleadoSegundaVenta`, `validacionSegundaVenta`, `idClienteGenerado`, `validacionInstalacion`, `estatusAsignacionInstalacion`, `idEmpleadoInstalacion`,
                                              `idAgenciaInstalacion`, `fechaAlta`, `fechaMod`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
            $insertInEstatusContrato->bind_param("iiiiiiiiiiiiiiiiiii", $idReport, $statusReport, $statusCensus, $statusSells, $setStatusNoCensus, $setStatusNoCensus, $statusVentaEnProceso, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus, $setStatusNoCensus);
            if($insertInEstatusContrato->execute()) {
                $insertInEstatusContrato = $conn->prepare("INSERT INTO report_employee_form(`idReport`, `idEmployee`, `idForm`, `created_at`,
                                              `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                $insertInEstatusContrato->bind_param("iii", $idReport, $idEmployee, $idFormSells);
                if($insertInEstatusContrato->execute()) {
                    $result["status"] = "OK";
                    $result["code"] = "200";
                    $result["result"] = "Reporte de Venta Creado Exitosamente";
                }
            }
        } else {
            echo $insertReportAssignedStatus->error;
        }
    } else {
        $result["status"] = "BAD";
        $result["code"] = "500";
        $result["result"] = $insertReport->error;
    }
    echo json_encode($result);
}