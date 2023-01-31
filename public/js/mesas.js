$(function () {
    $(".mesa").draggable({
        start: function (ev, ui) {
            $(this).attr("data-y", ui.offset.top);
            $(this).attr("data-x", ui.offset.left);
        },
        revert: true,
        revertDuration: 0,
        helper: 'clone',
        accept: '#almacen, #sala',
    });


    $('#almacen').droppable({
        drop: function (ev, ui) {
            let mesa = ui.draggable;
            console.log(mesa);
            mesa.css({ position: '' });
            $(this).append(mesa);
        }
    });

    $("#sala").droppable({
        drop: function (ev, ui) {
            // coge la mesa que esta siendo soltada
            var mesa = ui.draggable;
            var left = parseInt(ui.offset.left);
            mesa.newLeft = parseInt(ui.offset.left);
            mesa.newRight = left + mesa.width();
            var top = parseInt(ui.offset.top);
            mesa.newTop = parseInt(ui.offset.top);
            mesa.newBottom = top + mesa.width();
            let width = mesa.width();
            let height = mesa.height();

            let pos1 = [left, left + width, top, top + height];

            // metodo mesa posicion valida (mesa, sala)
            // dada una mesa y una sala, comprueba que la mesa este dentro de la sala
            // y que no choque con ninguna de el resto de mesas de la sala

            posicionValida(mesa, $(this));

            let mesaYa = $('#sala .mesa').eq(0);
            if (mesaYa.length > 0) {
                let posX = parseInt(mesaYa.offset().left);
                let posY = parseInt(mesaYa.offset().top);
                let anchura = mesaYa.width();
                let longitud = mesaYa.height();
                let pos2 = [posX, posX + anchura, posY, posY + longitud];

                if ((pos1[0] > pos2[0] && pos1[0] < pos2[1] ||
                    pos1[1] > pos2[0] && pos1[1] < pos2[1] ||
                    pos1[0] <= pos2[0] && pos1[1] >= pos2[1])

                    &&

                    (pos1[2] > pos2[2] && pos1[2] < pos2[3] ||
                        pos1[3] > pos2[2] && pos1[3] < pos2[3] ||
                        pos1[2] <= pos2[2] && pos1[3] >= pos2[3])) {
                } else {
                    $(this).append(mesa);
                    mesa.css({ position: 'absolute', top: top + "px", left: left + "px" });
                }
            } else {
                $(this).append(mesa);
                mesa.css({ position: 'absolute', top: top + "px", left: left + "px" });
            }
        }
    });
});

// TODO:
function posicionValida(mesa, sala) {

    if (!chocaSala(mesa, sala)) {
        console.log('no choca sala');

        // TODO: coger las mesas por id en vez de por clase
        // si la cogemos por clase, tambien se incluye el helper del draggable
        console.log($('#sala '));
        console.log($('#sala .mesa').length);
    }

    return false;
}

function chocaSala(mesa, sala) {
    dimensiona(sala);

    console.log(mesa);
    console.log(sala);

    if (mesa.newLeft >= sala.left && mesa.newRight <= sala.right &&
        mesa.newTop >= sala.top && mesa.newBottom <= sala.bottom) {
        return false
    }

    return true;
}

function chocaMesa(mesa1, mesa2) { }

function dimensiona(comp) {
    comp.left = comp.offset().left;
    comp.top = comp.offset().top;
    comp.right = comp.left + comp.width();
    comp.bottom = comp.top + comp.height();
}