$(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('contratosdocumentos-texto');
    //bootstrap WYSIHTML5 - text editor
    //$(".textarea").wysihtml5();
    $("#contratosdocumentos-plantilla_id").change(function(){
        get_template_text();
    });
});


function get_template_text() {
    $.ajax({
        url: base_url + "/index.php?r=contratos-documentos/get-template-by-id",
        type: "POST",
        dataType: 'json',
        data: {id: $("#contratosdocumentos-plantilla_id").val(), 
            id_operacion: $("#contratosdocumentos-operacion_inmobiliaria_id").val(),
            },
        success: function (data) {            
            CKEDITOR.instances["contratosdocumentos-texto"].setData(data.texto)
        }
    });
}