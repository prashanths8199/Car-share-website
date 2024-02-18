<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
include 'connection.php';

$tripid = $_POST['tripid'];
$sql = "select * from carsharetrips where trip_id = '$tripid'";
$result = mysqli_query($conn,$sql);

if(!$result)
{
    echo  "error";
}
else{
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    echo json_encode($row);
}


?>