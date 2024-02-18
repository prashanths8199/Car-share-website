<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'connection.php';

$missingEmail = '<p><stong>Please enter your email address!</strong></p>';
$missingPassword = '<p><stong>Please enter your password!</strong></p>';

$errors ='';
if(empty($_POST["loginemail"])){
    $errors .= $missingEmail;
}
else{
    $email = filter_var($_POST["loginemail"],FILTER_SANITIZE_EMAIL);
}

if(empty($_POST["loginpassword"])){
    $errors .= $missingPassword;
}
else{
    $password = filter_var($_POST["loginpassword"],FILTER_SANITIZE_STRING);
}


if($errors){
    $resultmessage = '<div class="alert alert-danger">'. $errors .'</div>';
    echo $resultmessage;
    exit;
}
else{
    $email = mysqli_real_escape_string($conn,$email);
    $password = mysqli_real_escape_string($conn,$password);
    $password = hash("sha256",$password);

    $sql = "select * from users where email = '$email' and password = '$password' and activation='activated'";
    $result = mysqli_query($conn, $sql);
  
    if(!$result){
        echo '<div class ="alert alert-danger">Error running the query<div/>';
        exit;
    }
    $count = mysqli_num_rows($result);
    if($count !==1){
        echo '<div class ="alert alert-danger">Wrong Username or Password<div/>';
        exit;

    }
    else{
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $_SESSION["userid"]=$row["id"];
        $_SESSION["username"]=$row["username"];
        $_SESSION["email"]=$row["email"];
        //echo var_dump($_SESSION);

        if(empty($_POST["rememberme"])){
            echo "success";
        }
        else{
            $auth1 = bin2hex(openssl_random_pseudo_bytes(10));
            $auth2 = openssl_random_pseudo_bytes(20);

            function f1($a,$b){
                $c = $a . "," .bin2hex($b);
                return $c;
            }
            $cookieValue = f1($auth1,$auth2);

            setcookie(
                "rememberme",
                $cookieValue,
                time() + (15*24*60*60)
            );

            function f2($a){
                return hash("sha256",$a);
            }
            $f2authenticator2 = f2($auth2);
            $userid = $_SESSION['userid'];
            $expirydate = date('Y-m-d H:i:s',time() + (15*24*60*60));
            $sql = "insert into rememberme(authenticator1,f2authenticator2,userid,expirydate) values 
            ('$auth1','$f2authenticator2','$userid','$expirydate')";
            $result = mysqli_query($conn,$sql);

            if(!$result){
                echo '<div class ="alert alert-danger">Error running the query<div/>';
                exit;
            }
            else{
                echo "success";
            }
        }

    }
}
?>