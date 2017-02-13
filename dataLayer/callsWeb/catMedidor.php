<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
$catInstArr =  [];
//consultamos el catalogo de vivienda
$catInstSQL = "SELECT idMedidor, medidorDesc FROM catMedidor;";
if ($catInst = $conn->prepare($catInstSQL)) {
    //devolvemos la respuesta
    if ($catInst->execute()) {
        $catInst->store_result();
        $catInst->bind_result($idMedidor,$medidorDesc);
        //var_dump($catInst);
        $cont=0;
        while ($catInst->fetch()) {
            $requests[$cont]["id"] = $idMedidor;
            $requests[$cont]["desc"] = $medidorDesc;
            $cont++;
        }
        $info["respuesta"] = $requests;
        $requests = null;
        $response["status"] = "OK";
        $response["code"] = "200";
        $response["response"] = $info;
    }
}else{
    echo "error ".$conn->error;
}
echo json_encode($response);
$conn->close();