<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
//consultamos el catalogo de vivienda
$DB = new DAO();
$conn = $DB->getConnect();
$catVivCensoSQL = "SELECT idCatCenso, nivelVivienda FROM catCenso;";
if ($catVivCenso = $conn->prepare($catVivCensoSQL)) {
    //devolvemos la respuesta
    if ($catVivCenso->execute()) {
        $catVivCenso->store_result();
        $catVivCenso->bind_result($idCatCenso, $nivelVivienda);
        $cont=0;
        while ($catVivCenso->fetch()) {
            $requests[$cont]["id"] = $idCatCenso;
            $requests[$cont]["desc"] = $nivelVivienda;
            $cont++;
        }
    }
    $info["respuesta"] = $requests;
    $requests = null;
    $response["status"] = "OK";
    $response["code"] = "200";
    $response["response"] = $info;
}
echo json_encode($response);