$(async () => {

    var invitaciones = $('#tablaInvitadosEvento').find('tr[invitacionid]');
    var presentaciones = $('#tablaJuegosEvento').find('tr[presentacionid]');
    var dataTableInvitados = $('#dataTableInvitados');
    var dataTableJuegos = $('#dataTableJuegos');
    var idEvento = $('[name=edita_evento_form]').attr('eventoid');

    // programamos cada uno de los botones de borra de las invitaciones
    invitaciones.each(i => {

        invitaciones.eq(i).children().last()
            .click(function (e) {
                e.preventDefault();

                var tr = $(this).parent();
                var idInvitacion = tr.attr('invitacionid');

                $.ajax({
                    type: "DELETE",
                    url: "/api/invitacion",
                    data: JSON.stringify({
                        invitacion: {
                            id: idInvitacion
                        }
                    }),
                    dataType: "json",
                    success: function (response) {
                        if (response.ok) {
                            if (tr.parent().children().length === 1) {
                                $('<tr>').addClass('emptyHolder').append(
                                    $('<td>').attr('colspan', 5)
                                        .append(
                                            $('<p>').addClass('h5 text-light m-0')
                                                .text('No hay invitados :(')
                                        )
                                ).appendTo(tr.parent());
                            }

                            tr.remove();
                        }
                    }
                });
            });
    })

    // programamos cada uno de los botones de borra de las presentaciones
    presentaciones.each(i => {

        presentaciones.eq(i).children().last()
            .click(function (e) {
                e.preventDefault();

                var tr = $(this).parent();
                var idPresentacion = tr.attr('presentacionid');

                $.ajax({
                    type: "DELETE",
                    url: "/api/presentacion",
                    data: JSON.stringify({
                        presentacion: {
                            id: idPresentacion
                        }
                    }),
                    dataType: "json",
                    success: function (response) {
                        if (response.ok) {
                            console.log(tr);
                            if (tr.parent().children().length === 1) {
                                $('<tr>').addClass('emptyHolder').append(
                                    $('<td>').attr('colspan', 3)
                                        .append(
                                            $('<p>').addClass('h5 text-light m-0')
                                                .text('No hay juegos :(')
                                        )
                                ).appendTo(tr.parent());
                            }

                            tr.remove();
                        }
                    }
                });
            });
    })


    lanzaDataTableInvitados(dataTableInvitados, idEvento);

    lanzaDataTableJuegos(dataTableJuegos, idEvento);

    $('#btnModalInvitados').click(() => {
        lanzaDataTableInvitados(dataTableInvitados, idEvento);

    })

    $('#btnModalJuegos').click(() => {
        lanzaDataTableJuegos(dataTableJuegos, idEvento);

    })

});

async function lanzaDataTableInvitados(tabla, idEvento) {
    if (tabla.DataTable()) {
        tabla.DataTable().destroy();
    }

    var usuarios = await getUsuariosDisponibles(idEvento);

    usuarios.usuarios.forEach(usuario => {
        usuario.añadir = `<button class="bg-transparent border-0" usuarioid="${usuario.id}"><i class="fa fa-plus"></i></button>`
    });

    console.log(usuarios);
    tabla.DataTable({
        data: usuarios.usuarios,
        columns: [
            { data: 'email' },
            { data: 'nombre' },
            { data: 'ap1' },
            { data: 'tlf' },
            { data: 'añadir' },
        ]
    });

    // boton añade invitado
    tabla.find('button').click(e => {
        var boton = $(e.currentTarget);

        var data = {
            invitacion: JSON.stringify({
                usuario_id: boton.attr('usuarioid'),
                evento_id: idEvento,
                asiste: false
            })
        }

        $.post("/api/invitacion", data,
            function (data, textStatus, jqXHR) {
                if (data.ok) {
                    let usuario = data.invitacion.usuario;
                    let asiste = data.invitacion.asiste ? '✅' : '❌';

                    console.log(data);
                    boton.parent().parent().remove();

                    let nuevoTr = `<tr invitacionid="${data.invitacion.id}">
                        <td>${usuario.email}</td>
                        <td>${usuario.nombre}</td>
                        <td>${usuario.ap1}</td>
                        <td>${asiste}</td>
                        <td>
                            <span class="fa fa-times h5 m-0"></span>
                        </td>
                    </tr>`;

                    if ($('#tablaInvitadosEvento .emptyHolder').length > 0) {
                        $('#tablaInvitadosEvento tbody').empty();
                    }

                    $(nuevoTr).appendTo($('#tablaInvitadosEvento tbody')).children().last()
                        .click(function (e) {
                            e.preventDefault();

                            var tr = $(this).parent();
                            var idInvitacion = tr.attr('invitacionid');

                            $.ajax({
                                type: "DELETE",
                                url: "/api/invitacion",
                                data: JSON.stringify({
                                    invitacion: {
                                        id: idInvitacion
                                    }
                                }),
                                dataType: "json",
                                success: function (response) {
                                    if (response.ok) {
                                        if (tr.parent().children().length === 1) {
                                            $('<tr>').addClass('emptyHolder').append(
                                                $('<td>').attr('colspan', 5)
                                                    .append(
                                                        $('<p>').addClass('h5 text-light m-0')
                                                            .text('No hay invitados :(')
                                                    )
                                            ).appendTo(tr.parent());
                                        }

                                        tr.remove();
                                    }
                                }
                            });
                        });
                }
            },
            "json"
        );

    });
}

