<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'connection.php';

$tripid = $_POST['tripid'];
$sql = "delete from carsharetrips where trip_id = '$tripid'";
$result = mysqli_query($conn,$sql);
if(!$result){
    echo "error";
}


?>