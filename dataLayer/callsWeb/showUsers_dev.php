<?php include_once "../DAO.php";
      include_once "../libs/utils.php";

    session_start();

    $DB = new DAO();
    $conn = $DB->getConnect();

	$username; $param; $agencyID; $data=array();

	if( isset($_POST['nickname']) && isset($_POST['param']) ) {
		$username = $_POST['nickname'];
		$param = $_POST['param'];
	} else if ( isset($_POST['elem']) && isset($_POST['param']) ) {
        $username = $_POST['elem'];
        $param = $_POST['param'];
    }

    if( isset($_POST['agency']) ) {
        $agencyID = $_POST['agency'];
    }

	$users = array();
	$returnData = array();

	$returnAdmins = array();
	$returnAgencies = array();
	$returnEmployees = array();

	$id; $nickname; $email; $type;

	$getUserNickname = $conn->prepare("SELECT `user`.`nickname`, `user`.`email`, `rol`.`type` FROM `user` LEFT JOIN `user_rol` ON `user_rol`.`idUser` = `user`.`id` LEFT JOIN `rol` ON `rol`.`id` = `user_rol`.`idRol` AND `user_rol`.`idUser` = `user`.`id` WHERE `user`.`nickname` = ? AND `user`.`active` = 1;");
	$getUserNickname->bind_param('s', $username);

	if( $getUserNickname->execute() ) {
		$getUserNickname->store_result();
		$getUserNickname->bind_result($nickname, $email, $type);

		if( $getUserNickname->fetch() ) {
			$_SESSION["nickname"] = $nickname;
			$_SESSION["email"] = $email;
			$_SESSION["rol"] = $type;
		}
	}
	$getUserNickname->close();

		/*if( $_SESSION["rol"] == "SuperAdmin" ) {
			$param = "admins";
		} else if ( $_SESSION["rol"] == "Admin" ) {
			$param = "agencies";
		} else if ( $_SESSION["rol"] == "Agency" ) {
			$param = "employees";
		}*/
		var_dump($_SESSION["nickname"]);
		var_dump($_SESSION["email"]);
		var_dump($_SESSION["rol"]);

		if( isset($_SESSION["nickname"]) && $param == "admins" && isset($_SESSION["rol"]) && $_SESSION["rol"] == "SuperAdmin" ) {
			retrieveAdmins($conn);
		} else if( isset($_SESSION["nickname"]) && $param == "agencies" && isset($_SESSION["rol"]) && $_SESSION["rol"] == "Admin" ) {
			retrieveAgencies($conn);
        } else if( isset($_SESSION["nickname"]) && $param == "employees" && isset($_SESSION["rol"]) && isset($agencyID) ) {
            retrieveEmployeesAgency($conn, $agencyID);
        } else if( isset($_SESSION["nickname"]) && $param == "employees" && isset($_SESSION["rol"]) && $_SESSION["rol"] == "Agency" ) {
			retrieveEmployees($conn);
        }

		function retrieveAdmins($conn) {
			
			$getAdmins = "SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, US.avatar, PF.name 
					FROM user AS US LEFT JOIN employee AS EM ON EM.id = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id 
					LEFT JOIN agency AS AG ON AG.idUser = AGEM.idAgency INNER JOIN profile AS PF ON PF.id = EM.idProfile 
					WHERE AG.id = 1 AND US.active = 1;";
			
			$result = $conn->query($getAdmins);
			
			while( $row = $result->fetch_array() ) {
				$users['id'] = $row[0];
				$users['nickname'] = $row[1];
				$users['name'] = $row[2];
				$users['lastName'] = $row[3];
				$users['email'] = $row[4];
				$users['avatar'] = $row[5];
				$users['phoneNumber'] = $row[6];	
				$users['photoUrl'] = $row[7];
				$users['type'] = $row[8];
				
				$returnAdmins[]=$users;
			}
			
			$users=null;
			
			$getAgenciesResult = "SELECT US.id, US.nickname, US.name, US.lastName, US.lastNameOP, US.email, US.avatar, US.phoneNumber, US.created_at, AG.tipo, AG.plazo FROM agency AS AG LEFT JOIN user AS US ON US.id = AG.idUser WHERE US.active=1;";
			$resultAgenciesData = $conn->query($getAgenciesResult);
			
			while( $row = $resultAgenciesData->fetch_array() ) {
				$users['id'] = $row[0];
				$users['nickname'] = $row[1];
				$users['name'] = $row[2];
				$users['lastName'] = $row[3];
				$users['lastNameOP'] = $row[4];
				$users['email'] = $row[5];
				$users['avatar'] = $row[6];
				$users['phoneNumber'] = $row[7];
				$users['created_at'] = $row[8];
				$users['tipo'] = $row[9];
				$users['plazo'] = $row[10];
				
				$returnAgencies[]=$users;
			}
			
			$users=null;
			
				$dropTable = "DROP TABLE employeesAssigned";
				$result = $conn->query($dropTable);

				$crateTempEmployeesTables = "CREATE TEMPORARY TABLE IF NOT EXISTS employeesAssigned AS (SELECT US.id AS id, USAG.nickname AS agency,
															US.nickname AS employee, US.name AS employeeName, US.lastName AS employeeLastName, US.email AS email, US.phoneNumber as phone, PF.name as profile
											FROM user AS US LEFT JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id
											LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
											INNER JOIN profile AS PF ON PF.id = EM.idProfile WHERE US.id <> AGEM.idAgency ORDER BY AG.id);";
				$result = $conn->query($crateTempEmployeesTables);

				$getEmployeesFinal = "SELECT id, agency, employee, employeeName, employeeLastName, email, phone, profile FROM employeesAssigned ORDER BY id;";
				$resultEmployees = $conn->query($getEmployeesFinal);

				while( $row = $resultEmployees->fetch_array() ) {
					$users['id'] = $row[0];
					$users['agency'] = $row[1];
					$users['employee'] = $row[2];
					$users['employeeName'] = $row[3];
					$users['employeeLastName'] = $row[4];
					$users['email'] = $row[5];
					$users['phone'] = $row[6];
					$users['profile'] = $row[7];

					$returnEmployees[]=$users;
				}

				$users=null;
			
			$returnData["Admins"]=$returnAdmins;
			$returnData["Agencies"]=$returnAgencies;
			$returnData["Employees"]=$returnEmployees;
			
			/*echo "<pre>";
				print_r($returnData);
			echo "</pre>";
			exit;*/
			echo json_encode($returnData);
		}

		function retrieveAgencies($conn) {
				$agenciesData=array();
			
				$getAgencies = "SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, US.photoUrl, rol.type, USAG.name FROM user AS US LEFT JOIN user_rol AS USR ON US.id = USR.idUser LEFT JOIN rol ON rol.id = USR.idRol INNER JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AE ON AE.idemployee = EM.id LEFT JOIN agency AS AG ON AG.id = AE.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser WHERE US.active = 1 AND rol.type = 'Agency';";	
				$result = $conn->query($getAgencies);

				while( $row = $result->fetch_array() ) {
						$agency['id'] = $row[0];
						$agency['nickname'] = $row[1];
						$agency['name'] = $row[2];
						$agency['lastName'] = $row[3];
						$agency['email'] = $row[4];
						$agency['avatar'] = $row[5];
						$agency['phoneNumber'] = $row[6];	
						$agency['photoUrl'] = $row[7];
						$agency['type'] = $row[8];
						$agency['agency'] = $row[9];
					
						$agenciesData['id'] = $row[0];
						$agenciesData['nickname'] = $row[1];
						$agenciesData['name'] = $row[2];

						$returnAgencies[]=$agency;
						$returnAgenciesSelect[]=$agenciesData;
				}
				//$users=null;
				//$agenciesData=null;
			
				$users=null;
			
				$dropTable = "DROP TABLE employeesAssigned";
				$result = $conn->query($dropTable);

				$crateTempEmployeesTables = "CREATE TEMPORARY TABLE IF NOT EXISTS employeesAssigned AS (SELECT US.id AS id, USAG.nickname AS agency, 
															US.nickname AS employee, US.name AS employeeName, US.lastName AS employeeLastName, US.email AS email, US.phoneNumber as phone, PF.name as profile 
											FROM user AS US LEFT JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id 
											LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
											INNER JOIN profile AS PF ON PF.id = EM.idProfile WHERE US.id <> AGEM.idAgency ORDER BY AG.id);";
				$result = $conn->query($crateTempEmployeesTables);

				$getEmployeesFinal = "SELECT id, agency, employee, employeeName, employeeLastName, email, phone, profile FROM employeesAssigned ORDER BY id;";
				$resultEmployees = $conn->query($getEmployeesFinal);

				while( $row = $resultEmployees->fetch_array() ) {
					$users['id'] = $row[0];
					$users['agency'] = $row[1];
					$users['employee'] = $row[2];
					$users['employeeName'] = $row[3];
					$users['employeeLastName'] = $row[4];
					$users['email'] = $row[5];
					$users['phone'] = $row[6];
					$users['profile'] = $row[7];

					$returnEmployees[]=$users;
				}

				$users=null;
				
				/*$getEmployees = "SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, US.photoUrl, rol.type, USAG.nickname
												FROM user AS US LEFT JOIN user_rol AS USR ON US.id = USR.id LEFT JOIN rol ON rol.id = USR.idRol 
												INNER JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AE ON AE.idemployee = EM.id
												LEFT JOIN agency AS AG ON AG.id = AE.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
												WHERE US.active = 1 AND rol.type = 'Employee' AND USAG.nickname is not null;";
				$result = $conn->query($getEmployees);

				while( $row = $result->fetch_array() ) {
					$employee['id'] = $row[0];
					$employee['nickname'] = $row[1];
					$employee['name'] = $row[2];
					$employee['lastName'] = $row[3];
					$employee['email'] = $row[4];
					$employee['avatar'] = $row[5];
					$employee['phoneNumber'] = $row[6];	
					$employee['photoUrl'] = $row[7];
					$employee['type'] = $row[8];
					
					$returnEmployees[]=$employee;
				}*/
				$returnData["Agencies"]=$returnAgencies;
				$returnData["AgenciesSelect"]=$returnAgenciesSelect;
				$returnData["Employees"]=$returnEmployees;
				//$users=null;
				echo json_encode($returnData);
		}

		function retrieveEmployees($conn) {
				$getAgencies = "SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, US.photoUrl, rol.type, USAG.name FROM user AS US LEFT JOIN user_rol AS USR ON US.id = USR.idUser LEFT JOIN rol ON rol.id = USR.idRol INNER JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AE ON AE.idemployee = EM.id LEFT JOIN agency AS AG ON AG.id = AE.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser WHERE US.active = 1 AND rol.type = 'Agency';";	
				$result = $conn->query($getAgencies);

				while( $row = $result->fetch_array() ) {
						$agency['id'] = $row[0];
						$agency['nickname'] = $row[1];
						$agency['name'] = $row[2];
						$returnAgencies[]=$agency;
				}
			
				/*$getEmployees = "SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, US.photoUrl, rol.type, USAG.nickname
												FROM user AS US LEFT JOIN user_rol AS USR ON US.id = USR.id LEFT JOIN rol ON rol.id = USR.idRol 
												INNER JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AE ON AE.idemployee = EM.id
												LEFT JOIN agency AS AG ON AG.id = AE.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
												WHERE US.active = 1 AND rol.type = 'Employee' AND USAG.nickname is not null;";

				$result = $conn->query($getEmployees);

				while( $row = $result->fetch_array() ) {
					$users['id'] = $row[0];
					$users['nickname'] = $row[1];
					$users['name'] = $row[2];
					$users['lastName'] = $row[3];
					$users['email'] = $row[4];
					$users['avatar'] = $row[5];
					$users['phoneNumber'] = $row[6];	
					$users['photoUrl'] = $row[7];
					$users['type'] = $row[8];
					$users['agency'] = $row[9];
					
					$returnEmployees[]=$users;
				}*/
			
				$users=null;
			
				$dropTable = "DROP TABLE employeesAssigned";
				$result = $conn->query($dropTable);

				$crateTempEmployeesTables = "CREATE TEMPORARY TABLE IF NOT EXISTS employeesAssigned AS (SELECT US.id AS id, USAG.nickname AS agency, 
															US.nickname AS employee, US.name AS employeeName, US.lastName AS employeeLastName, US.email AS email, US.phoneNumber as phone, PF.name as profile 
											FROM user AS US LEFT JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id 
											LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
											INNER JOIN profile AS PF ON PF.id = EM.idProfile WHERE US.id <> AGEM.idAgency ORDER BY AG.id);";
				$result = $conn->query($crateTempEmployeesTables);

				$getEmployeesFinal = "SELECT id, agency, employee, employeeName, employeeLastName, email, phone, profile FROM employeesAssigned ORDER BY id;";
				$resultEmployees = $conn->query($getEmployeesFinal);

				while( $row = $resultEmployees->fetch_array() ) {
					$users['id'] = $row[0];
					$users['agency'] = $row[1];
					$users['employee'] = $row[2];
					$users['employeeName'] = $row[3];
					$users['employeeLastName'] = $row[4];
					$users['email'] = $row[5];
					$users['phone'] = $row[6];
					$users['profile'] = $row[7];

					$returnEmployees[]=$users;
				}

				$users=null;
			
				$returnData["Agencies"]=$returnAgencies;
				$returnData["Employees"]=$returnEmployees;
				//$users=null;
				echo json_encode($returnData);
		}

		function retrieveEmployeesAgency($conn, $agencyID) {

			$id=0; $employee=""; $employeeName=""; $employeeLastName="";

            //Cambiar sentencia y solo obtener el nombre de la agencia seleccionada

			$getAgencies = "SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, US.photoUrl, rol.type, USAG.name FROM user AS US LEFT JOIN user_rol AS USR ON US.id = USR.idUser LEFT JOIN rol ON rol.id = USR.idRol INNER JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AE ON AE.idemployee = EM.id LEFT JOIN agency AS AG ON AG.id = AE.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser WHERE US.active = 1 AND rol.type = 'Agency';";
			$result = $conn->query($getAgencies);

			while( $row = $result->fetch_array() ) {
				$agency['id'] = $row[0];
				$agency['nickname'] = $row[1];
				$agency['name'] = $row[2];
				$returnAgencies[]=$agency;
			}

			$users=null;

			$getEmployeesByAgency = $conn->prepare("SELECT US.id AS id, US.nickname AS employee, US.name AS employeeName, US.lastName AS employeeLastName
										FROM user AS US LEFT JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id 
										LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
										INNER JOIN profile AS PF ON PF.id = EM.idProfile WHERE US.id <> AGEM.idAgency AND AG.id = ? ORDER BY AG.id;");

			$getEmployeesByAgency->bind_param("i", $agencyID);

			$getEmployeesByAgency->store_result();
			$getEmployeesByAgency->bind_result($id, $employee, $employeeName, $employeeLastName);

			if( $getEmployeesByAgency->execute() ) {
				while( $getEmployeesByAgency->fetch() ) {
					$users['id'] = $id;
					$users['employee'] = $employee;
					$users['employeeName'] = $employeeName;
					$users['employeeLastName'] = $employeeLastName;

					$returnEmployees[]=$users;
				}

				$users=null;

                $returnData["selectedAgency"]=$agencyID;
				$returnData["Agencies"]=$returnAgencies;
				$returnData["Employees"]=$returnEmployees;
				echo json_encode($returnData);
			}
		}
?>