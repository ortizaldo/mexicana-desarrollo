<?php include_once "../DAO.php";

 session_start();
 $nickName = $_SESSION["nickname"];
    
 
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
        $type = "Plomero"; $profile1 = 3; $profile2 = 6; $profile3 = 7; $profile4 = 8; $profile5 = 9;
        $agencyNickname=$_GET['agencia'];
        $returnData = [];
        $getReportStatus = "SELECT 
                                a.id, a.nickname
                            FROM
                                user a,
                                employee b,
                                agency_employee c,
                                agency d,
                                profile e
                            WHERE
                            0 = 0 AND a.id = b.idUser
                            AND b.id = c.idemployee
                            AND c.idAgency = d.id
                            AND b.idProfile = e.id
                            AND e.name LIKE '%plomero%'
                            AND d.id IN (SELECT 
                                            a.id
                                         FROM
                                            agency a,
                                            user b
                                         WHERE
                                         a.idUser = b.id
                                         AND b.nickname LIKE '%".$nickName."%')
                            AND (a.nickname NOT IN ('enruta_test') AND a.nickname NOT IN ('enruta_test2') AND a.nickname NOT IN ('Pendiente de Asignar'));";

        $result = $conn->query($getReportStatus);
        $cont=0;
        while( $row = $result->fetch_array() ) {
            $returnData[$cont]['employeeUserID'] = $row[0];
            $returnData[$cont]['nickname'] = $row[1];
            $cont++;
            //$reports[] = $returnData;
        }
        $result->free_result();
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
            WHERE 
            
                AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ? OR AG.id = ? AND PRF.id = ?
                
                
            ;    
            ");
        $searchEmployeesMexicana->bind_param("iiiiiiiiii",$nickName,$nickName, $agency, $profile1, $agency, $profile2, $agency, $profile3, $agency, $profile4, $agency, $profile5);
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
            WHERE 
            
                (CASE WHEN ('SuperAdmin' = ? OR 'MexicanaDeGasAgencia' = ?) THEN 
                    PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ?
                ELSE
                    US.nickname = ?
                END)
                
            ;
            ");
        $searchAgency->bind_param("ssiiiis", $nickName, $nickName, $profile1, $profile2, $profile3, $profile4, $nickName);
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
            WHERE 
                
                (CASE WHEN ('SuperAdmin' = ? OR 'MexicanaDeGasAgencia' = ?) THEN 
                    PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ?
                ELSE
                    US.nickname = ?
                END)
                ;");
        $searchAgency->bind_param("ssiiiiis",$nickName,$nickName, $profile1, $profile2, $profile3, $profile4, $profile5,$nickName);
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