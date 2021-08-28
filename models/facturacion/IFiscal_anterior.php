<?php
class class_IFiscal
{
  function __construct() {

  }
  
  public function method_version($params, $error) {
  	global $conexion;
  	
  	$p = $params[0];
  	
  	require("rutinas_ifiscal.php");

	$resultado = array();
	
	$conexion = fsockopen($p->conexion->host, $p->conexion->port);
	if ($conexion) {
		$resultado[] = GetPrinterVersion();
		
		fclose($conexion);
	} else {
		return "no conecta";
	}

	$aux = array();
	foreach($resultado as $i) {
		$item = explode(chr(28), $i);
		$item[1] = $this->status_impresora($item[1]);
		$item[2] = $this->status_fiscal($item[2]);
		$aux[] = $item;
	}
	$resultado = $aux;
	
	return $resultado;
  }
  
  
  public function method_anular_comprobante($params, $error) {
  	global $conexion;
  	
  	$p = $params[0];
  	
  	require("rutinas_ifiscal.php");

	$resultado = array();
	
	$conexion = fsockopen($p->conexion->host, $p->conexion->port);
	if ($conexion) {
		$resultado[] = TotalTender(" ", 0, "C", 0);
		$resultado[] = CloseFiscalReceipt();
		$resultado[] = CloseDNFH();
		
		fclose($conexion);
	} else {
		return "no conecta";
	}
	
	$aux = array();
	foreach($resultado as $i) {
		$item = explode(chr(28), $i);
		$item[1] = $this->status_impresora($item[1]);
		$item[2] = $this->status_fiscal($item[2]);
		$aux[] = $item;
	}
	$resultado = $aux;
	
	return $resultado;
  }
  

