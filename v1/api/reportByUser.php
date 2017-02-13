<?php

include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (isset($_POST["token"])) {
    $DB = new DAO();
    $conn = $DB->getConnect();

    $token = $_POST["token"];
    $token = base64_decode($token);

    list($id, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);

    $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
    $searchToken->bind_param('i', $id);

    if ($searchToken->execute()) {

        $searchToken->store_result();
        $searchToken->bind_result($userToken);

        if ($searchToken->fetch()) {
            if ($_POST["token"] == $userToken) {
                //var_dump($userToken);
                $params = "i";

                $reportsHistory = $conn->stmt_init();
                $reportsHistory->prepare("SELECT RP.id, RP.agreementNumber, RP.clientName, RP.colonia, RP.street, RP.betweenStreets, RP.nse,
                            RP.newStreet, RP.streets, RP.coloniaType, RP.marketType, RP.innerNumber, RP.outterNumber, RP.street, CNT.name, ST.name, 
                            CTY.name, RP.cp, UsEMP.nickname, RPT.name, UsCreator.nickname, RP.dot_latitude, RP.dot_longitude, STS.description
                        FROM report AS RP 
                        LEFT JOIN reportMultimedia AS RPM ON RPM.idReport = RP.id 
                        LEFT JOIN multimedia AS M ON RPM.idMultimedia = M.id
                        INNER JOIN reportType AS RPT ON RP.idReportType = RPT.id
                        INNER JOIN country AS CNT ON CNT.id = RP.idCountry
                        INNER JOIN state AS ST ON ST.id = RP.idState
                        INNER JOIN city AS CTY ON CTY.id = RP.idCity
                        INNER JOIN user AS UsEMP ON RP.idEmployee = UsEMP.id 
                        INNER JOIN user AS UsCreator ON RP.idUserCreator = UsCreator.id
                        INNER JOIN workflow_status_report AS WSR ON RP.id = WSR.idReport
                        LEFT JOIN status AS STS ON STS.id = WSR.idStatus
                        WHERE UsCreator.id = ?;");
                $reportsHistory->bind_param($params, $id);

                if ($reportsHistory->execute()) {
                    $reportsHistory->store_result();
                    $reportsHistory->bind_result($id, $agreementNumber, $clientName, $colonia, $street, $betweenStreets, $nse, $newStreet, $streets, $coloniaType, $marketType, $innerNumber, $outterNumber, $street, $country, $state, $city, $cp, $employeeNickname, $reportType, $creatorNickname, $dot_latitude, $dot_longitude, $status);

                    while ($reportsHistory->fetch()) {
                        $reportInfo["id"] = $id;
                        $reportInfo["agreementNumber"] = $agreementNumber;
                        $reportInfo["clientName"] = $clientName;
                        $reportInfo["colonia"] = $colonia;
                        $reportInfo["street"] = $street;
                        $reportInfo["betweenStreets"] = $betweenStreets;
                        $reportInfo["nse"] = $nse;
                        $reportInfo["newStreet"] = $newStreet;
                        $reportInfo["streets"] = $streets;
                        $reportInfo["coloniaType"] = $coloniaType;
                        $reportInfo["marketType"] = $marketType;
                        $reportInfo["innerNumber"] = $innerNumber;
                        $reportInfo["outterNumber"] = $outterNumber;
                        $reportInfo["street"] = $street;
                        $reportInfo["country"] = $country;
                        $reportInfo["state"] = $state;
                        $reportInfo["city"] = $city;
                        $reportInfo["cp"] = $cp;
                        $reportInfo["employeeNickname"] = $employeeNickname;
                        $reportInfo["reportType"] = $reportType;
                        $reportInfo["creatorNickname"] = $creatorNickname;
                        $reportInfo["dot_latitude"] = $dot_latitude;
                        $reportInfo["dot_longitude"] = $dot_longitude;
                        $reportInfo["dot_longitude"] = $dot_longitude;
                        $reportInfo["status"] = $status;
                        $reportsInfo[] = $reportInfo;
                    }

                    $response["status"] = "OK";
                    $response["code"] = "200";
                    $response["response"] = $reportsInfo;
                    echo json_encode($response);
                } else {
                    $response["status"] = "OK";
                    $response["code"] = "200";
                    $response["response"] = "Report Created";
                    $response["reportId"] = $insertReport->insert_id;
                    echo json_encode($response);
                }

                $reportsHistory->close();
            }
        }
    }
}