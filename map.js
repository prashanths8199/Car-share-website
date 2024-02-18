var coordinate = {
    lat:12.9719,
    lng:77.5937
};

var mapOptions ={
    center : coordinate,
    zoom : 13,
    mapTypeId : google.maps.MapTypeId.ROADMAP
};

var map,directionRender,directionService ;

var options = {
    types : ['(cities)']
};
var input1 = document.getElementById("departure");
var autocomplete1 = new google.maps.places.Autocomplete(input1,options);

var input2 = document.getElementById("destination");
var autocomplete2 = new google.maps.places.Autocomplete(input2,options);

var input3 = document.getElementById("departure2");
var autocomplete3 = new google.maps.places.Autocomplete(input3,options);

var input4 = document.getElementById("destination2");
var autocomplete4 = new google.maps.places.Autocomplete(input4,options);

google.maps.event.addDomListener(window,"load",initialise);

function initialise(){
    directionService = new google.maps.DirectionsService();
    directionRender = new google.maps.DirectionsRenderer();
    map = new google.maps.Map(document.getElementById("map"),mapOptions);
    directionRender.setMap(map);

}

google.maps.event.addListener(autocomplete1,'place_changed',calculateRoute);
google.maps.event.addListener(autocomplete2,'place_changed',calculateRoute);


function calculateRoute(){

    var start = $("#departure").val();
    var end = $("#destination").val();
    var request ={
        origin :start,
        destination :end,
        travelMode : google.maps.DirectionsTravelMode.DRIVING
    }

    if(start && end){
        directionService.route(request,function(result,status){
            if(status == google.maps.DirectionsStatus.OK){
                directionRender.setDirections(result);

            }

        })

    }
    else{
        initialise()
    }
}