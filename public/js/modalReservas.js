$(function () {

    var modal = $('#c-modalReservas');

    // al clickar en cualquiera de los juegos se muestra el modal y se rellena la primera pantalla con los datos del juego
    $('.product__item').click(async function (e) {
        e.preventDefault();

        var idJuego = $(this).data('id');

        await actualizaModal(idJuego);

        creaDatePickerReservas($('#dataPicker-reservas'));
        modal.modal('show');

    });

    // al cambiar el juego con el select, se pintan los datos de el juego nuevo
    $('#seleccionJuego .selectorJuego').change(function (e) {
        e.preventDefault(e)
        var idJuego = $(this).val();

        actualizaModal(idJuego);
    });

    // al clickar en siguiente se cierra la pestaña de seleccion de juego y se abre la final
    $('#btn-siguiente-reserva').click(() => {

        new bootstrap.Collapse($('#seleccionJuego')[0], {
            toggle: true
        })

        new bootstrap.Collapse($('#seleccionFinal')[0], {
            toggle: true
        })
    })


});

async function actualizaModal(idJuego) {
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
                $('#seleccionJuego .selectNJugadores').eq(1).css({ display: 'none' });
                console.log($('#seleccionJuego .selectNJugadores').val());
            }

            $('#seleccionJuego .NJugSeleccionJuego > span').text(textNJugadores);

        }
    });

}

function creaDatePickerReservas(dateReserv) {
    dateReserv.data('fechaValida', false);

    // creamos una nueva fecha para poner de fecha por defecto la de mañana
    // hay que tener en cuenta que la fecha no puede ser un fin de semana
    fechaDefault = new Date();

    // si hoy no es ni viernes ni sabado, le sumamos 1
    if (fechaDefault.getDay() != 5 && fechaDefault.getDay() != 6) {
        fechaDefault.setDate(fechaDefault.getDate() + 1);
    } else {
        // si si que lo es, comprobamos si es viernes o sabado, y sumamos dias pàra que la fecha
        // default sea el siguiente lunes
        fechaDefault.setDate(fechaDefault.getDate() + ((fechaDefault.getDay() == 5) ? 3 : 2));
    }

    dateReserv.datepicker({
        minDate: 1,
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        maxDate: '+2W',
        defaultDate: fechaDefault,
        beforeShowDay: function (fecha) {
            // comprueba que no se pueda seleccionar un fin de semana
            var respuesta = [true, '', ''];

            if (fecha.getDay() % 6 == 0) {
                respuesta = [false, '', 'día inválido.'];
            }

            return respuesta
        },
        onSelect: function () {
            dateReserv.data('fechaValida', true);
        }
    });

    var dia = dateReserv.data('datepicker').currentDay;
    var mes = dateReserv.data('datepicker').currentMonth + 1;
    var año = dateReserv.data('datepicker').currentYear;

    dateReserv.datepicker().val(((dia < 10) ? "0" + dia : dia) + '/' + ((mes < 10) ? "0" + mes : mes) + '/' + año);
}