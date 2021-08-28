<?php

require_once("config.php");

//session_start();

//AQUI TOMO LOS DATOS DEL USUARIO LOGUEADO


function ChequearUsuario($usuario, $password) {

    $usuario = strip_tags($usuario);
    $password = strip_tags($password);

    if (!empty($usuario) && !empty($password)) {
        //print $usuario.' -'.$password.'  ---';
        $Inyecciones = " AND INSERT DROP DELETE SELECT UPDATE TABLE HEX and insert drop delete select update table hex";
        $pos_usuario = strpos($Inyecciones, $usuario);
        $pos_password = strpos($Inyecciones, "delete");
        //print $pos_usuario.' -'.$pos_password;
        //die();
        if ($pos_usuario === true || $pos_password === true) {
            //die ($pos_usuario.' -'.$pos_password);
            print '<script language="JavaScript">location.href=("index.php");</script>';
        }
    } else
        Header("Location: index.php?error=Ingrese usuario");

    $usuario = mysql_real_escape_string($usuario);
    $password = mysql_real_escape_string(md5($password));

    $cadena = "SELECT `nikname`, `password`, sitio, permisos,numero_caja from a_noticias_usuarios where `nikname`='$usuario' AND password='$password'";
//die($cadena);
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n";
        print $cadena;
        die();
    }
    $cantidad = mysql_num_rows($result);
    if ($cantidad == 1) {
        $row = mysql_fetch_array($result);
        $datos[0] = $row["nikname"];
        $datos[1] = $row["sitio"];
        $datos[2] = $row["permisos"];
        $datos[16] = $row["numero_caja"];

        //--------------SE VERIFICA SI EL USUARIO TIENE UNA CAJA ABIERTA------
        $obj_caja = new Cajas();
        $resultado_caja = $obj_caja->obtener_caja_abierta($row["numero_caja"]);
        if (mysql_num_rows($resultado_caja) > 0) {
            $fila = mysql_fetch_array($resultado_caja);
            $datos['id_caja'] = $fila["id_caja"];
        }


        //DEFINO CON TABBLA TRABAJO LA PORTADA y EL DIRECTORIO DE ARCHIVOS PARA CADA SITIO
        if ($row["sitio"] == 1) {
            $datos[3] = "a2_portada";
            $datos[4] = "../../files/"; //directorio del kfm
            $datos[5] = "../files/"; //directorio base
            $datos[6] = ""; //path a partir del directorio base que grabo
            //define('PATH_RAIZ_FOTOS', '../files/');
        }

        //--------------SACO PARAMETROS GLOBALES		
        $cadena = "SELECT 
		empresa, 
		impresora_host, 
		impresora_puerto,
		impresora_modelo,
		impresora_firmware,
		impresora_directorio 
		FROM a2_parametros_globales 
		WHERE id_parametro=1";
        $result = mysql_query($cadena);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n";
            print $cadena;
            die();
        }
        $cantidad = mysql_num_rows($result);
        if ($cantidad == 1) {
            $row = mysql_fetch_array($result);
            $datos[10] = $row["empresa"];
            $datos[11] = $row["impresora_host"];
            $datos[12] = $row["impresora_puerto"];
            $datos[13] = $row["impresora_modelo"];
            $datos[14] = $row["impresora_firmware"];
            $datos[15] = $row["impresora_directorio"];
        }
        session_register('datosUsuario');
        $_SESSION['datosUsuario'] = $datos;
        if ($datos[2] == "administrador")
            Header("Location: inicio.php");
        else
            Header("Location: inicio.php");
    }
    else {
        //die($cadena);
        Header("Location: index.php?error=cantidad_usuarios-" . $cantidad);
    }
}

// de la funcion

function SesionUsuario() {
//session_start();
//session_register('datosUsuario');
    $datos = $_SESSION['datosUsuario'];
    return $datos;
}

///////////////////////////////////////////////////////////////////////////
function Resetear_usuario() {
    session_unregister('datosUsuario');
    session_destroy();
    session_start();
//session_regenerate_id();
    /*

      unset($_SESSION);
      session_start();
     */
}

