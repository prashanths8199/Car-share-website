<?php
$conn = mysqli_connect("localhost","development","rm4LRqTloh3fdYfz","carsharing");
if(mysqli_connect_error()){
    die("Error : Unable to connect to:" +mysqli_connect_error());
}
?>