class Sala {

    constructor(div) {
        this.mesas = [];
        this.div = div;
        this.distribucion = null;

        this.div.droppable({
            drop: function (ev, ui) {
                // coge el div de la mesa que esta siendo soltada
                var divMesa = ui.draggable;
                var mesa = divMesa.data('mesa');
                var sala = $(this).data('sala');

                divMesa.newLeft = parseInt(ui.offset.left);
                divMesa.newTop = parseInt(ui.offset.top);
                divMesa.newRight = divMesa.newLeft + divMesa.width();
                divMesa.newBottom = divMesa.newTop + divMesa.height();

                if (posicionValida(divMesa, sala.div)) {
                    sala.addMesa(mesa, divMesa.newTop, divMesa.newLeft);
                    mesa.update();

                } else if (mesa.padre !== sala) {
                    let texto = [$('<p>').html(mesa.width + ' cm').css({ margin: 0 }).attr({ 'class': 'text-light' }),
                    $('<p>').html('x').css({ margin: 0 }).attr({ 'class': 'text-light' }),
                    $('<p>').html(mesa.height + ' cm').css({ margin: 0 }).attr({ 'class': 'text-light' })];

                    divMesa.css({
                        display: 'flex',
                        flexDirection: 'column',
                        justifyContent: 'center',
                        alignItems: 'center',
                        position: '',
                        width: 100,
                        height: 100
                    }).html(texto);
                }
            }
        }).data('sala', this);
    }

    addMesa(mesa, top, left) {
        // lo quitamos del array de mesas de su padre para meterlo en el de este
        if (mesa.padre instanceof Almacen) {
            let index = mesa.padre.mesas.indexOf(mesa);

            mesa.padre.mesas.splice(index, 1);
        }

        this.mesas.push(mesa);
        mesa.padre = this;

        mesa.top = top - this.div.offset().top;
        mesa.left = left - this.div.offset().left;
        mesa.bottom = top + mesa.height - this.div.offset().top;
        mesa.right = left + mesa.width - this.div.offset().left;

        if (mesa.div) {
            mesa.div.appendTo(this.div)
                .css({
                    display: '',
                    position: 'absolute',
                    top: top,
                    left: left,
                    backgroundColor: '',

                    // nos aseguramos de que la mesa tiene el tama??o adecuado
                    height: mesa.height,
                    width: mesa.width
                });

        }
    }

}

class Almacen {

    constructor(div) {
        this.mesas = [];
        this.div = div;
        this.distribucion = null;

        this.div.droppable({
            drop: function (ev, ui) {
                let divMesa = ui.draggable;
                let mesa = divMesa.data('mesa');
                let almacen = $(this).data('almacen');

                almacen.addMesa(mesa);
                mesa.update();
            }
        })
            .data('almacen', this);
    }

    addMesa(mesa) {
        // lo quitamos del array de mesas de su padre para meterlo en el de este
        if (mesa.padre instanceof Almacen) {
            let index = mesa.padre.mesas.indexOf(mesa);

            mesa.padre.mesas.splice(index, 1);
        }

        this.mesas.push(mesa);
        mesa.padre = this;

        mesa.top = -1;
        mesa.left = -1;
        mesa.bottom = -1;
        mesa.right = -1;

        if (mesa.div) {
            let texto = [$('<p>').html(mesa.width + ' cm').css({ margin: 0 }).attr({ 'class': 'text-light' }),
            $('<p>').html('x').css({ margin: 0 }).attr({ 'class': 'text-light' }),
            $('<p>').html(mesa.height + ' cm').css({ margin: 0 }).attr({ 'class': 'text-light' })];

            mesa.div.appendTo(this.div)
                // le ponemos el mismo tama??o a todas las mesas
                .css({
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'center',
                    alignItems: 'center',
                    position: '',
                    width: 100,
                    height: 100
                })
                .html(texto);

            if (!mesa.reservas[0]) {
                mesa.div.append(
                    $('<button>').addClass('btn site-btn c-mantenimiento-sala__borraMesa')
                        .html($('<span>').addClass('fa fa-trash'))
                        .click(() => {

                            mesaDeleteAlert(mesa).appendTo('body');
                        })
                );
            }
        }
    }

}

class Mesa {

    constructor(left, top, width, height, sillas, id, reservas) {
        this.left = left;
        this.top = top;
        this.width = width;
        this.height = height;
        this.right = left + width;
        this.bottom = top + height;
        this.sillas = sillas
        this.idBD = id;
        this.id = 'mesa_' + id;
        this.reservas = reservas;
        this.posicionamiento = null;
    }

    creaDiv() {
        this.div = $('<div>')
            .attr({ id: this.id, class: 'c-mantenimiento-sala__mesa' })
            .data('mesa', this)
            .css({
                width: this.width + 'px',
                height: this.height + 'px',
            }).draggable({
                drag: function (ev, ui) {
                    let mesa = $(this);

                    mesa.newLeft = parseInt(ui.offset.left);
                    mesa.newTop = parseInt(ui.offset.top);
                    mesa.newRight = mesa.newLeft + mesa.width();
                    mesa.newBottom = mesa.newTop + mesa.height();

                    if (posicionValida(mesa, $("#sala"))) {
                        ui.helper.css({ backgroundColor: 'green' });
                    } else {
                        ui.helper.css({ backgroundColor: 'red' });
                    }
                },
                start: function (ev, ui) {
                    var mesa = $(this).data('mesa');

                    // nos aseguramos de que el helper tiene el tama??o adecuado
                    ui.helper.css({
                        height: mesa.height,
                        width: mesa.width,
                    }).text('');

                    $(this).css({
                        height: mesa.height,
                        width: mesa.width,
                        display: 'none'
                    }).text('');
                },
                stop: function () {
                    // para que en caso de que la intentemos mover y no se pueda
                    // al no ser droppeada en ningun div, no se le aplicaria ningun estilo
                    // le ponemos flex para que no desaparezca al chocar
                    $(this).css({
                        display: 'flex'
                    });
                },
                revert: true,
                revertDuration: 0,
                helper: 'clone',
                accept: '#almacen, #sala',
            });

        return this.div;
    }

