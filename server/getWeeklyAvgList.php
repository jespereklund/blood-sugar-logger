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

//dont work! Week number jumps at new year and the 7 day period could be shorter after new year
//$sql = "SELECT `datetime`, AVG(`bloodsucker`) as avg, MIN(`bloodsucker`) as min, MAX(`bloodsucker`) as max, Week(`datetime`) as weeknr FROM blood_log GROUP BY Week(`datetime`)" . 

//This works since we use UNIX_TIMESTAMP (seconds since epoch) to calculate weeknumber
$sql = "SELECT `datetime`, ".
            "AVG(`bloodsucker`) as avg, ". 
            "MIN(`bloodsucker`) as min, ". 
            "MAX(`bloodsucker`) as max, ".
            "Week(`datetime`) as weeknr ".
            "FROM blood_log ". 
            "GROUP BY CAST(UNIX_TIMESTAMP(datetime) / 604800 AS UNSIGNED) ". 
            "ORDER BY datetime asc LIMIT " .$pageFirstResult.",".$rowsPerPage;

$result = mysqli_query($conn, $sql);

if (!$result) {
    printf("Error2: %s\n", mysqli_error($conn));
    exit;
}

$min = [];
$avg = [];
$max = [];
$dates = [];
$weeknr = [];
while($row = mysqli_fetch_assoc($result)) {
    array_push($min, number_format($row['min'], 1));
    array_push($avg, number_format($row['avg'], 1));
    array_push($max, number_format($row['max'], 1));
    array_push($dates, date_format(date_create($row['datetime']), 'Y-m-d'));
    array_push($weeknr, number_format($row['weeknr'], 0));
}

$obj=new stdClass;
$obj->min = $min;
$obj->avg = $avg;
$obj->max = $max;
$obj->dates = $dates;
$obj->weeknr = $weeknr;

$myJSON = json_encode($obj);

echo $myJSON;

?>
