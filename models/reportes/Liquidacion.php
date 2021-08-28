<?php

class Liquidacion {

    function __construct() {
        
    }

    public function existe_liquidacion($parametros) {
        $query = "select count(*) as cantidad FROM a2_liquidaciones WHERE id_operacion=" . $parametros['id_operacion'] . " AND liq_anio=" . $parametros['year'] .
                " AND liq_mes=" . $parametros['mes'];
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
        return $result;
    }
    
    public function obtener_liquidacion_by_operacion($parametros) {
        $query = "select * FROM a2_liquidaciones WHERE id_operacion=" . $parametros['id_operacion'] . " AND liq_anio=" . $parametros['year'] .
                " AND liq_mes=" . $parametros['mes'];
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
        return $result;
    }

    public function obtener_periodos_liquidados() {
        $query = "select liq_anio,liq_mes FROM a2_liquidaciones GROUP BY liq_anio,liq_mes";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
        return $result;
    }

    public function obtener_mora_pagada($id_liquidacion) {
        $cadena = "SELECT a2_movimientos.monto FROM  a2_movimientos WHERE id_liquidacion=$id_liquidacion AND movimiento_tipo=7 AND estado_pago_pendiente<>'anulado'";
        $result = mysql_query($cadena);

        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
        $row = mysql_fetch_array($result);

        if ($row["monto"] > 0) {
            return $row["monto"];
        } else {
            return 0;
        }
    }

    public function obtener_liquidacion_por_liquidacion($parametros) {

        $sql = "SELECT a2_liquidaciones.liq_anio,liq_mes,id_operacion from a2_liquidaciones where a2_liquidaciones.id_liquidacion=" . $parametros['id_liquidacion'];
        $result = mysql_query($sql);
        return $result;
    }

