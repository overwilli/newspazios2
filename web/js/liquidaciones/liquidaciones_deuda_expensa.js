$(document).ready(function () {
    $('.deposito').css('display', 'none');
    $("#deposito_monto").change(function () {
        if ($("#deposito_monto").val().length > 0) {
            $('.deposito').css('display', 'inline-block');
            $("#fecha_deposito").focus();
        } else {
            $('.deposito').css('display', 'none');
            //$("#fecha_deposito").datepicker("destroy");
        }
    });

    $("#periodos_deuda").bind('change', function () {
        ver_detalle();
    });
    if ($('#periodos_deuda').length) {
        ver_detalle();
    }

    $("#form_registrar_pago").submit(function (e) {
        if(!registrar_pago()){
            e.preventDefault();
        }
    });
    

    $('#fecha_deposito').change(function() { 
        recalcular_intereses();
    });

    $('.decimales').on('input', function () {
        this.value = this.value.replace(/[^0-9,.]/g, '').replace(/,/g, '.');
      });
});

function recalcular_intereses() {
    $.ajax({
        url: base_url + "/index.php?r=a2-liquidaciones/calcular-intereses-expensas",
        type: "POST",
        dataType: 'json',
        data: {accion: "recalcular_interes", operacion_id: $("#operacion_id").val(),'mes':$("#mes").val(),
        'anio':$("#anio").val(), fecha_deposito: $("#fecha_deposito").val(),
            deposito_monto: $("#deposito_monto").val(), monto: $("#monto").val()},
        success: function (data) {
            if (data.estado == 1) {

                $("#bonificado").val(data.formateado_bonificado);
                $("#monto_interes").val(data.monto_interes);
                $("#monto_total").val(data.monto_total);
                
                $("#label_total_sin_interes").html(data.formateado_total_sin_interes);
                $("#label_bonificado").html(data.formateado_bonificado);
                $("#label_interes").html(data.formateado_monto_interes+' ('+data.dias_atraso+') días');
                $("#label_monto_total").html(data.formateado_monto_total);

            } else {
                alert(data.mensaje_error);
            }
        }
    });
}

function ver_detalle() {
    $('#myModal').modal('show');
    $.ajax({
        url: base_url + "/index.php?r=a2-liquidaciones/detalle-deuda-expensa",
        type: "POST",
        data: {periodo: $("#periodos_deuda").val(),
            operacion_id: $("#id_operacion").val()},
        success: function (data) {
            $("#resultado").html(data);
            $('#myModal').modal('hide');
        }
    });
}

function registrar_pago() {
    var total = parseFloat($("#monto_total").val());
    var efectivo_monto = parseFloat($("#efectivo_monto").val());
    var cheque_monto = parseFloat($("#cheque_monto").val());
    var deposito_monto = parseFloat($("#deposito_monto").val());
    var retencion_monto = parseFloat($("#retencion_monto").val());
    var mora_monto = parseFloat($("#mora_monto").val());

    var errores = "";
    var parciales = 0;

    //----------pregunto del EFECTIVO
    if ($("#efectivo_monto").val() != "") {
        if (isNaN($("#efectivo_monto").val()))
            errores = errores + "Ingrese un monton válido de efectivo \n";
        else {
            parciales = parseFloat($("#efectivo_monto").val());
        }

    }

    //----------pregunto del cheque
    if ($("#cheque_monto").val() != "") {
        if (isNaN($("#cheque_monto").val()))
            errores = errores + "Ingrese un monton válido del cheque \n";
        else {
            parciales = parciales + parseFloat($("#cheque_monto").val());
        }

//            if ($("#cheque_numero").val() == "" || $("#cheque_banco").val() == "")
//                errores = errores + "Ingrese todos los datos del cheque \n";
//
//            if (isNaN($("#cheque_numero").val()))
//                errores = errores + "Ingrese un n�mero v�lido de cheque \n";

    }

    //----------pregunto por el deposito
    if ($("#deposito_monto").val() != "") {
        if (isNaN($("#deposito_monto").val()))
            errores = errores + "Ingrese un monton válido de depósito \n";
        else {
            parciales = parciales + parseFloat(deposito_monto);
        }
//            if ($("#deposito_numero").val() == "")
//                errores = errores + "Ingrese todos los datos del dep�sito \n";
//
//            if (isNaN($("#deposito_numero").val()))
//                errores = errores + "Ingrese un n�mero v�lido de dep�sito \n";


    }
    //----------pregunto por la retenci�n
    if ($("#retencion_monto").val() != "") {
        if (isNaN($("#retencion_monto").val()))
            errores = errores + "Ingrese un monton válido de retención \n";
        else {
            parciales = parciales + parseFloat(retencion_monto);
        }
        if ($("#retencion_tipo").val() == "")
            errores = errores + "Ingrese el tipo de retención, ej: Ingresos Brutos, Iva, Ganancias \n";
    }


    //----------pregunto por la MORA
    /*if ($("#mora_monto").val() != "") {
        if (isNaN($("#mora_monto").val()))
            errores = errores + "Ingrese un monto válido de Mora \n";

    }*/


    if (total.toFixed(2) == parciales.toFixed(2))
        result = true;
    else
        errores = errores + "No coinciden los montos \n MONTO A COBRAR: " + total + " \n MONTO TIPEADO: " + parciales;

    if (errores != "") {
        alert(errores);
        result = false;
    }
    return result;
}
