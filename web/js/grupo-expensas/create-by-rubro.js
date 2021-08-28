$(document).ready(function(){
    total_items=0;
    $("#grupoexpensas-grupo_id").change(function(){
        inmuebles_por_grupo();
    });
});

function inmuebles_por_grupo(){
    $("#tabla_expensas_inmuebles td").remove();
    $.ajax({
        url: base_url + "/index.php?r=grupo-expensas/inmuebles-por-grupo",
        type: "POST",
        //dataType: 'json',
        data: { grupo_id: $("#grupoexpensas-grupo_id").val(), },
        success: function (data) {
            $("#tabla_expensas_inmuebles").append(data);
            $(".solo_numeros").ForceNumericOnly();
        }
    });
}

var agregar_expensa=function(){
    var row="<tr id='fila"+total_items+"'>";
    var id_expensa=$("#grupoexpensas-tipo_expensa_id").val();
    var mes=$("#grupoexpensas-mes").val();
    var anio=$("#grupoexpensas-year").val();
    var importe=$("#grupoexpensas-importe").val();
    var control="<input type='hidden' name='indice[]' value='"+total_items+"' />";

    if(!existe_item(id_expensa,mes,anio)){
        control+="<input type='hidden' name='datos["+total_items+"][id_expensa]' value='"+id_expensa+"' />";
        control+="<input type='hidden' name='datos["+total_items+"][mes]' value='"+mes+"' />";
        control+="<input type='hidden' name='datos["+total_items+"][anio]' value='"+anio+"' />";
        control+="<input type='hidden' name='datos["+total_items+"][importe]' value='"+importe+"' />";
        row+="<td >"+control+"</td>";
        row+="<td>"+$("#grupoexpensas-tipo_expensa_id option:selected").html()+"</td>";
        row+="<td>"+$("#grupoexpensas-mes").val()+"/"+$("#grupoexpensas-year").val()+"</td>";
        row+="<td>"+$("#grupoexpensas-importe").val()+"</td>";
        row+="<td><a href='#' class='glyphicon glyphicon-trash' onclick='remover_item("+total_items+")'></a></td>";        
        row+="</tr>";
        $("#tabla_expensas").append(row);        
    }else{
        alert("Ya se encuentra cargado");
    }
    
    total_items++;
};

function existe_item(id_expensa,mes,anio){
    var bandera=false;
    $("input[name='indice[]']").each(function(i,val){
        if($("input[name='datos["+$(this).val()+"][id_expensa]']").val()==id_expensa && 
            $("input[name='datos["+$(this).val()+"][mes]']").val()==mes && 
            $("input[name='datos["+$(this).val()+"][anio]']").val()==anio){            
            bandera=true;
        }
        
    });
    return bandera;
}


function remover_item(indice){
    $("#fila"+indice).remove();
}

// Numeric only control handler
jQuery.fn.ForceNumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 46 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        });
    });
};