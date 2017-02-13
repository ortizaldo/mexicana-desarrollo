<?php 
include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
$idFI=$_GET['idFI'];
$catInstSQL = "SELECT id,material,qty FROM form_installation_details where idFormInstallation=?;";
if ($catInst = $conn->prepare($catInstSQL)) {
    //devolvemos la respuesta
    $catInst->bind_param("i", $idFI);
    if ($catInst->execute()) {
        $catInst->store_result();
        $catInst->bind_result($id,$material, $qty);
        //var_dump($catInst);
        $cont=0;
        while ($catInst->fetch()) {
            $requests[$cont]["id"] = $id;
            $requests[$cont]["material"] = $material;
            $requests[$cont]["qty"] = $qty;
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