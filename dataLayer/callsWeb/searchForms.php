<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

/*var_dump($_POST['limit']);
var_dump($_POST['type']);
var_dump($_POST['status']);
var_dump($_POST['dateFrom']);
var_dump($_POST['dateTo']);*/

if(isset($_POST['limit']) && isset($_POST['type']) && isset($_POST['status']) && isset($_POST['dateFrom']) && isset($_POST['dateTo'])) {
    $limit = $_POST['limit'];
    $limit = intval($limit);
    $type = $_POST['type'];
    $type = intval($type);
    $status = $_POST['status'];
    $status = intval($status);
    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];

    $returnData = [];
    $reports = [];

    /*if( $status == 0 ) {

    }*/

    if( $type == 0 ) {

        //SELECT ALL FORMS

        //Get status
        // IF all status get all documents, else validate.


        $searchForms = $conn->prepare("SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, city.name, RP.colonia, RP.street+RP.outterNumber, usEMP.name, USAG.nickname, DATE(RP.created_at)
FROM report AS RP
LEFT JOIN agency_employee AS AGEMP ON AGEMP.idemployee = RP.idEmployee LEFT JOIN employee AS EMP ON EMP.id = AGEMP.idemployee
LEFT JOIN user AS usEMP ON usEMP.id = EMP.idUser
INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id LEFT JOIN user AS USAG ON USAG.id = AG.idUser
INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id INNER JOIN workflow_status_report AS WSR ON RP.id = WSR.idReport
LEFT JOIN status AS ST ON ST.id = WSR.idStatus
INNER JOIN state ON RP.idState = state.id INNER JOIN city ON RP.idCity = city.id WHERE DATE(RP.created_at) >= ? AND DATE(RP.created_at) <= ?
ORDER BY RP.id ASC LIMIT ?;");
        $searchForms->bind_param("ssi", $dateFrom, $dateTo, $limit);
        if ($searchForms->execute()) {
            $searchForms->store_result();
            $searchForms->bind_result($reportId, $agreementNumber, $reportType, $status, $statusDescription, $city, $colonia, $street, $employee, $agency, $createdDate);
            while ($searchForms->fetch()) {
                /*$returnData['id'] = $reportId;
                $returnData['agreementNumber'] = $agreementNumber;
                $returnData['reportType'] = $reportType;
                $returnData['status'] = $status;
                $returnData['statusDescription'] = $statusDescription;
                $returnData['city'] = $city;
                $returnData['colonia'] = $colonia;
                $returnData['street'] = $street;
                $returnData['employee'] = $employee;
                $returnData['agency'] = $agency;
                $returnData['createdDate'] = $createdDate;*/

                $returnData['Id'] = $reportId;
                $returnData['Contrato'] = $agreementNumber;
                $returnData['Tipo'] = $reportType;
                $returnData['idStatus'] = $status;
                $returnData['Status'] = $statusDescription;
                $returnData['Municipio'] = $city;
                $returnData['Colonia'] = $colonia;
                $returnData['Calle'] = $street;
                $returnData['Usuario'] = $employee;
                $returnData['Agencia'] = $agency;
                $returnData['Fecha'] = $createdDate;

                $reports[] = $returnData;
            }
        } else {
            $reports["ERROR"] = true;
            $reports["CODE"] = 500;
            $reports["RESPONSE"] = $formSearchQuery->error();
        }
        $searchForms->free_result();




    } else if ( $type > 0 && $type < 5) {
         $searchForms = $conn->prepare("SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, city.name, RP.colonia, RP.street+RP.outterNumber, usEMP.name, USAG.nickname, DATE(RP.created_at)
FROM report AS RP
LEFT JOIN agency_employee AS AGEMP ON AGEMP.idemployee = RP.idEmployee LEFT JOIN employee AS EMP ON EMP.id = AGEMP.idemployee
LEFT JOIN user AS usEMP ON usEMP.id = EMP.idUser
INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id LEFT JOIN user AS USAG ON USAG.id = AG.idUser
INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id INNER JOIN workflow_status_report AS WSR ON RP.id = WSR.idReport
LEFT JOIN status AS ST ON ST.id = WSR.idStatus
INNER JOIN state ON RP.idState = state.id INNER JOIN city ON RP.idCity = city.id WHERE RPT.id = ? AND ST.id = ? AND DATE(RP.created_at) >= ? AND DATE(RP.created_at) <= ?
ORDER BY RP.id ASC LIMIT ?;");
        $searchForms->bind_param("iissi", $type, $status, $dateFrom, $dateTo, $limit);
        if ($searchForms->execute()) {
            $searchForms->store_result();
            $searchForms->bind_result($reportId, $agreementNumber, $reportType, $status, $statusDescription, $city, $colonia, $street, $employee, $agency, $createdDate);
            while ($searchForms->fetch()) {
                /*$returnData['id'] = $reportId;
                $returnData['agreementNumber'] = $agreementNumber;
                $returnData['reportType'] = $reportType;
                $returnData['status'] = $status;
                $returnData['statusDescription'] = $statusDescription;
                $returnData['city'] = $city;
                $returnData['colonia'] = $colonia;
                $returnData['street'] = $street;
                $returnData['employee'] = $employee;
                $returnData['agency'] = $agency;
                $returnData['createdDate'] = $createdDate;*/

                $returnData['Id'] = $reportId;
                $returnData['Contrato'] = $agreementNumber;
                $returnData['Tipo'] = $reportType;
                $returnData['idStatus'] = $status;
                $returnData['Status'] = $statusDescription;
                $returnData['Municipio'] = $city;
                $returnData['Colonia'] = $colonia;
                $returnData['Calle'] = $street;
                $returnData['Usuario'] = $employee;
                $returnData['Agencia'] = $agency;
                $returnData['Fecha'] = $createdDate;

                $reports[] = $returnData;
            }
        } else {
            $reports["ERROR"] = true;
            $reports["CODE"] = 500;
            $reports["RESPONSE"] = $formSearchQuery->error();
        }
        $searchForms->free_result();
    } else if ($type == 5) {
            $formSearchQuery = $conn->prepare("SELECT DISTINCT AGRP.idReport, 'Agreement Number', 'Segunda Venta', STATS.description, CTY.name, COL.name, AG.street, 'Employee Name', US.nickname, DATE(AG.created_at)
                FROM agreement_employee_report AS AGRP
                LEFT JOIN agreement AS AG ON AG.id = AGRP.idAgreement
                LEFT JOIN agency AS AGE ON AGE.id = AG.idAgency
                LEFT JOIN user AS US ON US.id = AGE.idUser
                INNER JOIN city AS CTY ON AG.idCity = CTY.id
                INNER JOIN Colonia AS COL ON AG.idColonia = COL.id
                INNER JOIN state AS ST ON AG.idState = ST.id
                INNER JOIN agreement_reference AS AGREF ON AG.id = AGREF.idAgreement
                LEFT JOIN workflow_status_agreement AS WFLAG ON WFLAG.idAgreement = AG.id
                LEFT JOIN status AS STATS ON STATS.id = WFLAG.idStatus
                INNER JOIN workflow AS WFL ON WFLAG.idWorkflow = WFL.id
                WHERE STATS.id = ? AND DATE(AGRP.created_at) >= ? AND DATE(AGRP.created_at) <= ?
                ORDER BY RP.id ASC;");
            $formSearchQuery->bind_param("issi", $status, $dateFrom, $dateTo, $limit);
            if ($searchForms->execute()) {
                $searchForms->store_result();
                $searchForms->bind_result($reportId, $agreementNumber, $reportType, $statusDescription, $city, $colonia, $street, $employee, $agency, $createdDate);
                while ($searchForms->fetch()) {
                    /*$returnData['id'] = $reportId;
                    $returnData['agreementNumber'] = $agreementNumber;
                    $returnData['reportType'] = $reportType;
                    $returnData['statusDescription'] = $statusDescription;
                    $returnData['city'] = $city;
                    $returnData['colonia'] = $colonia;
                    $returnData['street'] = $street;
                    $returnData['employee'] = $employee;
                    $returnData['agency'] = $agency;
                    $returnData['createdDate'] = $createdDate;*/

                    $returnData['Id'] = $reportId;
                    $returnData['Contrato'] = $agreementNumber;
                    $returnData['Tipo'] = $reportType;
                    $returnData['idStatus'] = $status;
                    $returnData['Status'] = $statusDescription;
                    $returnData['Municipio'] = $city;
                    $returnData['Colonia'] = $colonia;
                    $returnData['Calle'] = $street;
                    $returnData['Usuario'] = $employee;
                    $returnData['Agencia'] = $agency;
                    $returnData['Fecha'] = $createdDate;

                    $reports[] = $returnData;
                }
            } else {
                $reports["ERROR"] = true;
                $reports["CODE"] = 500;
                $reports["RESPONSE"] = $formSearchQuery->error();
            }
        $searchForms->free_result();
    }
    echo json_encode($reports);
}

/*$getReports = "SELECT DISTINCT REF.idReport, RP.agreementNumber, RPT.name, ST.id, ST.description,
  city.name, RP.colonia, RP.street+outterNumber, usEMP.name, USAG.nickname, DATE(RP.created_at)
    FROM report_employee_form AS REF
    LEFT JOIN report AS RP ON RP.id = REF.idReport
    LEFT JOIN user AS US ON US.id = RP.idEmployee
    INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
    INNER JOIN workflow_status_report AS WSR ON RP.id = WSR.idReport
    LEFT JOIN status AS ST ON ST.id = WSR.idStatus
    INNER JOIN state ON RP.idState = state.id
    INNER JOIN city ON RP.idCity = city.id
    INNER JOIN user AS usEMP ON RP.idEmployee = usEMP.id
    LEFT JOIN agency_employee AS AGEMP ON AGEMP.idemployee = usEMP.id
    LEFT JOIN agency AS AG ON AG.id = AGEMP.idAgency
    LEFT JOIN user AS USAG ON USAG.id = AG.idUser
    ORDER BY REF.idReport DESC;";*/

/*$getagreements = "SELECT AG.id, RP.agreementNumber, 'Segunda Venta', STATS.description, CTY.name, COL.name, AG.street, USEMP.nickname, US.nickname, DATE(AG.created_at)
    FROM agreement_employee_report AS AGRP
    LEFT JOIN agreement AS AG ON AG.id = AGRP.idAgreement
    LEFT JOIN agency AS AGE ON AGE.id = AG.idAgency
    LEFT JOIN user AS US ON US.id = AGE.idUser
    INNER JOIN city AS CTY ON AG.idCity = CTY.id
    INNER JOIN Colonia AS COL ON AG.idColonia = COL.id
    INNER JOIN state AS ST ON AG.idState = ST.id
    INNER JOIN agreement_reference AS AGREF ON AG.id = AGREF.idAgreement
    LEFT JOIN workflow_status_agreement AS WFLAG ON WFLAG.idAgreement = AG.id
    LEFT JOIN status AS STATS ON STATS.id = WFLAG.idStatus
    INNER JOIN workflow AS WFL ON WFLAG.idWorkflow = WFL.id
    INNER JOIN agency_employee AS AGEMP ON AGE.id = AGEMP.idAgency
    INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id
    LEFT JOIN user AS USEMP ON USEMP.id = EMP.idUser
    INNER JOIN report AS RP ON AGRP.idReport = RP.id;";*/