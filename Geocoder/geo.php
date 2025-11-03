<?php 
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

$key = "AmN2qIMDgfRIv3C5TyXY0ZD_fbv4ZxNO7P8qI-JSY4xz1mApS8day20WAFwAnmht";
$dir = str_replace( " ", "%20", "KR 3 # 11-2 SAN NICOLAS, Cali" );
$url = "http://dev.virtualearth.net/REST/v1/Locations/$dir?key=$key";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url ); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = json_decode(curl_exec($ch), true);

echo "<pre>";
print_r( $response );
echo "</pre>";