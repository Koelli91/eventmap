<?php
$ip = json_decode(file_get_contents('https://api.ipify.org?format=json'));
//$location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));
$location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip->ip));

$latlon = array(
    'city' => $location['geoplugin_city'],
    'latitude' => $location['geoplugin_latitude'],
    'longitude' => $location['geoplugin_longitude']
);

echo json_encode($latlon);