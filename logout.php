<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
// echo "<div class='alert alert-danger' style='margin-top:50px;'>Entered IF</div>";
// echo($_SESSION['userid']);
//echo($_GET['logout']);
if(isset($_SESSION['userid']) && $_GET['logout']==1){
    session_destroy();
    setcookie("rememberme","",time()-3600);
    echo("logged out");

    header("location:index.php");
}
// else{
//     echo("logged in");

// }
?>