<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
//consultamos el catalogo de vivienda
$catAnomaliasSQL = "SELECT idAnomalia, descAnomalia FROM catAnomalias;";
if ($catAnomalias = $conn->prepare($catAnomaliasSQL)) {
    //devolvemos la respuesta
    if ($catAnomalias->execute()) {
        $catAnomalias->store_result();
        $catAnomalias->bind_result($idAnomalia, $descAnomalia);
        $cont=0;
        while ($catAnomalias->fetch()) {
            $requests[$cont]["id"] = $idAnomalia;
            $requests[$cont]["desc"] = $descAnomalia;
            $cont++;
        }
        $info["respuesta"] = $requests;
        $requests = null;
        $response["status"] = "OK";
        $response["code"] = "200";
        $response["response"] = $info;
    }
}
echo json_encode($response);
$conn->close();