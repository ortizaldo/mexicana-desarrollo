<?php include_once "DAO.php";
	include_once "libs/utils.php";

	session_start();

    $DB = new DAO();
    $conn = $DB->getConnect();
    
    print_r($conn);