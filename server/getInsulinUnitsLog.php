<?php

//https only!
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    exit;
}

header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

include "connection.php";

$direction = ( isset($_GET["direction"])) ? $_GET["direction"] : "asc";

$sql = "SELECT datetime, insulin_units FROM blood_insulin_units_log order by datetime " . $direction;
$result_logs = mysqli_query($conn, $sql);

$rows = array();
while($r = mysqli_fetch_assoc($result_logs)) {
    $rows[] = $r;
}

print json_encode($rows);

if (!$result_logs) {
    printf("Error: %s\n", mysqli_error($conn));
    exit;
}
?>