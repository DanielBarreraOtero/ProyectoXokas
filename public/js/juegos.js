$(function () {
    $.ajax({
        type: "GET",
        url: "/api/juego",
        success: function (response) {
            console.log(response);
            response.juegos.forEach(juego => {
                console.log(juego);
            });
        }
    });
})