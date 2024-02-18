<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'connection.php';
$userid = $_SESSION['userid'];
//print_r($_FILES);


$name = $_FILES["picture"]["name"];
$extension = pathinfo($name,PATHINFO_EXTENSION);
$tmp_location = $_FILES["picture"]["tmp_name"];
$file_error = $_FILES["picture"]["error"];

$permanentDestination ="ProfilePicture/" .md5(time()) . ".$extension";

if($file_error>0){
    echo  '<div class="alert alert-danger">Unable to update profile picture. Try later !</div>'; 
    exit;
}
if(move_uploaded_file($tmp_location,$permanentDestination)){
    $sql ="update users set profilepicture='$permanentDestination'  where id='$userid'";
    $result = mysqli_query($conn,$sql);
    if(!$result){
        echo  '<div class="alert alert-danger">There was an error running the query.</div>'; 
        exit;
    }
}
else{
    echo  '<div class="alert alert-danger">There was an error running the query.</div>'; 
    exit;
}

?>