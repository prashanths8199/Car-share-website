<?php


session_start();
include 'connection.php';

$userid = $_SESSION['userid'];
$new_email = $_POST['email'];

$sql = "select * from users where email = '$new_email'";
$result =  mysqli_query($conn,$sql);
$count = mysqli_num_rows($result);
if($count>0){
    echo '<div class="alert alert-danger">Email is already registered.</div>';
    exit;
}


$sql = "select * from users where id = '$userid'";
$result =  mysqli_query($conn,$sql);
$count = mysqli_num_rows($result);
if($count==1){
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $email = $row['email'];
}
else{
    echo '<div class ="alert alert-danger">Error retrieving the email<div/>';
    exit;
}

$activationkey = bin2hex(openssl_random_pseudo_bytes(16));

$sql ="update users set activation = '$activationkey' where id = '$userid'";
$result =  mysqli_query($conn,$sql);
if(!$result){
    echo '<div class ="alert alert-danger">Error running the query ' . mysqli_error($conn) .'<div/>';
    exit;
}
else{
    
$message = "Please click on this link to update the email:\n\n";
$message .= "http://localhost/carsharing/activatenewemail.php?email=". urlencode($email) ."&new_email=". urlencode($new_email) ."&key=$activationkey";
if(mail($new_email,'Email Update',$message, 'From:prashanths8199@gmail.com'))
{
    echo "<div class='alert alert-success'>An email has been sent to $new_email. Please click on the link to reset your password.</div>";
}
else{
    echo '<div class ="alert alert-danger">Sorry, failed while sending mail!<div/>';
}
}
?>