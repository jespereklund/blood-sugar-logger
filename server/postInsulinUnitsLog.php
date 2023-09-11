<?php

//https only!
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    exit("ERROR! https only!");
}

header("Access-Control-Allow-Origin: *");

include "connection.php";

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    //missing params
    if(!isset($_POST['token']) || !isset($_POST['insulin_units']) || ( !isset($_POST['now']) && !isset($_POST['datetime']))) {
        exit("parametre mangler!");
    }
    
    //token check
    $token = $_POST['token'];    
    if ($token != 'your-secret-token') {
        exit('wrong token');
    }

    //remove 'T' from date form data
    $datetime = str_replace("T", " ", $_POST['datetime']);

    //sanitize datetinme
    if(isset($_POST['datetime']) && validateDate($datetime, 'Y-m-d H:i' ) === false) {
        exit("wrong datetime format: " . $datetime); 
    }

    //sanitize now
    if(isset($_POST['now'])) {
        if($_POST['now'] != 'now') {
            exit("error parsing 'now' data");
        }
    }

    //sanitize insulin units
    if(!is_numeric($_POST['insulin_units'])) {
        exit("error parsing 'insuling units' data");
    }

    date_default_timezone_set ("Europe/Berlin");

    $datetimeServer = new DateTime('NOW');
    $datetimeServer = $datetimeServer->format('Y-m-d H:i:s');
    $datetime = ($_POST['now'] == "now") ? $datetimeServer : $datetime;
    $insulin_units = $_POST['insulin_units'];

    //insert into log table
    $sql = "insert into blood_insulin_units_log(datetime, insulin_units) values ('".$datetime."', ".$insulin_units.");";
    
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit;
    }

    print "success";
}
?>