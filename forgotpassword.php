<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'connection.php';

$missingEmail = '<p><strong>Please enter your email address!</strong></p>';
$invalidEmail = '<p><strong>Please enter a valid email address!</strong></p>';

$errors='';
if(empty($_POST['forgotemail'])){
    $errors .= $missingEmail;
}
else{
    $email = filter_var($_POST['forgotemail'],FILTER_SANITIZE_EMAIL);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors .= $invalidEmail;
    }
}

if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
    echo $resultMessage;
    exit;
}

$email = mysqli_real_escape_string($conn,$email);

$query ="select * from users where email = '$email'";
$result = mysqli_query($conn,$query);
if(!$result){
    echo '<div class ="alert alert-danger">Error running the query ' . mysqli_error($conn) .'<div/>';
    exit;
}
$results= mysqli_num_rows($result);
if(!$results){
    echo '<div class="alert alert-danger">Email does not exists in database!</div>';
    exit;
}

$rows = mysqli_fetch_array($result,MYSQLI_ASSOC);
$userid = $rows['id'];

$activationkey = bin2hex(openssl_random_pseudo_bytes(16));
$time=time();
$status = 'pending';

$sql = "insert into forgotpassword(userid,validationkey,time,status) values ('$userid','$activationkey','$time','$status')";
$result = mysqli_query($conn,$sql);
if(!$result){
    echo '<div class ="alert alert-danger">Error running the query ' . mysqli_error($conn) .'<div/>';
    exit;
}


$message = "Please click on this link to reset your password:\n\n";
$message .= "http://localhost/carsharing/resetpassword.php?userid=$userid&key=$activationkey";
if(mail($email,'Reset your password',$message, 'From:prashanths8199@gmail.com'))
{
    echo "<div class='alert alert-success'>An email has been sent to $email. Please click on the link to reset your password.</div>";
}
else{
    echo '<div class ="alert alert-danger">Sorry, failed while sending mail!<div/>';
}

?>