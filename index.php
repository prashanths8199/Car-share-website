<?php  

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
// if(!isset($_SESSION['userid'])){
//   header("location:navbar.php");
// }

include 'connection.php';

// include 'logout.php';

include 'rememberme.php';

include 'navbar.php';

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
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/sunny/jquery-ui.css">
   
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTrtkkaf-4PXR8TyizYwfDiXehDhM5FVw&libraries=places"></script>
     <style>
      body{
        height: 100%;
      }
      #container{
        margin-top: 50px;
        text-align: center;
        color:black;
      }
      #container h1{
        font-size: 3.5em;
      }
      .bold{
        font-weight: bold;
      }
      #map{
        width : 100%;
        height : 40vh;
        margin : 10px auto;
      }
      .signup{
        margin : 10px auto;
      }
      #searchresult{
        margin-top: 30px;
        margin-bottom: 60px;
      }
     </style>  

  </head>
  <body>
    <div class="container-fluid" id="container">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
        <h1>Plan your next trip now!</h1>
        <p class="lead">Save Money! Save the Environment!</p>
        <p class="bold">You can save up to 3000$ a year using car sharing!</p>

        <form class="form-inline" id="searchform">
          <div class="form-group">
            <input type="text" class="form-control" id="departure" name="departure" placeholder="Departure"/>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="destination" name="destination" placeholder="Destination"/>
          </div>
          <input type="submit" value="Search" class="btn btn-lg green"/>
        </form>

        <div id="map"></div>
        <?php if(!isset($_SESSION['username'])) {
          echo "<button type='button' class ='btn btn-lg green signup'  data-toggle='modal' data-target='#signupModal' >Sign up-It's free</button>";
        }?>
        <div id="searchresult"></div>
        </div>
      </div>
    </div>


  <script src="index.js"></script>
  <script src="map.js"></script>
    
  
</body>
</html>