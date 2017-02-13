<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
$catPlomSQL = "SELECT idCatPlom,idMedidor, tuberia FROM catPlomeria;";
if ($catPlom = $conn->prepare($catPlomSQL)) {
    //devolvemos la respuesta
    if ($catPlom->execute()) {
        $catPlom->store_result();
        $catPlom->bind_result($idCatPlom, $idMedidor, $tuberia);
        $cont=0;
        while ($catPlom->fetch()) {
            $requests[$cont]["id"] = $idCatPlom;
            $requests[$cont]["desc"] = $tuberia;
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