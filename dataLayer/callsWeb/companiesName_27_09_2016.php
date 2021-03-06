<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = []; $agency = [];
$profile1 = 2; $profile2 = 5; $profile3 = 6; $profile4 = 7; $profile5 = 8;
//$type = "Venta";
/*
 * Busca Agencia encargada de venta.
 */
/***************************VENTA************************************/
$searchAgency = $conn->prepare("SELECT DISTINCT AGEN.id, US.nickname, US.id
            FROM agency AS AGEN
            INNER JOIN agency_employee AS AGENE ON AGEN.id = AGENE.idAgency
            INNER JOIN employee AS EMP ON AGENE.idemployee = EMP.id
            INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
            INNER JOIN user AS US ON AGEN.idUser = US.id
            WHERE PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ? OR PRF.id = ?;");
$searchAgency->bind_param("iiiii", $profile1, $profile2, $profile3, $profile4, $profile5);
if ($searchAgency->execute()) {
    $searchAgency->store_result();
    $searchAgency->bind_result($agencyId, $nickname, $userId);
    while ($searchAgency->fetch()) {
        $agency['id'] = $agencyId;
        $agency['agency'] = $nickname;
        $agency['idUser'] = $userId;
        $returnData[] = $agency;
    }
}
$searchAgency->free_result();
echo json_encode($returnData);