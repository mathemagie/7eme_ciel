<?php

function getaltitude($lat,$lng){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_URL, "http://maps.googleapis.com/maps/api/elevation/json?locations=".$lat.",".$lng."&sensor=true");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
// 	curl_setopt($ch, CURLOPT_PROXY, 'proxy.ape');
// 	curl_setopt($ch, CURLOPT_PROXYPORT, '3128'); 
	$response =   curl_exec($ch);
	$pattern = '/"elevation" : (.*),/';
	preg_match($pattern, $response, $matches);
	return $matches[1];

}


$hostname = "localhost";
$dbname = "elevation";
$username = "root";
$pw = "P67=PMre-2";

if (!$link = mysql_connect($hostname, $username, $pw)) {
    echo 'Could not connect to mysql';
    exit;
}

if (!mysql_select_db($dbname, $link)) {
    echo 'Could not select database';
    exit;
}

$sql    = 'SELECT * FROM elevation';
$result = mysql_query($sql, $link);

if (!$result) {
    echo "DB Error, could not query the database\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

///
while ($row = mysql_fetch_assoc($result)) {
    echo $row['foo'];
}
///

$lat = $_GET['lat'];
$lng = $_GET['lng'];

$elevation = getaltitude($lat,$lng);
$sql = "INSERT INTO  `elevation`.`elevation` (`id` ,`lat` ,`lng` ,`elevation`,`created_at`) VALUES ( '',  '$lat',  '$lng',  '$elevation',now())";
mysql_query($sql);
?>
