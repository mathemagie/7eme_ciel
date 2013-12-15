<?php

ini_set("display_errors", 0);
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

$hostname = "localhost";
$dbname = "elevation";
$username = "root";
$pw = "P67=PMre-2";

$user_id = 1;

if (!$link = mysql_connect($hostname, $username, $pw)) {
    echo 'Could not connect to mysql';
    exit;
}

if (!mysql_select_db($dbname, $link)) {
    echo 'Could not select database';
    exit;
}

function update_maxid($user_id,$id) {
        global $link;
        $sql  = "SELECT * FROM current_elevation where user = "  . $user_id;
	//echo $sql;
	//print_r($link);
	 $result = mysql_query($sql, $link);
	$in_db = 0;
	 while ($row = mysql_fetch_assoc($result)) {
                $sql    = "UPDATE current_elevation set max_id = " . $id . " where user = " . $user_id;
                $result = mysql_query($sql, $link);
                $in_db = 1;

        }
        if (!$in_db){
                $sql = "INSERT INTO  `elevation`.`current_elevation` (`user` ,`max_id`) VALUES ( '$user_id',  '$id')";
                $result = mysql_query($sql, $link);
        }
}



$sql    = 'SELECT max(id) as id FROM elevation';
$result = mysql_query($sql, $link);
while ($row = mysql_fetch_assoc($result)) {
    $last_id = $row['id'];
    $ante_last_id = intval($last_id) - 1;
}

//echo $last_id;
//echo $ante_last_id;

$sql    = 'SELECT elevation FROM elevation where id=' . $last_id;
$result = mysql_query($sql, $link);

while ($row = mysql_fetch_assoc($result)) {
    $last_elevation = $row['elevation'];
}

$sql    = 'SELECT elevation FROM elevation where id=' . $ante_last_id;
$result = mysql_query($sql, $link);

while ($row = mysql_fetch_assoc($result)) {
    $ante_elevation = $row['elevation'];
}

//echo $last_elevation;
//echo $ante_elevation;
//$delta = intval($ante_elevation) - intval($last_elevation);
$delta = $last_elevation - $ante_elevation;

$sql    = 'SELECT max_id FROM current_elevation where user = ' . $user_id;
$result = mysql_query($sql, $link);
while ($row = mysql_fetch_assoc($result)) {
    $max_id  = $row['max_id'];
}
if ($last_id == $max_id) {
	$delta = '0';
}


if ($_GET['init'] == '1') {
	$delta = $last_elevation - 100;
}

update_maxid($user_id, $last_id);
echo "<" . $delta . ">";
?>
