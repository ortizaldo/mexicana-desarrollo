<?php include_once 'dataLayer/DAO.php';
include_once 'dataLayer/libs/utils.php';
session_start();

if (isset($_SESSION["nickname"]) && isset($_SESSION["rol"])) {
    movePage(200, "index.php");
} else {

    $DB = new DAO();
    $conn = $DB->getConnect();

    $nickname = "";
    $email = "";
    $rol = "";

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $username = $_POST["email"];
        $password = $_POST["password"];
        //$password = md5($password);
        $getUserNickname = $conn->prepare("SELECT user.id, user.nickname, user.email, rol.type FROM user INNER JOIN user_rol ON user.id = user_rol.idUser INNER JOIN rol ON user_rol.idRol = rol.id AND user_rol.idUser = user.id WHERE user.email = ? AND user.password = ? OR user.nickname = ? AND user.password = ? AND user.active = 1 LIMIT 1;");
        $getUserNickname->bind_param('ssss', $username, $password, $username, $password);
        if ($getUserNickname->execute()) {
            $getUserNickname->store_result();
            $getUserNickname->bind_result($id, $nickname, $email, $rol);
            if ( $getUserNickname->fetch() ) {
                //Redirigir según el rol del usuario (Súper Admin, Admin, Agencia)
                //Solo empleados de AYOPSA pueden acceder al sistema
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
                    echo "Usuario sin permiso de accesar a esta sección";

            } else {
                $response["status"] = "Forbidden";
                $response["code"] = "500";
                $response["response"] = "Error en el ingreso de Nickname - contraseña:". $getUserNickname->error;
                //echo json_encode($response);
            }
        } else {
            $response["status"] = "Not Found";
            $response["code"] = "404";
            $response["response"] = "Usuario no Encontrado.";
            //echo json_encode($response);
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
<div class="container log-row">
    <form class="form-signin" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
        <div class="login-wrap">
            <input type="text" class="form-control" name="email" placeholder="Usuario de acceso o Correo electr&oacute;nico" autofocus>
            <input type="password" class="form-control" name="password" placeholder="Contrase&ntilde;a">
            <button class="btn btn-lg btn-success btn-block" type="submit">Continuar</button>
        </div>
    </form>
    <div class="col-lg-4">&nbsp;</div>
    <div class="col-lg-6">
        &nbsp;&nbsp;<a href="forgotPass.php" style="color:#333333; !important">¿Olvidaste tu contrase&ntilde;a?</a>
    </div>
    <div class="col-lg-2">&nbsp;</div>
</div>

<!--jquery-1.10.2.min-->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<!--Bootstrap Js-->
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/respond.min.js"></script>
</body>
</html>