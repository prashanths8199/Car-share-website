<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'connection.php';
$userid = $_SESSION['userid'];


$sql = "select * from carsharetrips where user_id= '$userid'";
$result = mysqli_query($conn,$sql);

if($result){
    if(mysqli_num_rows($result)){
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            if($row['regular']=="N"){
                $frequency = "One-off journey";
                $time = $row['date'] .' at '. $row['time'];
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
            $departure = $row['departure'];
            $destination = $row['destination'];
            $price = $row['price'];
            $seatsavailable = $row['seatsavailable'];
            $seatsavailable = $row['seatsavailable'];
            $tripid = $row['trip_id'];
            

            echo "
            <div class = 'row trip'>
                <div class='col-sm-8 journey'>
                    <div><span class='departure'>Departure : </span> .$departure. </div>
                    <div><span class='destination'>Destination : </span> .$destination. </div>
                    <div class='time'>$time</div>
                    <div>$frequency</div>
                </div>
                <div class='col-sm-2'>
                    <div class='price'>$$price</div>
                    <div class='perseat'>Per Seat</div>
                    <div class='seatsavailable'>$seatsavailable left</div>
                </div>
                <div class='col-sm-2'>
                    <button type='button' class='btn btn-lg green' data-toggle='modal' data-target='#edittripModal' data-tripid='$tripid' >Edit</button>
                </div>
            </div>
            </div>
            ";
        }

    }
    else{
        echo "<div class='alert alert-warning'>You have not created any trips</div>";
        
    }

}


?>