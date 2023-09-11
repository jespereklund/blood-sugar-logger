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

    $sql = "SELECT spotoffset FROM blood_insulinspotcounter";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    $offset = $row["spotoffset"] % 21;

    $obj=new stdClass;
    $obj->offset = $offset;

    $myJSON = json_encode($obj);

    echo $myJSON;
?>