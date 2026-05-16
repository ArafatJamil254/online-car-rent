<?php

    // Database connection settings
    // Change these values to match your local setup
    $host       = "127.0.0.1";
    $dbuser     = "root";
    $dbpassword = "";
    $dbname     = "webtech";

    // This function creates and returns a database connection
    function getConnection(){
        global $host, $dbuser, $dbpassword, $dbname;
        $con = mysqli_connect($host, $dbuser, $dbpassword, $dbname);

        // Check if connection failed
        if(!$con){
            die("Connection failed: " . mysqli_connect_error());
        }

        return $con;
    }

?>
