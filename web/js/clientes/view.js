$('#myModal').on('shown.bs.modal', function () {
    $("#resultado").html("Confirme la acci&oacute;n?");
});

$('#myModal').on('hidden.bs.modal', function (e) {
  location.href="";
})
function enviar_invitacion_mail() {
    $("#resultado").html("<img src='" + base_url + "/images/loader.gif' />");
    $.ajax({
        dataType: "json",
        type: "GET",
        url: base_url + "/index.php?r=clientes/enviar-invitacion-mail",
        data: {dni: $("#dni").val()},
    }).done(function (data) {
        $("#resultado").html(data.mensaje);
    });
}