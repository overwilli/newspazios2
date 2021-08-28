$(document).ready(function() {
	$("#marcar_todos").click(function() {
		marcar_checkbox();
	});
});

function marcar_checkbox() {
	$(".expensas_pendientes").each(function(index,element) {
		if (!$("#marcar_todos").is(':checked')) {
			$(element).prop('checked', false);
		} else {
			$(element).prop('checked', true);
		}
	});	
	$(".expensas_pendientes_grupo").each(function(index,element) {
		if (!$("#marcar_todos").is(':checked')) {
			$(element).prop('checked', false);
		} else {
			$(element).prop('checked', true);
		}
	});
}