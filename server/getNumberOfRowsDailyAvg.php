<?php

    //https only!
    if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
    {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
        exit;
    }

    header("Access-Control-Allow-Origin: *");
    
    include "connection.php";

    $sql = "select count(*) as count from (SELECT AVG(`bloodsucker`) as count FROM blood_log GROUP BY Date(`datetime`)) as hej";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        printf("Error1: %s\n", mysqli_error($conn));
        exit;
    }

    $number_of_result = mysqli_fetch_assoc($result)['count'];

    print $number_of_result;

?>
