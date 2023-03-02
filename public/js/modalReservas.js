$(function () {

    var modal = $('#c-modalReservas');
    salaReservas = new SalaReservas($('#salaReserva'));

    // al clickar en cualquiera de los juegos se muestra el modal y se rellena la primera pantalla con los datos del juego
    $('.product__item').click(async function (e) {
        e.preventDefault();

        var idJuego = $(this).data('id');

        creaDatePickerReservas($('#dataPicker-reservas'), salaReservas);

        await actualizaModal(idJuego, salaReservas);

        modal.modal('show');

    });

    // al cambiar el juego con el select, se pintan los datos de el juego nuevo
    $('#seleccionJuego .selectorJuego').change(function (e) {
        e.preventDefault(e)
        var idJuego = $(this).val();

        actualizaModal(idJuego, salaReservas);
    });

    // al cambiar los jugadores en la pagina de seleccionJuego, tambien se cambian en la de seleccionFinal
    $('#seleccionJuego .selectNJugadores').change(function (e) {
        e.preventDefault(e)

        $('#seleccionFinal .selectNJugadoresFinal').val($(this).val()).niceSelect('update');

    });

    // al cambiar los jugadores en la pagina de seleccionFinal, tambien se cambian en la de seleccionJuego
    $('#seleccionFinal .selectNJugadoresFinal').change(function (e) {
        e.preventDefault(e)

        $('#seleccionJuego .selectNJugadores').val($(this).val()).niceSelect('update');

    });

    // al cambiar el juego con el select, se pintan los datos de el juego nuevo
    $('#seleccionJuegoFinal').change(function (e) {
        e.preventDefault(e)
        var idJuego = $(this).val();

        actualizaModal(idJuego, salaReservas);
    });

    // al clickar en siguiente se cierra la pestaña de seleccion de juego y se abre la final
    $('#btn-siguiente-reserva').click(async () => {
        new bootstrap.Collapse($('#seleccionJuego')[0], {
            toggle: true
        })

        new bootstrap.Collapse($('#seleccionFinal')[0], {
            toggle: true
        })

        var dia = $('#dataPicker-reservas').data('datepicker').currentDay;
        var mes = $('#dataPicker-reservas').data('datepicker').currentMonth + 1;
        var año = $('#dataPicker-reservas').data('datepicker').currentYear;

        var fecha = año + '-' + ((mes < 10) ? "0" + mes : mes) + '-' + ((dia < 10) ? "0" + dia : dia);

        await cargaSalaReservas(fecha, salaReservas);
        await actualizaDisponibilidadMesas(fecha, $('#seleccionFinal .tramoInicioReservas'), $('#seleccionFinal .tramoFinReservas'), salaReservas);

    })

    $('#seleccionFinal .tramoInicioReservas').data('lastVal', $('#seleccionFinal .tramoInicioReservas').val());
    $('#seleccionFinal .tramoFinReservas').data('lastVal', $('#seleccionFinal .tramoFinReservas').val());

    // al cambiar de tramoInicio, se tiene que ver que sea menor que el tramoFin
    $('#seleccionFinal .tramoInicioReservas').change(async function (e) {
        e.preventDefault(e)

        let tramoInicio = $(this);
        let tramoFin = $('#seleccionFinal .tramoFinReservas');

        let fechaInicio = getFechaFromTramo(tramoInicio);

        let fechaFin = getFechaFromTramo(tramoFin);

        if (fechaInicio < fechaFin) {
            // si es menor, lo actualizamos como nuevo valor y actualizamos la disponibilidad de las mesas
            tramoInicio.data('lastVal', tramoInicio.val());

            actualizaDisponibilidadMesas($('#dataPicker-reservas').data('fechaBD'), tramoInicio, tramoFin, salaReservas);
        } else {
            // si no, volvemos al valor anterior
            tramoInicio.val(tramoInicio.data('lastVal')).niceSelect('update');
        }

    });

    // al cambiar de tramoInicio, se tiene que ver que sea menor que el tramoFin y actualizamos la disponibilidad de las mesas
    $('#seleccionFinal .tramoFinReservas').change(function (e) {
        e.preventDefault(e)

        let tramoFin = $(this);
        let tramoInicio = $('#seleccionFinal .tramoInicioReservas');

        let fechaFin = getFechaFromTramo(tramoFin);
        let fechaInicio = getFechaFromTramo(tramoInicio);

        if (fechaFin > fechaInicio) {
            // si es mayor, lo actualizamos como nuevo valor y actualizamos la disponibilidad de las mesas
            tramoFin.data('lastVal', tramoFin.val());

            actualizaDisponibilidadMesas($('#dataPicker-reservas').data('fechaBD'), tramoInicio, tramoFin, salaReservas);
        } else {
            // si no, volvemos al valor anterior
            tramoFin.val(tramoFin.data('lastVal')).niceSelect('update');
        }

    });

    $('#btn-reservar-reserva').click(() => {
        if (salaReservas.seleccionada) {
            console.log(salaReservas.seleccionada);

            let datepicker = $('#dataPicker-reservas').data('datepicker');
            let newRes = {
                reserva: JSON.stringify({
                    juego_id: $('#seleccionJuegoFinal').val(),
                    mesa_id: salaReservas.seleccionada.id,
                    tramo_inicio_id: $('#seleccionFinal .tramoInicioReservas').val(),
                    tramo_fin_id: $('#seleccionFinal .tramoFinReservas').val(),
                    fecha: new Date(datepicker.currentYear, datepicker.currentMonth, datepicker.currentDay + 1)
                })
            };

            $.post("/api/reserva", newRes,
                async function (data) {
                    if (data.ok) {
                        console.log('bien');

                        modal.modal('hide')

                        console.log(data);
                    } else {
                        console.log('mal');
                        console.log(data);
                    }
                }
            );
        } else {
            console.log('no haiga mesa');
        }
    })

    // al cambiar de tamaño la pagina, se reescalan las mesas de la sala
    $(window).resize(function () {
        salaReservas.resize();
    });

});

