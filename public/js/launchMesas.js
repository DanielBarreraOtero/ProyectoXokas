$(async function () {
    var sala = new Sala($('#sala'));
    var almacen = new Almacen($('#almacen'));
    var selectDistri = $('#selectDistribuciones');

    var distribuciones = (await $.ajax({
        type: "GET",
        url: "/api/distribucion",
        success: function (response) {
            return response;
        }
    })).distribuciones;

    console.log(distribuciones);
    distribuciones.forEach(distribucion => {
        $('<option>').val(distribucion.id)
            .text(distribucion.fecha.date.substr(0, 10)).appendTo(selectDistri)
    });
    selectDistri.niceSelect('update');

    // cuando se cambie la distribucion
    selectDistri.change(function (e) {
        e.preventDefault();

        // buscamos cual ha sido seleccionada
        if ($(this).val() != 0) {
            distribuciones.forEach(distribucion => {

                if (distribucion.id == $(this).val()) {
                    // cuando encontremos la distribucion vamos a pintarla en pantalla

                    sala.distribucion = distribucion;
                    almacen.distribucion = distribucion;

                    // primero mandamos todas las mesas al almacen (sin cambiar la posicion base en la BD)
                    sala.mesas.forEach(mesa => {
                        almacen.addMesa(mesa);
                    });


                    // y ahora vamos a pintar todas las mesas
                    distribucion.posicionamientos.forEach(posicionamiento => {
                        almacen.mesas.forEach(mesa => {
                            if (mesa.idBD == posicionamiento.mesa_id) {
                                // como para pintarlo dentro de el div de sala, tenemos que sumarle el offset de este
                                let top = posicionamiento.posY + sala.div.offset().top;
                                let left = posicionamiento.posX + sala.div.offset().left;

                                mesa.posicionamiento = posicionamiento;
                                mesa.div.empty();

                                sala.addMesa(mesa, top, left);
                            }
                        });
                    });



                    return false;
                }

            });

        } else {
            // si la seleccionada es ninguna le ponemos null
            console.log('ninguna');

        }

    })

    $.ajax({
        type: "GET",
        url: "/api/mesa",
        success: function (response) {
            response.mesas.forEach(mesa => {
                let nuevaMesa = new Mesa(mesa.posX, mesa.posY, mesa.ancho, mesa.alto, mesa.sillas, mesa.id);
                nuevaMesa.creaDiv();

                if (nuevaMesa.left >= 0 && nuevaMesa.top >= 0) {
                    sala.addMesa(nuevaMesa, nuevaMesa.top + sala.div.offset().top, nuevaMesa.left + sala.div.offset().left);
                } else {
                    almacen.addMesa(nuevaMesa);
                }
            });
        }
    });
});