$(function() {

    var modal = $('#c-modalReservas');

    $('.product__item').click(function (e) { 
        e.preventDefault();
        // al clickar en cualquiera de los juegos se muestra el modal y se rellena la primera pantalla con los datos del juego

        var idJuego = $(this).data('id');
        var seleccionJuego = $('#seleccionJuego');

        modal.modal('show');


    });
});