    public function obtener_liquidaciones_por_cobrar($condicion) {
        $cadenaSQL = "SELECT 
				a2_liquidaciones.id_liquidacion,
				a2_liquidaciones.liq_anio,
				a2_liquidaciones.liq_mes,
				a2_liquidaciones.fecha_pago,
				a2_liquidaciones.monto_pagado,
				a2_liquidaciones.monto_intereses,
				a2_liquidaciones.usuario,
				a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_operaciones_inmobiliarias.dia_venc_mensual,	
				a2_clientes.NOMBRE as nombrecliente
                                
				
				FROM a2_liquidaciones 
				LEFT JOIN a2_operaciones_inmobiliarias ON 
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
                                
				WHERE 1								
				 
				" . $condicion . " AND a2_liquidaciones.fecha_pago='0000-00-00 00:00:00' ORDER BY a2_liquidaciones.liq_anio DESC, a2_liquidaciones.liq_mes DESC";

        $result = mysql_query($cadenaSQL);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $cadenaSQL;
            die();
        }
        return $result;
    }

    public function liquidaciones_pendientes_por_cobrar($condicion) {
        $cadenaSQL = "SELECT 
				a2_liquidaciones.id_liquidacion,
				a2_liquidaciones.liq_anio,
				a2_liquidaciones.liq_mes,
				a2_liquidaciones.fecha_pago,
				a2_liquidaciones.monto_pagado,
				a2_liquidaciones.monto_intereses,
				a2_liquidaciones.usuario,
				a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_operaciones_inmobiliarias.dia_venc_mensual,	
				a2_clientes.NOMBRE as nombrecliente
				
				FROM a2_liquidaciones 
				LEFT JOIN a2_operaciones_inmobiliarias ON 
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente 
                                LEFT JOIN  a2_movimientos 
                                ON a2_liquidaciones.id_liquidacion=a2_movimientos.id_liquidacion
				WHERE 1								
				 
				" . $condicion . " AND tipo_registro='pago_pendiente'AND estado_pago_pendiente='pendiente' GROUP BY a2_liquidaciones.id_liquidacion ORDER BY a2_liquidaciones.fecha_pago DESC";

        $result = mysql_query($cadenaSQL);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $cadena;
            die();
        }
        return $result;
    }

    public function liquidaciones_pagadas($condicion, $indice, &$total_registos) {
        $cadenaSQL = "SELECT COUNT(*) AS total	FROM a2_liquidaciones 
				LEFT JOIN a2_operaciones_inmobiliarias ON 
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                                LEFT JOIN inmuebles_propietarios ON a2_noticias.id_noticia=inmuebles_propietarios.inmueble_id
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
				WHERE 1								
				 
				" . $condicion . " AND a2_liquidaciones.fecha_pago<>'0000-00-00 00:00:00'  ORDER BY a2_liquidaciones.fecha_pago DESC";

        $result = mysql_query($cadenaSQL);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $cadena;
            die();
        }
        $fila = mysql_fetch_array($result);
        $total_registos = $fila['total'];

        $cadenaSQL = "SELECT 
				a2_liquidaciones.id_liquidacion,
				a2_liquidaciones.liq_anio,
				a2_liquidaciones.liq_mes,
				a2_liquidaciones.fecha_pago,
				a2_liquidaciones.monto_pagado,
				a2_liquidaciones.monto_intereses,
				a2_liquidaciones.usuario,
				a2_noticias.id_noticia, 
				a2_noticias.titulo, 
				a2_noticias.direccion,
				a2_noticias.fecha, 
				a2_noticias.foto, 
				a2_noticias.fotopie,
				a2_noticias.alq_vendida,				 				
				a2_secciones.seccion, 
				a2_objeto_de_propiedad.operacion,
				a2_operaciones_inmobiliarias.fecha_ope,
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria,
				a2_operaciones_inmobiliarias.hasta_anio,
				a2_operaciones_inmobiliarias.hasta_mes,
				a2_operaciones_inmobiliarias.dia_venc_mensual,	
				a2_clientes.NOMBRE as nombrecliente
				
				FROM a2_liquidaciones 
				LEFT JOIN a2_operaciones_inmobiliarias ON 
				a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion
				LEFT JOIN a2_noticias ON
				a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
                                LEFT JOIN inmuebles_propietarios ON a2_noticias.id_noticia=inmuebles_propietarios.inmueble_id
				LEFT JOIN a2_secciones	ON			
				a2_noticias.seccion=a2_secciones.id_seccion
				LEFT JOIN a2_objeto_de_propiedad ON
				a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
				LEFT JOIN a2_clientes ON
				a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
				WHERE 1								
				 
				" . $condicion . " AND a2_liquidaciones.fecha_pago<>'0000-00-00 00:00:00'  ORDER BY a2_liquidaciones.fecha_pago DESC LIMIT 50 OFFSET {$indice}";
        
        $result = mysql_query($cadenaSQL);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $cadena;
            die();
        }
        return $result;
    }

    public function obtener_importe_pagado_by_liq($id_liquidacion) {
        $query = "SELECT a2_movimientos.*,denominacion FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON "
                . "(a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' AND "
                . "(estado_pago_pendiente IS NULL OR estado_pago_pendiente='')AND movimiento_tipo<> 9 AND movimiento_tipo<> 7 AND id_liquidacion=" . $id_liquidacion;
        $resultado = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $query;
            die();
        }
        $importe_cobrado = 0;
        $nota_credito = 0;
        $interes = 0;
        while ($fila = mysql_fetch_array($resultado)) {
            //if ($fila['movimiento_tipo'] != 7 && $fila['movimiento_tipo'] != 9) {
            $importe_cobrado+=$fila['monto'];
            /* }
              if ($fila['movimiento_tipo'] == 9) {//SI ES UNA NOTA DE CREDITO
              $nota_credito+=$fila['monto'];
              }
              if ($fila['movimiento_tipo'] == 7) {
              $interes+=$fila['monto'];
              } */
        }
        return $importe_cobrado;
    }
    
    public function tiene_movimiento_tipo_by_liquidacion_id($id_liquidacion,$tipo_movimiento=null) {
        $query = "SELECT count(*) as cantidad FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON "
                . "(a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' AND "
                . "(estado_pago_pendiente IS NULL OR estado_pago_pendiente='')AND movimiento_tipo<> 9 AND movimiento_tipo<> 7 AND id_liquidacion=" . $id_liquidacion;
        if($tipo_movimiento!=null){
            $query.=" AND movimiento_tipo={$tipo_movimiento}";
        }        
        $resultado = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $query;
            die();
        }
        $row = mysql_fetch_array($resultado);
        if ($row['cantidad'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }        
    }

}
