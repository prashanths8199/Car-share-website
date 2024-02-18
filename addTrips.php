<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'connection.php';

$missingdeparture = '<p><strong>Please enter your departure!</strong></p>';
$invaliddeparture = '<p><strong>Please enter a valid departure!</strong></p>';
$missingdestination = '<p><strong>Please enter your destination!</strong></p>';
$invaliddestination = '<p><strong>Please enter a valid destination!</strong></p>';
$missingprice = '<p><strong>Please choose a price per seat!</strong></p>';
$invalidprice = '<p><strong>Please choose a valid price per seat using numbers only!!</strong></p>';
$missingseatsavailable = '<p><strong>Please select the number of available seats!</strong></p>';
$invalidseatsavailable = '<p><strong>The number of available seats should contain digits only!</strong></p>';
$missingfrequency = '<p><strong>Please select a frequency!</strong></p>';
$missingdays = '<p><strong>Please select at least one weekday!</strong></p>';
$missingdate = '<p><strong>Please choose a date for your trip!</strong></p>';
$missingtime = '<p><strong>Please choose a time for your trip!</strong></p>';



$departure = $_POST["departure"];
$destination = $_POST["destination"];
$price = $_POST["price"];
$seatsavailable = $_POST["seatsavailable"];
$regular = null;
// $date = null;
// $time = $_POST["time"];
// $monday = $_POST["monday"];
// $tuesday = $_POST["tuesday"];
// $wednesday = $_POST["wednesday"];
// $thursday = $_POST["thursday"];
// $friday = $_POST["friday"];
// $saturday = $_POST["saturday"];
// $sunday = $_POST["sunday"];

$errors='';

if(!isset($_POST['departureLatitude']) or !isset($_POST['departureLongitude'])){
    $errors .= $invaliddeparture; 
}
else{
    $departureLatitude = $_POST["departureLatitude"];
    $departureLongitude = $_POST["departureLongitude"];
}

if(!isset($_POST["destinationLatitude"]) or !isset($_POST["destinationLongitude"])){
    $errors .= $invaliddestination;   
}else{
    $destinationLatitude = $_POST["destinationLatitude"];
    $destinationLongitude = $_POST["destinationLongitude"];
}


if(!$departure){
    $errors .= $missingdeparture;
}
else{
    $departure = filter_var($departure,FILTER_SANITIZE_STRING);
}

if(!$destination){
    $errors .= $missingdestination;   
}else{
    $destination = filter_var($destination, FILTER_SANITIZE_STRING); 
}

if(!$price){
    $errors .= $missingprice; 
}elseif(preg_match('/\D/', $price)  // you can use ctype_digit($price)
){
        $errors .= $invalidprice;   
}else{
    $price = filter_var($price, FILTER_SANITIZE_STRING);    
}

if(!$seatsavailable){
    $errors .= $missingseatsavailable; 
}elseif(preg_match('/\D/', $seatsavailable)  // you can use ctype_digit($seatsavailable)
){
        $errors .= $invalidseatsavailable;   
}else{
    $seatsavailable = filter_var($seatsavailable, FILTER_SANITIZE_STRING);    
}

if(empty($_POST["regular"])){
    $errors .= $missingfrequency;    
}elseif($_POST["regular"] == "Y"){
    if(empty($_POST["monday"]) && empty($_POST["tuesday"]) && empty($_POST["wednesday"])&& empty($_POST["thursday"]) && empty($_POST["friday"]) && empty($_POST["saturday"]) && empty($_POST["sunday"]) ){
        $errors .= $missingdays; 
    }
    else{
        $regular = $_POST["regular"];
        $monday = $_POST["monday"];
        $tuesday = $_POST["tuesday"];
        $wednesday = $_POST["wednesday"];
        $thursday = $_POST["thursday"];
        $friday = $_POST["friday"];
        $saturday = $_POST["saturday"];
        $sunday = $_POST["sunday"];
    }
    if(empty($_POST["time"])){
        $errors .= $missingtime;   
    }
    else{
        $time = $_POST["time"];
    }
}elseif($_POST["regular"] == "N"){
    $regular = $_POST["regular"];
    if(empty($_POST["date"])){
        $errors.= $missingdate;   
    }
    else{
        $date = $_POST["date"];
    }
    if(empty($_POST["time"])){
        $errors .= $missingtime;   
    }
    else{
        $time = $_POST["time"];
    }
}


if($errors){
    $resultMessage = "<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
}
else{
    $departure = mysqli_real_escape_string($conn,$departure);
    $destination = mysqli_real_escape_string($conn,$destination);
    if($regular == "Y"){
        //query for a regular trip
        $sql = "INSERT INTO carsharetrips (user_id,departure, departureLongitude, departureLatitude, destination, destinationLongitude, destinationLatitude, price, seatsavailable, regular, monday, tuesday, wednesday, thursday, friday, saturday, sunday, time) VALUES ('".$_SESSION['userid']."', '$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatsavailable','$regular','$monday','$tuesday','$wednesday','$thursday','$friday','$saturday','$sunday','$time')";
    }else{ 
        //query for a one off trip
        $sql = "INSERT INTO carsharetrips (user_id,departure, departureLongitude, departureLatitude, destination, destinationLongitude, destinationLatitude, price, seatsavailable, regular, date, time) VALUES ('".$_SESSION['userid']."', '$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatsavailable','$regular','$date','$time')";   
    }
    $results = mysqli_query($conn, $sql);
    //check if query is successful
    if(!$results){
        echo '<div class=" alert alert-danger">There was an error! The trip could not be added to database!</div>';        
    }
}


?>