async function lanzaDataTableJuegos(tabla, idEvento) {
    if (tabla.DataTable()) {
        tabla.DataTable().destroy();
    }

    var juegos = await getJuegosDisponibles(idEvento);

    juegos.juegos.forEach(juego => {
        juego.añadir = `<button class="bg-transparent border-0" juegoid="${juego.id}"><i class="fa fa-plus"></i></button>`;
        juego.nJugadores = juego.minJugadores === juego.maxJugadores ? juego.maxJugadores : juego.minJugadores + ' - ' + juego.maxJugadores;
    });

    console.log(juegos);

    tabla.DataTable({
        data: juegos.juegos,
        columns: [
            { data: 'nombre' },
            { data: 'anchoTablero' },
            { data: 'altoTablero' },
            { data: 'nJugadores' },
            { data: 'añadir' },
        ]
    });

    // boton añade invitado
    tabla.find('button').click(e => {
        var boton = $(e.currentTarget);

        var data = {
            presentacion: JSON.stringify({
                juego_id: boton.attr('juegoid'),
                evento_id: idEvento,
            })
        }

        $.post("/api/presentacion", data,
            function (data, textStatus, jqXHR) {
                if (data.ok) {
                    let juego = data.presentacion.juego;
                    let nJugadores = juego.minJugadores === juego.maxJugadores ? juego.maxJugadores : juego.minJugadores + ' - ' + juego.maxJugadores;

                    boton.parent().parent().remove();

                    let nuevoTr = `<tr presentacionid="${data.presentacion.id}">
                        <td>${juego.nombre}</td>
                        <td>${nJugadores}</td>
                        <td>
                            <span class="fa fa-times h5 m-0"></span>
                        </td>
                    </tr>`;

                    if ($('#tablaJuegosEvento .emptyHolder').length > 0) {
                        $('#tablaJuegosEvento tbody').empty();
                    }

                    $(nuevoTr).appendTo($('#tablaJuegosEvento tbody')).children().last()
                        .click(function (e) {
                            e.preventDefault();

                            var tr = $(this).parent();
                            var idPresentacion = tr.attr('presentacionid');

                            $.ajax({
                                type: "DELETE",
                                url: "/api/presentacion",
                                data: JSON.stringify({
                                    presentacion: {
                                        id: idPresentacion
                                    }
                                }),
                                dataType: "json",
                                success: function (response) {
                                    if (response.ok) {
                                        if (tr.parent().children().length === 1) {
                                            $('<tr>').addClass('emptyHolder').append(
                                                $('<td>').attr('colspan', 5)
                                                    .append(
                                                        $('<p>').addClass('h5 text-light m-0')
                                                            .text('No hay juegos :(')
                                                    )
                                            ).appendTo(tr.parent());
                                        }

                                        tr.remove();
                                    }
                                }
                            });
                        });
                }
            },
            "json"
        );

    });
}

async function getJuegosDisponibles(idEvento) {

    idEvento = idEvento === '' ? 0 : idEvento;

    return juegos = await $.ajax({
        type: "GET",
        url: "/api/juego/notEvento/" + idEvento,
    })
}

async function getUsuariosDisponibles(idEvento) {

    idEvento = idEvento === '' ? 0 : idEvento;

    return usuarios = await $.ajax({
        type: "GET",
        url: "/api/usuario/notEvento/" + idEvento,
    })
}