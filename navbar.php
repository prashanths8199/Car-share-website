<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}

include 'connection.php';

if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $sql = "select * from users where id = '$userid'";
    $result = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($result);
    if($count==1)
    {
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
        $picture = $row['profilepicture'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>
      Car Sharing
    </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="styling.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Arvo&display=swap" rel="stylesheet">
   


  </head>
  <body>

  <nav class="navbar navbar-custom navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand">Car Sharing</a>
            <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        </div>

        <div class="navbar-collapse collapse" id="navbarCollapse">
            <ul class="nav navbar-nav">
                <?php if(!isset($_SESSION['username'])) {?>
                <li ><a href="#">Home</a></li><?php }
                else {?>
                <li ><a href="index.php">Search</a></li>
                <?php } ?>
                <?php if(isset($_SESSION['username'])) {?>
                <li><a href="profile.php">Profile</a></li><?php }?>
                <li><a href="#">Help</a></li>
                <li><a href="#">Contact</a></li>
                <?php if(isset($_SESSION['username'])) {?>
                <li><a href="trips.php">My Trips</a></li><?php }?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            <?php if(isset($_SESSION['username']) && basename($_SERVER['PHP_SELF']) =='profile.php') {?> 
            <li><a href="#"><div data-toggle="modal" data-target="#updatepicture">
                <?php if(empty($picture)){
                    echo "<img class='preview' src='ProfilePicture/noimage.jpg'></img>";
                }
                else{
                    echo "<img class='preview' src='$picture'></img>";
                }?>
                </div></a></li>
            <?php }?>
            <?php if(!isset($_SESSION['username'])) {?>
            <li><a href="#loginModal" data-toggle="modal">Login</a></li><?php }?>
            <?php if(isset($_SESSION['username'])) {?>
            <li><a href="#">Logged in as <b><?php echo $_SESSION['username']?></b></a></li><?php }?>
            <?php if(isset($_SESSION['username'])) {?>
            <li><a href="logout.php?logout=1">LogOut</a></li><?php }?>
            </ul>
        </div>

    </div>
    
  </nav>

  <form method="post" id="loginform">
        <div class="modal" id="loginModal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Login:</h4>
                    </div>
                    <div class="modal-body">
                        <div id="loginmessage"></div>
                        <div class="form-group">
                            <input class="form-control" type="email" name="loginemail" id="loginemail" placeholder="E-mail" maxlength="50">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="loginpassword" id="loginpassword" placeholder="Password" maxlength="30">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="rememberme" id="rememberme">
                                Rememberme
                            </label>
                            <a class="pull-right" data-dismiss="modal" style="cursor:pointer"  data-target="#forgotpasswordModal" data-toggle="modal">Forgot Password?</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn green" name="login" type="submit" value="Login">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="#signupModal" data-toggle="modal">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

   <!-- Login Form -->

  
    <!-- Sign Up Form -->
    <form method="post" id="signupform">
        <div class="modal" id="signupModal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Sign Up:</h4>
                    </div>
                    <div class="modal-body signupmodal">
                        <div id="signupmessage"></div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="signupusername" id="signupusername" placeholder="Username" maxlength="30">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="signupfirstname" id="signupfirstname" placeholder="Firstname" maxlength="30">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="signuplastname" id="signuplastname" placeholder="Lastname" maxlength="30">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="email" name="signupemail" id="signupemail" placeholder="E-mail" maxlength="50">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="signuppassword" id="signuppassword" placeholder="Password" maxlength="30">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="signuppassword2" id="signuppassword2" placeholder="Confirm Password" maxlength="30">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="phonenumber" id="phonenumber" placeholder="Telephone Number" maxlength="30">
                        </div>
                        <div class="form-group">
                            <label><input  type="radio" name="gender" id="male" value="male">Male</label>
                            <label><input  type="radio" name="gender" id="female" value="female">FFemale</label>
                        </div>
                        <div class="form-group">
                            <label for="#moreinfo">Comments:</label>
                            <textarea class="form-control" type="text-area" name="moreinfo" id="moreinfo" rows="5" maxlength="300"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input class="btn green" name="signup" type="submit" value="Sign up">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

      <!-- Forgot Password Form -->
  <form method="post" id="forgotpasswordform">
        <div class="modal" id="forgotpasswordModal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">Forgot Password? Enter your email address:</h4>
                    </div>
                    <div class="modal-body">
                        <div id="forgotpasswordmessage"></div>
                        <div class="form-group">
                            <input class="form-control" type="email" name="forgotemail" id="forgotemail" placeholder="E-mail" maxlength="50">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn green" name="forgotpassword" type="submit" value="Submit">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" data-dismiss="modal" class="btn btn-default pull-left" data-target="#signupModal" data-toggle="modal">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <form method="post" id="updatepictureform" enctype="multipart/form-data">
        <div class="modal" id="updatepicture" data-backdrop="static">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close"data-dismiss="modal">&times;</a>
                    <h4 class="modal-title">Upload Picture:</h4>

                </div>
                <div class="modal-body">
                <div id="updatepicturemessage"></div>
                    <div>
                     <?php if(empty($picture)){
                    echo "<img class='preview2' id='preview2' src='ProfilePicture/noimage.jpg'></img>";
                    }
                    else{
                    echo "<img class='preview2' id='preview2' src='$picture'></img>";
                    }?>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="file" name="picture" id="picture" >
                    </div>

                </div>
                <div class="modal-footer">
                    <input class="btn green" name="updatepicturesubmit" type="submit" value="Submit">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> 

                </div>
            </div>

            </div>
           
        </div>
    </form>




<!-- Footer -->
  <div class="footer">
    <div class="container">
        <p>Copyright &copy; <?php $today = date("Y"); echo $today ?></p>
    </div>
  </div>

  <div id="spinner">
    <img src="ajax-loader.gif" width="64" height="64"/><br/>Loading...
  </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

     
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js">
    </script>
    
  
</body>
</html>