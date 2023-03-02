$(async function () {
    var sala = new Sala($('#sala'));
    var almacen = new Almacen($('#almacen'));
    var selectDistri = $('#selectDistribuciones');
    var dialogDistri = $("#dialog-nueva-distri");
    var dialogMesa = $("#dialog-nueva-mesa");
    var dateDistri = $("#datepicker-Distri");

    // pedimos las distribuciones
    var distribuciones = await getDistribuciones();

    // logica de dialogDistri
    creaDialogDistri(dialogDistri, dateDistri, distribuciones, selectDistri);
    creaDialogMesa(dialogMesa, almacen);
    creaDatePickerDistribuciones(dateDistri, distribuciones);

    $('.c-mantenimiento-sala__creaDistri').click(() => {
        dialogDistri.dialog('open');
    })

    $('.c-mantenimiento-sala__creaMesa').click(() => {
        dialogMesa.dialog('open');
    })

    // rellenamos el select de distribuciones
    actualizaSelectDistri(distribuciones, selectDistri);

    // cuando se cambie la distribucion
    selectDistri.change(async function (e) {
        e.preventDefault();

        // buscamos cual ha sido seleccionada
        if ($(this).val() != 0) {
            distribuciones.forEach(async distribucion => {

                if (distribucion.id == $(this).val()) {
                    // cuando encontremos la distribucion
                    // La actualizamos

                    distribucion = await getDistribucion(distribucion.id);

                    // Y la pintamos
                    pintaMesasDistribucion(distribucion, sala, almacen);

                    // una vez se pinte, cortamos el bucle
                    return false;
                }

            });

        } else {
            // si la seleccionada es ninguna pintamos la sala base
            pintaSalaBase(sala, almacen);
        }

        // al cambiar de distribucion vamos a hacer que se alctualize el select
        // por si se ha creado alguna nueva distribucion
        distribuciones = await getDistribuciones();
        actualizaSelectDistri(distribuciones, selectDistri);

    })

    // al cargar por primera vez cargamos la sala base
    pintaSalaBase(sala, almacen);

});

async function getDistribuciones() {
    return (await $.ajax({
        type: "GET",
        url: "/api/distribucion",
        success: function (response) {
            return response;
        }
    })).distribuciones;
}

async function getDistribucion(id) {
    return (await $.ajax({
        type: "GET",
        url: "/api/distribucion/" + id,
        success: function (response) {
            return response;
        }
    })).distribuciones[0];
}

function actualizaSelectDistri(distribuciones, selectDistri) {
    // nos guardamos la opcion por defecto y el valor que tuviese seleccionado
    var optDefault = selectDistri.children()[0];
    var valActual = selectDistri.val();

    // vaciamos el select y volvemos a añadir la opcion por defecto
    selectDistri.empty().append(optDefault);

    distribuciones.forEach(distribucion => {
        $('<option>').val(distribucion.id)
            .text(distribucion.fecha.date.substr(0, 10)).appendTo(selectDistri)
    });

    // una vez han sido actualizadas las distribuciones
    // volvemos a seleccionar la que habia escogido el usuario
    selectDistri.val(valActual);

    selectDistri.niceSelect('update');
}

// manda todas las mesas al almacen sin cambiar su posicion en la BD
function limpiaSala(sala, almacen) {
    sala.mesas.forEach(mesa => {
        almacen.addMesa(mesa);
    });

    sala.mesas = [];
}

function pintaSalaBase(sala, almacen) {
    sala.distribucion = null;
    almacen.distribucion = null;

    // primero mandamos todas las mesas al almacen (sin cambiar la posicion base en la BD)
    limpiaSala(sala, almacen);

    // vaciamos el almacen para que cuando pidamos las mesas desde la BD
    // no haya duplicados
    almacen.mesas = [];
    almacen.div.empty();

    $.ajax({
        type: "GET",
        url: "/api/mesa",
        success: function (response) {
            response.mesas.forEach(mesa => {
                let nuevaMesa = new Mesa(mesa.posX, mesa.posY, mesa.ancho, mesa.alto, mesa.sillas, mesa.id, mesa.reservas);
                nuevaMesa.creaDiv();

                if (nuevaMesa.left >= 0 && nuevaMesa.top >= 0) {
                    sala.addMesa(nuevaMesa, nuevaMesa.top + sala.div.offset().top, nuevaMesa.left + sala.div.offset().left);
                } else {
                    almacen.addMesa(nuevaMesa);
                }
            });
        }
    });
}

