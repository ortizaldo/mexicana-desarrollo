<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];
$reasons1 = [];
//$reasons2 = [];
//$reasons3 = [];

$reasons1['Contrato'] = 107;
$reasons1['Agencia'] = 1;
$reasons1['RevVentas'] = 1;
$reasons1['RevFinanciera'] = 1;
$reasons1['RechVenta'] = 2;
$reasons1['RechFinanciera'] = 1;
$reasons1['SegCaptura'] = 0;
$reasons1['PHPorAsignar'] = 2;
$reasons1['PHEnProceso'] = 1;
$reasons1['PHPendiente'] = 1;
$reasons1['PHCompleto'] = 1;
$reasons1['IPorAsignar'] = 1;
$reasons1['IEnProceso'] = 1;
$reasons1['IPendiente'] = 2;
$reasons1['ICompleto'] = 1;
$reasons1['FechaIni'] = '25/07/2016';
$reasons1['FechaFi'] = '25/07/2016';

$returnData[] = $reasons1;

//$result->free_result();
echo json_encode($returnData);

/*$getResonsSells="SELECT RP.id, RP.agreementNumber,COUNT(SLLT.sellRevStart),COUNT(SLLT.finRevStart),
COUNT(SLLT.docRejectStart),COUNT(SLLT.docRejectStart)
FROM report AS RP
INNER JOIN report_employee_form AS RPEMP ON RP.id=RPEMP.idReport
INNER JOIN employee AS EMP ON RPEMP.idemployee= EMP.id
INNER JOIN agency_employee AS AGMEMP ON EMP.id=AGMEMP.idemployee
INNER JOIN agency AS AG ON AGMEMP.idAgency=AG.id
INNER JOIN form_sells AS FRMS ON RPEMP.idForm=FRMS.id
INNER JOIN sell_time AS SLLT ON  RPEMP.id=SLLT.id
WHERE  SLLT.created_at=SLLT.created_at GROUP BY FRMS.id;";

$result1= $conn->query($getResonsSells);

while($row =$result1->fetch_array()){
	$reasons1['id']=$row[0];
	$reasons1['Agencia']=$row[1];
	$reasons1['RevVentas']=$row[2];
	$reasons1['RevFinanciera']=$row[3];
	$reasons1['RecVenta']=$row[4];
	$reasons1['RecFinanciera']=$row[5];
	$reasons1['SegundaCaptura']=$row[6];

}

$getReasonsPlumber="SELECT DISTINCT AGEN.id,AGEN.tipo,AGENE.idemployee,FRMP.idStatus,COUNT(FRMP.idStatus),FRMP.created_at
FROM agency as AGEN
INNER JOIN agency_employee AS AGENE ON AGEN.id=AGENE.idAgency
INNER JOIN employee AS EMP ON AGENE.idemployee=EMP.id
INNER JOIN report_employee_form AS RPEF ON  EMP.id=RPEF.idemployee
INNER JOIN form_plumber AS FRMP ON RPEF.idForm=FRMP.id
WHERE AGEN.tipo="Plomeria" GROUP BY FRMP.idStatus;";

$result2= $conn->quey($getReasonsPlumber);

while($row= $result2->fetch_array() ){
	$reasons2['id']=$row[0];
	$reasons2['nombreAgencia']=$row[1];
	$reasons2['empleadoDeAgencia']=$row[2];
	$reasons2['status']=$row[3];
	if($row[3]==1){
		$reasons2['asignadosPlomeria']=$row[4];
	}else if($row[3]==2){
		$reasons2['porcesoPlomeria']=$row[5];
	}else if($row[3]==3){
		$reasons2['pendientePlomeria']=$row[6];
	}else if($row[3]==4){
		$reasons2['completadoPlomeria']=$row[7];
	}
	$reasons2['fechaInicial']=$row[8];
	$reasons2['fechaFinal']=$row[9];

}

$getReasonsInstallation="SELECT DISTINCT AGEN.id,AGEN.tipo,AGENE.idemployee,FRMI.idStatus,COUNT(FRMI.idStatus),FRMI.created_at
FROM agency AS AGEN
INNER JOIN agency_employee AS AGENE ON AGEN.id=AGENE.idAgency
INNER JOIN employee AS EMP ON AGENE.idemployee=EMP.id
INNER JOIN report_employee_form AS RPEF ON EMP.id=RPEF.idEmployee
INNER JOIN  form_installation AS FRMI ON RPEF.idForm=FRMI.id
WHERE AGEN.tipo="Instalacion" GROUP BY FRMI.idStatus;";

$result3= $conn->query($getReasonsInstallation);

while($row = $result3->fetch_array() ){
	$reasons3['id']=$row[0];
	$reasons3['mombreAgencia']=$row[1];
	$reasons3['empleadoDeAgencia']=$row[2];
	$reasons3['status']=$row[3];
	if($row[3]==1){
		$reasons3['asignadosInstalacion']=$row[4];
	}else if($row[3]==2){
		$reasons3['procesoInstalacion']=$row[5];
	}else if($row[3]==3){
		$reasons3['pendienteInstalacion']=$row[6];
	}else if($row[3]==4){
		$reasons['completadoInstalacion']=$row[7]
	}
	$reasons3['fechaInicial']=$row[8];
	$reasons3['fechaFinal']=$row[9];
}


 function compareDate($reasons2,$reasons3){
 	foreach($reasons2 as $key => $value){
 		if($reasons2[$key]==$value){

 		}
 	}*/
//}
?>