function UsuarioCambiarClave($usuario, $old_password, $new_password_1, $new_password_2) {

    $result = mysql_query("SELECT nikname, password, permisos from a_noticias_usuarios where nikname='$usuario' AND password='$old_password'");
    $cantidad = mysql_numrows($result);
    if ($cantidad == 1) {
        //veo si las nuevas claves son iguales
        if ($new_password_1 == $new_password_2) {
            $result = mysql_query("UPDATE a_noticias_usuarios SET password='$new_password_2' where nikname='$usuario'");
            Header("Location: usuario_cambiar_clave.php?mensaje=Contrase�a cambiada con �xito&usuario=$usuario");
        } else
            Header("Location: usuario_cambiar_clave.php?mensaje=Las contrase�as nuevas no coinciden&usuario=$usuario");
    }
    else {
        Header("Location: usuario_cambiar_clave.php?mensaje=La contrase�a actual no es correcta&usuario=$usuario");
    }
}

// de la funcion

function Interes_por_dia_para_una_operacion($id_operacion) {

    $cadena = "SELECT *	FROM   a2_operaciones_inmobiliarias
		WHERE id_operacion_inmobiliaria=$id_operacion
		";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . $cadena;
        die();
    }
    $row = mysql_fetch_array($result);
    return $row["interes_dia_mora"];
}

// de la funci�n
//---------------------------------------------------------------------------------

function Calcular_Monto_Periodo($id_operacion, $anio, $mes, $imprimir, $fecha_pago = "") {

    if ($imprimir != 0) {
        $datos = SesionUsuario();
    }
    $cadena = "
	SELECT 
	id_item,
	id_operacion,
	anio,
	mes,
	a2_tipos_facturacion.descripcion,
	a2_tipos_facturacion.iva,
	a2_tipos_facturacion.plantilla,	
	monto,
	a2_clientes.nombre,
	a2_clientes.direccion as direccion_cliente,
	a2_clientes.cuil,
	a2_operaciones_inmobiliarias.cod_propiedad,
	a2_noticias.direccion
		
	FROM a2_operaciones_items
	LEFT JOIN a2_tipos_facturacion ON
	a2_tipos_facturacion.id_facturacion=a2_operaciones_items.id_factura
	LEFT JOIN a2_operaciones_inmobiliarias ON
	a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_operaciones_items.id_operacion
	LEFT JOIN  a2_noticias ON
	 a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad	
	
	LEFT JOIN a2_clientes ON
	a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
	
	WHERE a2_operaciones_items.id_operacion=" . $id_operacion . "
	AND a2_operaciones_items.anio=" . $anio . " 
	AND a2_operaciones_items.mes=" . $mes . "
	ORDER BY anio ASC, mes DESC
	";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
        die();
    }

    $monto_total = 0;
    while ($row = mysql_fetch_array($result)) {
        if ($row["iva"] == 1) {
            if ($row["monto"] >= 1501) {
                $monto_sin_iva = $row["monto"];
                $monto = $row["monto"] + ($row["monto"] * IVA) / 100;
                $monto_a_imprimir = $row["monto"] + ($row["monto"] * IVA) / 100;
            } else {
                $monto = $row["monto"];
                $monto_a_imprimir = $row["monto"];
            }
        } else {

            $monto = $row["monto"];
            $monto_a_imprimir = $row["monto"];
        }

        $monto_total+=$monto; //acumulo para mostrar en la cabezera	

        if (!empty($fecha_pago)) {
            $fecha_pago = "&fecha_pago=" . $fecha_pago;
        }

        $query = "SELECT id_liquidacion FROM a2_liquidaciones WHERE id_operacion=" . $id_operacion .
                " AND liq_anio=" . $anio . " AND liq_mes=" . $mes . ";";
        $resultado = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
            die();
        }
        $fila = mysql_fetch_array($resultado);

        if ($imprimir != 0) {
            $linea.=$row["descripcion"] . ': $<b>' . number_format($monto_a_imprimir, CANT_DIGITOS, ',', '.') . '</b> <a href="' . $datos[15] . '/' . $row["plantilla"] .
                    '.php?nombre=' . $row["nombre"] .
                    '&cuil=' . $row["cuil"] .
                    '&dni=' . $row["dni"] .
                    '&monto_sin_iva=' . $monto_sin_iva .
                    '&monto=' . $monto .
                    '&periodo=' . $row["mes"] . '-' . $row["anio"] .
                    '&id_liquidacion=' . $fila['id_liquidacion'] .
                    '&cod_inmueble=' . $row["cod_propiedad"] .
                    '&direccion_inmueble=' . $row["direccion"] .
                    '&direccion_cliente=' . $row["direccion_cliente"] . $fecha_pago .
                    '" target="_blank">
	<img src="imagenes/fileprint.png" alt="" title="Imprimir comprobante de pago" border="0" width="25">
	</a><br><hr>';
        }
    }
    if ($imprimir == 0)
        return $monto_total;
    else
        print $linea;
}

