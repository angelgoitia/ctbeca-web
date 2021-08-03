$(document).ready( function () {
    $("#nav-"+statusMenu).addClass("active");

    if(statusMenu == "dashboard")
        $("#title-navbar").html("Inicio");
    else if(statusMenu == "players")
        $("#title-navbar").html("Becados");
    else if(statusMenu == "gameHistory")
        $("#title-navbar").html("Historial Axie infinity");

});