<?php include_once "dataLayer/libs/utils.php";
    session_start();

    if( isset($_SESSION["nickname"]) ) {
			
			unset($_SESSION["nickname"]);
			unset($_SESSION["email"]);
			unset($_SESSION["rol"]);
			unset($_SESSION['typeAgency']);
			
			session_destroy();
			movePage(200, "login.php");
	  }
?>
<script src="js/jquery.js"></script>
<script>window.location = "login.php";</script>