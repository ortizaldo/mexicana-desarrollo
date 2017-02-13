<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];
$reasons = [];

/*$getReasons ="SELECT RP.agreementNumber, USAG.nickname, USAGADMIN.nickname, USADMIN.name + USADMIN.lastName, RJRSN.reason
FROM report AS RP
INNER JOIN report_employee_form AS RPEMP ON RP.id = RPEMP.idReport
INNER JOIN form_sells AS FRMS ON RPEMP.idForm = FRMS.id
INNER JOIN form_sells_validation AS FRMVAL ON FRMS.id = FRMVAL.idFormSell 
INNER JOIN form_sell_rejected_reasons AS FRMSRJ ON FRMS.id = FRMSRJ.idFormSell
INNER JOIN rejected_reason AS RJRSN ON FRMSRJ.idRejectedReasons = RJRSN.id
INNER JOIN employee AS EMP ON RPEMP.idEmployee = EMP.id
INNER JOIN agency_employee AS AGEMP ON EMP.id = AGEMP.idemployee
INNER JOIN agency AS AGEN ON AGEMP.idAgency = AGEN.id
INNER JOIN agency_profile AS AGPROF ON AGEN.id = AGPROF.idAgency
INNER JOIN user AS USAG ON AGEN.idUser = USAG.id
INNER JOIN user AS USADMIN ON FRMVAL.idUser = USADMIN.id
INNER JOIN employee AS EMPADMIN ON USADMIN.id = EMPADMIN.idUser
INNER JOIN agency_employee AS AGEMPADMIN ON EMPADMIN.id = AGEMPADMIN.idemployee
INNER JOIN agency AS AGADMIN ON AGEMPADMIN.idAgency = AGADMIN.id
INNER JOIN user AS USAGADMIN ON AGADMIN.idUser = USAGADMIN.id;";*/

/*$getReasons = "SELECT RP.agreementNumber, USAG.nickname, USADMIN.nickname, CONCAT(USADMIN.name, ' ', USADMIN.lastName), RJRSN.reason
FROM report AS RP
INNER JOIN report_employee_form AS RPEMP ON RP.id = RPEMP.idReport
INNER JOIN form_sells AS FRMS ON RPEMP.idForm = FRMS.id
INNER JOIN form_sells_validation AS FRMVAL ON FRMS.id = FRMVAL.idFormSell 
INNER JOIN form_sell_rejected_reasons AS FRMSRJ ON FRMS.id = FRMSRJ.idFormSell
INNER JOIN rejected_reason AS RJRSN ON FRMSRJ.idRejectedReasons = RJRSN.id
INNER JOIN employee AS EMP ON RPEMP.idEmployee = EMP.id
INNER JOIN agency_employee AS AGEMP ON EMP.id = AGEMP.idemployee
INNER JOIN agency AS AGEN ON AGEMP.idAgency = AGEN.id
INNER JOIN agency_profile AS AGPROF ON AGEN.id = AGPROF.idAgency
INNER JOIN user AS USAG ON AGEN.idUser = USAG.id
INNER JOIN user AS USADMIN ON FRMVAL.idUser = USADMIN.id;";*/

$getReasons = "SELECT RP.agreementNumber, USAG.nickname, (SELECT user.nickname FROM user WHERE AGEN.idUser = USEMP.id) AS agencies, CONCAT(USEMP.name, ' ', USEMP.lastName) , RJRSN.reason
FROM report AS RP
INNER JOIN report_employee_form AS RPEMP ON RP.id = RPEMP.idReport
INNER JOIN form_sells AS FRMS ON RPEMP.idForm = FRMS.id
INNER JOIN form_sells_validation AS FRMVAL ON FRMS.id = FRMVAL.idFormSell 
INNER JOIN form_sell_rejected_reasons AS FRMSRJ ON FRMS.id = FRMSRJ.idFormSell
INNER JOIN rejected_reason AS RJRSN ON FRMSRJ.idRejectedReasons = RJRSN.id
INNER JOIN employee AS EMP ON RPEMP.idEmployee = EMP.id
INNER JOIN agency_employee AS AGEMP ON EMP.id = AGEMP.idemployee
INNER JOIN agency AS AGEN ON AGEMP.idAgency = AGEN.id
INNER JOIN user AS USAG ON AGEN.idUser = USAG.id
INNER JOIN user AS USEMP ON FRMVAL.idUser = USEMP.id;";

$result = $conn->query($getReasons);

while( $row = $result->fetch_array() ) {
    $reasons['Contrato'] = $row[0];
    $reasons['RechazadoUno'] = $row[1];
    $reasons['RechazadoDos'] = $row[2];
    $reasons['Empleado'] = $row[3];
    $reasons['Motivo'] = $row[4];
    $returnData[] = $reasons;
}

$result->free_result();
echo json_encode($returnData);
?>