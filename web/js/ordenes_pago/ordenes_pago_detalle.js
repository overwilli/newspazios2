$(document).ready(function () {
    $("#agregar_todas_liq").click(function () {
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

	$("#btn_anular_orden").click(function () {
        anular_orden_pago();
    });
    $("#guardar_otros_gastos").click(function () {
        actualizar_gastos();
    });
	
	$("#agregar_todas_gastos").click(function () {
        agregar_todos_gastos_propietario();
    });
    $("#eliminar_todas_gastos").click(function () {
        eliminar_todos_gastos();
    });
	$("#agregar_todas_expensas").click(function () {
        agregar_todas_expensas_propietario();
    });
    $("#eliminar_todas_expensas").click(function () {
        eliminar_todas_expensas();
    });
    $("#btn_cerrar").click(function () {
        location.href=$("#btn_cerrar").attr('data-id');
    });
    
	
    obtener_detalle_liquidacion();
	obtener_detalle_gastos();
	obtener_detalle_expensas();
});

function calcular_valores(){
    $("#label_total_dinero").html($("#field_dinero_liq").val());
    $("#label_total_dinero1").html($("#field_dinero_liq").val());
    $("#label_total_comision").html($("#field_total_comision_liq").val());
    $("#label_total_expensas").html($("#field_total_expensas").val());
    $("#label_total_gastos").html($("#field_total_gastos").val());
    var total=(parseFloat($("#field_dinero_liq").val())+parseFloat($("#field_total_expensas").val()))-(
        parseFloat($("#field_total_comision_liq").val())+
        parseFloat($("#field_total_gastos").val()));
    $("#label_total_percibir").html(parseFloat(total).toFixed(2));
    
}

function obtener_detalle_liquidacion() {
    $("#resultado_items").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/mostrar-items-orden",
        type: "POST",
        //dataType: 'json',
        data: { orden_id: $("input[name='orden_id']").val(), },
        success: function (data) {
            $("#resultado_items").html(data);
            calcular_valores();
            $('#tabla_liquidaciones_pagadas').DataTable({
                'paging'      : false,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : true,
                'info'        : false,
                'autoWidth'   : false,
                'order': [[ 0, 'desc' ],[ 1, 'asc' ]],
                
              });
        }
    });

}

function obtener_detalle_gastos() {
    $("#resultado_items_gastos").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/mostrar-items-gastos",
        type: "POST",
        //dataType: 'json',
        data: { orden_id: $("input[name='orden_id']").val(), },
        success: function (data) {
            $("#resultado_items_gastos").html(data);
            calcular_valores();
        }
    });

}

function obtener_detalle_expensas() {
    $("#resultado_items_expensas").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/mostrar-items-expensas",
        type: "POST",        
        data: { orden_id: $("input[name='orden_id']").val(), },
        success: function (data) {
            $("#resultado_items_expensas").html(data);
            calcular_valores();
        }
    });

}


function agregar_todas_liq_propietario() {
    $("#resultado_items").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/agregar-todas-liquidaciones",
        type: "POST",
        data: {
            fecha_pago_liq: $("input[name='fecha_pago_liq']").val(),
            orden_id: $("input[name='orden_id']").val(),
            incluir_depositos: $("select[name='incluir_depositos']").val(),
        },
        success: function (data) {
            obtener_detalle_liquidacion();
        }
    });
}

function agregar_todas_expensas_propietario() {
    $("#resultado_items_expensas").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/agregar-todas-expensas",
        type: "POST",
        data: {            
            orden_id: $("input[name='orden_id']").val(),            
        },
        success: function (data) {
            obtener_detalle_expensas();
        }
    });
}

function agregar_todos_gastos_propietario() {
    $("#resultado_items_gastos").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/agregar-todos-gastos",
        type: "POST",
        data: {            
            orden_id: $("input[name='orden_id']").val(),            
        },
        success: function (data) {
            obtener_detalle_gastos();
        }
    });
}

