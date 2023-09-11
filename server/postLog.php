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
    return $d && $d->format($format) === $date;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //missing params
    //either datetime or now should be set
    if(!isset($_POST['token']) || !isset($_POST['bloodsucker']) || !isset($_POST['note']) || ( !isset($_POST['now']) && !isset($_POST['datetime']))) {
        exit("parametre mangler!");
    }
    
    $token = $_POST['token'];
    if ($token != 'your-secret-token') {
        exit('wrong token');
    }

    //remove 'T' from datetime
    $datetime = str_replace("T", " ", $_POST['datetime'] ?? null);

    //test datetime
    if(isset($_POST['datetime']) && validateDate($datetime, 'Y-m-d H:i' ) === false) {
        exit("wrong datetime format: " . $datetime); 
    }

    
    //test note
    if (strlen($_POST['note']) > 0 && !preg_match( "/^[a-zA-ZæøåÆØÅ0-9., ]{1,50}$/" , $_POST['note'])) { 
        exit("'note' contains illegal characters!");
    }    

    //test now
    if(isset($_POST['now'])) {
        if($_POST['now'] != 'now') {
            exit("error parsing 'now' data");
        }
    }

    //test bloodsugar 
    if(!is_numeric($_POST['bloodsucker'])) {
        exit("error parsing 'bloodsucker' data");
    }

    date_default_timezone_set ("Europe/Berlin");

    $datetimeServer = new DateTime('NOW');
    $datetimeServer = $datetimeServer->format('Y-m-d H:i:s');
    $datetime = (($_POST['now'] ?? null ) == "now") ? $datetimeServer : $datetime;
    $note = $_POST['note'];
    $bloodsucker = $_POST['bloodsucker'];

    //insert into log table
    $sql = "insert into blood_log(datetime, note, bloodsucker) values ('".$datetime."', '".$note."', ".$bloodsucker.");";
    
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit;
    }

    //increment finger counter
    $sql = "CALL `fingercounter_inc`()";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit;
    }

    print "success";
}
?>