  public function method_cierreZ($params, $error) {
  	global $conexion;
  	
  	$p = $params[0];
  	
  	require("rutinas_ifiscal.php");

	$resultado = array();
	
	$conexion = fsockopen($p->conexion->host, $p->conexion->port);
	if ($conexion) {
		$fecha = date('ymd-His');
		$fecha = explode("-", $fecha);
		$resultado[] = DailyClose("Z");
		if ($p->cambiar_fecha_hora) {
			$resultado[] = SetDateTime($fecha[0], $fecha[1]);
		}
		
		fclose($conexion);
	} else {
		return "no conecta";
	}
	
	$aux = array();
	foreach($resultado as $i) {
		$item = explode(chr(28), $i);
		$item[1] = $this->status_impresora($item[1]);
		$item[2] = $this->status_fiscal($item[2]);
		$aux[] = $item;
	}
	$resultado = $aux;
	
	return $resultado;
  }
  
  
  public function method_cierreX($params, $error) {
  	global $conexion;
  	
  	$p = $params[0];
  	
  	require("rutinas_ifiscal.php");

	$resultado = array();
	
	$conexion = fsockopen($p->conexion->host, $p->conexion->port);
	if ($conexion) {
		$fecha = date('ymd-His');
		$fecha = explode("-", $fecha);
		$resultado[] = DailyClose("X");
		if ($p->cambiar_fecha_hora) {
			$resultado[] = SetDateTime($fecha[0], $fecha[1]);
		}
		
		fclose($conexion);
	} else {
		return "no conecta";
	}
	
	$aux = array();
	foreach($resultado as $i) {
		$item = explode(chr(28), $i);
		$item[1] = $this->status_impresora($item[1]);
		$item[2] = $this->status_fiscal($item[2]);
		$aux[] = $item;
	}
	$resultado = $aux;
	
	return $resultado;
  }
  
  
  public function method_documento_fiscal($params, $error) {
  	global $conexion;
  	
  	$p = $params[0];
  	
  	require("rutinas_ifiscal.php");

	$resultado = array();
	
	$conexion = fsockopen($p->conexion->host, $p->conexion->port);
	if ($conexion) {
		if (!is_null($p->copias)) {
			$resultado[] = ConfigureControllerByOne("9", $p->copias);
		}
		
		if (!is_null($p->encabezado)) {
			foreach($p->encabezado as $key => $value) {
				$resultado[] = SetHeaderTrailer($key, $value);
			}
		}
		
	    if (! empty($p->cliente->domicilio) && $p->ifiscal->modelo == "P-615F") {
	    	$resultado[] = SetHeaderTrailer(9, "Domicilio: ");
	    	$resultado[] = SetHeaderTrailer(10, $p->cliente->domicilio);
	    }
	    
		if (!is_null($p->cola)) {
			foreach($p->cola as $key => $value) {
				$resultado[] = SetHeaderTrailer($key + 10, $value);
			}
		}
		

		if (empty($p->cliente->nrodoc)) $p->cliente->nrodoc = "1";
		$resultado[] = SetCustomerData($p->cliente->nombre, $p->cliente->nrodoc, $p->cliente->responsabilidadIVA, $p->cliente->tipodoc, (($p->ifiscal->modelo == "P-615F") ? null : $p->cliente->domicilio));
	    
		if (!is_null($p->documento_vinculado)) {
			if (!is_null($p->documento_vinculado[0]) && $p->ifiscal->modelo != "P-615F") {
				$resultado[] = SetEmbarkNumber(1, $p->documento_vinculado[0]);
			}
			if (!is_null($p->documento_vinculado[1]) && $p->ifiscal->modelo != "P-615F") {
				$resultado[] = SetEmbarkNumber(2, $p->documento_vinculado[1]);
			}			
		}
		
    	$resultado[] = OpenFiscalReceipt($p->documento->tipo, $p->documento->estacion);
	    
	    if ($p->documento->estacion == "T") {
	    	$lineaFT = 28;
	    	$lineaItem = 20;
	    } else {
	    	$lineaFT = 50;
	    	$lineaItem = 50;
	    }
	    foreach($p->detalle as $item) {
	    	$descrip = explode(" ", trim($item->descrip));
	    	$count = count($descrip);
	    	$aux = "";
	    	
	    	for ($i = 0; $i < $count; $i++) {
	    		if (!empty($descrip[$i])) {
		    		if ($i == $count - 1) {
		    			if (strlen($aux . " " . $descrip[$i]) > $lineaItem) {
		    				$resultado[] = PrintFiscalText(trim($aux), 0);
		    				$aux = $descrip[$i];
		    				$resultado[] = PrintLineItem(trim($aux), $item->cantidad, $item->monto, $item->iva, $item->imputacion, $item->impuesto_interno, 0, $item->calificador_monto);
		    			} else {
		    				$aux.= " " . $descrip[$i];
		    				$resultado[] = PrintLineItem(trim($aux), $item->cantidad, $item->monto, $item->iva, $item->imputacion, $item->impuesto_interno, 0, $item->calificador_monto);	    				
		    			}
	
		    		} else {
		    			if (strlen($aux . " " . $descrip[$i]) > $lineaFT) {
		    				$resultado[] = PrintFiscalText(trim($aux), 0);
		    				$aux = $descrip[$i];
		    			} else {
		    				$aux.= " " . $descrip[$i];
		    			}
		    		}
	    		}
	    	}
	    }
	    
	    
	    if (!is_null($p->descuento_gral)) {
	    	$resultado[] = GeneralDiscount($p->descuento_gral->descrip, $p->descuento_gral->monto, $p->descuento_gral->imputacion, 0, $p->descuento_gral->calificador_monto);
	    }
	    
	    
	    $resultado[] = Subtotal("P", " ", 0);

	    
		foreach($p->pago as $item) {
			$resultado[] = TotalTender($item->descrip, $item->monto, "T", 0);
		}
		
	    $resultado[] = CloseFiscalReceipt();
	    
	    fclose ($conexion);
	} else {
		return "no conecta";
	}
	
	$aux = array();
	foreach($resultado as $i) {
		$item = explode(chr(28), $i);
		$item[1] = $this->status_impresora($item[1]);
		$item[2] = $this->status_fiscal($item[2]);
		$aux[] = $item;
	}
	$resultado = $aux;
	
	return $resultado;
  }
  
  
  public function method_documento_no_fiscal_homologado($params, $error) {
  	global $conexion;
  	
  	$p = $params[0];
  	
  	require("rutinas_ifiscal.php");

	$resultado = array();
	
	$conexion = fsockopen($p->conexion->host, $p->conexion->port);
	if ($conexion) {
		if (!is_null($p->copias)) {
			$resultado[] = ConfigureControllerByOne("9", $p->copias);
		}
		
		if (!is_null($p->encabezado)) {
			foreach($p->encabezado as $key => $value) {
				$resultado[] = SetHeaderTrailer($key, $value);
			}
		}
		
	    if (! empty($p->cliente->domicilio) && $p->ifiscal->modelo == "P-615F") {
	    	$resultado[] = SetHeaderTrailer(9, "Domicilio: ");
	    	$resultado[] = SetHeaderTrailer(10, $p->cliente->domicilio);
	    }
	    
		if (!is_null($p->cola)) {
			foreach($p->cola as $key => $value) {
				$resultado[] = SetHeaderTrailer($key + 10, $value);
			}
		}
		

		if (empty($p->cliente->nrodoc)) $p->cliente->nrodoc = "1";
		$resultado[] = SetCustomerData($p->cliente->nombre, $p->cliente->nrodoc, $p->cliente->responsabilidadIVA, $p->cliente->tipodoc, (($p->ifiscal->modelo == "P-615F") ? null : $p->cliente->domicilio));
	    
		if (!is_null($p->documento_vinculado)) {
			if (!is_null($p->documento_vinculado[0]) && $p->ifiscal->modelo != "P-615F") {
				$resultado[] = SetEmbarkNumber(1, $p->documento_vinculado[0]);
			}
			if (!is_null($p->documento_vinculado[1]) && $p->ifiscal->modelo != "P-615F") {
				$resultado[] = SetEmbarkNumber(2, $p->documento_vinculado[1]);
			}			
		}
		
    	$resultado[] = OpenDNFH($p->documento->tipo, $p->documento->estacion);
	    
	    if ($p->documento->estacion == "T") {
	    	$lineaFT = 28;
	    	$lineaItem = 20;
	    } else {
	    	$lineaFT = 50;
	    	$lineaItem = 50;
	    }
	    foreach($p->detalle as $item) {
	    	$descrip = explode(" ", trim($item->descrip));
	    	$count = count($descrip);
	    	$aux = "";
	    	
	    	for ($i = 0; $i < $count; $i++) {
	    		if (!empty($descrip[$i])) {
		    		if ($i == $count - 1) {
		    			if (strlen($aux . " " . $descrip[$i]) > $lineaItem) {
		    				$resultado[] = PrintFiscalText(trim($aux), 0);
		    				$aux = $descrip[$i];
		    				$resultado[] = PrintLineItem(trim($aux), $item->cantidad, $item->monto, $item->iva, $item->imputacion, $item->impuesto_interno, 0, $item->calificador_monto);
		    			} else {
		    				$aux.= " " . $descrip[$i];
		    				$resultado[] = PrintLineItem(trim($aux), $item->cantidad, $item->monto, $item->iva, $item->imputacion, $item->impuesto_interno, 0, $item->calificador_monto);	    				
		    			}
	
		    		} else {
		    			if (strlen($aux . " " . $descrip[$i]) > $lineaFT) {
		    				$resultado[] = PrintFiscalText(trim($aux), 0);
		    				$aux = $descrip[$i];
		    			} else {
		    				$aux.= " " . $descrip[$i];
		    			}
		    		}
	    		}
	    	}
	    }
	    
	    
	    if (!is_null($p->descuento_gral)) {
	    	$resultado[] = GeneralDiscount($p->descuento_gral->descrip, $p->descuento_gral->monto, $p->descuento_gral->imputacion, 0, $p->descuento_gral->calificador_monto);
	    }
	    
	    
	    $resultado[] = Subtotal("P", " ", 0);

	    
		foreach($p->pago as $item) {
			$resultado[] = TotalTender($item->descrip, $item->monto, "T", 0);
		}
		
	    $resultado[] = CloseDNFH();
	    
	    fclose ($conexion);
	} else {
		return "no conecta";
	}
	
	$aux = array();
	foreach($resultado as $i) {
		$item = explode(chr(28), $i);
		$item[1] = $this->status_impresora($item[1]);
		$item[2] = $this->status_fiscal($item[2]);
		$aux[] = $item;
	}
	$resultado = $aux;
	
	return $resultado;
  }
  

  
  public function status_fiscal($status) {
	$resultado = new stdClass;
	$resultado->status_fiscal = $status;
	$resultado->bits = array();
	
	$status = hexdec($status);
	if ((bool)($status & bindec('0000000000000001'))) {
		$aux = new stdClass;
		$aux->bit = 0;
		$aux->descrip = "Error en chequeo de memoria fiscal. Al encenderse la impresora se produjo un error en el checksum. La impresora no funcionará.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000000010'))) {
		$aux = new stdClass;
		$aux->bit = 1;
		$aux->descrip = "Error en chequeo de memoria de trabajo. Al encenderse la impresora se produjo un error en el checksum. La impresora no funcionará.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000000100'))) {
		$aux = new stdClass;
		$aux->bit = 2;
		$aux->descrip = "Carga de batería baja. La carga de la batería de respaldo de la memoria de trabajo se encuentra baja.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000001000'))) {
		$aux = new stdClass;
		$aux->bit = 3;
		$aux->descrip = "Comando desconocido.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000010000'))) {
		$aux = new stdClass;
		$aux->bit = 4;
		$aux->descrip = "Datos no válidos en un campo.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000100000'))) {
		$aux = new stdClass;
		$aux->bit = 5;
		$aux->descrip = "Comando no válido para el estado fiscal actual.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000001000000'))) {
		$aux = new stdClass;
		$aux->bit = 6;
		$aux->descrip = "Desborde del Total.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000010000000'))) {
		$aux = new stdClass;
		$aux->bit = 7;
		$aux->descrip = "Memoria fiscal llena, bloqueada o dada de baja.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000100000000'))) {
		$aux = new stdClass;
		$aux->bit = 8;
		$aux->descrip = "Memoria fiscal a punto de llenarse.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000001000000000'))) {
		//$aux = new stdClass;
		//$aux->bit = 9;
		//$aux->descrip = "Terminal fiscal certificada.";
		//$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000010000000000'))) {
		//$aux = new stdClass;
		//$aux->bit = 10;
		//$aux->descrip = "Terminal fiscal fiscalizada.";
		//$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000100000000000'))) {
		$aux = new stdClass;
		$aux->bit = 11;
		$aux->descrip = "Error en ingreso de fecha.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0001000000000000'))) {
		//$aux = new stdClass;
		//$aux->bit = 12;
		//$aux->descrip = "Documento fiscal abierto.";
		//$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0010000000000000'))) {
		//$aux = new stdClass;
		//$aux->bit = 13;
		//$aux->descrip = "Documento abierto.";
		//$resultado->bits[] = $aux;
	}

	
	return $resultado;
  }
  
  
  public function status_impresora($status) {
	$resultado = new stdClass;
	$resultado->status_impresora = $status;
	$resultado->bits = array();
	
	$status = hexdec($status);
	if ((bool)($status & bindec('0000000000000001'))) {
		$aux = new stdClass;
		$aux->bit = 0;
		$aux->descrip = "Impresora ocupada.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000000100'))) {
		$aux = new stdClass;
		$aux->bit = 2;
		$aux->descrip = "Error de impresora. Se ha interrumpido la conexión entre el controlador fiscal y la impresora.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000001000'))) {
		$aux = new stdClass;
		$aux->bit = 3;
		$aux->descrip = "Impresora offline. La impresora no ha logrado comunicarse dentro del período de tiempo establecido.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000010000'))) {
		$aux = new stdClass;
		$aux->bit = 4;
		$aux->descrip = "Falta papel del diario. El sensor de papel del diario ha detectado falta de papel.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000000100000'))) {
		$aux = new stdClass;
		$aux->bit = 5;
		$aux->descrip = "Falta papel de tiques. El sensor de papel de tiques ha detectado falta de papel.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000001000000'))) {
		$aux = new stdClass;
		$aux->bit = 6;
		$aux->descrip = "Buffer de impresora lleno.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000010000000'))) {
		//$aux = new stdClass;
		//$aux->bit = 7;
		//$aux->descrip = "Buffer de impresora vacío.";
		//$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0000000100000000'))) {
		$aux = new stdClass;
		$aux->bit = 8;
		$aux->descrip = "Tapa de impresora abierta.";
		$resultado->bits[] = $aux;
	}
	if ((bool)($status & bindec('0100000000000000'))) {
		$aux = new stdClass;
		$aux->bit = 14;
		$aux->descrip = "Cajón de dinero cerrado o ausente.";
		$resultado->bits[] = $aux;
	}
	
	return $resultado;
  }
}

?>