function Calcular_Monto_Periodo_eliminado($id_operacion, $anio, $mes, $imprimir, $fecha_pago = "") {

    if ($imprimir != 0) {
        $datos = SesionUsuario();
    }
    $cadena = "
	SELECT 
	id_item,
	id_operacion,
	anio,
	mes,
	a2_tipos_facturacion.descripcion,
	a2_tipos_facturacion.iva,
	a2_tipos_facturacion.plantilla,	
	monto,
	a2_clientes.nombre,
	a2_clientes.direccion as direccion_cliente,
	a2_clientes.cuil,
	a2_operaciones_inmobiliarias_copy.cod_propiedad,
	a2_noticias.direccion
		
	FROM a2_operaciones_items_copy
	LEFT JOIN a2_tipos_facturacion ON
	a2_tipos_facturacion.id_facturacion=a2_operaciones_items_copy.id_factura
	LEFT JOIN a2_operaciones_inmobiliarias_copy ON
	a2_operaciones_inmobiliarias_copy.id_operacion_inmobiliaria=a2_operaciones_items_copy.id_operacion
	LEFT JOIN  a2_noticias ON
	 a2_noticias.id_noticia=a2_operaciones_inmobiliarias_copy.cod_propiedad	
	
	LEFT JOIN a2_clientes ON
	a2_clientes.id_cliente=a2_operaciones_inmobiliarias_copy.cod_cliente
	
	WHERE a2_operaciones_items_copy.id_operacion=" . $id_operacion . "
	AND a2_operaciones_items_copy.anio=" . $anio . " 
	AND a2_operaciones_items_copy.mes=" . $mes . "
	ORDER BY anio ASC, mes DESC
	";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
        die();
    }

    $monto_total = 0;
    while ($row = mysql_fetch_array($result)) {
        if ($row["iva"] == 1) {
            if ($row["monto"] >= 1501) {
                $monto_sin_iva = $row["monto"];
                $monto = $row["monto"] + ($row["monto"] * IVA) / 100;
                $monto_a_imprimir = $row["monto"] + ($row["monto"] * IVA) / 100;
            } else {
                $monto = $row["monto"];
                $monto_a_imprimir = $row["monto"];
            }
        } else {

            $monto = $row["monto"];
            $monto_a_imprimir = $row["monto"];
        }

        $monto_total+=$monto; //acumulo para mostrar en la cabezera	

        if (!empty($fecha_pago)) {
            $fecha_pago = "&fecha_pago=" . $fecha_pago;
        }

        $query = "SELECT id_liquidacion FROM a2_liquidaciones WHERE id_operacion=" . $id_operacion .
                " AND liq_anio=" . $anio . " AND liq_mes=" . $mes . ";";
        $resultado = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
            die();
        }
        $fila = mysql_fetch_array($resultado);

        if ($imprimir != 0) {
            $linea.=$row["descripcion"] . ': $<b>' . number_format($monto_a_imprimir, CANT_DIGITOS, ',', '.') . '</b> <a href="' . $datos[15] . '/' . $row["plantilla"] .
                    '.php?nombre=' . $row["nombre"] .
                    '&cuil=' . $row["cuil"] .
                    '&dni=' . $row["dni"] .
                    '&monto_sin_iva=' . $monto_sin_iva .
                    '&monto=' . $monto .
                    '&periodo=' . $row["mes"] . '-' . $row["anio"] .
                    '&id_liquidacion=' . $fila['id_liquidacion'] .
                    '&cod_inmueble=' . $row["cod_propiedad"] .
                    '&direccion_inmueble=' . $row["direccion"] .
                    '&direccion_cliente=' . $row["direccion_cliente"] . $fecha_pago .
                    '" target="_blank">
            <img src="imagenes/fileprint.png" alt="" title="Imprimir comprobante de pago" border="0" width="25">
            </a><br><hr>';
        }
    }
    if ($imprimir == 0)
        return $monto_total;
    else
        print $linea;
}

