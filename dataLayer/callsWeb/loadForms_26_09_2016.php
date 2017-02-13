<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
if (isset($_POST["idUsuario"])) {
    /***VIENEN LOS DOS PARAMETROS SIN PROBLEMA**/
    $idUsuario = $_POST["idUsuario"];
    $reportData = []; $returnData = [];

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtObtenerContratos = $conn->prepare("call spObtenerContratos(?);");
    mysqli_stmt_bind_param($stmtObtenerContratos, 'i', $idUsuario);
    if ($stmtObtenerContratos->execute()) {
        $stmtObtenerContratos->store_result();
        $stmtObtenerContratos->bind_result($id, $agreementNumber, $name, $idStatus, $description, $idCity, $colonia, $street, $nicknameEmpleado, $nicknameAgencia, $created_at);
        while ($stmtObtenerContratos->fetch()) {
            $reportData["Id"] = $id;
            $reportData["Contrato"] = $agreementNumber;
            $reportData["Tipo"] = $name;
            $reportData['idStatus'] =$idStatus;
            $reportData["Status"] = $description;
            $reportData["Municipio"] = $idCity;
            $reportData["Colonia"] = $colonia;
            $reportData['Calle'] = $street;
            $reportData["Usuario"] = $nicknameEmpleado;
            $reportData["Agencia"] = $nicknameAgencia;
            $reportData["Fecha"] = $created_at;
            $returnData[] = $reportData;
        }
        $conn->close();
    }
    echo json_encode($returnData);
}