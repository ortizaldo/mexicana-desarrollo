<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

if (isset($_POST['type'])) {
    $formType = $_POST['type'];

    $returnData = [];
    $reports = [];

    if ($formType == "Censo") {
        $getReports = "SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = 1
            ORDER BY RP.id DESC;";
        $result = $conn->query($getReports);

        while ($row = $result->fetch_array()) {
            $returnData['Id'] = $row[0];
            $returnData['Contrato'] = $row[1];
            $returnData['Tipo'] = $row[2];
            $returnData['idStatus'] = $row[3];
            $returnData['Status'] = $row[4];
            $returnData['Municipio'] = $row[5];
            $returnData['Colonia'] = $row[6];
            $returnData['Calle'] = $row[7];
            $returnData['Usuario'] = $row[8];
            $returnData['Agencia'] = $row[9];
            $returnData['Fecha'] = $row[10];

            $reports[] = $returnData;
        }
        $result->free_result();
    } else if ($formType == "Venta") {
        $getReports = "SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = 2
            ORDER BY RP.id DESC;";
        $result = $conn->query($getReports);

        while( $row = $result->fetch_array() ) {
            $returnData['Id'] = $row[0];
            $returnData['Contrato'] = $row[1];
            $returnData['Tipo'] = $row[2];
            $returnData['idStatus'] = $row[3];
            $returnData['Status'] = $row[4];
            $returnData['Municipio'] = $row[5];
            $returnData['Colonia'] = $row[6];
            $returnData['Calle'] = $row[7];
            $returnData['Usuario'] = $row[8];
            $returnData['Agencia'] = $row[9];
            $returnData['Fecha'] = $row[10];

            $reports[] = $returnData;
        }
        $result->free_result();
    } else if ($formType == "Plomero") {
        $getReports = "SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = 3
            ORDER BY RP.id DESC;";
        $result = $conn->query($getReports);

        while( $row = $result->fetch_array() ) {
            $returnData['Id'] = $row[0];
            $returnData['Contrato'] = $row[1];
            $returnData['Tipo'] = $row[2];
            $returnData['idStatus'] = $row[3];
            $returnData['Status'] = $row[4];
            $returnData['Municipio'] = $row[5];
            $returnData['Colonia'] = $row[6];
            $returnData['Calle'] = $row[7];
            $returnData['Usuario'] = $row[8];
            $returnData['Agencia'] = $row[9];
            $returnData['Fecha'] = $row[10];

            $reports[] = $returnData;
        }
        $result->free_result();
    } else if ($formType == "Instalacion") {
        $getReports = "SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = 3
            ORDER BY RP.id DESC;";
        $result = $conn->query($getReports);

        while( $row = $result->fetch_array() ) {
            $returnData['Id'] = $row[0];
            $returnData['Contrato'] = $row[1];
            $returnData['Tipo'] = $row[2];
            $returnData['idStatus'] = $row[3];
            $returnData['Status'] = $row[4];
            $returnData['Municipio'] = $row[5];
            $returnData['Colonia'] = $row[6];
            $returnData['Calle'] = $row[7];
            $returnData['Usuario'] = $row[8];
            $returnData['Agencia'] = $row[9];
            $returnData['Fecha'] = $row[10];

            $reports[] = $returnData;
        }
        $result->free_result();
    } else if ($formType == "Segunda Venta") {
        $getagreements = "SELECT DISTINCT AGRP.idReport, 'Agreement Number', 'Segunda Venta', STATS.id, STATS.description, CTY.name, COL.name, AG.street, 'Employee Name', US.nickname, DATE(AG.created_at)
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
            WHERE AGRP.created_at BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE()
            ORDER BY AGRP.id DESC;";
        $result = $conn->query($getagreements);

        while( $row = $result->fetch_array() ) {
            $returnData['Id'] = $row[0];
            $returnData['Contrato'] = $row[1];
            $returnData['Tipo'] = $row[2];
            $returnData['idStatus'] = $row[3];
            $returnData['Status'] = $row[4];
            $returnData['Municipio'] = $row[5];
            $returnData['Colonia'] = $row[6];
            $returnData['Calle'] = $row[7];
            $returnData['Usuario'] = $row[8];
            $returnData['Agencia'] = $row[9];
            $returnData['Fecha'] = $row[10];
            $reports[] = $returnData;
        }
        $result->free_result();
    }
}
echo json_encode($reports);