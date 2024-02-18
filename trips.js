
$(function(){
    $("#addtripModal").on("shown.bs.modal", function () {
        $('#addtripform')[0].reset();
        $('#addtripmessage').empty();
        $(".regular,.regular2").hide();
        $(".oneoff,.oneoff2").hide();
        google.maps.event.trigger(map,"resize");
        initialise();
    });

getTrips();
var geoCoder = new google.maps.Geocoder();



var myradio = $("input[name='regular']");

myradio.click(function(){
    if($(this).is(':checked')){
        if($(this).val()=='Y'){
            $(".oneoff").hide();
            $(".regular").show();
        }
        else{
            $(".regular").hide();
            $(".oneoff").show();
        }
    }
})

var myradio2 = $("input[name='regular2']");

myradio2.click(function(){
    if($(this).is(':checked')){
        if($(this).val()=='Y'){
            $(".oneoff2").hide();
            $(".regular2").show();
        }
        else{
            $(".regular2").hide();
            $(".oneoff2").show();
        }
    }
})

$("input[name='date'] , input[name='date2']").datepicker({
    numberOfMonths :1,
    minDate : +1,
    maxDate : "12M",
    dateFormat: "D d M, yy"
});

// $('#time , #time2').datetimepicker({
//     format: 'HH:mm',
//     keepOpen: true,
// });




var data_topost;
$("#addtripform").submit(function (e) {
    $("#spinner").show();
    $("#addtripmessage").hide();
    e.preventDefault();
    data_topost = $(this).serializeArray();
    getUncheckedCheckedBox();
    getAddTripDepartureCoordinate();
    
});

function getUncheckedCheckedBox(){
    $('#addtripform input[type=checkbox]:not(:checked)').each(function()
    {data_topost.push({"name": this.name, "value": 0})
    });
    
}

var departureLongitude , departureLatitude;
function getAddTripDepartureCoordinate(){
    geoCoder.geocode(
        {
            address : document.getElementById('departure').value
        },
        function(result,status){
            if(status == google.maps.GeocoderStatus.OK){
                departureLongitude = result[0].geometry.location.lng();
                departureLatitude = result[0].geometry.location.lat();
                data_topost.push({name : 'departureLongitude' , value : departureLongitude});
                data_topost.push({name : 'departureLatitude' , value : departureLatitude});
                getAddTripDestinationCoordinate();
            }
            else{
                getAddTripDestinationCoordinate();
            }
        }
    )
}

var destinationLongitude , destinationLatitude;

function getAddTripDestinationCoordinate(){
    geoCoder.geocode(
        {
            address : document.getElementById('destination').value
        },
        function(result,status){
            if(status == google.maps.GeocoderStatus.OK){
                destinationLongitude = result[0].geometry.location.lng();
                destinationLatitude = result[0].geometry.location.lat();
                data_topost.push({name : 'destinationLongitude' , value : destinationLongitude});
                data_topost.push({name : 'destinationLatitude' , value : destinationLatitude});
                console.log(data_topost);
                submitAddTripRequest();
            }
            else{
                submitAddTripRequest();
            }
        }
    )
}


function submitAddTripRequest(){
    $.ajax({
        url: "addTrips.php",
        type: "POST",
        data: data_topost,
        success: function (response) {
            $("#spinner").hide();
            if(response){
                $("#addtripmessage").hide();
                $("#addtripmessage").html(response);
                $("#addtripmessage").slideDown();
            }
            else{
                //hide modal
                $("#addtripModal").modal('hide');
                //reset form
                $('#addtripform')[0].reset();
                //load trips
                getTrips();
            }
        },
        error: function(){
            $("#spinner").hide();
            $("#addtripmessage").hide();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#addtripmessage").html(error);
            $("#addtripmessage").slideDown();
        }
    });
}


function getTrips(){
    $("#spinner").show();
    $.ajax({
        url: "getTrips.php",
        type: "POST",
        success: function (response) {
            $("#spinner").hide();
            if(response){
                $("#mytrips").hide();
                $("#mytrips").html(response);
                $("#mytrips").fadeIn();
            }
        },
        error: function(){
            $("#spinner").hide();
            $("#mytrips").hide();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#mytrips").html(error);
            $("#mytrips").fadeIn();
        }
    });
}

function formatModal(){
    $("#departure2").val(trip['departure']);
    $("#destination2").val(trip['destination']);
    $("#price2").val(trip['price']);
    $("#seatsavailable2").val(trip['seatsavailable']);
    if(trip['regular'] == 'Y'){
        $("#yes2").prop('checked',true);
        $("#monday2").prop('checked' , trip['monday'] == "1" ? true : false);
        $("#tuesday2").prop('checked' , trip['tuesday'] == "1" ? true : false);
        $("#wednesday2").prop('checked' , trip['wednesday'] == "1" ? true : false);
        $("#thursday2").prop('checked' , trip['thursday'] == "1" ? true : false);
        $("#friday2").prop('checked' , trip['friday'] == "1" ? true : false);
        $("#saturday2").prop('checked' , trip['saturday'] == "1" ? true : false);
        $("#sunday2").prop('checked' , trip['sunday'] == "1" ? true : false);
        $("input[name ='time2']").val(trip['time']);
        $(".oneoff2").hide();
        $(".regular2").show();
    }
    else{
        $("#no2").prop('checked',true);
        $("input[name ='time2']").val(trip['time']);
        $("input[name ='date2']").val(trip['date']);
        $(".regular2").hide();
        $(".oneoff2").show();
        

    }
}



function getUncheckedCheckedBoxForEditForm(data){
    $('#edittripform input[type=checkbox]:not(:checked)').each(function()
    {data.push({"name": this.name, "value": 0})
    });
    
}


function getEditTripDepartureCoordinate(data){
    geoCoder.geocode(
        {
            address : document.getElementById('departure2').value
        },
        function(result,status){
            if(status == google.maps.GeocoderStatus.OK){
                departureLongitude = result[0].geometry.location.lng();
                departureLatitude = result[0].geometry.location.lat();
                data.push({name : 'departureLongitude' , value : departureLongitude});
                data.push({name : 'departureLatitude' , value : departureLatitude});
                getEditTripDestinationCoordinate(data);
            }
            else{
                getEditTripDestinationCoordinate(data);
            }
        }
    )
}


function getEditTripDestinationCoordinate(data){
    geoCoder.geocode(
        {
            address : document.getElementById('destination2').value
        },
        function(result,status){
            if(status == google.maps.GeocoderStatus.OK){
                destinationLongitude = result[0].geometry.location.lng();
                destinationLatitude = result[0].geometry.location.lat();
                data.push({name : 'destinationLongitude' , value : destinationLongitude});
                data.push({name : 'destinationLatitude' , value : destinationLatitude});
                //console.log(data);
                submitEditTripRequest(data);
            }
            else{
                submitEditTripRequest(data);
            }
        }
    )
}


function submitEditTripRequest(data){
    $.ajax({
        url: "updateTrips.php",
        type: "POST",
        data: data,
        success: function (response) {
            $("#spinner").hide();
            if(response){
                $("#edittripmessage").show();
                $("#edittripmessage").html(response);
                $("#edittripmessage").slideDown();
            }
            else{
                //hide modal
                $("#edittripModal").modal('hide');
                //reset form
                $('#edittripform')[0].reset();
                //load trips
                getTrips();
            }
        },
        error: function(){
            $("#spinner").hide();
            $("#edittripmessage").show();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#edittripmessage").html(error);
            $("#edittripmessage").slideDown();
        }
    });
}

var trip;
$("#edittripform").on("show.bs.modal", function (event) {
    $("#edittripmessage").empty();
    var invoker = $(event.relatedTarget);
    console.log(invoker);
    $.ajax({
        url : "gettripdetails.php",
        type : "POST",
        data : {tripid : invoker.data('tripid')},
        success : function(data){
            //console.log(data);
            //JSON.parse(data);
            if(data){
                if(data == "error"){
                    var error = "<div class='alert alert-danger'>Try again later!</div>"
                    $("#edittripmessage").html(error);
                }
                else{
                    trip = JSON.parse(data);
                    console.log(trip);
                    formatModal();
                }
            }

        },
        error : function(){
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#edittripmessage").html(error);
        }
    });

    $("#edittripform").submit(function (e) {
        $("#spinner").show();
        $("#edittripmessage").hide();
        e.preventDefault();
        //$("#edittripmessage").empty();
        var data = $(this).serializeArray();
        data.push({name : 'tripid' , value : invoker.data('tripid')});
        getUncheckedCheckedBoxForEditForm(data);
        getEditTripDepartureCoordinate(data);
    });

    $("#deletetrip").click(function (e) { 
        $("#spinner").show();
        $("#edittripmessage").hide();
        e.preventDefault();
        $.ajax({
            url : "deletetrips.php",
            type : "POST",
            data : {tripid : invoker.data('tripid')},
            success : function(data){
                //console.log(data);
                //JSON.parse(data);
                $("#spinner").hide();
                if(data){
                    $("#edittripmessage").show();
                    var error = "<div class='alert alert-danger'>Trip cannot be deleted. Try again later!!</div>"
                    $("#edittripmessage").html(error);
                    $("#edittripmessage").slideDown();
                }
                else{
                    $("#edittripModal").modal('hide');
                    //load trips
                    getTrips();
                }
                
    
            },
            error : function(){
                $("#spinner").hide();
                $("#edittripmessage").show();
                var error = "<div class='alert alert-danger'>Try again later!</div>"
                $("#edittripmessage").html(error);
                $("#edittripmessage").slideDown();

            }
        });
        
    });
    
});

});

