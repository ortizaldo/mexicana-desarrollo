<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];
$reasons = [];

//$getReasons = "SELECT id, reason FROM rejected_reason;";
$getReasons ="SELECT RP.agreementNumber, RP.agreementNumber, STPH.name, FRMSLL.name, FRMPMB.name, RP.idCity, RP.coloniaType, RP.street, DATE(RP.created_at)
FROM report AS RP
INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
INNER JOIN agency_employee AS AGEMP ON EMP.id = AGEMP.idemployee
INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
INNER JOIN user AS USAG ON AG.idUser = USAG.id
INNER JOIN report_employee_form AS RPEMP ON RP.id = RPEMP.idReport
INNER JOIN form_plumber AS FRMPM ON RPEMP.idForm = FRMPM.id
INNER JOIN status AS STPH ON FRMPM.idStatus = STPH.id
INNER JOIN form_sells AS FRMSLL ON RPEMP.idForm = FRMSLL.id
INNER JOIN status AS STSLL ON FRMSLL.idStatus = STSLL.id
INNER JOIN form_installation AS FRMPMB ON RPEMP.idForm = FRMPMB.id
INNER JOIN status AS STINST ON FRMPM.idStatus = STINST.id;";

$result = $conn->query($getReasons);

while( $row = $result->fetch_array() ) {
    $reasons['Id'] = $row[0];
    $reasons['Contrato'] = $row[1];
    $reasons['Cliente'] = $row[3];
    $reasons['Ph'] = $row[4];
    $reasons['Venta'] = $row[5];
    $reasons['Instalacion'] = $row[6];
    $reasons['Municipio'] = $row[7];
    $reasons['Colonia'] = $row[8];
    $reasons['Calle'] = $row[9];
    $reasons['Usuario'] = $row[10];
    $reasons['Agencia'] = $row[11];
    $returnData[] = $reasons;
}

$result->free_result();
echo json_encode($returnData);
?>