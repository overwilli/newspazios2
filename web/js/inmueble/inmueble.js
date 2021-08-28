$(document).ready(function () {
    $("#a2noticias-id_grupo").change(function () {
        if ($("#a2noticias-id_grupo").val().length == 0) {
            $(".field-a2noticias-porcion").css('display', 'none');
        } else {
            $(".field-a2noticias-porcion").css('display', 'block');
        }
    });
    if ($("#a2noticias-id_grupo").val().length == 0) {
        $(".field-a2noticias-porcion").css('display', 'none');
    } else {
        $(".field-a2noticias-porcion").css('display', 'block');
    }
});