<?php //set POST variables

// abrimos la sesión cURL
$ch = curl_init();
 
// definimos la URL a la que hacemos la petición
curl_setopt($ch, CURLOPT_URL,"http://mty.rutadirecta.com/api/get/route");
// definimos el número de campos o parámetros que enviamos mediante POST
curl_setopt($ch, CURLOPT_POST, 1);
// definimos cada uno de los parámetros
curl_setopt($ch, CURLOPT_POSTFIELDS, "id=71");
 
// recibimos la respuesta y la guardamos en una variable
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$remote_server_output = curl_exec ($ch);

// cerramos la sesión cURL
curl_close ($ch);
 
// hacemos lo que queramos con los datos recibidos
// por ejemplo, los mostramos
echo $remote_server_output;
var_dump($remote_server_output);
exit;
print_r($remote_server_output);