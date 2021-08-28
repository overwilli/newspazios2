$(document).ready(function(){
     if ($('#promocionesmails-segmentacion').val() == 1) {
            $('#form_edad').css('display', 'block');
        } else {
            $('#form_edad').css('display', 'none');
        }
        if ($('#promocionesmails-segmentacion').val() == 2) {
            $('#form_codigo_postal').css('display', 'block');
        } else {
            $('#form_codigo_postal').css('display', 'none');
        }
        if ($('#promocionesmails-segmentacion').val() == 3) {
            $('#form_codigo_empleado').css('display', 'block');
        } else {
            $('#form_codigo_empleado').css('display', 'none');
        }
        if ($('#promocionesmails-segmentacion').val() == 4) {
            $('#form_deuda').css('display', 'block');
        } else {
            $('#form_deuda').css('display', 'none');
        }
});


function agregar_localidad(){    
    if ($("#promocionesmails-codigo_postal").val() != "") {
        bandera = false;
        $("input[name='localidades_promo[]']").each(function(index) {
            if ($(this).val() == $("#promocionesmails-codigo_postal").val()) {
                bandera = true;
            }
        });
        
        if (!bandera) {
            if ($("tr").last().attr("class") == 'odd') {
                clase = "even";
            } else {
                clase = "odd";
            }


            var insertar_registro = "<tr class='" + clase + "' id='registro_" + $("#promocionesmails-codigo_postal").val() + "'>";
            var input_registro = "<input type='hidden' name='localidades_promo[]' value='" + $("#promocionesmails-codigo_postal").val() + "'/>";
            insertar_registro += "<td>" + $("select[id='promocionesmails-codigo_postal'] option:selected").text() + input_registro + "</td>";
            insertar_registro += "<td>" + $("select[id='promocionesmails-codigo_postal'] option:selected").val()  + "</td>";
            insertar_registro += "<td ><a title='Remover' class='glyphicon glyphicon-minus-sign' onclick='borrar_registro_localidad(" + $("#promocionesmails-codigo_postal").val() + ")'></a></td>";
            insertar_registro += "</tr>";
            $("#tabla_localidades").append(insertar_registro);
        } else {
            alert("La localidad:" + $("select[id='promocionesmails-codigo_postal'] option:selected").text() + "  ya se encuentra cargada.");
        }
    } else {
        alert("Debe seleccionar una localidad.");
    }
}

function borrar_registro_localidad(registro_id_table) {
    $("#registro_" + registro_id_table).remove();
}