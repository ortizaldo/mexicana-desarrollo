<?php 
include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$employee = 0;
$trackdata = [];
$employee = ($_POST['employee'] == "0") ? '' : $_POST['employee'];
$date = $_POST['date'];
$arrTrayectorias= getTrayectoria($conn, $employee, $_POST['date']);
$returnData['trayectoria'] =$arrTrayectorias;
//echo json_encode($_POST);
if($employee != '' && $date != '') {
    $reportsHistory = "SELECT RP.id, RP.agreementNumber, RP.clientName, RP.colonia, RP.street, 
                      RP.betweenStreets, RP.nse,RP.newStreet, RP.streets, RP.coloniaType, 
                      RP.marketType, RP.innerNumber, RP.outterNumber, RP.street, RP.cp,
                      RP.dot_latitude, RP.dot_longitude, UsCreator.nickname, status.description,status.name, rph.rechazado, RP.created_at, RP.idCity, rpt.description, UsCreator.id,
                      (select name from profile where id in (select idProfile from employee where idUser=UsCreator.id)) as tipoPerfil
                      FROM report AS RP 
                      INNER JOIN reportHistory as rph on rph.idReport = RP.id
                      INNER JOIN reportType as rpt on rpt.id = rph.idReportType
                      INNER JOIN user AS UsCreator ON rph.idUserAssigned = UsCreator.id
                      INNER JOIN tEstatusContrato AS te ON te.idReporte = RP.id
                      LEFT JOIN status ON status.id = rph.idStatusReport
                      WHERE 0=0
                      and rph.idUserAssigned =$employee
                      and rph.created_at LIKE '".$date."%'
                      limit 100;";
    $result = $conn->query($reportsHistory);
    $res="";
    $cont=0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $trackdata[$cont]['id']=$row[0];
            $trackdata[$cont]['agreementNumber']=$row[1];
            $trackdata[$cont]['clientName']=$row[2];
            $trackdata[$cont]['colonia']=$row[3];
            $trackdata[$cont]['street']=$row[4];
            $trackdata[$cont]['betweenStreets']=$row[5];
            $trackdata[$cont]['nse']=$row[6];
            $trackdata[$cont]['newStreet']=$row[7];
            $trackdata[$cont]['streets']=$row[8];
            $trackdata[$cont]['coloniaType']=$row[9];
            $trackdata[$cont]['marketType']=$row[10];
            $trackdata[$cont]['innerNumber']=$row[11];
            $trackdata[$cont]['outterNumber']=$row[12];
            $trackdata[$cont]['street']=$row[13];
            $trackdata[$cont]['cp']=$row[14];
            $trackdata[$cont]['dot_latitude']=$row[15];
            $trackdata[$cont]['dot_longitude']=$row[16];
            $trackdata[$cont]['nickname']=$row[17];
            $trackdata[$cont]['status']=$row[18];
            $trackdata[$cont]['idStatus']=$row[19];
            $trackdata[$cont]['rechazado']=$row[20];
            $trackdata[$cont]['created_at']=$row[21];
            $trackdata[$cont]['idCity']=$row[22];
            $trackdata[$cont]['tipoReporte']=$row[23];
            $trackdata[$cont]['idUser']=$row[24];
            $trackdata[$cont]['perfil']=$row[25];
            $cont++;
        }
        $returnData['vectores'] = $trackdata;
    }
}    
echo json_encode($returnData);
function getTrayectoria($conn, $idEmpleado, $date)
{
    $returnData = [];
    if (isset($idEmpleado) && isset($date)) {
        # code...
        $track = "SELECT track.start_latitude, 
                         track.created_at, 
                         track.start_longitude, 
                         USEMP.nickname, 
                         track.idEmployee, 
                         USEMP.id,
                         (select name from profile where id in (select idProfile from employee where idUser=USEMP.id)) as tipoPerfil
            FROM track 
            LEFT JOIN employee AS EMP ON EMP.idUser = track.idEmployee 
            LEFT JOIN user AS USEMP ON USEMP.id = EMP.idUser 
            WHERE 0=0 
            AND track.idEmployee = $idEmpleado
            AND track.created_at LIKE '".$date."%'
            GROUP BY track.start_latitude, track.created_at, track.start_longitude, USEMP.nickname, track.idEmployee, USEMP.id";
        $result = $conn->query($track);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $trackData["latitude"] = $row[0];
                $trackData["created"] = $row[1];
                $trackData["longitude"] = $row[2];
                $trackData["nickname"] = $row[3];
                $trackData["idUser"] = $row[5];
                $trackData["tipoPerfil"] = $row[6];
                $returnData[] = $trackData;
            }
            return $returnData;
        }
    }
}
