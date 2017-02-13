<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
$catMotivosSQL = "SELECT idMotivo, motivo FROM catMotivosDesinteres;";
if ($catMotivos = $conn->prepare($catMotivosSQL)) {
    //devolvemos la respuesta
    if ($catMotivos->execute()) {
        $catMotivos->store_result();
        $catMotivos->bind_result($idMotivo, $motivo);
        $cont=0;
        while ($catMotivos->fetch()) {
            $requests[$cont]["id"] = $idMotivo;
            $requests[$cont]["desc"] = $motivo;
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