//de la funci�nt

function Fecha_en_Castellano($fecharequerida) {

    $diassemana = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
//$fecharequerida=date('Y-m-d H:i:s');
    $fecharequerida = strtotime($fecharequerida);
    $mostrar="";
    $mostrar.= "" . $diassemana[(date("w", $fecharequerida))] . " " . date("j", $fecharequerida) . " de " . $meses[(date("n", $fecharequerida)) - 1] . " de " . date("Y", $fecharequerida);
    return $mostrar;
}

// de la funci�n

function Chequear_Caja_Habilitada($tipo_aviso) {

    if ($tipo_aviso == "mostrar_en_form_de_pago")
        $target_link = ' target="_parent"';
    elseif ($tipo_aviso == "mostrar_en_arqueo")
        $target_link = ' target="_parent"';

    $HOY = date("Y-m-d");
    $numero_caja = $_SESSION['datosUsuario'][16];
    if (empty($numero_caja) || !is_numeric($numero_caja)) {
        $mensaje .= "<br><br><center>El usuario no tiene asignado un numero de caja. Debe realizarlo en la opcion del men� mantenimiento,
            y en la sub-opci�n asignar caja. Si ya le asigno una caja al usuario, cierre sesion y vuelva a iniciar.</center><br><br>";
        print '<div class="Errores"><br><br>' . $mensaje . $numero_caja . '<br><br></div>';
        die();
    }

    $cadenaSQL = "SELECT 
	a2_caja.id_caja,
	a2_caja.inicio_fecha,
	a2_caja.inicio,
	a2_caja.caja_cierre,
        a2_caja.cierre_fecha,
	a2_caja.cierre_hora,
	a2_caja.cierre_usuario
	FROM a2_caja WHERE numero_caja={$numero_caja}
	ORDER BY  inicio_fecha DESC
	";
    $result = mysql_query($cadenaSQL);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $cadenaSQL;
        die();
    }
    if (mysql_num_rows($result) <= 0) {
        $mensaje .= "<br><br><center>INICIALIZAR CAJA POR PRIMERA VEZ, N� $numero_caja</center><br><br>";
        $mensaje.='<a href="caja_iniciar.php?id_caja_anterior=0"' . $target_link . ' />INICIALIZAR CAJA</a>';
        print '<div class="Errores"><br><br>' . $mensaje . '<br><br></div>';
        die();
    }

    $row = mysql_fetch_array($result);
    if ($row["inicio_fecha"] != $HOY) {
        //SI NO EXISTE LA CAJA DEL DIA
        /* $aux_fecha = explode("-", $HOY);
          $HOY_FORMATEADO = $aux_fecha[2] . "-" . $aux_fecha[1] . "-" . $aux_fecha[0];
          $mensaje.="No existe caja del d�a: <b>" . Fecha_en_Castellano($HOY_FORMATEADO) . '</b><br><br>';
          //print '<div class="Errores"><br><br>'.$mensaje.'<br><br></div>';

          if ($row["cierre_usuario"] == "") {
          $mensaje = "DEBE CERRAR LA CAJA DEL D�A ";
          //if($tipo_aviso=="mostrar_en_form_de_pago")
          $aux_fecha = explode("-", $row["inicio_fecha"]);
          $fecha_inicio_formateada = $aux_fecha[2] . "-" . $aux_fecha[1] . "-" . $aux_fecha[0];
          $mensaje.='<BR><BR><a href="caja_arqueo_form.php?fecha_arqueo=' . $fecha_inicio_formateada . '"' . $target_link . '  />' . Fecha_en_Castellano($row["inicio_fecha"]) . '</a>';
          } else {
          $mensaje.='<a href="caja_iniciar.php?id_caja_anterior=' . $row["id_caja"] . '"' . $target_link . ' />INICIALIZAR CAJA</a>';
          print '<div class="Errores"><br><br>' . $mensaje . '<br><br></div>';
          die();
          }
          print '<div class="Errores"><br><br>' . $mensaje . '<br><br></div>';
          if ($tipo_aviso == "mostrar_en_form_de_pago")
          die(); */
    } elseif ($row["cierre_usuario"] != "") {
        print '<div class="Errores"><br><br>La caja iniciada el d�a ' . date('d/m/Y', strtotime($row["inicio_fecha"])) . ' fue cerrada ' . date('d/m/Y', strtotime($row["cierre_fecha"])) . ' por el usuario: <b>' . $row["cierre_usuario"] . '</b>. <br>Hora de cierre: ' . $row["cierre_hora"] . '<br><br></div>';
        if ($tipo_aviso == "mostrar_en_form_de_pago")
            die();
    }
    if ($row["cierre_fecha"] == $HOY) {
        $aux_fecha = explode("-", $row["inicio_fecha"]);
        $HOY_FORMATEADO = $aux_fecha[2] . "-" . $aux_fecha[1] . "-" . $aux_fecha[0];
        $mensaje.="La caja del d�a: <b>" . Fecha_en_Castellano($HOY_FORMATEADO) . ' fue cerrada en el d�a de la fecha. No se puede abrir nuevamente.</b><br><br>';
        print '<div class="Errores"><br><br>' . $mensaje . '<br><br></div>';
        //die();
    }
}

