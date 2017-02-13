<?php

include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

ini_set('memory_limit', '-1');
//error_reporting(E_ERROR | E_PARSE);

if (isset($_POST["test"])) {
    $data = (array) json_decode($_POST["test"]);

    $dir = "../../logs/";
    $fileName = "apiLog.txt";
    $fileData = fopen($dir.$fileName, "w");
    $text = "";
    
    foreach($data as $key => $value ) {
        $text .= $key;
        $text .= ": ";
        if ( $value = (array) $value ) {
            foreach ($value as $element => $content) {
                $text .= $element;
                $text .= ": ";
                $text .= $content;
                $text .= "\n";
            }
        } else {
            $text .= $value;
            $text .= "\n";
        }
    }
    
    fwrite($fileData, $text);
    fclose($fileData);
}