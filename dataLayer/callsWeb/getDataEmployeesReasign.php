<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
$rowTRTipoReporte=$_GET['rowTRTipoReporte'];
$agencia=$_GET['agencia'];
$idReporte=$_GET['rowIDReporte'];
$returnData = []; $reports = [];
$resultadoGetUserID = getUserID($idReporte, $rowTRTipoReporte);
if ($resultadoGetUserID[0]["idUserAssigned"] != "") {
	$getReportStatus = "SELECT 
						    a.id, a.nickname
						FROM
						    user a,
						    employee b,
						    agency_employee c,
						    agency d,
						    profile e
						WHERE
						0 = 0 AND a.id = b.idUser
						AND b.id = c.idemployee
						AND c.idAgency = d.id
						AND e.id = b.idProfile
						AND e.name LIKE '%".$rowTRTipoReporte."%'
						AND d.id IN (SELECT 
										g.id
									FROM
										user f,
										agency g
									WHERE
										0 = 0 AND f.id = g.idUser
											AND f.nickname LIKE '%".$agencia."%')
						AND a.nickname NOT IN ('".$resultadoGetUserID[0]["nickname"]."', 'Pendiente de Asignar');";
	$result = $conn->query($getReportStatus);
	while( $row = $result->fetch_array() ) {
	    $returnData['IDEmp'] = $row[0];
	    $returnData['nicknameEmp'] = $row[1];
	    $reports[] = $returnData;
	}
	$result->free_result();
	echo json_encode($reports);
}

function getUserID($idReport, $tipoRep)
{
	if ($idReport != "" && $tipoRep != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $stmtCol = "SELECT 
					    a.idUserAssigned, b.nickname, a.idStatusReport
					FROM
					    reportHistory a, user b
					WHERE 0=0
					AND a.idUserAssigned = b.id
					AND idReport = $idReport
					AND idReportType IN (
					SELECT 
						id
					FROM
						profile
					WHERE
						name LIKE '%".$tipoRep."%');";
        $reports=[];
        $contador = 0;
        $result = $conn->query($stmtCol);
        while( $row = $result->fetch_array() ) {
		    $returnData['idUserAssigned'] = $row[0];
		    $returnData['nickname'] = $row[1];
		    $returnData['idStatusReport']=$row[2];
		    $reports[] = $returnData;
		}
        return $reports; 
    }
}