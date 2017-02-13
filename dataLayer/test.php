<?php

  $name = $_POST["nickname"];

  $elems = [];

  for( $i=0; $i<99; $i++ ) {
    $elems[]=$i;
  }

  $finalElement = [];

  $finalElement["code"]=200;
  $finalElement["elems"]=$elems;

  if( isset($name) ) {
    echo json_encode($finalElement);
  }

?>
