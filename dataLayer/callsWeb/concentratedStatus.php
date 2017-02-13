<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];
$reasons = [];


$getReasons="call GetStatusReport();";

	$result=$conn_>query($getReasons);

	while($row =$result->fetch_array()){
		$reasons['Id']=$row[0];
		$reasons['Agencia']=$row[1];
		$reasons['RevVentas']=$row[2];
		$reasons['RevFinanciera']=$row[3];
		$reasons['RechVenta']=$row[4];
		$reasons['RechFinanciera']=$row[5];
		$reasons['SegCaptura']=$row[6];
		$reasons['PHPorAsignar']=$row[7];
		$reasons['PHEnProceso']=$row[8];
		$reasons['PHPendiente']=$row[9];
		$reasons['PHCompleto']=$row[10];
		$reasons['IPorAsignar']=$row[11];
		$reasons['IEnProceso']=$row[12];
		$reasons['IPendiente']=$row[13];
		$reasons['ICompletado']=$row[14];
		$reasons['FechIni']=$row[15];
		$reasons['FechFi']=$row[16];
	}



?>