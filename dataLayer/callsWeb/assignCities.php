<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

if (isset($_POST['userAgency']) && isset($_POST['cities'])) {
    $agencyUser = $_POST['userAgency'];
    $cities = $_POST['cities'];

    $citiesList = explode('|', $cities);

    $getAdmins = $conn->prepare("SELECT AG.id, US.nickname FROM user AS US INNER JOIN agency AS AG ON AG.idUser = US.id WHERE US.id=?");
    $getAdmins->bind_param("i", $agencyUser);

    if ($getAdmins->execute()) {
        $getAdmins->store_result();
        $getAdmins->bind_result($id, $nickname);
        if ($getAdmins->fetch()) {
            $deleteCityAsigned = $conn->prepare("DELETE from agency_cities where idAgency = ?;");
            $deleteCityAsigned->bind_param("i", $id);
            $deleteCityAsigned->execute();

            foreach ($citiesList as $key) {
                $agenciesCities = $conn->prepare("INSERT INTO agency_cities(idAgency, idCity, updated_at) VALUES(?, ?, NOW());");
                $agenciesCities->bind_param("ii", $id, $key);
                $agenciesCities->execute();
            }

            $resultWsMunicipios['assigned'] = true;
            echo json_encode($resultWsMunicipios);
        }
    }
}
else{
    $resultWsMunicipios['assigned'] = false;
    echo json_encode($resultWsMunicipios);
}

