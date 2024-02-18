<?php

session_start();
include 'connection.php';
if(!isset($_POST['userid']) ||!isset($_POST['key']))
{
    echo '<div class ="alert alert-danger">There was an error .Click on the  link<div/>';
    exit;
}
$userid=$_POST['userid'];
$key=$_POST['key'];
$time = time()-86400;

$userid = mysqli_real_escape_string($conn,$userid);
$key = mysqli_real_escape_string($conn,$key);

$sql="select userid from forgotpassword where validationkey='$key' and userid='$userid' and time>'$time' and status = 'pending'";
$result = mysqli_query($conn,$sql);
if(!$result){
    echo '<div class ="alert alert-danger">Error running the query ' . mysqli_error($conn) .'<div/>';
    exit;
}
$count = mysqli_num_rows($result);
if($count!=1){
    echo '<div class="alert alert-danger">Please try again.</div>';
    exit;
}

$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$invalidPassword = '<p><strong>Your password should be at least 6 characters long and inlcude one capital letter and one number!</strong></p>';
$differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your password</strong></p>';
$errors='';
//Get passwords
if(empty($_POST["password"])){
    $errors .= $missingPassword; 
}elseif(!(strlen($_POST["password"])>6
         and preg_match('/[A-Z]/',$_POST["password"])
         and preg_match('/[0-9]/',$_POST["password"])
        )
       ){
    $errors .= $invalidPassword; 
}else{
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING); 
    if(empty($_POST["password2"])){
        $errors .= $missingPassword2;
    }else{
        $password2 = filter_var($_POST["password2"], FILTER_SANITIZE_STRING);
        if($password !== $password2){
            $errors .= $differentPassword;
        }
    }
}

//If there are any errors print error
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
    echo $resultMessage;
    exit;
}

$password = mysqli_real_escape_string($conn,$password);
$password = hash('sha256',$password);
$userid = mysqli_real_escape_string($conn,$userid);

$sql ="update users set password = '$password' where id = '$userid'";
$result = mysqli_query($conn,$sql);
if(!$result){
    echo '<div class ="alert alert-danger">Error running the query ' . mysqli_error($conn) .'<div/>';
    exit;
}

$sql ="update forgotpassword set status = 'used'WHERE validationkey='$key' AND userid='$userid'";
$result = mysqli_query($conn,$sql);
if(!$result){
    echo '<div class ="alert alert-danger">Error running the query ' . mysqli_error($conn) .'<div/>';
    exit;
}
else{
    echo '<div class="alert alert-success">Your password has been update successfully!<a href="index.php">Login</a></div>';  
}


?>