<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

if (isset($_POST['type'])) {
    $formType = $_POST['type'];
    $status = isset($_POST['status']);

    $returnData = [];
    $reports = [];

    if ($formType == "Censo") {
        $formType = 1;
        $getReports = $conn->prepare("SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = ? AND ST.id = ?
            ORDER BY RP.id DESC;");
        $getReports->bind_param("ii", $formType, $status);
        if ($getReports->execute()) {
            $getReports->store_result();
            $getReports->bind_result($id, $agreement, $reportType, $statusId, $statusDescription, $city, $colonia, $street, $emlopyee, $agency, $creationDate);
            while ($getReports->fetch()) {
                $returnData['id'] = $id;
                $returnData['Contrato'] = $agreement;
                $returnData['Tipo'] = $reportType;
                $returnData['idStatus'] = $statusId;
                $returnData['Status'] = $statusDescription;
                $returnData['Municipio'] = $city;
                $returnData['Colonia'] = $colonia;
                $returnData['Calle'] = $street;
                $returnData['Usuario'] = $emlopyee;
                $returnData['Agencia'] = $agency;
                $returnData['Fecha'] = $creationDate;
                $reports[] = $returnData;
            }
        }
        $getReports->free_result();
    } else if ($formType == "Venta") {
        $formType = 2;
        $getReports = $conn->prepare("SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = ? AND ST.id = ?
            ORDER BY RP.id DESC;");
        $getReports->bind_param("ii", $formType, $status);
        if ($getReports->execute()) {
            $getReports->store_result();
            $getReports->bind_result($id, $agreement, $reportType, $statusId, $statusDescription, $city, $colonia, $street, $emlopyee, $agency, $creationDate);
            while ($getReports->fetch()) {
                $returnData['id'] = $id;
                $returnData['Contrato'] = $agreement;
                $returnData['Tipo'] = $reportType;
                $returnData['idStatus'] = $statusId;
                $returnData['Status'] = $statusDescription;
                $returnData['Municipio'] = $city;
                $returnData['Colonia'] = $colonia;
                $returnData['Calle'] = $street;
                $returnData['Usuario'] = $emlopyee;
                $returnData['Agencia'] = $agency;
                $returnData['Fecha'] = $creationDate;
                $reports[] = $returnData;
            }
        }
        $getReports->free_result();
    } else if ($formType == "Plomero") {
        $formType = 3;
        $getReports = $conn->prepare("SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = ? AND ST.id = ?
            ORDER BY RP.id DESC;");
        $getReports->bind_param("ii", $formType, $status);
        if ($getReports->execute()) {
            $getReports->store_result();
            $getReports->bind_result($id, $agreement, $reportType, $statusId, $statusDescription, $city, $colonia, $street, $emlopyee, $agency, $creationDate);
            while ($getReports->fetch()) {
                $returnData['id'] = $id;
                $returnData['Contrato'] = $agreement;
                $returnData['Tipo'] = $reportType;
                $returnData['idStatus'] = $statusId;
                $returnData['Status'] = $statusDescription;
                $returnData['Municipio'] = $city;
                $returnData['Colonia'] = $colonia;
                $returnData['Calle'] = $street;
                $returnData['Usuario'] = $emlopyee;
                $returnData['Agencia'] = $agency;
                $returnData['Fecha'] = $creationDate;
                $reports[] = $returnData;
            }
        }
        $getReports->free_result();
    } else if ($formType == "Instalacion") {
        $formType = 4;
        $getReports = $conn->prepare("SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = ? AND ST.id = ?
            ORDER BY RP.id DESC;");
        $getReports->bind_param("ii", $formType, $status);
        if ($getReports->execute()) {
            $getReports->store_result();
            $getReports->bind_result($id, $agreement, $reportType, $statusId, $statusDescription, $city, $colonia, $street, $emlopyee, $agency, $creationDate);
            while ($getReports->fetch()) {
                $returnData['id'] = $id;
                $returnData['Contrato'] = $agreement;
                $returnData['Tipo'] = $reportType;
                $returnData['idStatus'] = $statusId;
                $returnData['Status'] = $statusDescription;
                $returnData['Municipio'] = $city;
                $returnData['Colonia'] = $colonia;
                $returnData['Calle'] = $street;
                $returnData['Usuario'] = $emlopyee;
                $returnData['Agencia'] = $agency;
                $returnData['Fecha'] = $creationDate;
                $reports[] = $returnData;
            }
        }
        $getReports->free_result();
    } else if ($formType == "Segunda Venta") {
        $formType = 5;
        $getReports = $conn->prepare("SELECT DISTINCT RP.id, RP.agreementNumber, RPT.name, ST.id, ST.description, RP.idCity, RP.colonia, RP.street, USEMP.nickname, USAG.nickname, DATE(RP.created_at)
            FROM workflow_status_report AS WSR
            INNER JOIN report AS RP ON WSR.idReport = RP.id
            INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
            INNER JOIN status AS ST ON WSR.idStatus = ST.id
            INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            INNER JOIN agency_employee AS AGEMP ON RP.idEmployee = AGEMP.idemployee
            INNER JOIN agency AS AG ON AGEMP.idAgency = AG.id
            INNER JOIN user AS USAG ON AG.idUser = USAG.id
            WHERE DATE(RP.created_at) BETWEEN (CURDATE() - INTERVAL 45 DAY) AND CURDATE() AND RPT.id = ? AND ST.id = ?
            ORDER BY RP.id DESC;");
        $getReports->bind_param("ii", $formType, $status);
        if ($getReports->execute()) {
            $getReports->store_result();
            $getReports->bind_result($id, $agreement, $reportType, $statusId, $statusDescription, $city, $colonia, $street, $emlopyee, $agency, $creationDate);
            while ($getReports->fetch()) {
                $returnData['id'] = $id;
                $returnData['Contrato'] = $agreement;
                $returnData['Tipo'] = $reportType;
                $returnData['idStatus'] = $statusId;
                $returnData['Status'] = $statusDescription;
                $returnData['Municipio'] = $city;
                $returnData['Colonia'] = $colonia;
                $returnData['Calle'] = $street;
                $returnData['Usuario'] = $emlopyee;
                $returnData['Agencia'] = $agency;
                $returnData['Fecha'] = $creationDate;
                $reports[] = $returnData;
            }
        }
        $getReports->free_result();
    }
}
echo json_encode($reports);