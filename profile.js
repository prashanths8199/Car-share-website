$("#updateusernameform").submit(function (event) { 
    $("#spinner").show();
    $("#updateusernameerrormessage").hide();
    event.preventDefault();
    var data_topost = $(this).serializeArray();
    //console.log(data_topost);

    $.ajax({
        url: "updateusername.php",
        type: "POST",
        data: data_topost,
        success: function (response) {
            $("#spinner").hide();
            if(response){
                $("#updateusernameerrormessage").show();
                $("#updateusernameerrormessage").html(response);
                $("#updateusernameerrormessage").slideDown();
            }
            else{
                location.reload();
            }
        },
        error: function(){
            $("#spinner").hide();
            $("#updateusernameerrormessage").show();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#updateusernameerrormessage").html(error);
            $("#updateusernameerrormessage").slideDown();
        }
    });
});

$("#updatepasswordform").submit(function (event) { 
    $("#spinner").show();
    $("#updatepassworderrormessage").hide();
    event.preventDefault();
    var data_topost = $(this).serializeArray();
    //console.log(data_topost);

    $.ajax({
        url: "updatepassword.php",
        type: "POST",
        data: data_topost,
        success: function (response) {
            $("#spinner").hide();

            if(response){
                $("#updatepassworderrormessage").show();
                $("#updatepassworderrormessage").html(response);
                $("#updatepassworderrormessage").slideDown();
            }
            // else{
            //     location.reload();
            // }
        },
        error: function(){
            $("#spinner").hide();
            $("#updatepassworderrormessage").show();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#updatepassworderrormessage").html(error);
            $("#updatepassworderrormessage").slideDown();
        }
    });
});

$("#updateemailform").submit(function (event) { 
    $("#spinner").show();
    $("#updateemailerrormessage").hide();
    event.preventDefault();
    var data_topost = $(this).serializeArray();
    //console.log(data_topost);

    $.ajax({
        url: "updateemail.php",
        type: "POST",
        data: data_topost,
        success: function (response) {
            $("#spinner").hide();
            if(response){
                $("#updateemailerrormessage").show();
                $("#updateemailerrormessage").html(response);
                $("#updateemailerrormessage").slideDown();
            }
            // else{
            //     location.reload();
            // }
        },
        error: function(){
            $("#spinner").hide();
            $("#updateemailerrormessage").show();
            var error = "<div class='alert alert-danger'>Try again later!</div>"
            $("#updateemailerrormessage").html(error);
            $("#updateemailerrormessage").slideDown();
        }
    });
});