<?php

class LiquidacionPropietario {

    function nueva_liquidacion($param) {
        $query = "INSERT INTO liquidacionpagadas (fecha,propietario_id,fecha_creacion,estado,usuario) VALUES ('" .
                $param['fecha'] . "'," . $param['propietario_id'] . ",NOW(),'" . $param['estado'] . "','" . $param['usuario'] . "')";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return mysql_insert_id();
    }
    
    function obtener_todas_ordenes_pago($estado="",$nombre="") {
        $query = "SELECT liquidacionpagadas.*,propietarios.apellido, propietarios.nombre FROM liquidacionpagadas INNER JOIN propietarios ON "
                . "(liquidacionpagadas.propietario_id=propietarios.id)";
        if(!empty($estado)||!empty($nombre)){
            $query.=" WHERE ";
        }
        if(!empty($estado)){ 
            $query.=" liquidacionpagadas.estado='{$estado}' "; 
            if(!empty($nombre)){ 
                $query.=" AND ";
            }
        }
        if(!empty($nombre)){ 
            $query.=" (propietarios.apellido LIKE '%{$nombre}%' OR propietarios.nombre LIKE '%{$nombre}%') ";            
        }
        $query.=" ORDER BY liquidacionpagadas.id DESC";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }
    
    function obtener_todas_ordenes_pago_by_propietario($propietario_id) {
        $query = "SELECT liquidacionpagadas.*,propietarios.apellido, propietarios.nombre FROM liquidacionpagadas INNER JOIN propietarios ON "
                . "(liquidacionpagadas.propietario_id=propietarios.id) WHERE propietarios.id={$propietario_id} ORDER BY liquidacionpagadas.id DESC";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }

    function obtener_liquidacion_by_id($liquidacion_id) {
        $query = "SELECT liquidacionpagadas.*,propietarios.apellido, propietarios.nombre FROM liquidacionpagadas INNER JOIN propietarios ON "
                . "(liquidacionpagadas.propietario_id=propietarios.id) WHERE liquidacionpagadas.id=" . $liquidacion_id;
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }
    
    function actualizar_orden_gastos($otros_gastos,$liquidacion_id) {
        $query = "UPDATE liquidacionpagadas SET gastos=".$otros_gastos." WHERE liquidacionpagadas.id=" . $liquidacion_id;
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }
    
    function anular_orden_pago($liquidacion_id) {
        $query = "UPDATE liquidacionpagadas SET estado='Anulado' WHERE liquidacionpagadas.id=" . $liquidacion_id;
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }
    
    function cerrar_orden_pago($liquidacion_id) {
        $query = "UPDATE liquidacionpagadas SET estado='Cerrado' WHERE liquidacionpagadas.id=" . $liquidacion_id;
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }

    function agregar_item_detalle($param) {
        $query = "INSERT INTO liqpagadas_detalle (liquidacionpagadas_id,liquidaciones_id,costo, dinero_cobrado,interes_por_mora,fecha_carga) VALUES (" .
                $param["liquidacionpagadas_id"] . "," . $param["liquidaciones_id"] . ",0," . $param["dinero_cobrado"] . "," . $param["interes_por_mora"] . ",NOW() )";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
    }

    function obtener_detalle_liquidacion($liquidacionpagadas_id) {
        $query = "SELECT liqpagadas_detalle.*,liq_anio,liq_mes,fecha_pago FROM liqpagadas_detalle INNER JOIN a2_liquidaciones ON 
liqpagadas_detalle.liquidaciones_id=a2_liquidaciones.id_liquidacion WHERE liquidacionpagadas_id=" . $liquidacionpagadas_id." ORDER BY fecha_pago ASC, liq_anio,liq_mes ASC";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }

    function existe_liquidacion($liquidaciones_id) {
        $query = "SELECT COUNT(*)as cantidad FROM liqpagadas_detalle INNER JOIN liquidacionpagadas ON liquidacionpagadas.id=liqpagadas_detalle.liquidacionpagadas_id "
                . " WHERE (liquidacionpagadas.estado<>'Anulado' AND liquidaciones_id=" . $liquidaciones_id.")";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        $row = mysql_fetch_array($result);
        if ($row['cantidad'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }        
    }
    
    function eliminar_item_detalle($item_id) {
        $query = "DELETE FROM liqpagadas_detalle WHERE id=" . $item_id;
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }        
    }
    
    function eliminar_liquidacion_por_orden_id($orden_id) {
        $query = "DELETE FROM liqpagadas_detalle WHERE liquidacionpagadas_id=" . $orden_id;
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }
    
    function obtener_inmueble_propietario($propietario_id,$inmueble_id) {
        $query = "SELECT * FROM inmuebles_propietarios WHERE inmueble_id=".$inmueble_id." AND propietario_id=".$propietario_id;
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }
    
    function actualizar_montos_y_cambiar_estado($parametros) {
        $query = "UPDATE liquidacionpagadas SET estado='".$parametros['estado']."',"
                . "total_cobrado=".$parametros['total_cobrado'].",interes_mora=".$parametros['interes_mora'].","
                . "comision=".$parametros['comision']." "
                . " WHERE liquidacionpagadas.id=" . $parametros['liquidacion_id'];
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;    
    }

}