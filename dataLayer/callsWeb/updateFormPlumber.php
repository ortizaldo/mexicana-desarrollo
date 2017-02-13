<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
if (isset($_POST['dataID'])) {
	$idReport = $_POST['dataID'];
    $colorTapon = $_POST['colorTapon'];
    $riMenor = $_POST['riMenor'];
    $reqTuberia = $_POST['reqTuberia'];
    $numTomas = $_POST['numTomas'];
    $tipoSentencia = $_POST['update'];
    //obtenemos el idFormulario
    $idFormPlumber=getFormPlumber($idReport);
    if (isset($idFormPlumber) && intval($idFormPlumber) > 0) {
    	$updPlumberSQL = "UPDATE  form_plumber 
    					   SET tapon =?,
                               ri =?,
                               newPipe =?,
                               pipesCount =?
                           where id=?";
    	if ($updPlumber = $conn->prepare($updPlumberSQL)) {
    		$updPlumber->bind_param("iiisi", $colorTapon,$riMenor,$reqTuberia,$numTomas, $idFormPlumber);
			if ($updPlumber->execute()) {
                $result["status"] = "OK";
                $result["code"] = "200";
                $result["result"] = "Actualizacion exitosa";
                echo json_encode($result);
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

function getFormPlumber($idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT 
                            a.id
                        FROM
                            form_plumber a,
                            report b
                        WHERE
                        0 = 0 
                        AND a.consecutive = b.id
                        AND b.id = $idReport;";
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