    // guarda el estado de la mesa en la base de datos
    update() {

        // si no estamos tratando con una distribucion, cambiamos la poscion base de la mesa
        if (this.padre.distribucion === null) {
            var data = {
                mesa: {
                    id: this.idBD,
                    alto: this.height,
                    ancho: this.width,
                    posY: this.top,
                    posX: this.left,
                    sillas: this.sillas,
                }
            };

            $.ajax({
                type: "PUT",
                url: "/api/mesa",
                data: JSON.stringify(data),
                dataType: "JSON"
            });
        } else {
            // si tiene distribucion

            // si se mueve a la sala se tiene que crear o actualizar el posicionamiento de la mesa
            if (this.padre instanceof Sala) {
                // comprobamos si la mesa tiene algun posicionamiento existente

                if (this.posicionamiento !== null) {
                    // lo actualizamos

                    this.posicionamiento.posY = this.top;
                    this.posicionamiento.posX = this.left;

                    $.ajax({
                        type: "PUT",
                        url: "/api/posicionamiento",
                        data: JSON.stringify(data = { posicionamiento: this.posicionamiento }),
                        dataType: "JSON"
                    });
                } else {
                    // lo creamos
                    let mesa = this;
                    let newPos = {
                        posicionamiento: JSON.stringify({
                            distribucion_id: this.padre.distribucion.id,
                            mesa_id: this.idBD,
                            posX: this.left,
                            posY: this.top
                        })
                    };

                    $.post("/api/posicionamiento", newPos,
                        function (data) {
                            mesa.posicionamiento = data.posicionamiento;
                        }
                    );

                }

            } else {
                // si se mueve al almacen tendremos que borrar el posicionamiento de la mesa
                let mesa = this;

                $.ajax({
                    type: "DELETE",
                    url: "/api/posicionamiento",
                    data: JSON.stringify(data = { posicionamiento: this.posicionamiento }),
                    dataType: "JSON",
                    success: function () {
                        mesa.posicionamiento = null;
                    }
                });
            }
        }
    }

    // Elimina una mesa de la base de datos
    delete() {
        var oldMesa = this;

        var div = this.div;
        var padre = this.padre;

        this.div = '';
        this.padre = '';

        $.ajax({
            type: "DELETE",
            url: "/api/mesa",
            data: JSON.stringify({ mesa: this }),
            dataType: "JSON",
            success: function (respuesta) {
                // si se ha eliminado correctamente, la quitamos del alamacen y eliminamos su div
                if (padre) {
                    let index = padre.mesas.indexOf(oldMesa);

                    padre.mesas.splice(index, 1);
                    console.log(padre);
                }

                div.remove();
            }
        });
    }
}

function posicionValida(mesa, sala) {
    var choca = false;
    var idMesa = mesa.attr('id').split('_')[1];

    if (chocaSala(mesa, sala)) {
        return choca;
    }

    // comprueba si choca con cada mesa de la sala
    $.each($('#sala [id^=mesa_]'), function () {
        let mesa2 = $(this)
        let idMesa2 = mesa2.attr('id').split('_')[1];

        // si el id es el mismo no se comprueba, para que no comprueba si choca consigo misma
        if (idMesa != idMesa2) {
            choca = chocaMesa(mesa, mesa2);

            // si choca con una, dejamos de comprobar con el resto (corta el bucle)
            if (choca) {
                return false
            }
        }
    });

    return !choca;

}

function chocaSala(mesa, sala) {
    dimensiona(sala);

    let chocaX = mesa.newLeft < sala.left || mesa.newRight > sala.right;
    let chocaY = mesa.newTop < sala.top || mesa.newBottom > sala.bottom;

    return chocaX || chocaY;
}

function chocaMesa(mesa1, mesa2) {
    dimensiona(mesa2);

    let chocaX = (mesa1.newRight > mesa2.left && mesa1.newLeft < mesa2.right);
    let chocaY = (mesa1.newBottom > mesa2.top && mesa1.newTop < mesa2.bottom);

    return chocaX && chocaY;
}

function dimensiona(comp) {
    comp.left = comp.offset().left;
    comp.top = comp.offset().top;
    comp.right = comp.left + comp.width();
    comp.bottom = comp.top + comp.height();
}

function mesaDeleteAlert(mesa) {
    return $('<div>').addClass('alert alert-danger fade show c-mantenimiento-sala__borraMesaAlert active')
        .attr('role', 'alert')
        .html('??Est?? seguro que desea borrar esta mesa?')
        .append(
            $('<button>').addClass('btn btn-danger ml-2 mr-2').attr('type', 'button')
                .html('Eliminar')
                .click((e) => {
                    mesa.delete();
                    console.log($(e.target).parent());
                    $(e.target).parent().alert('close');
                })
        )
        .append(
            $('<button>').addClass('close').attr({
                'data-dismiss': "alert",
                'aria-label': "Close",
            }).append($('<span>').attr('aria-hidden', 'true')
                .html("&times;")
            )

        )
}