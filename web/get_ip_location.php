<?php
//$location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));

$location = json_decode(file_get_contents('http://api.ipinfodb.com/v3/ip-city/?key=13fda0acf9e429ac8e90c2f502ed1d19b48813351960c1717455b1e465b9109e&ip='.$_SERVER['REMOTE_ADDR'].'&format=json'), true);

//$ip = json_decode(file_get_contents('https://api.ipify.org?format=json'));
//$location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip->ip));

$latlon = array(
    'city' => $location['cityName'],
    'latitude' => $location['latitude'],
    'longitude' => $location['longitude'],
    'ip' => $_SERVER['REMOTE_ADDR']
);

echo json_encode($latlon);