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

$pageFirstResult = ( isset($_GET["pageFirstResult"])) ? $_GET["pageFirstResult"] : "0";
$rowsPerPage = ( isset($_GET["rowsPerPage"])) ? $_GET["rowsPerPage"] : "18446744073709551615";
$direction = ( isset($_GET["direction"])) ? $_GET["direction"] : "asc";

$sql = "SELECT datetime, note, bloodsucker FROM blood_log order by datetime " . $direction . " LIMIT " .$pageFirstResult.",".$rowsPerPage;
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