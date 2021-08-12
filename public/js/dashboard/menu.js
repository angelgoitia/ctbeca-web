$(document).ready( function () {
    $("#nav-"+statusMenu).addClass("active");

    if(statusMenu == "dashboard")
        $("#title-navbar").html("Inicio");
    else if(statusMenu == "players")
        $("#title-navbar").html("Becados");
    else if(statusMenu == "gameHistory")
        $("#title-navbar").html("Historial Axie infinity");
    else if(statusMenu == "depositHistory")
        $("#title-navbar").html("Historial del Pago");
    else if(statusMenu == "profile")
        $("#title-navbar").html("Perfil");

});