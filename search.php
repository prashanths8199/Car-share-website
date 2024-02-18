<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'connection.php';

$missingdeparture = '<p><strong>Please enter your departure!</strong></p>';
$invaliddeparture = '<p><strong>Please enter a valid departure!</strong></p>';
$missingdestination = '<p><strong>Please enter your destination!</strong></p>';
$invaliddestination = '<p><strong>Please enter a valid destination!</strong></p>';

$departure = $_POST["departure"];
$destination = $_POST["destination"];


$errors='';

if(!isset($_POST['departureLatitude']) or !isset($_POST['departureLongitude'])){
    $errors .= $invaliddeparture; 
}
else{
    $departureLatitude = $_POST["departureLatitude"];
    $departureLongitude = $_POST["departureLongitude"];
}

if(!isset($_POST["destinationLatitude"]) or !isset($_POST["destinationLongitude"])){
    $errors .= $invaliddestination;   
}else{
    $destinationLatitude = $_POST["destinationLatitude"];
    $destinationLongitude = $_POST["destinationLongitude"];
}


if(!$departure){
    $errors .= $missingdeparture;
}
else{
    $departure = filter_var($departure,FILTER_SANITIZE_STRING);
}

if(!$destination){
    $errors .= $missingdestination;   
}else{
    $destination = filter_var($destination, FILTER_SANITIZE_STRING); 
}

if($errors){
    $resultMessage = "<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
    exit;
}

$searchradius = 10;
// echo $departure;
// echo gettype($departure);
// echo $destination;
// echo gettype($destination);

/*
Longitude ranges from -180 to +180
10*360 - plus/minus 10 *360
24901 -total miles across longitude
cos(depatrue) -convert  deg to radian 
*/
$Departurelngoutofrange=false;
$destinationlngoutofrange=false;
//min max Departure Longitude
$deltadeparturelongitude = ($searchradius * 360)/(24901*cos(deg2rad($departureLongitude)));
$mindeparturelongitude = $departureLongitude - $deltadeparturelongitude;
if($mindeparturelongitude < -180){
    $Departurelngoutofrange=true;
    $mindeparturelongitude +=360 ;
}
$maxdeparturelongitude = $departureLongitude + $deltadeparturelongitude;
if($maxdeparturelongitude > 180){
    $Departurelngoutofrange=true;
    $maxdeparturelongitude -=360 ;
}

//min max Destination Longitude
$deltadestinationlongitude = ($searchradius * 360)/(24901*cos(deg2rad($destinationLongitude)));
$mindestinationlongitude = $destinationLongitude - $deltadestinationlongitude;
if($mindestinationlongitude < -180){
    $destinationlngoutofrange=true;
    $mindestinationlongitude +=360 ;
}
$maxdestinationlongitude = $destinationLongitude + $deltadestinationlongitude;
if($maxdestinationlongitude > 180){
    $destinationlngoutofrange=true;
    $maxdestinationlongitude -=360 ;
}

/*
Latitude ranges from -90 to +90
10*180 - plus/minus 10 *180
12430 -total miles across Latitude
*/

//min max Departure Latitude
$deltadeparturelatitude = ($searchradius * 180)/12430;
$mindeparturelatitude = $departureLatitude - $deltadeparturelatitude;
if($mindeparturelatitude < -90){
    $mindeparturelatitude = -90;
}
$maxdeparturelatitude = $departureLatitude + $deltadeparturelatitude;
if($maxdeparturelatitude > 90){
    $maxdeparturelatitude = 90;
}


//min max Destination Latitude
$deltadestinationlatitude = $searchradius*180/12430;
$mindestinationlatitude = $destinationLatitude - $deltadestinationlatitude;
if($mindestinationlatitude < -90){
    $mindestinationlatitude = -90;
}
$maxdestinationlatitude = $destinationLatitude + $deltadestinationlatitude;
if($maxdestinationlatitude > 90){
    $maxdestinationlatitude = 90;
}

$sql = "select * from carsharetrips where ";

//departureLongitude query
if($Departurelngoutofrange){
    $sql .= "((departureLongitude > $mindeparturelongitude) or (departureLongitude > $maxdeparturelongitude))";
}
else{
    $sql .= "(departureLongitude between $mindeparturelongitude and $maxdeparturelongitude)";
}

