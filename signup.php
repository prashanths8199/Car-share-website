<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'connection.php';

$missingUsername = '<p><strong>Please enter a username!</strong></p>';
$missingEmail = '<p><strong>Please enter your email address!</strong></p>';
$invalidEmail = '<p><strong>Please enter a valid email address!</strong></p>';
$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$invalidPassword = '<p><strong>Your password should be at least 6 characters long and inlcude one capital letter and one number!</strong></p>';
$differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your password</strong></p>';
$missingfirstname = '<p><strong>Please enter your firstname!</strong></p>';
$missinglastname = '<p><strong>Please enter your lastname!</strong></p>';
$missingPhone = '<p><strong>Please enter your phone number!</strong></p>';
$invalidPhoneNumber = '<p><strong>Please enter a valid phone number (digits only and less than 15 long)!</strong></p>';
$missinggender = '<p><strong>Please select your gender</strong></p>';
$missinginformaton = '<p><strong>Please share a few more words about yourself.</strong></p>';
$error="";

if(empty($_POST["signupusername"])){
    
    $error .= $missingUsername;
}
else{
    $username = filter_var($_POST["signupusername"],FILTER_SANITIZE_STRING);
}

if(empty($_POST["signupfirstname"])){
    
    $error .= $missingfirstname;
}
else{
    $firstname = filter_var($_POST["signupfirstname"],FILTER_SANITIZE_STRING);
}

if(empty($_POST["signuplastname"])){
    
    $error .= $missinglastname;
}
else{
    $lastname = filter_var($_POST["signuplastname"],FILTER_SANITIZE_STRING);
}

if(empty($_POST["signupemail"])){
    $error .= $missingEmail;
}
else{
    $email = filter_var($_POST["signupemail"],FILTER_SANITIZE_EMAIL);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $error .= $invalidEmail;
    }
}

if(empty($_POST["signuppassword"])){
    $error .= $missingPassword;
}
elseif(!(strlen($_POST["signuppassword"])>6 and 
preg_match('/[A-Z]/',$_POST["signuppassword"]) and 
preg_match('/[0-9]/',$_POST["signuppassword"])))
{
    $error .= $invalidPassword;
}
else{
    $password = filter_var($_POST["signuppassword"],FILTER_SANITIZE_STRING);
    if(empty($_POST["signuppassword2"])){
        $error .= $missingPassword2;
    }
    else{
        $password2 = filter_var($_POST["signuppassword2"],FILTER_SANITIZE_STRING);
        if($password!==$password2){
            $error .= $differentPassword;
        }
    }
}

if(empty($_POST["phonenumber"])){
    
    $error .= $missingPhone;
}
else if(preg_match('/\D/',$_POST["phonenumber"])){
    $error .= $invalidPhoneNumber;
}
else{
    $phonenumber = filter_var($_POST["phonenumber"],FILTER_SANITIZE_STRING);
}

if(empty($_POST["gender"])){
    $error .= $missinggender;
}else{
    $gender = $_POST["gender"];
}

if(empty($_POST["moreinfo"])){
    $error .= $missinginformaton;
}else{
    $moreinformation = filter_var($_POST["moreinfo"], FILTER_SANITIZE_STRING);
}

if($error){
    $resultMessage = '<div class="alert alert-danger">'. $error .'</div>';
    echo $resultMessage;
    exit;
}

$username = mysqli_real_escape_string($conn,$username);
$email = mysqli_real_escape_string($conn,$email);
$password = mysqli_real_escape_string($conn,$password);
//$password2 = mysqli_real_escape_string($conn,$password2);
//$password = md5($password);
$password = hash('sha256',$password);
$firstname = mysqli_real_escape_string($conn,$firstname);
$lastname = mysqli_real_escape_string($conn,$lastname);
$phonenumber = mysqli_real_escape_string($conn,$phonenumber);
$moreinformation = mysqli_real_escape_string($conn,$moreinformation);
$query ="select * from users where username = '$username'";
$result = mysqli_query($conn,$query);

if(!$result){
    echo '<div class ="alert alert-danger">Error running the query<div/>';
    exit;
}
$results= mysqli_num_rows($result);
if($results){
    echo '<div class="alert alert-danger">Username is already registered. Do you want to log in?</div>';
    exit;
}

$query ="select * from users where email = '$email'";
$result = mysqli_query($conn,$query);
if(!$result){
    echo '<div class ="alert alert-danger">Error running the query ' . mysqli_error($conn) .'<div/>';
    exit;
}
$results= mysqli_num_rows($result);
if($results){
    echo '<div class="alert alert-danger">Email is already registered. Do you want to log in?</div>';
    exit;
}

$activation = bin2hex(openssl_random_pseudo_bytes(16));
$sql = "insert into users (username,email,password,activation,firstname,lastname,gender,phonenumber,moreinformation) 
values ('$username','$email','$password','$activation','$firstname','$lastname','$gender','$phonenumber','$moreinformation')";
$result=mysqli_query($conn,$sql);
if(!$result){
    echo '<div class ="alert alert-danger">Error running the query ' . mysqli_error($conn) .'<div/>';
    exit;
}


$message = "Please click on this link to activate your account:\n\n";
$message .= "http://localhost/carsharing/activation.php?email=". urlencode($email) ."&key=$activation";
if(mail($email,'Confirm Your Registration',$message, 'From:prashanths8199@gmail.com'))
{
    echo "<div class='alert alert-success'>Thank for your registring! A confirmation email has been sent to $email. Please click on the activation link to activate your account.</div>";
}
else{
    echo '<div class ="alert alert-danger">Sorry, failed while sending mail!<div/>';
}











?>