<?php

session_start();
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Email Activation</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
            h1{
                color:purple;   
            }
            .contactForm{
                border:1px solid #7c73f6;
                margin-top: 50px;
                border-radius: 15px;
            }
        </style> 

    </head>
        <body>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-offset-1 col-sm-10 contactForm">
            <h1>Email Activation</h1>
    <?php
if(!isset($_GET['email']) || !isset($_GET['new_email']) ||!isset($_GET['key']))
{
    echo '<div class ="alert alert-danger">There was an error .Click on the activation link<div/>';
    exit;
}
$email=$_GET['email'];
$new_email=$_GET['new_email'];
$key=$_GET['key'];

$email = mysqli_real_escape_string($conn,$email);
$new_email = mysqli_real_escape_string($conn,$new_email);
$key = mysqli_real_escape_string($conn,$key);

$sql="update users set email='$new_email',activation='activated' where email='$email' and activation='$key' LIMIT 1";
$result = mysqli_query($conn,$sql);
if(mysqli_affected_rows($conn)==1){
    $_SESSION['email']=$new_email;
    echo '<div class="alert alert-success">Your email has been verified.</div>';
    echo '<a href="index.php" type="button" class="btn-lg btn-sucess">Log in<a/>';

}
else{
    echo '<div class="alert alert-danger">Your account could not be activated. Please try again later.</div>';
    echo '<div class="alert alert-danger">' . mysqli_error($conn) . '</div>';
}

?>
 </div>
    </div>
</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        </body>
</html>