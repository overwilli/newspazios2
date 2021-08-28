$(document).ready(function () {

    $("#errores_cierre_caja,#errores_movimientos_caja").css("display", "none");
    $("#form_cajas_abiertas").css('display', 'none');
    $("#form_cierre_caja").submit(function (e) {
        if (confirm("Esta Seguro que desea cerrrar la caja?")) {
            if (!ControlarCierre()) {
                e.preventDefault();
            }
        } else {
            e.preventDefault();
        }
    });
    $("button[name='registar_mov']").click(function () {
        registrar_movimiento();
    });
});
function ControlarCierre() {
    result = true;

    var errores = "";
    if (isNaN($("#caja_dia_siguiente").val()))
        errores = errores + "Ingrese un monto v�lido para la CAJA DEL DIA SIGUIENTE <br/>";
    if (($("#caja_dia_siguiente").val()) == "")
        errores = errores + "Ingrese una monto para la CAJA DEL D&Iacute;A SIGUIENTE<br/>";
    if (($("#caja_dia_siguiente").val()) < 0)
        errores = errores + "Ingrese una monto positivo para la CAJA DEL D&Iacute;A SIGUIENTE<br/>";
    if (($("#rendicion").val()) == "")
        errores = errores + "Ingrese una monto para la RENDICI&Oacute;N <br/>";
    if (isNaN($("#rendicion").val()))
        errores = errores + "Ingrese un monto v�lido para la RENDICI&Oacute;N <br/>";
    if (($("#rendicion").val()) < 0)
        errores = errores + "Ingrese una monto positivo para la RENDICI&Oacute;N <br/>";
    if (errores != "") {
        $("#data_errores_cierre_caja").html(errores);
        $("#errores_cierre_caja").css("display", "block");
        result = false;
    }

//        if (result == false) {
//            
//            //document.cierre_caja.submit();
//        }

    
    return result;
}

function cambiar_interface_movimiento() {
    if ($("#selectmovimientos").val() == 10) {
        $("#form_cajas_abiertas").css('display', 'block');
    } else {
        $("#form_cajas_abiertas").css('display', 'none');
    }
    if ($("#selectmovimientos").val() != 5 && $("#selectmovimientos").val() != 10) {
        $("#control_propiedades").css('display', 'block');
    } else {
        $("#control_propiedades").css('display', 'none');
    }
}

function registrar_movimiento() {
    if (confirm("Esta Seguro que desea registrar un movimiento?")) {
        result = true;
        var errores = "";
        if (isNaN($("#importe_movimiento").val())) {
            errores = errores + "Ingrese un monton v&aacute;lido de movimiento \n";
            result = false;
        }

        if ($("#selectmovimientos").val() == 10) {
            if (isNaN($("#cajas_abiertas").val())) {
                errores = errores + "Debe seleccionar una caja abierta. \n";
                result = false;
            }
        }
        if (result == true) {
            $.ajax({
                url: base_url + "/index.php?r=a2-caja/registrar-movimiento",
                type: "POST",
                dataType: "json",
                data: "selectmovimientos=" + $("#selectmovimientos").val() +
                        "&cajas_abiertas=" + $("#cajas_abiertas").val() + "&importe_movimiento=" + $("#importe_movimiento").val() +
                        "&observaciones_ingreso_egreso=" + $("#observaciones_ingreso_egreso").val() + 
                        "&fecha_movimiento=" + $("#fecha_movimiento").val()+"&propiedad_id="+$("#propiedad_id").val(),
                success: (function (respuesta) {
                    if (respuesta.estado == 0) {
                        $("#data_errores_movimientos_caja").html(respuesta.mensaje_error);
                        $("#errores_movimientos_caja").css("display", "block");
                    } else {
                        $("#content_modal").html(respuesta.mensaje);
                        $('#myModal').modal("show");
                        $("#cerrar_modal").click(function () {
                            location.href = "";
                        });
                    }
                })
            });
        } else {
            $("#data_errores_movimientos_caja").html(errores);
            $("#errores_movimientos_caja").css("display", "block");
        }
    }
}