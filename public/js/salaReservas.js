class SalaReservas {

    constructor(div) {
        this.div = div;
        this.mesas = [];
        this.distribucion = null;
        this.proporcion = 1050 / div.width();
    }

    addMesa(mesa, top, left) {
        this.proporcion = 1050 / this.div.width();
        this.mesas.push(mesa);

        // guardamos en el objeto el tamaño y posicion para poder luego escalar en base
        // a la proporcion del div de la sala
        mesa.altoOriginal = mesa.alto;
        mesa.anchoOriginal = mesa.ancho;
        mesa.posYOriginal = top;
        mesa.posXOriginal = left;

        // actualizamos los valores actuales de la mesa para que este en proporcion al tamaño de la sala
        mesa.alto = mesa.alto / this.proporcion;
        mesa.ancho = mesa.ancho / this.proporcion;

        mesa.posY = top / this.proporcion;
        mesa.posX = left / this.proporcion;
        mesa.bottom = top + mesa.alto;
        mesa.right = left + mesa.ancho;

        // pintamos la mesa
        mesa.div = $('<div>')
            .attr({ id: "mesaRes_" + mesa.id, class: 'c-modalReservas__mesa' })
            .data('mesa', mesa)
            .css({
                display: '',
                position: 'fixed',
                width: mesa.ancho + 'px',
                height: mesa.alto + 'px',
                top: mesa.posY + this.div.offset().top,
                left: mesa.posX + this.div.offset().left,
                backgroundColor: '',
            }).appendTo(this.div)
            .click((e) => {
                var divMesa = $(e.target);
                var mesa = divMesa.data('mesa');
                var imgJuego = $('#imgJuegoSeleccionFinal');
                var juego = $('#imgJuegoSeleccionFinal').data('juego');

                if (juego.anchoTablero <= mesa.anchoOriginal && juego.altoTablero <= mesa.altoOriginal) {
                    console.log('cabe');
                    imgJuego.css({
                        width: juego.anchoTablero / this.proporcion + 'px',
                        height: juego.altoTablero / this.proporcion + 'px',
                    }).appendTo(divMesa)
                        .data('mesa', mesa);

                    mesa.imgJuego = imgJuego;
                } else {
                    console.log('no cabe');
                }

            });

    }

    // recalcula la proporcion de la sala y cambia el tamaño y posicion de las mesas para ir acorde a esta
    resize() {
        this.proporcion = 1050 / this.div.width();


        this.mesas.forEach(mesa => {

            // calcula la proporcion
            mesa.alto = mesa.altoOriginal / this.proporcion;
            mesa.ancho = mesa.anchoOriginal / this.proporcion;

            mesa.posY = mesa.posYOriginal / this.proporcion;
            mesa.posX = mesa.posXOriginal / this.proporcion;
            mesa.bottom = mesa.posY + mesa.alto;
            mesa.right = mesa.posX + mesa.ancho;

            // cambia el tamaño y posicion de la mesa
            mesa.div.css({
                top: mesa.posY + this.div.offset().top,
                left: mesa.posX + this.div.offset().left,
                width: mesa.ancho + 'px',
                height: mesa.alto + 'px',
            })

            if (mesa.imgJuego) {
                var juego = mesa.imgJuego.data('juego');

                mesa.imgJuego.css({
                    width: juego.anchoTablero / this.proporcion + 'px',
                    height: juego.altoTablero / this.proporcion + 'px',
                })
            }
        });
    }
}