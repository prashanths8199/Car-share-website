<?php



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
      Trips
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTrtkkaf-4PXR8TyizYwfDiXehDhM5FVw&libraries=places"></script>
        
    
    <style>
            #container{
                margin-top:80px;
                margin-bottom: 40px;
            }
            #map{
                width : 100%;
                height : 40vh;
                margin : 10px auto;
            }
            /* To see the autocomplete dropdown-conflict with modal z-index */
            .pac-container{
                z-index:1051;
            }
            .addtrip.modal-body {
                height:460px;
                overflow:auto;
            }

            .time{
                margin-top:10px;
            }
            .trip{
                border : 1px solid gray;
                padding : 10px;
                border-radius: 10px;
                margin :10px auto;
                background: linear-gradient(#ECE9E6, #FFFFFF);
            }
            .departure,.destination,.seatsavailable{
                font-size: 1.5em;
            }
            .price{
                font-size: 2em;
            }

            

        </style>
  </head>
  <body>


  <div class="container" id="container">
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            <div>
                <button type="button" class="btn btn-lg green" data-toggle="modal"data-target="#addtripModal" >Add Trips</button>
                <!-- <button type="button" class="btn btn-lg green" data-toggle="modal"data-target="#edittripModal" >Edit Trips</button> -->

            </div>
            <div id="mytrips" class="trips">

            </div>
        </div>
    </div>

  </div>

  <!-- Add trip modal -->
  <form method="post" id="addtripform">
        <div class="modal addtrip" id="addtripModal"  data-backdrop="static" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a  class="close" data-dismiss="modal">&times;</a>
                        <h4 class="modal-title">New Trip:</h4>
                    </div>
                    <div class="modal-body">
                        <div id="addtripmessage"></div>
                        <div id="map"></div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="departure" id="departure" placeholder="Departure" >
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="destination" id="destination" placeholder="Destination">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="number" name="price" id="price" placeholder="Price">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="number" name="seatsavailable" id="seatsavailable" placeholder="Seat Available">
                        </div>
                        <div class="form-group">
                            <label>
                            <input type="radio" name="regular" id="yes" value="Y">Regular </label>
                            <label>
                            <input type="radio" name="regular" id="no" value="N">One-off </label>
                        </div>
                        <div class="checkbox checkbox-inline regular">
                            <label>
                                <input type="checkbox" name="monday" id="monday" value="1">Monday
                            </label>
                            <label>
                                <input type="checkbox" name="tuesday" id="tuesday" value="1">Tuesday
                            </label>
                            <label>
                                <input type="checkbox" name="wednesday" id="wednesday" value="1">Wednesday
                            </label>
                            <label>
                                <input type="checkbox" name="thursday" id="thursday" value="1">Thrusday
                            </label>
                            <label>
                                <input type="checkbox" name="friday" id="friday" value="1">Friday
                            </label>
                            <label>
                                <input type="checkbox" name="saturday" id="saturday" value="1">Saturday
                            </label>
                            <label>
                                <input type="checkbox" name="sunday" id="sunday" value="1">Sunday
                            </label>
                            
                        </div>
                        <div class="form-group oneoff">
                            <input class="form-control" readonly="readonly" name="date" id="date">
                        </div>
                        <div class="form-group regular oneoff time">
                                <input class="form-control" type="time" name="time" id="time" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn btn-primary" name="createtrip" type="submit" value="Create Trip">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Edit trip modal -->
  <form method="post" id="edittripform">
        <div class="modal " id="edittripModal"  data-backdrop="static" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button  class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Trip:</h4>
                    </div>
                    <div class="modal-body">
                        <div id="edittripmessage"></div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="departure2" id="departure2" placeholder="Departure" >
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="destination2" id="destination2" placeholder="Destination">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="number" name="price2" id="price2" placeholder="Price">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="number" name="seatsavailable2" id="seatsavailable2" placeholder="Seat Available">
                        </div>
                        <div class="form-group">
                            <label>
                            <input type="radio" name="regular2" id="yes2" value="Y">Regular </label>
                            <label>
                            <input type="radio" name="regular2" id="no2" value="N">One-off </label>
                        </div>
                        <div class="checkbox checkbox-inline regular2">
                            <label>
                                <input type="checkbox" name="monday2" id="monday2" value="1">Monday
                            </label>
                            <label>
                                <input type="checkbox" name="tuesday2" id="tuesday2" value="1">Tuesday
                            </label>
                            <label>
                                <input type="checkbox" name="wednesday2" id="wednesday2" value="1">Wednesday
                            </label>
                            <label>
                                <input type="checkbox" name="thursday2" id="thursday2" value="1">Thrusday
                            </label>
                            <label>
                                <input type="checkbox" name="friday2" id="friday2" value="1">Friday
                            </label>
                            <label>
                                <input type="checkbox" name="saturday2" id="saturday2" value="1">Saturday
                            </label>
                            <label>
                                <input type="checkbox" name="sunday2" id="sunday2" value="1">Sunday
                            </label>
                            
                        </div>
                        <div class="form-group oneoff2">
                            <input class="form-control" readonly="readonly" name="date2" id="date2">
                        </div>
                        <div class="form-group regular2 oneoff2 time">
                                <input class="form-control" type="time" name="time2" id="time2" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn btn-primary" name="edittrip" type="submit" value="Edit Trip">
                        <input class="btn btn-danger" name="deletetrip" id="deletetrip" type="button" value="Delete">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="map.js"></script>
   <script src="trips.js"></script>
</body>
</html>