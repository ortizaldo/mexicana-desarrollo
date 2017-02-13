<?php include_once "../DAO.php";

    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $employee = 0;

    if(isset($_POST['employee']) && isset($_POST['date'])) {
	   $employee = $_POST['employee'];
	   $date = $_POST['date'];
    }
    $trackdata = [];
    $reportsHistory = "SELECT RP.id, RP.agreementNumber, RP.clientName, RP.colonia, RP.street, 
                      RP.betweenStreets, RP.nse,RP.newStreet, RP.streets, RP.coloniaType, 
                      RP.marketType, RP.innerNumber, RP.outterNumber, RP.street, RP.cp,
                      RP.dot_latitude, RP.dot_longitude, UsCreator.nickname, status.description,status.name, rph.rechazado, RP.created_at, RP.idCity, rpt.description
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
    //echo "sql ".$reportsHistory;
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
            $cont++;
        }
        //$returnData[] = $trackdata;
    }
    echo json_encode($trackdata);