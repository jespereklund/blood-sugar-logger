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

    $sql = "SELECT fingerindex FROM blood_fingercounter";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    $fingerSpot = $row["fingerindex"] % 16;

    $obj=new stdClass;
    $obj->imageNumber = $fingerSpot + 1;

    $myJSON = json_encode($obj);

    echo $myJSON;
?>