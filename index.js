$("#signupform").submit(function (event) { 
    $("#spinner").show();
    $("#signupmessage").hide();
    event.preventDefault();
    var data_topost = $(this).serializeArray();
    //console.log(data_topost);

    $.ajax({
        url: "signup.php",
        type: "POST",
        data: data_topost,
        success: function (response) {
            $("#spinner").hide();
            if(response){
                $("#signupmessage").html(response);
                $("#signupmessage").slideDown();
            }
        },
        error: function(){
            $("#spinner").hide();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#signupmessage").html(error);
            $("#signupmessage").slideDown();
        }
    });
});

$("#loginform").submit(function (event) { 
    $("#spinner").show();
    $("#loginmessage").hide();
    event.preventDefault();
    var data_topost = $(this).serializeArray();
    //console.log(data_topost);

    $.ajax({
        url: "login.php",
        type: "POST",
        data: data_topost,
        success: function (data) {
            $("#spinner").hide();
            if(data == "success"){
                window.location = "trips.php";
            }
            else{
                $("#loginmessage").html(data);
                $("#loginmessage").slideDown();
            }
        },
        error: function(){
            $("#spinner").hide();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#signupmessage").html(error);
            $("#loginmessage").slideDown();
        }
    });
});


$("#forgotpasswordform").submit(function (event) { 
    $("#spinner").show();
    $("#forgotpasswordmessage").hide();
    event.preventDefault();
    var data_topost = $(this).serializeArray();
    //console.log(data_topost);

    $.ajax({
        url: "forgotpassword.php",
        type: "POST",
        data: data_topost,
        success: function (data) {
            $("#spinner").hide();
           $("#forgotpasswordmessage").html(data);
           $("#forgotpasswordmessage").slideDown();

        },
        error: function(){
            $("#spinner").hide();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#forgotpasswordmessage").html(error);
            $("#forgotpasswordmessage").slideDown();
        }
    });
});

var file,imagetype , imagesize,wrongtype;
$("#picture").change(function (e) { 
    e.preventDefault();
    file = this.files[0];
    imagetype = file.type;
    imagesize = file.size;
    var match= ["image/jpeg","image/png","image/jpg"];
    wrongtype = ($.inArray(imagetype,match)==-1);
    if(wrongtype){
        $("#updatepicturemessage").html('<div class="alert alert-danger">Wrong File Format</div>');
        return false;
    }
    if(imagesize >3*1024*1024){
        $("#updatepicturemessage").html('<div class="alert alert-danger">Image size should be less than 3MB!</div>');
        return false;
    }

    var filereader = new FileReader();
    filereader.onload = updatePreview;//callback function
    filereader.readAsDataURL(file);
    
});


function updatePreview(e){
    //console.log(e);
    $("#preview2").attr("src",e.target.result);
}


$("#updatepictureform").submit(function (e) { 
    $("#spinner").show();
    $("#updatepicturemessage").hide();
    e.preventDefault();
    if(!file){
        $("#spinner").hide();
        $("#updatepicturemessage").show();
        $("#updatepicturemessage").html('<div class="alert alert-danger">Please Upload picture</div>');
        $("#updatepicturemessage").slideDown();
        return false;
    }
    if(imagesize >3*1024*1024){
        $("#spinner").hide();
        $("#updatepicturemessage").show();
        $("#updatepicturemessage").html('<div class="alert alert-danger">Image size should be less than 3MB!</div>');
        $("#updatepicturemessage").slideDown();
        return false;
    }

    $.ajax({
        url: "updatepicture.php",
        type: "POST",
        data: new FormData(this),
        contentType : false,
        cache : false,
        processData : false, 
        success: function (data) {
            $("#spinner").hide();
            if(data){
                $("#updatepicturemessage").show();
                $("#updatepicturemessage").html(data);
                $("#updatepicturemessage").slideDown();
            }
            else{
                location.reload();
            }
        },
        error: function(){
            $("#spinner").hide();
            $("#updatepicturemessage").show();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#updatepicturemessage").html(error);
            $("#updatepicturemessage").slideDown();
        }
    });
    
});



$("#searchform").submit(function (e) { 
    $("#spinner").show();
    $("#searchresult").fadeOut();
    e.preventDefault();
    var data_topost = $(this).serializeArray();
    getSearchDepartureCoordinate(data_topost);
});


var geoCoder = new google.maps.Geocoder();
var departureLongitude , departureLatitude;
var destinationLongitude , destinationLatitude;

function getSearchDepartureCoordinate(data_topost){
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
                getSearchDestinationCoordinate(data_topost);
            }
            else{
                getSearchDestinationCoordinate(data_topost);
            }
        }
    )
}



function getSearchDestinationCoordinate(data_topost){
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
                //console.log(data_topost);
                submitSearchRequest(data_topost);
            }
            else{
                submitSearchRequest(data_topost);
            }
        }
    )
}


function submitSearchRequest(data_topost){
   // console.log(data_topost);
    $.ajax({
        url: "search.php",
        type: "POST",
        data: data_topost,
        success: function (response) {
            $("#spinner").hide();
            if(response){
                $("#searchresult").fadeIn();
                $("#searchresult").html(response);
                $("#tripresults").accordion({
                    active:false,
                    collapsible : true,
                    heightStyle :'content',
                    icons : false

                });
            }
        },
        error: function(){
            $("#spinner").hide();
            $("#searchresult").fadeIn();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#searchresult").html(error);
        }
    });
}