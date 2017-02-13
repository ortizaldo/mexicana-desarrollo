<?php require_once('../libs/nusoap_lib/nusoap.php');
include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$assignedCities = [];
$postData = [];

//$userNickname = $_POST['nickname'];
$id = $_POST['id'];

$clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
$nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
$nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";

$resultWsMunicipios = $nuSoapClientMexicana->call('ws_siscom_municipios', $postData);

if ($nuSoapClientMexicana->fault) {

} else {
    $err = $nuSoapClientMexicana->getError();
}
if ($err) {
    echo json_encode($err);
} else {
    $agencyCities = $conn->prepare("SELECT AG.id, AGCTY.idCity FROM agency_cities AS AGCTY INNER JOIN agency AS AG ON AGCTY.idAgency = AG.id INNER JOIN user AS US ON AG.idUser = US.id WHERE US.id = ?;");
    $agencyCities->bind_param("i", $id);
    if ($agencyCities->execute()) {
        $agencyCities->store_result();
        $agencyCities->bind_result($agencyId, $idCity);

        $assigned = [];
        while ($agencyCities->fetch()) {
            $assignedCities['agency'] = $agencyId;
            $assignedCities['city'] = $idCity;
            $assigned[] = $assignedCities;
        }
        $resultWsMunicipios['assigned'] = $assigned;
    }
    echo json_encode($resultWsMunicipios);
}
