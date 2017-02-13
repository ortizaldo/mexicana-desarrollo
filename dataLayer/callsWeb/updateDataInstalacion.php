<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
if (isset($_POST['numInstalacionGen']) && isset($_POST['idReport'])) {
	$numInstalacionGen = $_POST['numInstalacionGen'];
    $idReport = $_POST['idReport'];
    //obtenemos el idFormulario
    $idFormInst=getFormInst($idReport);
    if (isset($idFormInst) && intval($idFormInst) > 0) {
    	$updInstSQL = "UPDATE  form_installation 
    					   SET numInstalacionGen =? where id=?";
    	if ($updInst = $conn->prepare($updInstSQL)) {
    		$updInst->bind_param("si", $numInstalacionGen, $idFormInst);
			if ($updInst->execute()) {
                $updateEstatusContratoSQL ="UPDATE tEstatusContrato 
                                                        SET estatusAsignacionInstalacion = 54, 
                                                         fechaMod = NOW() 
                                                        WHERE idReporte = ?;";
                if ($updateEstatusContrato = $conn->prepare($updateEstatusContratoSQL)) {
                    $updateEstatusContrato->bind_param("i",$idReport);
                    if ($updateEstatusContrato->execute()) {
                        $result["status"] = "OK";
                        $result["code"] = "200";
                        $result["result"] = "Instalacion - #".$numInstalacionGen;
                        echo json_encode($result);
                    }  
                }
			}
    	}else{
    		$result["status"] = "ERROR";
            $result["code"] = "500";
            $result["result"] = "error - ".$conn->error;
		    echo json_encode($result);
    	}
    }else{
    	$result["status"] = "ERROR";
        $result["code"] = "500";
        $result["result"] = "error - ".$conn->error;
	    echo json_encode($result);
    }
}else{
	echo 'prueba '.json_encode($_POST);
}

function getFormInst($idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT 
					   id
					   FROM
					   form_installation fi,
					   reportHistory rh
					   WHERE
					   0 = 0 AND fi.consecutive = rh.idFormSell
					   AND rh.idReport = $idReport
					   AND rh.idReportType = 4;";
       	$result = $conn->query($getNameSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}