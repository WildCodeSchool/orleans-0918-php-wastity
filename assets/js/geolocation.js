let positionOption = {
    enableHighAccuracy: true,
    maximumAge        : 0,
    timeout           : 2
};

function myPosition(position) {
    fetch('/foodhero/position/'+position.coords.latitude+'/'+position.coords.longitude);
}

function erreurPosition(error) {
    var info = "Erreur lors de la géolocalisation : ";
    switch (error.code) {
        case error.TIMEOUT:
            info += "Timeout !";
            break;
        case error.PERMISSION_DENIED:
            info += "Vous n’avez pas donné la permission";
            break;
        case error.POSITION_UNAVAILABLE:
            info += "La position n’a pu être déterminée";
            break;
        case error.UNKNOWN_ERROR:
            info += "Erreur inconnue";
            break;
    }
    document.getElementById("infoposition").innerHTML = info;
}

if(navigator.geolocation) {

    navigator.geolocation.getCurrentPosition(myPosition, erreurPosition, positionOption);
}
