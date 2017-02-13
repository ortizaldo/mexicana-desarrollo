<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$username; $agencyID; $data = [];

if (isset($_POST['agency']) && isset($_POST['role'])) {
    $agencyID = $_POST['agency']; $rolID = $_POST['role'];
    if ($rolID == "Venta" ) {
        $profile1 = 2; $profile2 = 5; $profile3 = 6; $profile4 = 7; $profile5 = 8;
        $getEmployeesByAgency = $conn->prepare("SELECT US.id AS id, US.nickname AS employee, AG.tipo as agencyType, LOWER(PF.name) as profile
                                            FROM user AS US
                                            INNER JOIN employee AS EMP ON US.id = EMP.idUser
                                            INNER JOIN agency_employee AS AGEM ON EMP.id = AGEM.idemployee
                                            INNER JOIN agency AS AG ON AGEM.idAgency = AG.id
                                            INNER JOIN profile AS PF ON EMP.idProfile = PF.id
                                            WHERE AG.id = ? AND PF.id = ? OR AG.id = ? AND PF.id = ? OR AG.id = ? AND PF.id = ? OR AG.id = ? AND PF.id = ? OR AG.id = ? AND PF.id = ?
                                            ORDER BY AG.idUser;");
        $getEmployeesByAgency->bind_param("iiiiiiiiii", $agencyID, $profile1, $agencyID, $profile2, $agencyID, $profile3, $agencyID, $profile4, $agencyID, $profile5);
        $getEmployeesByAgency->store_result();
        $getEmployeesByAgency->bind_result($id, $employee,$agencyType, $profile);
        if ($getEmployeesByAgency->execute()) {
            while ($getEmployeesByAgency->fetch()) {
                $users['id'] = $id;
                $users['employee'] = $employee;
                $users['agencyType']=  $agencyType;
                $users['profile']=$profile;
                $data[] = $users;
            }
            echo json_encode($data);
        }
    } else {
        $getEmployeesByAgency = $conn->prepare("SELECT US.id AS id, US.nickname AS employee,AG.tipo as agencyType, LOWER(PF.name) as profile
                                            FROM user AS US
                                            INNER JOIN employee AS EMP ON US.id = EMP.idUser
                                            INNER JOIN agency_employee AS AGEM ON EMP.id = AGEM.idemployee
                                            INNER JOIN agency AS AG ON AGEM.idAgency = AG.id
                                            INNER JOIN profile AS PF ON EMP.idProfile = PF.id
                                            WHERE AG.id = ? AND PF.id = ? ORDER BY AG.idUser;");
        $getEmployeesByAgency->bind_param("ii", $agencyID, $rolID);
        $getEmployeesByAgency->store_result();
        $getEmployeesByAgency->bind_result($id, $employee,$agencyType,$profile);
        if ($getEmployeesByAgency->execute()) {
            while ($getEmployeesByAgency->fetch()) {
                $users['id'] = $id;
                $users['employee'] = $employee;
                $users['agencyType']=  $agencyType;
                $users['profile']=$profile;

                $data[] = $users;
            }
            echo json_encode($data);
        }
    }
} else if (isset($_POST['agency'])) {
    $agencyID = $_POST['agency'];

    $getEmployeesByAgency = $conn->prepare("SELECT US.id AS id, US.nickname AS employee,AG.tipo as agencyType, LOWER(PF.name) as profile
                                            FROM user AS US
                                            INNER JOIN employee AS EMP ON US.id = EMP.idUser
                                            INNER JOIN agency_employee AS AGEM ON EMP.id = AGEM.idemployee
                                            INNER JOIN agency AS AG ON AGEM.idAgency = AG.id
                                            INNER JOIN profile AS PF ON EMP.idProfile = PF.id
                                            WHERE AG.id = ? ORDER BY AG.id;");
/*SELECT US.id AS id, US.nickname AS employee
FROM user AS US
LEFT JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id
LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
INNER JOIN profile AS PF ON PF.id = EM.idProfile WHERE US.id <> AGEM.idAgency AND AG.id = ? ORDER BY AG.id;");*/
    $getEmployeesByAgency->bind_param("i", $agencyID);
    $getEmployeesByAgency->store_result();
    $getEmployeesByAgency->bind_result($id, $employee,$agencyType, $profile);

    if ($getEmployeesByAgency->execute()) {
        while ($getEmployeesByAgency->fetch()) {
            $users['id'] = $id;
            $users['employee'] = $employee;
            $users['agencyType']=  $agencyType;
            $users['profile']=$profile;

            $data[] = $users;
        }
        echo json_encode($data);
    }
}