$(document).ready(function () {
    $("#reincorporar").click(function () {
        remover_cliente_backlist_all();
    });
});

$('#myModal').on('hidden.bs.modal', function (e) {
    location.href = "";
})
function remover_cliente_backlist(dni) {
    $("#resultado").html("<img src='" + base_url + "/images/loader.gif' /> Procesando...");
    $('#myModal').modal('show');
    $.ajax({
        dataType: "json",
        type: "GET",
        url: base_url + "/index.php?r=clientes/remover-cliente-blacklist&dni=" + dni,
    }).done(function (data) {
        $("#resultado").html(data.mensaje);
    });
}

function remover_cliente_backlist_all() {
    $("#resultado").html("<img src='" + base_url + "/images/loader.gif' /> Procesando...");
    $('#myModal').modal('show');
    var dni_cliente = [];
    $("input[name='selection[]']:checked").each(function () {
        dni_cliente.push($(this).val());
    });
    if (dni_cliente == "") {
        $("#resultado").html("No existen registro seleccionados");
    } else {
        $.ajax({
            dataType: "json",
            type: "POST",
            data:{ listado_dni: dni_cliente,  },
            url: base_url + "/index.php?r=clientes/remover-cliente-blacklist-all",
        }).done(function (data) {
            $("#resultado").html(data.mensaje);
        });
    }
    /**/
}