<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "dbAngela";

try{
    $con = mysqli_connect($host, $username, $password, $database);
}catch(Exception $e){
    header("Location: ../errors/dberror.php");
    die();
}

?>