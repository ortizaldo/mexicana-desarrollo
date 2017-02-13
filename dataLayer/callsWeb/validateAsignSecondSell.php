<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
$reportID=$_GET['idReport'];
if ($reportID != '') {
	$returnData = []; $reports = [];
	$getReportStatus = "SELECT validacionSegundaVenta
						FROM tEstatusContrato
						WHERE 0=0
                        AND idReporte=$reportID
                        AND idEmpleadoSegundaVenta > 0";
    $result = $conn->query($getReportStatus);
	if ($result->num_rows > 0) {
		while( $row = $result->fetch_array() ) {
		    $returnData['validacionSegundaVenta'] = $row[0];
		    $reports[] = $returnData;
		}
	}
	$result->free_result();
	echo json_encode($reports);	
}