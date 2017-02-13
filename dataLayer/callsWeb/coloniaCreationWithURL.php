<?php require ("../libs/curl-easy-fast/curl.class.php");
    include_once '../DAO.php';

    $DB = new DAO();
    $conn = $DB->getConnect();

    $options = array(
        "url" => "https://www.linio.com.mx/api/address/neighborhoods?city=Monterrey&fk_customer_address_region=35&municipality=Monterrey&neighborhood=",
        "type" => "GET",
        "return_transfer" => "1"
    );

    $obj = new wArLeY_cURL($options);
    $resp = $obj->Execute();
    echo $obj->getError();

    $elems = array();
    $values = array();

    $elems = json_decode($resp);

    foreach ( $elems as $object ) {
        $values = (array) $object;

        $insertColoniaStatement = 'INSERT INTO colonia(name, created_at, updated_at) VALUES("' . $values['id'] . '", NOW(), NOW());';
        //echo $insertColoniaStatement;
        //echo '<br/>';
        $conn->query($insertColoniaStatement);

        $insertCityColoniaStatement = 'INSERT INTO city_colonia(idCity, idColonia, created_at, updated_at) VALUES(1, ' . $conn->insert_id . ', NOW(), NOW());';
        //echo $insertCityColoniaStatement;
        $conn->query($insertCityColoniaStatement);
    }
?>