async function actualizaDisponibilidadMesas(fecha, tramoInicio, tramoFin, sala) {
    let reservas = [];
    let arrayInicio = TramoToArray(tramoInicio);
    let arrayFin = TramoToArray(tramoFin);

    let horaInicio = arrayInicio[0] + ":" + arrayInicio[1] + ":" + arrayInicio[2];
    let horaFin = arrayFin[0] + ":" + arrayFin[1] + ":" + arrayFin[2];

    // pedimos las reservas del momento en cuestion
    let response = await $.ajax({
        type: "GET",
        url: "/api/reserva/" + fecha + "/" + horaInicio + "/" + horaFin,
        success: function (response) {
            return response;
        }
    });
    console.log(response);

    if (response.ok) {
        reservas = response.reservas;
    }

    // si ha encontrado alguna
    if (reservas[0]) {

        sala.mesas.forEach(mesa => {
            reservas.forEach(reserva => {

                if (mesa.id === reserva.mesa_id) {
                    mesa.ocupada = true;
                }

                if (mesa.ocupada) {
                    mesa.div.removeClass('disponible')
                    mesa.div.addClass('ocupada')
                    mesa.disponible = false;
                } else {
                    mesa.div.removeClass('ocupada')
                    mesa.div.addClass('disponible')
                    mesa.disponible = true;
                }
            });
        });
    } else {
        sala.mesas.forEach(mesa => {
            mesa.div.removeClass('ocupada')
            mesa.div.addClass('disponible')
            mesa.disponible = true;
        });
    }
}

function getFechaFromTramo(tramo) {
    let hora = TramoToArray(tramo);

    let fecha = new Date();
    fecha.setHours(hora[0] - 0);
    fecha.setMinutes(hora[1] - 0);
    fecha.setSeconds(hora[2] - 0);

    return fecha;
}

function TramoToArray(tramo) {
    return tramo.find('option[value="' + tramo.val() + '"]').html().split(':');
}

