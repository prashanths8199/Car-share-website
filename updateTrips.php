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


$tripid =$_POST["tripid"];
$departure = $_POST["departure2"];
$destination = $_POST["destination2"];
$price = $_POST["price2"];
$seatsavailable = $_POST["seatsavailable2"];
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

if(empty($_POST["regular2"])){
    $errors .= $missingfrequency;    
}elseif($_POST["regular2"] == "Y"){
    if(empty($_POST["monday2"]) && empty($_POST["tuesday2"]) && empty($_POST["wednesday2"])&& empty($_POST["thursday2"]) && empty($_POST["friday2"]) && empty($_POST["saturday2"]) && empty($_POST["sunday2"]) ){
        $errors .= $missingdays; 
    }
    else{
        $regular = $_POST["regular2"];
        $monday = $_POST["monday2"];
        $tuesday = $_POST["tuesday2"];
        $wednesday = $_POST["wednesday2"];
        $thursday = $_POST["thursday2"];
        $friday = $_POST["friday2"];
        $saturday = $_POST["saturday2"];
        $sunday = $_POST["sunday2"];
    }
    if(empty($_POST["time2"])){
        $errors .= $missingtime;   
    }
    else{
        $time = $_POST["time2"];
    }
}elseif($_POST["regular2"] == "N"){
    $regular = $_POST["regular2"];
    if(empty($_POST["date2"])){
        $errors.= $missingdate;   
    }
    else{
        $date = $_POST["date2"];
    }
    if(empty($_POST["time2"])){
        $errors .= $missingtime;   
    }
    else{
        $time = $_POST["time2"];
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
        $sql = "UPDATE carsharetrips SET departure= '$departure',departureLongitude='$departureLongitude',departureLatitude='$departureLatitude', destination='$destination',destinationLongitude='$destinationLongitude',destinationLatitude='$destinationLatitude', price='$price', seatsavailable='$seatsavailable', regular='$regular', monday='$monday', tuesday='$tuesday', wednesday='$wednesday', thursday='$thursday', friday='$friday', saturday='$saturday', sunday='$sunday', time='$time' WHERE trip_id='$tripid' LIMIT 1";    
    }
    else{ 
        //query for a one off trip
        $sql = "UPDATE carsharetrips SET departure= '$departure',departureLongitude='$departureLongitude',departureLatitude='$departureLatitude', destination='$destination',destinationLongitude='$destinationLongitude',destinationLatitude='$destinationLatitude', price='$price', seatsavailable='$seatsavailable', regular='$regular', date='$date', time='$time', monday='0', tuesday='0', wednesday='0', thursday='0', friday='0', saturday='0', sunday='0'  WHERE trip_id='$tripid'";    
    }
    $results = mysqli_query($conn, $sql);
    //check if query is successful
    if(!$results){
        echo '<div class=" alert alert-danger">There was an error! The trip could not be added to database!</div>';        
    }
}


?>