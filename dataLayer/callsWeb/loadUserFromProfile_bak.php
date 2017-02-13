<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$userData = []; $returnData = [];

if(isset($_POST["profile"]) && isset($_POST["agency"])) {

    $type = $_POST["profile"]; $type = intval($type);
    $agency = $_POST["agency"]; $agency = intval($agency);

    if ($type == 0) {
        //Todas las agencias
        //Todos los empleados
        //$type = "All";
    } else if ($type == 1) {
        $type = "Plomero"; $profile1 = 3; $profile2 = 6; $profile3 = 7; $profile4 = 8;
        /*var_dump($type);
        var_dump($agency);
        exit;*/
        /*
         * Busca empleados a cargo de la agencia de plomeria.
         */
        /***************************PLOMERIA************************************/
        $searchEmployeesMexicana = $conn->prepare("SELECT EMP.id, USEMP.id, USEMP.nickname
            FROM user AS USAG
            INNER JOIN agency AS AG ON USAG.id = AG.idUser
            INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency
            INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id
            INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            WHERE AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ?;");
        $searchEmployeesMexicana->bind_param("iiiiiiii", $agency, $profile1, $agency, $profile2, $agency, $profile3, $agency, $profile4);
        if ($searchEmployeesMexicana->execute()) {
            $searchEmployeesMexicana->store_result();
            $searchEmployeesMexicana->bind_result($employeeId, $employeeUserID, $nickname);
            while ($searchEmployeesMexicana->fetch()) {
                /*var_dump($employeeId);
                var_dump($employeeUserID);
                var_dump($nickname);*/
                $userData['employeeId'] = $employeeId;
                $userData['employeeUserID'] = $employeeUserID;
                $userData['nickname'] = $nickname;
                $returnData[] = $userData;
                //print_r($returnData);
            }
        }
        $searchEmployeesMexicana->free_result();
    } else if ($type == 2) {
        $type = "Venta";
        $profile1 = 2; $profile2 = 5; $profile3 = 6; $profile4 = 7; $profile5 = 8;
        /*
        * Busca empleados a cargo de la agencia de venta.
        */
        /***************************VENTA************************************/
        $searchEmployeesMexicana = $conn->prepare("SELECT EMP.id, USEMP.id, USEMP.nickname
            FROM user AS USAG
            INNER JOIN agency AS AG ON USAG.id = AG.idUser
            INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency
            INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id
            INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
            WHERE AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ?;");
        $searchEmployeesMexicana->bind_param("iiiiiiiiii", $agency, $profile1, $agency, $profile2, $agency, $profile3, $agency, $profile4, $agency, $profile5);
        if ($searchEmployeesMexicana->execute()) {
            $searchEmployeesMexicana->store_result();
            $searchEmployeesMexicana->bind_result($employeeId, $employeeUserID, $nickname);
            while ($searchEmployeesMexicana->fetch()) {
                $userData['employeeId'] = $employeeId;
                $userData['employeeUserID'] = $employeeUserID;
                $userData['nickname'] = $nickname;
                $returnData[] = $userData;
            }
        }
        $searchEmployeesMexicana->free_result();
    }

} else if(isset($_POST["profile"])) {
    $rol = 4;
    $type = $_POST["profile"];

    $type = intval($type);

    $agency = [];
    $returnData = [];

    if ($type == 0) {
        //Todas las agencias
        //Todos los empleados
        //$type = "All";
    } else if ($type == 1) {
        $type = "Plomero";
        $profile1 = 3; $profile2 = 6; $profile3 = 7; $profile4 = 8;
        //exit;
        /*
         * Busca Agencia encargada de plomeria.
         */
        /***************************PLOMERIA************************************/
        $searchAgency = $conn->prepare("SELECT DISTINCT AGEN.id, US.nickname
            FROM agency AS AGEN
            INNER JOIN agency_employee AS AGENE ON AGEN.id = AGENE.idAgency
            INNER JOIN employee AS EMP ON AGENE.idemployee = EMP.id
            INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
            INNER JOIN user AS US ON AGEN.idUser = US.id
            WHERE PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ?;");
        $searchAgency->bind_param("iiii", $profile1, $profile2, $profile3, $profile4);
        if ($searchAgency->execute()) {
            $searchAgency->store_result();
            $searchAgency->bind_result($agencyId, $nickname);
            while ($searchAgency->fetch()) {
                $userData['agencyId'] = $agencyId;
                $userData['nickname'] = $nickname;
                $returnData[] = $userData;
            }
        }
        $searchAgency->free_result();
    } else if ($type == 2) {
        $type = "Venta";
        $profile1 = 2; $profile2 = 5; $profile3 = 6; $profile4 = 7; $profile5 = 8;
        /*
         * Busca Agencia encargada de venta.
         */
        /***************************VENTA************************************/
        $searchAgency = $conn->prepare("SELECT DISTINCT AGEN.id, US.nickname
            FROM agency AS AGEN
            INNER JOIN agency_employee AS AGENE ON AGEN.id = AGENE.idAgency
            INNER JOIN employee AS EMP ON AGENE.idemployee = EMP.id
            INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
            INNER JOIN user AS US ON AGEN.idUser = US.id
            WHERE PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ?;");
        $searchAgency->bind_param("iiiii", $profile1, $profile2, $profile3, $profile4, $profile5);
        if ($searchAgency->execute()) {
            $searchAgency->store_result();
            $searchAgency->bind_result($agencyId, $nickname);
            while ($searchAgency->fetch()) {
                $userData['agencyId'] = $agencyId;
                $userData['nickname'] = $nickname;
                $returnData[] = $userData;
            }
        }
        $searchAgency->free_result();
    }
}
echo json_encode($returnData);