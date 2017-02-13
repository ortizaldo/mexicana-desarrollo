<?php include_once "../DAO.php";
include_once "../libs/utils.php";
session_start();

class reports
{

$DB = new DAO();
$conn = $DB->getConnect();

    var $reports = [];

    function __Construct()
    {
    }

public report()
{

}

public reports()
{

}

public assingReport($employeesID, $employee, $plumber, $idReport)
{
    $reassingReport = $conn->prepare("UPDATE report AS RP SET RP.employeesAssigned = ?, RP.idEmployee = ?, RP.idReportType = ? WHERE RP.id = ?;");
    $reassingReport->bind_param("siii", $employeesID, $employee, $plumber, $idReport);
    if ($reassingReport->execute()) {
        $response["status"] = "OK";
        $response["code"] = "200";
        $response["response"] = "Report sell created";
        echo json_encode($response);
    }
}

public assingToMexicana() {

}
//Busqueda del reporte por el parámetro idReport
/*
 * @Param idReport INT
 *
 * */
public createIdClient($idReport)
{
    //Validar si ya se realizó segunda venta, caso contrario asignarla.
    //BUSQUEDA DE CONTRATO PARA LA SOLICITUD
    $getAgreementNumber = $conn->prepare("SELECT AGR.idAgency, AGR.payment, AGR.idReport, AGR.requestDate, AGR.clientlastName, AGR.clientlastName2, AGR.clientName, AGR.clientRFC, AGR.clientCURP, AGR.clientEmail, AGR.clientRelationship, AGR.clientgender, AGR.clientIdNumber, AGR.identificationType, AGR.clientBirthDate, AGR.clientBirthCountry, AGR.idState, AGR.idCity, AGR.idColonia, AGR.street, AGR.inHome, AGR.homeTelephone, AGR.celullarTelephone, AGR.agreementType, AGR.price, AGR.agreementExpires, AGR.agreementMonthlyPayment, AGR.agreementRi, AGR.agreementRiDate, AGR.clientJobEnterprise, AGR.clientJobLocation, AGR.clientJobRange, AGR.clientJobActivity, AGR.clientJobTelephone, AGR.latitude, AGR.longitude, AGR.created_at, AGR.updated_at FROM report AS RP INNER JOIN agreement_employee_report AS AGEMP ON RP.id = AGEMP.idReport INNER JOIN agreement AS AGR ON AGEMP.idAgreement = AGR.id WHERE RP.id = ?;");
    $getAgreementNumber->bind_param("i", $idReport);
    if ($getAgreementNumber->execute()) {
        $getAgreementNumber->store_result();
        $getAgreementNumber->bind_result($idAgreement, $idAgency, $payment, $reportID, $requestDate, $clientlastName, $clientlastName2, $clientName, $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber, $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia, $street, $inHome, $homeTelephone, $celullarTelephone, $agreementType, $price, $agreementExpires, $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation, $clientJobRange, $clientJobActivity, $clientJobTelephone, $latitude, $longitude);
        if ($getAgreementNumber->fetch()) {
            $sell['tf_solcto_id'] = $reportID;
            $sell['tf_sucursal'] = null;
            $sell['tf_fecha_venta'] = null;
            $sell['tf_nombre'] = $clientName;
            $sell['tf_appaterno'] = $clientlastName;
            $sell['tf_apmaterno'] = $clientlastName2;
            $sell['tf_fnac'] = null;
            $sell['tf_rfc'] = null;
            $sell['tf_vendedor'] = null;
            $sell['tf_vivienda'] = null;
            $sell['tf_tiempo'] = null;
            $sell['tf_uni_tiempo'] = null;
            $sell['tf_nomemp'] = $clientJobEnterprise;
            $sell['tf_diremp'] = $clientJobLocation;
            $sell['tf_telemp'] = $clientJobTelephone;
            $sell['tf_cbtelemp'] = $clientJobActivity;
            $sell['tf_ptoemp'] = $clientJobRange;
            $sell['tf_actemp'] = null;
            $sell['tf_aparato'] = null;
            $sell['tf_causas'] = null;
            $sell['tf_dir_id'] = null;
            $sell['tf_ffirma'] = null;
            $sell['tf_fpago'] = null;
            $sell['tf_antsol'] = null;
            $sell['tf_mtsri'] = null;
            $sell['tf_tomasri'] = null;
            $sell['tf_fpbahr'] = null;
            $sell['tf_fredint'] = null;
            $sell['tf_email'] = null;
            $sell['tf_enviar_cfdi'] = null;
            $sell['tf_foliocto'] = null;
            $sell['tf_foliopag'] = null;
            $sell['tf_serie'] = null;
            $sell['tf_articulo'] = null;
            $sell['tf_financiar'] = null;
            $sell['tf_precio'] = null;
            $sell['tf_estufa'] = null;
            $sell['tf_boiler'] = null;
            $sell['tf_calentador'] = null;
            $sell['tf_tpocto'] = null;
            $sell['tf_tel1'] = $homeTelephone;
            $sell['tf_tel2'] = null;
            $sell['tf_tel3'] = null;
            $sell['tf_movil'] = $celullarTelephone;
            $sell['tf_ext2'] = null;
            $sell['tf_ext3'] = null;
            $sell['tf_nomrefer'] = null;
            $sell['tf_nomrefer2'] = null;
            $sell['tf_nomrefer3'] = null;
            $sell['tf_telTrab1'] = null;
            $sell['tf_telTrab2'] = null;
            $sell['tf_telTrab3'] = null;
            $sell['tf_cbTipoTelRef1'] = null;
            $sell['tf_cbTipoTelRef2'] = null;
            $sell['tf_cbTipoTelRef3'] = null;
            $sell['tf_telPart1'] = null;
            $sell['tf_telPart2'] = null;
            $sell['tf_telPart3'] = null;
            $sell['tf_cbTipoTelPartRef1'] = null;
            $sell['tf_cbTipoTelPartRef2'] = null;
            $sell['tf_cbTipoTelPartRef3'] = null;
            $sell['tf_pais'] = null;
            $sell['tf_docs'] = null;
            //$sellInfo[] = $sell;
            //print_r($sell);
            //exit;

            $it_solicitud = $sell;
            $ip_cia_id = 0;
            $ip_usr_id = $idUserCreator;
            $ip_contrato = $idAgreement;

            $clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
            $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);

            $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
            $nuSoapClientMexicana->decode_utf8 = false;

            $postData = array(
                'ip_cia_id' => $ip_cia_id,
                'ip_usr_id' => $ip_usr_id,
                'it_solicitud' => $it_solicitud,
                'ip_contrato' => $ip_contrato,
            );

            $AgreementResult = $nuSoapClientMexicana->call('ws_siscom_guarda_contrato', $postData);

            if ($nuSoapClientMexicana->fault) {

            } else {
                $err = $nuSoapClientMexicana->getError();
            }
            if ($err) {
                echo json_encode($err);
            } else {
                $instalation = 4;
                $employeesAssigned = "";
                $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ?;");
                $getEmployeeData->bind_param('i', $id);
                if ($getEmployeeData->execute()) {
                    $getEmployeeData->store_result();
                    $getEmployeeData->bind_result($id, $employee, $profileID, $profileName);
                    if ($getEmployeeData->fetch()) {
                        if ($profileID == 4 || $profileID == 8 && $reportType == 3) {
                            //Asignación de instalacion
                            $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                            $reassingReport->bind_param("iisi", $employee, $instalation, $employeesAssigned, $reportID);
                            $reassingReport->execute();
                        } else {
                            //ASIGNAR A AGENCIA QUE PERTENEZCA AL MUNICIPIO DONDE FUE CREADO EL REPORTE
                            $getReportCity = $conn->prepare("SELECT DISTINCT RP.`id`, RP.`idUserCreator`, RP.`idEmployee`, RP.`idCity` FROM report AS RP WHERE RP.id = ?;");
                            $getReportCity->bind_param("i", $idReport);
                            if ($getReportCity->execute()) {
                                $getReportCity->store_result();
                                $getReportCity->bind_result($reportID, $idUserCreator, $idEmployee, $city);
                                if ($getReportCity->fetch()) {
                                    //SELECCIONAR AGENCIA QUE TENGA EL MUNICIPIO ASIGNADO

                                    $getAgencyCity = $conn->prepare("SELECT DISTINCT ASCTY.id FROM agency_cities AS ASCTY WHERE ASCTY.idCity = ?;");
                                    $getAgencyCity->bind_param("i", $city);
                                    if ($getAgencyCity->execute()) {
                                        $getAgencyCity->store_result();
                                        $getAgencyCity->bind_result($agency);
                                        if ($getAgencyCity->fetch()) {
                                            $reassingReport = $conn->prepare("UPDATE report SET RP.idEmployee = ?, RP.idReportType = ?, RP.employeesAssigned = ? WHERE RP.`id` = ?;");
                                            $reassingReport->bind_param("iisi", $agency, $instalation, $employeesAssigned, $reportID);
                                            $reassingReport->execute();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $updateClientNumber = $conn->prepare("UPDATE report SET idAgreement = ? WHERE id = ?;");
                $updateClientNumber->bind_param("ii", intval($AgreementResult['ip_contrato']), $reportID);
                if ($updateClientNumber->execute()) {
                    $finish["status"] = "OK";
                    $finish["code"] = "200";
                    $finish["response"] = "Contrato Creado Exitosamente";
                    echo json_encode($finish);
                }
            }
        } else {
            //Asignacion a segunda venta
            $secondSell = 5;
            $getFinancialAgencyEmployees = $conn->prepare("UPDATE report SET idReportType = ? WHERE id = ?;");
            $getFinancialAgencyEmployees->bind_param("si", $secondSell, $reportID);
            $getFinancialAgencyEmployees->execute();

            $getFinancialAgencyEmployees = $conn->prepare("UPDATE workflow_status_report SET idStauts = ? WHERE idReport = ?;");
            $getFinancialAgencyEmployees->bind_param("si", $idStatus, $reportID);
            $getFinancialAgencyEmployees->execute();

            //refresh workflow for this shitttttt
            //workflow_status_report
        }
    }


}
