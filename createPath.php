<?php include_once 'dataLayer/DAO.php';
    include_once 'dataLayer/libs/utils.php';
    session_start();

    $DB = new DAO();
    $conn = $DB->getConnect();

    if( isset($_FILES["file"]) ) {
		//print_r($_FILES["file"]);
		
		$file = fopen($_FILES["file"]["tmp_name"],"r");
		$content = [];
			
		while( !feof($file) ) {
			$content = fgetcsv($file);
			//print_r($content);
			if ( $content[0] !== "LATITUDE" && $content[1] !== "LONGITUDE" ) {
				$latitude = $content[0];
				$longitude = $content[1];
				
				$latitude = floatval($latitude);
				$longitude = floatval($longitude);
				
				$idEmployee = 775;
				$idDot_Place_finish = 0;
				
				sleep(2);
				
				$insertIntoTrack = $conn->prepare("INSERT INTO track (`idEmployee`, `idDot`, `idPlace_from`, `idPlace_to`, `start_latitude`, `start_longitude`, `finish_latitude`, `finish_longitude`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1);");
				 //print_r($insertIntoTrack);
				$insertIntoTrack->bind_param('iiiidddd', $idEmployee, $idDot_Place_finish, $idDot_Place_finish, $idDot_Place_finish, $latitude, $longitude, $idDot_Place_finish, $idDot_Place_finish);
				
				
				if($insertIntoTrack->execute()) {
					echo "Ea!";
				} else {
					var_dump($insertIntoTrack->error());
				}
			}
		}
	 	fclose($file);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin">
    <meta name="keywords" content="admin dashboard, admin, flat, flat ui, ui kit, app, web app, responsive">
    <link rel="shortcut icon" href="img/ico/favicon.png">
    <title>Mexicana de Gas - Create Path</title>

    <!-- Base Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/img/ico/favicon.png">

    <!-- Base Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-body">
    <div class="login-logo">
        <img src="assets/img/logoMexicana.png" alt="Logotipo de la empresa"/>
    </div>
    <div class="container log-row">
        <form class="form-signin" method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
            <div class="login-wrap">
            	<label>Introduce un archivo CSV con el recorrido a cargar</label>
            	<!--Select con los empleados para asignarle el recorrido-->
                <!--<input type="text" class="form-control" name="email" placeholder="Usuario de acceso" autofocus>-->
                <input type="file" class="form-control" name="file" placeholder="Archivo con el recorrido">
				<button class="btn btn-lg btn-success btn-block" type="submit">Crear recorrido</button>
            </div>
        </form>
    </div>

    <!--jquery-1.10.2.min-->
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <!--Bootstrap Js-->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
</body>
</html>