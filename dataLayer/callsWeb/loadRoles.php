<?php 
include_once "../DAO.php";
session_start();

$DB = new DAO();
$conn = $DB->getConnect();
if (isset($_GET['agency'])) {
    $idAgency=$_GET['agency'];
    $returnData = []; $agency = [];
    $searchRolesSQL = "SELECT DISTINCT
                        (d.id) AS idProfile,
                        (SELECT 
                                name
                            FROM
                                profile
                            WHERE
                                id = idProfile) AS nameProfile
                    FROM
                        agency a,
                        agency_employee b,
                        employee c,
                        profile d,
                        user e
                    WHERE
                        0 = 0 AND a.id = b.idAgency
                            AND b.idemployee = c.id
                            AND d.id = c.idProfile
                            AND c.idUser = e.id
                            AND a.id = ?
                            AND e.nickname != 'Pendiente de Asignar';";
    if ($searchRoles = $conn->prepare($searchRolesSQL)) {
        $searchRoles->bind_param("i",$idAgency);
        if ($searchRoles->execute()) {
            $searchRoles->store_result();
            $searchRoles->bind_result($idProfile, $nameProfile);
            while ($searchRoles->fetch()) {
                $agency['idProfile'] = $idProfile;
                $agency['nameProfile'] = $nameProfile;
                $returnData[] = $agency;
            }
        }
    }
}
//$searchRoles->free_result();
echo json_encode($returnData);