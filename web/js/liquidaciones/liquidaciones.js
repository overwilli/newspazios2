$(document).ready(function () {
    $('.deposito').css('display', 'none');
    $("#deposito_monto").change(function () {
        if ($("#deposito_monto").val().length > 0) {
            $('.deposito').css('display', 'inline-block');
        } else {
            $('.deposito').css('display', 'none');
            //$("#fecha_deposito").datepicker("destroy");
        }
    });
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
    
    $("#registrar_pago_parcial").click(function(){
        pago_parcial();
    });
});


function recalcular_intereses() {
    $.ajax({
        url: base_url + "/index.php?r=a2-liquidaciones/calcular-intereses",
        type: "POST",
        dataType: 'json',
        data: {accion: "recalcular_interes", id_liquidacion: $("#id_liquidacion").val(), fecha_deposito: $("#fecha_deposito").val(),
            deposito_monto: $("#deposito_monto").val(), monto: $("#monto").val()},
        success: function (data) {
            if (data.estado == 1) {
//                    $("#mora_monto").val(data.interes_por_mora);
//                    $("#dias_atraso_id").html("(" + data.dias_atraso + " d�as) ");
//                    $("#interes_por_dia_id").html(data.interes_por_dia);
//                    $("#monto_a_facturar").html(data.monto_a_facturar_formateado);
//                    $("#monto").val(data.monto_a_facturar);
                //$("#dias_mora_label").html('Mora (' + data.dias_mora + ' d&iacute;as) ' + data.interes_por_dia);
                $("#dias_mora_label").html('Mora (' + data.dias_mora + ' d&iacute;as) ');
                $("#monto_interes_label").html(data.interes);
                $("#monto").val(data.monto);
                $("#monto_label_dinero").html(data.formateado_monto);
                $("#mora_monto").val(data.interes);

            } else {
                alert(data.mensaje_error);
            }
        }
    });
}

function registrar_pago() {
    var total = parseFloat($("#monto").val());
    var efectivo_monto = parseFloat($("#efectivo_monto").val());
    var cheque_monto = parseFloat($("#cheque_monto").val());
    var deposito_monto = parseFloat($("#deposito_monto").val());
    var retencion_monto = parseFloat($("#retencion_monto").val());
    var mora_monto = parseFloat($("#mora_monto").val());

    var result=true;
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
            errores = errores + "Ingrese el tipo de retencián, ej: Ingresos Brutos, Iva, Ganancias \n";
    }


    //----------pregunto por la MORA
    if ($("#mora_monto").val() != "") {
        if (isNaN($("#mora_monto").val()))
            errores = errores + "Ingrese un monton válido de Mora \n";

    }


    if (total == parciales.toFixed(2))
        result = true;
    else
        errores = errores + "No coinciden los montos \n MONTO A COBRAR: " + total + " \n MONTO TIPEADO: " + parciales;

    if (errores != "") {
        alert(errores);
        result = false;
    }
    return result;
}

function pago_parcial(){
    var total = parseFloat($("#monto").val());
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
    }

    if ($("#deposito_monto").val() != "") {
        if (isNaN($("#deposito_monto").val()))
            errores = errores + "Ingrese un monton válido de depósito \n";
        else {
            parciales = parciales + parseFloat(deposito_monto);
        }
    }

    //----------pregunto por la retenci�n
    if ($("#retencion_monto").val() != "") {
        if (isNaN($("#retencion_monto").val()))
            errores = errores + "Ingrese un monton válido de retención \n";
        else {
            parciales = parciales + parseFloat(retencion_monto);
        }
        if ($("#retencion_tipo").val() == "")
            errores = errores + "Ingrese el tipo de retencián, ej: Ingresos Brutos, Iva, Ganancias \n";
    }
    if (errores != "") {
        alert(errores);
    }else{
        
        $.ajax({
            url: base_url + "/index.php?r=a2-liquidaciones/registrar-pago-parcial",
            type: "POST",
            dataType: 'json',
            data: {id_liquidacion: $("#id_liquidacion").val(), fecha_deposito: $("#fecha_deposito").val(),
                deposito_monto: $("#deposito_monto").val(), monto: $("#monto").val(),
                efectivo_monto:$("#efectivo_monto").val(),cheque_monto:$("#cheque_monto").val(),
                retencion_monto:$("#retencion_monto").val(),mora_monto:$("#mora_monto").val()
            },
            success: function (data) {
                console.log(data);
                if (data.estado == 1) {
                    location.href=base_url+"/index.php?r=a2-liquidaciones/imprimir-pago-parcial&id_liquidacion="+
                        $("#id_liquidacion").val()+"&pago_parcial_id="+data.pago_parcial_id+"&liq_cancelado="+data.liq_cancelado;
                } else {
                    alert(data.mensaje_error);
                }
            }
        });
    }

}