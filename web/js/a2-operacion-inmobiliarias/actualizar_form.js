$(document).ready(function() {
	if($("#a2operacionesinmobiliarias-firma_representante").val()==1){
		$("#div_representante").css('display','block');
	}else{
		$("#div_representante").css('display','none');
	}
	$("#a2operacionesinmobiliarias-firma_representante").change(function(){
		if($("#a2operacionesinmobiliarias-firma_representante").val()==1){
			$("#div_representante").css('display','block');
		}else{
			$("#div_representante").css('display','none');
		}
	});

	if($("#a2operacionesinmobiliarias-tiene_expensas").val()==1){
		$("#div_expensas").css('display','block');
	}else{
		$("#div_expensas").css('display','none');
	}
	$("#a2operacionesinmobiliarias-tiene_expensas").change(function(){
		if($("#a2operacionesinmobiliarias-tiene_expensas").val()==1){
			$("#div_expensas").css('display','block');
		}else{
			$("#div_expensas").css('display','none');
		}
	});

	if($("#a2operacionesinmobiliarias-deposito_garantia").val()==1){
		$("#a2operacionesinmobiliarias-deposito_monto").css('display','block');
		$("label[for='a2operacionesinmobiliarias-deposito_monto']").css('display','block');
		$("#a2operacionesinmobiliarias-deposito_cuotas").css('display','block');
		$("label[for='a2operacionesinmobiliarias-deposito_cuotas']").css('display','block');
	}else{
		$("#a2operacionesinmobiliarias-deposito_monto").css('display','none');
		$("label[for='a2operacionesinmobiliarias-deposito_monto']").css('display','none');
		$("#a2operacionesinmobiliarias-deposito_cuotas").css('display','none');
		$("label[for='a2operacionesinmobiliarias-deposito_cuotas']").css('display','none');
	}
	$("#a2operacionesinmobiliarias-deposito_garantia").change(function(){
		if($("#a2operacionesinmobiliarias-deposito_garantia").val()==1){
			$("#a2operacionesinmobiliarias-deposito_monto").css('display','block');
			$("label[for='a2operacionesinmobiliarias-deposito_monto']").css('display','block');
			$("#a2operacionesinmobiliarias-deposito_cuotas").css('display','block');
			$("label[for='a2operacionesinmobiliarias-deposito_cuotas']").css('display','block');
		}else{
			$("#a2operacionesinmobiliarias-deposito_monto").css('display','none');
			$("label[for='a2operacionesinmobiliarias-deposito_monto']").css('display','none');
			$("#a2operacionesinmobiliarias-deposito_cuotas").css('display','none');
			$("label[for='a2operacionesinmobiliarias-deposito_cuotas']").css('display','none');
		}
	});

	if($("#a2operacionesinmobiliarias-honorarios").val()==1){
		$("#a2operacionesinmobiliarias-excento_monto").css('display','block');
		$("label[for='a2operacionesinmobiliarias-excento_monto']").css('display','block');
		$("#a2operacionesinmobiliarias-excento_cuotas").css('display','block');
		$("label[for='a2operacionesinmobiliarias-excento_cuotas']").css('display','block');
	}else{
		$("#a2operacionesinmobiliarias-excento_monto").css('display','none');
		$("label[for='a2operacionesinmobiliarias-excento_monto']").css('display','none');
		$("#a2operacionesinmobiliarias-excento_cuotas").css('display','none');
		$("label[for='a2operacionesinmobiliarias-excento_cuotas']").css('display','none');
	}
	$("#a2operacionesinmobiliarias-honorarios").change(function(){
		if($("#a2operacionesinmobiliarias-honorarios").val()==1){
			$("#a2operacionesinmobiliarias-excento_monto").css('display','block');
			$("label[for='a2operacionesinmobiliarias-excento_monto']").css('display','block');
			$("#a2operacionesinmobiliarias-excento_cuotas").css('display','block');
			$("label[for='a2operacionesinmobiliarias-excento_cuotas']").css('display','block');
		}else{
			$("#a2operacionesinmobiliarias-excento_monto").css('display','none');
			$("label[for='a2operacionesinmobiliarias-excento_monto']").css('display','none');
			$("#a2operacionesinmobiliarias-excento_cuotas").css('display','none');
			$("label[for='a2operacionesinmobiliarias-excento_cuotas']").css('display','none');
		}
	});
	
});

    