//de la funci�n

function Grabar_Respuesta_Impresora($resultado, $tipo_movimiento, $monto, $id_liquidacion = 0, $nro_documento = "", $tipo_documento = "") {

    $today = date("Y-m-d");
    $hora = date("H:i:s");
    $usuario = $_SESSION['datosUsuario'][0];
    if ($monto == "")
        $monto = 0;

    $nro_comprobante_fiscal = "";
    $nro_cai = "";


    if ($tipo_movimiento != "recibo com�n") {
        $respuesta = json_decode($resultado);
        for ($i = 0; $i < count($respuesta); $i++) {
            if ($respuesta[$i][0] == 'CloseFiscalReceipt') {
                $nro_comprobante_fiscal = $respuesta[$i][3];
                $nro_cai = $respuesta[$i][5];
            }
            if ($tipo_movimiento == "Nota de Cr�dito") {
                if ($respuesta[$i][0] == 'CloseDNFH') {
                    $nro_comprobante_fiscal = $respuesta[$i][3];
                    $nro_cai = $respuesta[$i][5];
                }
            }
        }
        if ($tipo_movimiento == "recibo X") {
            $nro_comprobante_fiscal = $nro_documento;
        }
    } else {
        $nro_comprobante_fiscal = $nro_documento;
    }
    $cadena = "INSERT INTO a2_facturacion (
			tipo_movimiento,
			monto,
			usuario,
			fecha,
			hora,
			respuesta,
                        id_liquidacion,
                        nro_comprobante_fiscal,
                        nro_cai,
                        tipo_documento
			)
			VALUES(
			'$tipo_movimiento',
			$monto,
			'$usuario',
			'$today',
			'$hora',
			'$resultado',
                        $id_liquidacion,
                        '$nro_comprobante_fiscal',
                        '$nro_cai',
                        '$tipo_documento'
			)";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
        die();
    }
    print '<br><br><br><br><center>MOVIMIENTO DE ' . $tipo_movimiento . ' GRABADO CON EXITO<br><br><br>';
    print '<input name="button" type="button" onclick="window.close();" value="Cerrar esta ventana" /> ';
    print '</center>';
}