async function actualizaModal(idJuego, salaReservas) {
    await $.ajax({
        type: "GET",
        url: "/api/juego/" + idJuego,
        success: function (response) {
            var juego = response.juegos[0];
            var textNJugadores;

            // rellenamos la imagen
            $('#imgSeleccionJuego').attr('src', 'img/juegos/' + juego.imagen);
            $('#imgJuegoSeleccionFinal').attr('src', 'img/juegos/' + juego.imagen)
                .data('juego', juego);
            // select de juego
            $('#seleccionJuego .selectorJuego').val(idJuego).niceSelect('update');
            $('#seleccionJuegoFinal').val(idJuego).niceSelect('update');
            // titulo
            $('#seleccionJuego .tituloJuegoSeleccionado').text(juego.nombre);
            // descripcion TODO:
            $('#seleccionJuego .descrpJuegoSeleccionado').text(juego.descripcion);
            // select de jugadores
            $('#seleccionJuego .tituloJuegoSeleccionado').text(juego.nombre);

            $('#seleccionJuego .selectNJugadores').empty();
            $('#seleccionFinal .selectNJugadoresFinal').empty();

            if (juego.maxJugadores !== juego.minJugadores) {
                textNJugadores = juego.minJugadores + ' / ' + juego.maxJugadores;


                for (let i = juego.minJugadores; i <= juego.maxJugadores; i++) {
                    $('#seleccionJuego .selectNJugadores').append($('<option>', {
                        value: i,
                        text: i
                    }));
                    $('#seleccionFinal .selectNJugadoresFinal').append($('<option>', {
                        value: i,
                        text: i
                    }));
                }
                $('#seleccionJuego .selectNJugadores').niceSelect('update');
                $('#seleccionFinal .selectNJugadoresFinal').niceSelect('update');
            } else {
                textNJugadores = juego.maxJugadores;

                $('#seleccionJuego .selectNJugadores').val(juego.maxJugadores).niceSelect('update');
                $('#seleccionJuego .selectNJugadores').eq(1).css({ display: 'none' });

                $('#seleccionFinal .selectNJugadoresFinal').append($('<option>', {
                    value: textNJugadores,
                    text: textNJugadores
                })).niceSelect('update');
            }

            $('#seleccionJuego .NJugSeleccionJuego > span').text(textNJugadores);

        }
    });

}

function creaDatePickerReservas(dateReserv, salaReservas) {
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
        onSelect: async function () {
            dateReserv.data('fechaValida', true);

            var dia = dateReserv.data('datepicker').currentDay;
            var mes = dateReserv.data('datepicker').currentMonth + 1;
            var año = dateReserv.data('datepicker').currentYear;

            var fecha = año + '-' + ((mes < 10) ? "0" + mes : mes) + '-' + ((dia < 10) ? "0" + dia : dia);
            dateReserv.data('fechaBD', fecha);

            $('#imgJuegoSeleccionFinal').attr('style', '').data('mesa', undefined).appendTo($('#imgJuegoFinalHolder'));

            salaReservas.div.empty();
            salaReservas.mesas = [];
            await cargaSalaReservas(fecha, salaReservas);

            await actualizaDisponibilidadMesas(fecha, $('#seleccionFinal .tramoInicioReservas'), $('#seleccionFinal .tramoFinReservas'), salaReservas);
        }
    });

    var dia = dateReserv.data('datepicker').currentDay;
    var mes = dateReserv.data('datepicker').currentMonth + 1;
    var año = dateReserv.data('datepicker').currentYear;

    dateReserv.data('fechaBD', (año + '/' + ((mes < 10) ? "0" + mes : mes) + '/' + (dia < 10) ? "0" + dia : dia));
    dateReserv.datepicker().val(((dia < 10) ? "0" + dia : dia) + '/' + ((mes < 10) ? "0" + mes : mes) + '/' + año);

}

async function cargaSalaReservas(fecha, salaReservas) {

    var response = await $.ajax({
        type: "GET",
        url: "/api/mesa/fecha/" + fecha,
        success: function (response) {
            console.log(response);
        }
    });

    // si tiene id es una distibucion
    if (response.id) {
        response.posicionamientos.forEach(posicionamiento => {
            salaReservas.addMesa(posicionamiento.mesa, posicionamiento.posY, posicionamiento.posX);
        });
    } else {
        // si no tiene id, es la distribucion base
        response.forEach(mesa => {
            if (mesa.posX > -1 && mesa.posY > -1) {
                salaReservas.addMesa(mesa, mesa.posY, mesa.posX);
            }
        });
    }

}
