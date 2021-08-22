$(document).ready( function () {
    $("#nav-"+statusMenu).addClass("active");

    if(statusMenu == "dashboard")
        $("#title-navbar").html("Inicio");
    else if(statusMenu == "players")
        $("#title-navbar").html("Becados");
    else if(statusMenu == "gameHistory")
        $("#title-navbar").html("Historial Axie infinity");
    else if(statusMenu == "claimHistory")
        $("#title-navbar").html("Historial Reclamos");
    else if(statusMenu == "profile")
        $("#title-navbar").html("Perfil");
    else if(statusMenu == "rates")
        $("#title-navbar").html("Tasas");
    else if(statusMenu == "rate")
        $("#title-navbar").html("Tasa");
    else if(statusMenu == "groups")
        $("#title-navbar").html("Grupos");

});