<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
//consultamos el catalogo de vivienda
$catDNLaboralesSQL = "SELECT id, dia, descripcion FROM diasNoLaborales;";
if ($catDNLaborales = $conn->prepare($catDNLaboralesSQL)) {
    //devolvemos la respuesta
    if ($catDNLaborales->execute()) {
        $catDNLaborales->store_result();
        $catDNLaborales->bind_result($id, $dia, $descripcion);
        $cont=0;
        while ($catDNLaborales->fetch()) {
            $requests[$cont]["id"] = $id;
            $requests[$cont]["dia"] = $dia;
            $requests[$cont]["desc"] = $descripcion;
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