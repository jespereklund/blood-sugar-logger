<?php

//https only!
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    exit("ERROR! https only!");
}

header("Access-Control-Allow-Origin: *");

include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    if ($token != 'your-secret-token') {
        exit('wrong token');
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