function pintaMesasDistribucion(distribucion, sala, almacen) {
    sala.distribucion = distribucion;
    almacen.distribucion = distribucion;

    // primero mandamos todas las mesas al almacen (sin cambiar la posicion base en la BD)
    limpiaSala(sala, almacen);

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
}

async function creaDialogDistri(dialog, dateDistri, distribuciones, selectDistri) {
    dialog.dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
            'Cancelar': function () {
                $(this).dialog("close");
            },
            "Crear": async function () {
                if (dateDistri.data('fechaValida')) {
                    let newDistri = await creaDistribucion(dateDistri.data('datepicker'));
                    console.log(newDistri);
                    distribuciones.push(newDistri);

                    actualizaSelectDistri(distribuciones, selectDistri);
                } else {
                    console.log('error');
                }
            },
        }
    }).dialog('close');
}

function creaDialogMesa(dialog, almacen) {
    dialog.dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
            'Cancelar': function () {
                $(this).dialog("close");
            },
            "Crear": async function () {
                campos = $(this).find('input');
                let formuCompleto = true;

                campos.each((i) => {
                    let campo = campos.eq(i)
                    if (campo.val() === '') {
                        formuCompleto = false;
                    }
                });

                if (formuCompleto) {
                    let newMes = {
                        mesa: JSON.stringify({
                            ancho: campos.eq(0).val(),
                            alto: campos.eq(1).val(),
                            posX: -1,
                            posY: -1,
                            sillas: parseInt(campos.eq(2).val()),
                        })
                    };

                    $.post("/api/mesa", newMes,
                        function (data) {
                            dialog.dialog("close");
                            let mesa = data.mesa;
                            let nuevaMesa = new Mesa(mesa.posX, mesa.posY, mesa.ancho, mesa.alto, mesa.sillas, mesa.id, mesa.reservas);

                            nuevaMesa.creaDiv();
                            almacen.addMesa(nuevaMesa);
                        }
                    );
                }
            },
        }
    }).dialog('close');
}

function creaDatePickerDistribuciones(dateDistri, distribuciones) {
    dateDistri.data('fechaValida', false);

    var diasInvalidos = [];

    distribuciones.forEach(distribucion => {
        diasInvalidos.push(distribucion.fecha.date.substr(0, 10));
    });

    dateDistri.datepicker({
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
        beforeShowDay: function (fecha) {
            // comprueba que no se pueda seleccionar una fecha en la que ya exista una distribucion o un fin de semana
            var respuesta = [true, '', ''];

            var dia = fecha.getDate();
            var mes = fecha.getMonth() + 1;
            var año = fecha.getFullYear();
            var cadenaFecha = (año + '-' + ((mes < 10) ? "0" + mes : mes) + '-' + ((dia < 10) ? "0" + dia : dia));
            if (fecha.getDay() % 6 == 0 || diasInvalidos.indexOf(cadenaFecha) > -1) {
                respuesta = [false, '', 'día inválido.'];
            }

            return respuesta
        },
        onSelect: function () {
            dateDistri.data('fechaValida', true);
        }
    });
}

async function creaDistribucion(datepicker) {
    console.log(datepicker);
    var newDistri = {
        distribucion: JSON.stringify({
            fecha: new Date(datepicker.currentYear, datepicker.currentMonth, datepicker.currentDay + 1)
        })
    }
    let response = await $.post("/api/distribucion", newDistri)

    return response.distribucion;
}