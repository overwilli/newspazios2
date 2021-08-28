<?php

class Expensas {

    public function agregar_nueva($parametros) {

        $sql = "INSERT INTO operaciones_expensas (operacion_id,tipo_expensas_id,inmuebles_id,mes,year,importe) VALUES (" .
                $parametros['operacion_id'] . "," . $parametros['tipo_expensas_id'] . "," . $parametros['inmuebles_id'] . "," . $parametros['mes'] . "," .
                $parametros['year'] . "," . $parametros['importe'] . ")";
        $result = mysql_query($sql);
        return $result;
    }

    public function existe_expensa($parametros) {

        $sql = "SELECT COUNT(*) cantidad FROM operaciones_expensas WHERE operacion_id=" . $parametros['operacion_id'] . " AND 
            tipo_expensas_id=" . $parametros['tipo_expensas_id'] . " AND inmuebles_id=" . $parametros['inmuebles_id'] . " AND mes=" . $parametros['mes'] .
                " AND year=" . $parametros['year'];
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        if ($row['cantidad'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function borrar_expensa($parametros) {

        $sql = "DELETE FROM operaciones_expensas WHERE operacion_id=" . $parametros['operacion_id'] . " AND 
            tipo_expensas_id=" . $parametros['tipo_expensas_id'] . " AND inmuebles_id=" . $parametros['inmuebles_id'] . " AND mes=" . $parametros['mes'] .
                " AND year=" . $parametros['year'];
        $result = mysql_query($sql);
        return $result;
    }

    public function deuda_expensa($parametros) {

        $sql = "SELECT * FROM deuda_expensas WHERE operacion_id=" . $parametros['operacion_id'] . "  AND mes=" . $parametros['mes'] .
                " AND year=" . $parametros['year'];
        $result = mysql_query($sql);
        return $result;
    }

    public function obtener_datos_expensa_por_comprobante_id($parametros) {

        $sql = "SELECT mes,year from operaciones_expensas where "
                . "comprobante_id=" . $parametros['comprobante_id'];
        $result = mysql_query($sql);
        return $result;
    }

    public function agregar_nueva_pendiente($parametros) {

        $sql = "INSERT INTO operaciones_expensas_pendientes (operacion_id,tipo_expensas_id,inmuebles_id,mes,year,importe,estado) VALUES (" .
                $parametros['operacion_id'] . "," . $parametros['tipo_expensas_id'] . "," . $parametros['inmuebles_id'] . "," . $parametros['mes'] . "," .
                $parametros['year'] . "," . $parametros['importe'] . ",'pendiente')";
        $result = mysql_query($sql);
        return $result;
    }

    public function existe_expensa_pendiente($parametros) {

        $sql = "SELECT COUNT(*) cantidad FROM operaciones_expensas_pendientes WHERE operacion_id=" . $parametros['operacion_id'] . " AND 
            tipo_expensas_id=" . $parametros['tipo_expensas_id'] . " AND inmuebles_id=" . $parametros['inmuebles_id'] . " AND mes=" . $parametros['mes'] .
                " AND year=" . $parametros['year']." AND estado='pendiente'";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        if ($row['cantidad'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function borrar_expensa_pendiente($parametros) {

        $sql = "DELETE FROM operaciones_expensas_pendientes WHERE operacion_id=" . $parametros['operacion_id'] . " AND 
            tipo_expensas_id=" . $parametros['tipo_expensas_id'] . " AND inmuebles_id=" . $parametros['inmuebles_id'] . " AND mes=" . $parametros['mes'] .
                " AND year=" . $parametros['year'];
        $result = mysql_query($sql);
        return $result;
    }
    
    public function anular_expensa_pendiente($parametros) {

        $sql = "UPDATE operaciones_expensas_pendientes SET estado='anulado' WHERE id=" . $parametros['id_expensa'];
        $result = mysql_query($sql);
        return $result;
    }

    public function obtener_expensa_pendiente_by_id($parametros) {
        $sql = "SELECT * FROM operaciones_expensas_pendientes WHERE id=" . $parametros['id'] . " AND estado='pendiente'";
        $result = mysql_query($sql);
        return $result;
    }

    function actualizar_registro_estado($arreglo) {
        $query = "UPDATE operaciones_expensas_pendientes SET estado='" . $arreglo['estado'] . "' WHERE id=" . $arreglo['id'];
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }

    function obtener_expensas_pagadas($parametros = null) {
        $query = "SELECT * FROM(SELECT operaciones_expensas.operacion_id,operaciones_expensas.`year`,
        operaciones_expensas.mes,
        operaciones_expensas.importe,operaciones_expensas.comprobante_id, tipo_expensas.descripcion,
        a2_movimientos.fecha_movimiento,
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

        FROM operaciones_expensas 
        LEFT JOIN a2_movimientos ON operaciones_expensas.comprobante_id=a2_movimientos.id_movimiento
	LEFT JOIN tipo_expensas ON operaciones_expensas.tipo_expensas_id=tipo_expensas.id
        INNER JOIN a2_operaciones_inmobiliarias ON 
        a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=operaciones_expensas.operacion_id                				
        LEFT JOIN a2_noticias ON
        a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad
        LEFT JOIN a2_secciones	ON			
        a2_noticias.seccion=a2_secciones.id_seccion
        LEFT JOIN a2_objeto_de_propiedad ON
        a2_noticias.operacion=a2_objeto_de_propiedad.id_operacion
        LEFT JOIN a2_clientes ON
        a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente
        WHERE operaciones_expensas.estado='pagado'
				) as t ";
        if(count($parametros)>0){
            $query.=" WHERE t.operacion_id=" . $parametros['operacion_id'] . " AND t.id_noticia=" . $parametros['inmuebles_id'] . 
                    " AND t.mes=" . $parametros['mes'] ." AND t.year=" . $parametros['year'];
        }
        $query.=" ORDER BY fecha_movimiento DESC";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }

}