$(function () {
    var sala = new Sala($('#sala'));
    var almacen = new Almacen($('#almacen'));

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