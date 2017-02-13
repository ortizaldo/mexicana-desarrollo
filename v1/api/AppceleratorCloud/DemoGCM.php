
<?php


// API access key from Google API's Console
//define( 'API_ACCESS_KEY', 'AIzaSyBkmxLdE6xQn-5lClI45II77-QugZhG71I' );
define( 'API_ACCESS_KEY', 'AIzaSyDZipQUBc1qwu7IW2tHcnjF6tl_dgU6Vc4' );



$registrationIds = array("APA91bFu4Yqnn9SQfiPf1UoTtebJHNugkVkgJoBnnKrwg10xMOx7jW3r2rY_TbiJLhrS_llZps-wTyx9WY3p3o2lEFirEmz7oq1glqrJ0CDKvadZ0MHHpdanFHS_G4ah7ITZjWJMHetM");

// prep the bundle
$msg = array
(
    'message'       => 'here is a message. message',
    'title'         => 'This is a title. title',
    'subtitle'      => 'This is a subtitle. subtitle',
    'tickerText'    => 'Ticker text here...Ticker text here...Ticker text here',
    'vibrate'   => 1,
    'sound'     => 1
);

$fields = array
(
    'registration_ids'  => $registrationIds,
    'data'              => $msg
);

$headers = array
(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );

echo $result;
?>
