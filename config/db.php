<?php
    $host ="127.0.0.1";
    $dbuser="root";
    $dbpassword="";
    $dbname="carrent";

    function getConnection(){
        $conn=mysqli_connect($GLOBALS['host'],$GLOBALS['dbuser'],$GLOBALS['dbpassword'],$GLOBALS['dbname']);
        if(!$conn){
            die("DB Connection Failed: ".mysqli_connect_error());
        }
        return $conn;
    }
?>