//departureLatitude query
$sql .= "AND (departureLatitude between $mindeparturelatitude and $maxdeparturelatitude)";


//destinationLongitude query
if($destinationlngoutofrange)
{
    $sql .= "AND ((destinationLongitude > $mindestinationlongitude) or (destinationLongitude > $maxdestinationlongitude))";
}
else{
    $sql .= "AND (destinationLongitude between $mindestinationlongitude and $maxdestinationlongitude)";
}

//destinationLatitude query
$sql .= "AND (destinationLatitude between $mindestinationlatitude and $maxdestinationlatitude)";
//echo $sql;
$result = mysqli_query($conn, $sql);
if(!$result){
    echo "ERROR: Unable to excecute: $sql. " . mysqli_error($conn); 
    exit;   
}

if(mysqli_num_rows($result) ==0){
    echo "<div class='alert alert-info noresults'>There are no journeys matching your search!</div>"; 
    exit;
}

echo "<div class='alert alert-info journeysummary'>From $departure to $destination.<br />Closest Journeys:</div>";            

echo '<div id="tripresults">'; 
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    if($row['regular']=="N"){
        $frequency = "One-off journey";
        $time = $row['date'] .' at '. $row['time'];
        //for one-off journey check date is future
        $source = $row['date'];
        $tripdate = DateTime::createFromFormat('D d M, Y',$source);
        $today = date("D d M, Y");
        $todaydate = DateTime::createFromFormat('D d M, Y',$today);
        // var_dump($tripdate);
        // var_dump($todaydate);
        if($tripdate < $todaydate) {echo("skipping");continue;}

    }
    else{
        $frequency = "Regular";
        $array = [];
        if($row['monday']==1){array_push($array,"Mon");}
        if($row['tuesday']==1){array_push($array,"Tue");}
        if($row['wednesday']==1){array_push($array,"Wed");}
        if($row['thursday']==1){array_push($array,"Thu");}
        if($row['friday']==1){array_push($array,"Fri");}
        if($row['saturday']==1){array_push($array,"Sat");}
        if($row['sunday']==1){array_push($array,"Sun");}
        $time = implode("-",$array). " at " .$row['time'];
    }
    $trip_departure = $row['departure'];
    $trip_destination = $row['destination'];
    $price = $row['price'];
    $seatsavailable = $row['seatsavailable'];

    $personid = $row['user_id'];

    $sql1  = "select * from users where id = '$personid' limit 1";
    $result1 = mysqli_query($conn,$sql1);

    if(!$result1){
        echo "ERROR: Unable to excecute: $sql. " . mysqli_error($conn); 
        exit;
    }
    $row2 = mysqli_fetch_array($result1,MYSQLI_ASSOC);
    $firstname = $row2['firstname'];
    $gender = $row2['gender'];
    $moreinfo = $row2['moreinformation'];
    $profilepicture = $row2['profilepicture'];
    if(isset($_SESSION['username'])){
        $phonenumber = $row2['phonenumber'];
    }
    else{
        $phonenumber = "Please sign up! Only members have access to contact information.";   
    }

    echo "
        <h4 class ='row'>
            <div class='col-sm-2'>
                <div class='driver'>$firstname</div>
                <div>
                    <img class='profile' src='$profilepicture'></img>
                </div>
            </div>
            <div class='col-sm-8 journey'>
                <div><span class='departure'>Departure : </span> $trip_departure </div>
                <div><span class='destination'>Destination : </span> $trip_destination </div>
                <div class='time'>$time</div>
                <div>$frequency</div>
            </div>
            <div class='col-sm-2 journey2'>
                <div class='price'>$$price</div>
                <div class='perseat'>Per Seat</div>
                <div class='seatsavailable'>$seatsavailable left</div>
            </div>
        </h4>";

        echo"
        <div class='moreinfo'>
            <div>
                <div>Gender : $gender</div>
                <div class='telephone'>
                        &#9742: $phonenumber
                </div>
            </div>
            <div class='aboutme'> 
                    About me: $moreinfo
            </div>
        </div>   
    ";

}

echo '</div>'

?>