function cerrar_orden_pago() {
    if (confirm("Esta seguro que desea cerrar la orden de pago")) {
        $("#resultado_items").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
        $.ajax({
            url: base_url + "/index.php?r=liquidacionpagadas/cerrar-orden",
            type: "POST",
            data: {
                orden_id: $("input[name='orden_id']").val(),
            },
            success: function (data) {
                location.href = base_url + "/index.php?r=liquidacionpagadas/form-liquidaciones-ordenes&orden_id=" + $("input[name='orden_id']").val();
            }
        });
    }
}



function anular_orden_pago() {
    if (confirm("Esta seguro que desea anular la orden de pago")) {
        $("#respuesta_modal_pago_orden").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
        $.ajax({
            url: base_url + "/index.php?r=liquidacionpagadas/anular-orden",
            type: "POST",
            dataType: 'json',
            data: {
                orden_id: $("input[name='orden_id']").val(),
            },
            success: function (data) {
                if(data.estado==1){                    
					alert(data.mensaje);
                }else{
                    alert(data.mensaje_error);
                }
                //$("#respuesta_modal_pago_orden").html(data);
                location.href = base_url + "/index.php?r=liquidacionpagadas/form-liquidaciones-ordenes&orden_id=" + $("input[name='orden_id']").val();
            }
        });
    }
}

function pagar_orden_pago() {
    if (confirm("Esta seguro que desea efectuar el pago de la orden")) {
        $("#respuesta_modal_pago_orden").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
        $.ajax({
            url: base_url + "/index.php?r=liquidacionpagadas/pagar-orden",
            type: "POST",
            dataType: 'json',
            data: {
                orden_id: $("input[name='orden_id']").val(),
            },
            success: function (data) {
                if(data.estado==1){
                    $("#respuesta_modal_pago_orden").html(data.imprimir_comprobante);
                }else{
                    $("#respuesta_modal_pago_orden").html(data.mensaje_error);
                }
                //$("#respuesta_modal_pago_orden").html(data);
                //location.href = base_url + "/index.php?r=liquidacionpagadas/form-liquidaciones-ordenes&orden_id=" + $("input[name='orden_id']").val();
            }
        });
    }
}

function actualizar_gastos() {
    $("#resultado_items").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/otros-gastos",
        type: "POST",
        data: {
            orden_id: $("input[name='orden_id']").val(),
            otros_gastos: $("input[id='otros_gastos']").val(),
        },
        success: function (data) {
            obtener_detalle_liquidacion();
        }
    });

}

function eliminar_todas_liquidaciones() {
    $("#resultado_items").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/eliminar-todas-liquidaciones",
        type: "POST",
        data: {
            orden_id: $("input[name='orden_id']").val(),
        },
        success: function (data) {
            obtener_detalle_liquidacion();
        }
    });
}

function eliminar_todos_gastos() {
    $("#resultado_items_gastos").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/eliminar-todos-gastos",
        type: "POST",
        data: {
            orden_id: $("input[name='orden_id']").val(),
        },
        success: function (data) {
            obtener_detalle_gastos();
        }
    });
}

function eliminar_todas_expensas() {
    $("#resultado_items_expensas").html("<center><img src='" + base_url + "/images/loading.gif' /></center>");
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/eliminar-todas-expensas",
        type: "POST",
        data: {
            orden_id: $("input[name='orden_id']").val(),
        },
        success: function (data) {
            obtener_detalle_expensas();
        }
    });
}

function eliminar_item(id) {
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/eliminar-item-detalle",
        type: "POST",
        data: {
            liq_detalle_id: id,
        },
        success: function (data) {
            obtener_detalle_liquidacion();
        }
    });
}


function eliminar_item_gasto(id) {
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/eliminar-item-gasto",
        type: "POST",
        data: {
            liq_gasto_id: id,
        },
        success: function (data) {
            obtener_detalle_gastos();
        }
    });
}

function eliminar_item_expensas(id) {
    $.ajax({
        url: base_url + "/index.php?r=liquidacionpagadas/eliminar-item-expensas",
        type: "POST",
        data: {
            liq_expensa_id: id,
        },
        success: function (data) {
            obtener_detalle_expensas();
        }
    });
}
