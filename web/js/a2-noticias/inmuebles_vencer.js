$(document).ready(function () {
    /*$("#agregar_todas_liq").click(function () {
        agregar_todas_liq_propietario();
    });
    $("#eliminar_todas_liq").click(function () {
        eliminar_todas_liquidaciones();
    });
    $("#cerrar_orden").click(function () {
        cerrar_orden_pago();
    });
    $("#btn_registrar_pago").click(function () {
        pagar_orden_pago();
    });

    $("#guardar_otros_gastos").click(function () {
        actualizar_gastos();
    });
    obtener_detalle_liquidacion();*/
});

function firmar_contrato(id) {    
    //$("#resultado_items").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    if(confirm("Esta seguro que desea marcar el contrato como firmado?")){
        $.ajax({
            url: base_url + "/index.php?r=a2-noticias/firmar-contrato",
            type: "POST",
            dataType: 'json',
            data: { operacion_id: id, },
            success: function (data) {
                if(data.error==0){
                        alert(data.mensaje);
                        location.href="";
                }else{
                        alert(data.mensaje_error);
                        location.href="";
                }
            }
        });
    }

}

