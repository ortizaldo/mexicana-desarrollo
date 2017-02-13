<?php 
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
if (isset($_POST["token"])) {
    $DB = new DAO();
    $conn = $DB->getConnect();
    $token =  $_POST["token"];
    $token = base64_decode($token);
    list($id, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
    //var_dump(intval($id));
    $idUser=intval($id);
    $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
    $searchToken->bind_param('i', $idUser);
    $searchToken->execute();
    $searchToken->store_result();
    $searchToken->bind_result($userToken);
    $searchToken->fetch();

    $searchIdEmployee = $conn->prepare("SELECT id FROM employee WHERE idUser = ?;");
    $searchIdEmployee->bind_param("i", $idUser);
    //$idEmployee;
    //var_dump($idEmployee);
    if ($searchIdEmployee->execute()){
        $searchIdEmployee->store_result();
        $searchIdEmployee->bind_result($idEmployee);
        if($searchIdEmployee->fetch()){
            $idEmployee;
        }
    }
    if (isset($userToken) && $userToken!= "") {
        
        if ($_POST["token"] == $userToken) {
            //Objeto donde se almacenaran los arreglos de reportes consultados
            $info = [];
            //Arreglo en el cual se guardaran los reportes referentes a las nuevas ventas o callejeros (report table)
            $sellsCallejero = [];
            //Arreglo en el cual se guardaran los reportes referentes a las nuevas tareas (task table)
            $tasksElems =  [];
            $requests =  [];
            $reasons = array();
            $rechazado = NULL;
            $iconta = 0;
            $status = NULL;
        
            $reportsPendingSQL = "
                SELECT 
                    DISTINCT(RPH.idReport)
                FROM report AS RP
                INNER JOIN employee AS EMP ON RP.idEmployee = EMP.id
                LEFT JOIN user AS UsEMP ON UsEMP.id = EMP.idUser
                LEFT JOIN reportHistory AS RPH ON RP.id = RPH.idReport
                LEFT JOIN form_installation AS FI ON FI.consecutive = RPH.idFormSell
                LEFT JOIN user AS UsCreator ON UsCreator.id = RPH.idUserAssigned
                INNER JOIN reportType AS RPT ON RPH.idReportType = RPT.id
                -- INNER JOIN tEstatusContrato as te ON te.idReporte = RP.id
                WHERE 0=0
                AND RPH.idUserAssigned = ?
                AND (FI.numInstalacionGen != '0' and FI.numInstalacionGen != '');";
            if ($reportsPending = $conn->prepare($reportsPendingSQL)) {
                $reportsPending->bind_param("i", $idUser);
                if ($reportsPending->execute()) {
                    //var_dump($reportsPending);
                    $reportsPending->store_result();
                    $reportsPending->bind_result($idReport);
                    while ($reportsPending->fetch()) {
                        //echo "rep ".$idReport;
                        $requests["idReport"] = $idReport;
                        $tasksElems[] = $requests;
                        $iconta++;
                    }//end while
                    $info["solicitudes"] = $tasksElems;
                } else {
                    $response["status"] = "BAD";
                    $response["code"] = "404";
                    $response["response"] = "No se encontraron callejeros";
                    echo json_encode($response);
                }//end if
                
                $requests = null;
                $response["status"] = "OK";
                $response["code"] = "200";
                $response["response"] = $info;
                echo json_encode($response);
                
            }else{
                $response["status"] = "ERROR";
                $response["code"] = "500";
                $response["response"] = "Error en BD - ".$conn->error;
                echo json_encode($response);
            }
            //$conn->close();
        } else {
            $response["status"] = "ERROR";
            $response["code"] = "404";
            $response["response"] = "Error en el token";
            echo json_encode($response);
        }
    }
}