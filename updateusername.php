<?php


session_start();
include 'connection.php';

if(empty($_POST['username'])){
    echo '<div class="alert alert-danger">Please enter Username</div>';
    exit;
}
$userid = $_SESSION['userid'];
$username = $_POST['username'];

$sql = "UPDATE users SET username='$username' WHERE id='$userid'";
$result = mysqli_query($conn, $sql);

if(!$result){
    echo '<div class="alert alert-danger">There was an error updating storing the new username in the database!</div>';
}
else{
    $_SESSION['username']=$username;
}
?>