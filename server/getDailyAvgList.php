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

$sql = "SELECT `datetime`, AVG(`bloodsucker`) as avg, MIN(`bloodsucker`) as min, MAX(`bloodsucker`) as max FROM blood_log GROUP BY Date(`datetime`)" . 
    " order by datetime asc LIMIT " .$pageFirstResult.",".$rowsPerPage;
    $result = mysqli_query($conn, $sql);

if (!$result) {
    printf("Error2: %s\n", mysqli_error($conn));
    exit;
}

$min = [];
$avg = [];
$max = [];
$dates = [];
while($row = mysqli_fetch_assoc($result)) {
    array_push($min, number_format($row['min'], 1));
    array_push($avg, number_format($row['avg'], 1));
    array_push($max, number_format($row['max'], 1));
    array_push($dates, date_format(date_create($row['datetime']), 'Y-m-d'));
}

$obj=new stdClass;
$obj->min = $min;
$obj->avg = $avg;
$obj->max = $max;
$obj->dates = $dates;

$myJSON = json_encode($obj);

echo $myJSON;

?>
