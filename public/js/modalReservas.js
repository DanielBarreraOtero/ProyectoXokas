$(function () {

    var modal = $('#c-modalReservas');

    $('.product__item').click(async function (e) {
        e.preventDefault();
        // al clickar en cualquiera de los juegos se muestra el modal y se rellena la primera pantalla con los datos del juego

        var idJuego = $(this).data('id');
        var seleccionJuego = $('#seleccionJuego');

        await actualizaModal(modal, idJuego);

        $('#eldatepiker').datepicker();
        modal.modal('show');

    });

    $('#seleccionJuego .selectorJuego').change(function (e) {
        e.preventDefault(e)
        var idJuego = $(this).val();

        actualizaModal(modal, idJuego);
    });


});

async function actualizaModal(modal, idJuego) {
    await $.ajax({
        type: "GET",
        url: "/api/juego/" + idJuego,
        success: function (response) {
            var juego = response.juegos[0];
            var textNJugadores;
            console.log(juego);


            // rellenamos la imagen
            $('#imgSeleccionJuego').attr('src', 'img/juegos/' + juego.imagen)
            // select de juego
            $('#seleccionJuego .selectorJuego').val(idJuego).niceSelect('update');
            // titulo
            $('#seleccionJuego .tituloJuegoSeleccionado').text(juego.nombre);
            // descripcion TODO:
            $('#seleccionJuego .descrpJuegoSeleccionado').text(juego.descripcion);
            // select de jugadores
            $('#seleccionJuego .tituloJuegoSeleccionado').text(juego.nombre);
            if (juego.maxJugadores !== juego.minJugadores) {
                textNJugadores = juego.minJugadores + ' / ' + juego.maxJugadores;

                $('#seleccionJuego .selectNJugadores').empty();

                for (let i = juego.minJugadores; i <= juego.maxJugadores; i++) {
                    $('#seleccionJuego .selectNJugadores').append($('<option>', {
                        value: i,
                        text: i
                    }));
                }
                $('#seleccionJuego .selectNJugadores').niceSelect('update');
            } else {
                textNJugadores = juego.maxJugadores;

                $('#seleccionJuego .selectNJugadores').val(juego.maxJugadores).niceSelect('update');
                $('#seleccionJuego .selectNJugadores').eq(1).css({display: 'none'});
                console.log($('#seleccionJuego .selectNJugadores').val());
            }

            $('#seleccionJuego .NJugSeleccionJuego > span').text(textNJugadores);

        }
    });

}