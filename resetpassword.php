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
        <title>Reset Password</title>
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
            <h1>Reset Password</h1>
            <div  id="resultmessage"></div>
    <?php
if(!isset($_GET['userid']) ||!isset($_GET['key']))
{
    echo '<div class ="alert alert-danger">There was an error .Click on the  link<div/>';
    exit;
}
$userid=$_GET['userid'];
$key=$_GET['key'];
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

echo "
<form method=post id='passwordreset'>
<input type=hidden name=key value=$key>
<input type=hidden name=userid value=$userid>
<div class='form-group'>
    <label for='password'>Enter your new Password:</label>
    <input type='password' name='password' id='password' placeholder='Enter Password' class='form-control'>
</div>
<div class='form-group'>
    <label for='password2'>Re-enter Password::</label>
    <input type='password' name='password2' id='password2' placeholder='Re-enter Password' class='form-control'>
</div>
<input type='submit' name='resetpassword' class='btn btn-success btn-lg' value='Reset Password'>


</form>
";

?>
 </div>
    </div>
</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            $("#passwordreset").submit(function (e) { 
                e.preventDefault();
                var data_topost = $(this).serializeArray();

                $.ajax({
                    url:"storeresetpassword.php",
                    type:"POST",
                    data : data_topost,
                    success:function(data){
                        $("#resultmessage").html(data);
                    },
                    error: function(){
                        var error = "<div class='alert alert-danger'>Try again later!</div>"
                        $("#resultmessage").html(error);
                    }
                })
                
            });
        </script>
        </body>
</html>