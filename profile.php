<?php  

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 


include('connection.php');
include 'navbar.php';

// $userid = $_SESSION['userid'];

// //get username and email
// $sql = "SELECT * FROM users WHERE id='$userid'";
// $result = mysqli_query($conn, $sql);

// $count = mysqli_num_rows($result);
// // echo "<div class='alert alert-danger' style='margin-top:50px;'>Entered'</div>";
// // echo $_SESSION['userid'];
// if($count == 1){
//     $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
//     $username = $row['username'];
//     $email = $row['email']; 
//     //$_SESSION['username'] = $username;
// }else{
//     echo "There was an error retrieving the username and email from the database";   
// }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>
      Profile
    </title>
       
        <style>
            #container{
                margin-top:150px;
            }
            tr{
                cursor : pointer;
            }

        </style>

    

  </head>
  <body>


  <div class="container" id="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <h2>General Account Settings:</h2>
            <div class="table-responsive">
                <table class="table table-hover table-condensed table-bordered">
                    <tr data-target="#updateusername" data-toggle="modal">
                        <td>UserName</td>
                        <td><?php echo $_SESSION['username']; ?></td>
                    </tr>
                    <tr data-target="#updateemail" data-toggle="modal">
                        <td>Email</td>
                        <td><?php echo $_SESSION["email"] ?></td>
                    </tr>
                    <tr data-target="#updatepassword" data-toggle="modal">
                        <td>Password</td>
                        <td>hidden</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

  </div>

  <!-- updateusername -->
  <form method="post" id="updateusernameform" >
    <div class="modal" id="updateusername" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">&times;</a>
                    <h4>Edit Username: </h4>
                </div>
                <div class="modal-body">
                    <div id="updateusernameerrormessage"></div>

                    <div class="form-group">
                        <input type="text" id="username" name="username" class="form-control" maxlength="30" value="<?php echo $_SESSION['username']; ?>"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="updateusername" class="btn green" value="submit"/>
                    <button data-dismiss="modal" class="btn btn-default" >Close</button>

                </div>
            </div>
        </div>
    </div>
  </form>

  <!-- updateemail -->
  <form method="post" id="updateemailform">
    <div class="modal" id="updateemail" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" >&times;</a>
                    <h4>Enter new email:</h4>
                </div>
                <div class="modal-body">
                <div id="updateemailerrormessage"></div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="email" name="email"maxlength="50" value="<?php echo $_SESSION["email"] ?>"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn green" name="updateemail" value="Submit"/>
                    <button data-dismiss="modal" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>
  </form>

  <!-- updatepassword -->
  <form method="post" id="updatepasswordform">
    <div class="modal" id="updatepassword" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" >&times;</a>
                    <h4>Enter Current and New password:</h4>
                </div>
                <div class="modal-body">
                <div id="updatepassworderrormessage"></div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="currentpassword" name="currentpassword" placeholder="Your Current Password" maxlength="30"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Choose a password" maxlength="30"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm password" maxlength="30"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn green" name="updatepassword" value="Submit"/>
                    <button data-dismiss="modal" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>
  </form>
  <script src="index.js"></script>
  <script src="profile.js"></script>
  
</body>
</html>