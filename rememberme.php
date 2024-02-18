<?php
//If the user is not logged in & rememberme cookie exists
if(!isset($_SESSION['userid']) && !empty($_COOKIE['rememberme']))
{
    //f1: COOKIE: $a . "," . bin2hex($b)
    //f2: hash('sha256', $a)
    
     // echo "<div class='alert alert-danger' style='margin-top:50px;'>Entered IF</div>";
    //  echo($_SESSION['userid']);
    list($auth1,$auth2)=explode(",",$_COOKIE['rememberme']);
    $auth2 = hex2bin($auth2);
    $f2auth2 = hash("sha256",$auth2);

    $sql ="select * from rememberme where authenticator1='$auth1'";
    $result = mysqli_query($conn,$sql);

    if(!$result){
        echo  '<div class="alert alert-danger">There was an error running the query.</div>'; 
        exit;
    }
    $count = mysqli_num_rows($result);
    if($count !==1){
        echo '<div class="alert alert-danger">Remember me process failed!</div>';
        exit;
    }

    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if(!hash_equals($row['f2authenticator2'],$f2auth2)){
        echo '<div class="alert alert-danger">hash_equals returned false.</div>';
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
        $userid = $row['userid'];
        $expirydate = date('Y-m-d H:i:s',time() + (15*24*60*60));
        $sql = "insert into rememberme(authenticator1,f2authenticator2,userid,expirydate) values 
        ('$auth1','$f2authenticator2','$userid','$expirydate')";
        $result = mysqli_query($conn,$sql);

        
        if(!$result){
            echo '<div class ="alert alert-danger">Error running the query<div/>';
            exit;
        }
        $sql = "select * from users where id = '$userid'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $_SESSION["userid"]=$row["id"];
        $_SESSION["username"]=$row["username"];
        $_SESSION["email"]=$row["email"];
       header("location:profile.php");
      // echo($_SESSION["userid"]);
    }   
}
// else{
//     // echo $_SESSION['userid'];
//     // echo $_COOKIE['rememberme'];
//     $res = "<div class='alert alert-danger' style='margin-top:50px;'>Else Userid : " . $_SESSION['userid'] ."
//     Cookie : " . $_COOKIE['rememberme'] ."
//     </div>";
//     echo $res;
// }


?>