//de la funci�n

function Ultimo_recibo_X() {
    $cadena = "SELECT 
		ultimo_recibo_x 
		FROM a2_parametros_globales 
		WHERE id_parametro=1";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n";
        print $cadena;
        die();
    }
    $cantidad = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $nuevo_id_recibo_x = $row["ultimo_recibo_x"] + 1;
    $cadena = "UPDATE a2_parametros_globales SET
				ultimo_recibo_x=$nuevo_id_recibo_x
				WHERE id_parametro=1
				";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . $cadena;
        die();
    }

    return $nuevo_id_recibo_x;
}

// de la funci�n

function Ultimo_recibo_C() {
    $cadena = "SELECT 
		ultimo_recibo_c 
		FROM a2_parametros_globales 
		WHERE id_parametro=1";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n";
        print $cadena;
        die();
    }
    $cantidad = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $nuevo_id_recibo_c = $row["ultimo_recibo_c"] + 1;
    $cadena = "UPDATE a2_parametros_globales SET
				ultimo_recibo_c=$nuevo_id_recibo_c
				WHERE id_parametro=1
				";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . $cadena;
        die();
    }

    return $nuevo_id_recibo_c;
}

// de la funci�n

function Ultimo_recibo_D() {
    $cadena = "SELECT 
		ultimo_recibo_d 
		FROM a2_parametros_globales 
		WHERE id_parametro=1";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . "\n";
        print $cadena;
        die();
    }
    $cantidad = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $nuevo_id_recibo_d = $row["ultimo_recibo_d"] + 1;
    $cadena = "UPDATE a2_parametros_globales SET
				ultimo_recibo_d=$nuevo_id_recibo_d
				WHERE id_parametro=1
				";
    $result = mysql_query($cadena);
    if (mysql_errno() > 0) {
        print mysql_errno() . " " . mysql_error() . $cadena;
        die();
    }

    return $nuevo_id_recibo_d;
}

// de la funci�n
//--------------------------------------------------------------------

function Codigo_para_galeria($foto, $fotopie, $id_noticia) {

    $sql = "SELECT ImgPath, ImgDesc, id_noticia
		FROM  a2_noticias_imagenes	
		WHERE id_noticia=$id_noticia
		ORDER BY ImgOrden  ASC";

    $result = mysql_query($sql);
    if (mysql_errno() > 0) {
        print "Error devuelto por la Base de Datos: " . mysql_errno() . " " . mysql_error() . "\n";
    } elseif (mysql_num_rows($result) == 0)
        return;


    $cont = 1;
    while ($row = mysql_fetch_array($result)) {
        $row["ImgPath"] = '../' . PATH_RAIZ_FOTOS . $row["ImgPath"];
        if ($cont == 1) {
            /*
              $cadena='<a href="'.$row["ImgPath"].'" rel="prettyPhoto[galeria'.$row["id_noticia"].']" title="'.utf8_decode($row["ImgDesc"]).'"><img src="'.$row["ImgPath"].'" width="100" alt="ver fotos" border="0" /></a>';
             */
            $cadena = '<a href="' . $row["ImgPath"] . '" rel="prettyPhoto[galeria' . $row["id_noticia"] . ']" title="' . utf8_decode($row["ImgDesc"]) . '"><img src="' . $row["ImgPath"] . '" width="60" alt="ver fotos" title="ver fotos" border="0" /></a>';
        } else {
            $cadena.='<a href="' . $row["ImgPath"] . '" rel="prettyPhoto[galeria' . $row["id_noticia"] . ']" title="' . utf8_decode($row["ImgDesc"]) . '"></a>';
        }
        $cont++;
    }

    return $cadena;
}

// --------------- de la funcion
?>