<?php include_once 'dataLayer/DAO.php';
    include_once 'dataLayer/libs/utils.php';
    session_start();

    if( isset($_SESSION["nickname"]) ) {
        $_SESSION["nickname"] = null;
        movePage(200, "index.php");
    } else {
        if ( isset($_GET["activation"]) || isset($_SESSION["activationCode"]) ) {
            if ( isset($_SESSION["activationCode"]) && !isset($_GET["activation"]) ) {
                $_GET["activation"] = $_SESSION["activationCode"];
            }
            $_SESSION["activationCode"] = $_GET["activation"];
            $activationCode = $_SESSION["activationCode"];

            if ( isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["retypePass"]) ) {
                $DB = new DAO();
                $conn = $DB->getConnect();

                $nickname="";
                $email="";
                $rol="";

                $username = $_POST["email"];

                $password = $_POST["password"];
                $tempPass = $_POST["retypePass"];

                if( $password ==  $tempPass ) {
                    $getUserNickname = $conn->prepare("SELECT user.id, user.nickname, user.email, rol.type FROM user INNER JOIN user_rol ON user.id = user_rol.idUser INNER JOIN rol ON user_rol.idRol = rol.id AND user_rol.idUser = user.id WHERE user.email = ? OR user.nickname = ? AND user.active = 1 LIMIT 1");
                    $getUserNickname->bind_param('ss', $username, $username);

                    if($getUserNickname->execute()) {
                        $getUserNickname->store_result();
                        $getUserNickname->bind_result($id, $nickname, $email, $rol);
                        if($getUserNickname->fetch()) {
                            if( isset($email) ) {
                                $setActivation = $conn->prepare("UPDATE user SET user.password = ? WHERE id = ?");
                                $setActivation->bind_param('si', $password, $id);

                                if($setActivation->execute()) {
                                    /*$result=array();
                                    $result["status"] = "OK";
                                    echo json_encode($result);*/
                                    if($rol == "Agencia" || $rol == "Agency") {
                                        $stm = $conn->prepare("SELECT ap.name FROM agency a INNER JOIN agency_profile ap ON a.id = ap.idAgency WHERE a.idUser = ?;");
                                        $stm->bind_param('i', $id);
                                        if($stm->execute()){
                                            $stm->store_result();
                                            $stm->bind_result($typeAgency);
                                            if($stm->fetch()){
                                                $_SESSION['typeAgency'] = $typeAgency;
                                            }
                                        }
                                    } 
                                    $_SESSION["id"] = $id;
                                    $_SESSION["nickname"] = $nickname;
                                    $_SESSION["email"] = $email;
                                    $_SESSION["rol"] = $rol;

                                    if(isset($_SESSION["nickname"]) && ($rol == "SuperAdmin" || $rol == "Admin" || $rol == "Agencia" || $rol == "Agency") ) 
                                        movePage(200, "forms.php");
                                    else
                                        movePage(200, "logout.php");
                                }
                            }
                        }
                    }
                }
            }
        }
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
    <title>Mexicana de Gas - Login</title>

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
    <!--<h2 class="form-heading">Iniciar Sesi&oacute;n</h2>-->
     <div class="container log-row">
        <form class="form-signin" method="POST" action='<?=$_SERVER['PHP_SELF']?>'>
            <div class="login-wrap">
              <center><p>&nbsp;Introduce tu usuario de acceso o correo electrónico y la nueva contrase&ntilde;a</p></center>
                <input type="text" class="form-control" name="email" placeholder="Usuario de acceso o Correo electr&oacute;nico" autofocus>
                <input type="text" class="form-control" name="password" placeholder="Nueva contrase&ntilde;a">
                <input type="text" class="form-control" name="retypePass" placeholder="Confirmar nueva contrase&ntilde;a">
                <button class="btn btn-lg btn-success btn-block" type="submit">Confirmar</button>
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