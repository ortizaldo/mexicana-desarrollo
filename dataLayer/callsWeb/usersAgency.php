<?php include_once "../DAO.php";

    $DB = new DAO();
    $conn = $DB->getConnect();

	$agencyID; $data = [];
    $agencyID = $_POST['agency'];
	
	$getEmployeesByAgency = $conn->prepare("SELECT EM.id AS id, US.nickname AS employee, EM.idUser AS idUser
										FROM user AS US 
										LEFT JOIN employee AS EM ON EM.idUser = US.id 
										LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id 
										LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency 
										LEFT JOIN user AS USAG ON USAG.id = AG.idUser
										INNER JOIN profile AS PF ON PF.id = EM.idProfile 
										WHERE US.id <> AGEM.idAgency AND AG.id = ? ORDER BY AG.id;");
	$getEmployeesByAgency->bind_param("i", $agencyID);
	$getEmployeesByAgency->store_result();
	$getEmployeesByAgency->bind_result($id,$employee,$idUser);

	if( $getEmployeesByAgency->execute() ) {
		while( $getEmployeesByAgency->fetch() ) {
			$users['id'] = $id;
			$users['employee'] = $employee;
			$users['idUser'] = $idUser;

			$data[] = $users;
		}
		echo json_encode($data);
	}