<?php require("curl.class.php");

    $options = array(
        "url" => "https://www.linio.com.mx/api/address/neighborhoods?city=Monterrey&fk_customer_address_region=35&municipality=Monterrey&neighborhood=",
        "type" => "GET",
        "return_transfer" => "1"
    );

    $obj = new wArLeY_cURL($options);
    $resp = $obj->Execute();
    echo $obj->getError();

    echo $resp;
?>