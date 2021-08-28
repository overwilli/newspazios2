<?php

class Movimiento {

    public function nuevo_movimiento($parametros) {
        $cadena = "INSERT INTO a2_movimientos (fecha_movimiento,usuario,movimiento_tipo,monto,
            id_liquidacion,observaciones,comprobante_pago_unico";
        if (isset($parametros['comprobante_nro'])) {
            $cadena.=",comprobante_nro";
        }
        $cadena.=",numero_caja,tipo_registro,
            estado_pago_pendiente,caja_id) VALUES('" . $parametros['fecha'] . "','" . $parametros['usuario'] . "',
            " . $parametros['tipo_mov'] . "," . $parametros['monto'] . "," . $parametros['id_liquidacion'] . ",
            '" . $parametros['observaciones'] . "'," . $parametros['comprobante_unico'];
        if (isset($parametros['comprobante_nro'])) {
            $cadena.="," . $parametros['comprobante_nro'];
        }
        $cadena.="," . $parametros['numero_caja'] . ", '" . $parametros['tipo_registro'] . "',
            '" . $parametros['estado_registro_pendiente'] . "'," . $parametros['id_caja'] . ")";
        $result = mysql_query($cadena);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
            die();
        }
    }

    public function obtener_movimiento_por_tipo($parametros) {
        $cajas = "";
        for ($i = $parametros['numero_caja_desde']; $i <= $parametros['numero_caja_hasta']; $i++) {
            if ($i != $parametros['numero_caja_desde']) {
                $cajas.=" OR ";
            }
            $cajas.= "numero_caja={$i}";
        }

        $sql = "SELECT a2_movimientos.fecha_movimiento,a2_movimientos.usuario,a2_movimientos.movimiento_tipo,
            a2_movimientos.monto,a2_movimientos.id_liquidacion,	a2_movimientos.observaciones,a2_movimientos.comprobante_fecha,
    a2_movimientos.comprobante_nro,a2_movimiento_tipo.denominacion,a2_movimiento_tipo.tipo_movimiento,
    a2_movimientos.numero_caja,a2_movimientos.tipo_registro FROM a2_movimientos LEFT JOIN  
    a2_movimiento_tipo ON  a2_movimiento_tipo.id_tipo=a2_movimientos.movimiento_tipo WHERE 
    a2_movimiento_tipo.id_tipo=" . $parametros['tipo_movimiento'] . " AND (fecha_movimiento>='" .
                $parametros['fecha_desde'] . "' AND fecha_movimiento<='" . $parametros['fecha_hasta'] . "')"
                . " AND (" . $cajas . ")  ORDER BY a2_movimientos.fecha_movimiento ASC, a2_movimientos.id_movimiento ASC";
        $result = mysql_query($sql);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $sql;
            die();
        }
        return $result;
    }

    public function exitste_movimiento_tipo_pendiente($liquidacion_id) {
        $cadena = "SELECT count(*) as total FROM a2_movimientos WHERE estado_pago_pendiente='pendiente'AND id_liquidacion=" . $liquidacion_id;
        $result = mysql_query($cadena);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
        $fila = mysql_fetch_array($result);
        if ($fila['total'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function anular_movimiento($parametros) {
        $query = "UPDATE a2_movimientos SET estado_pago_pendiente='anulado' WHERE id_movimiento=".
                $parametros['id_movimiento'];
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $query;
            die();
        }
    }
    
    public function  registrar_operacion_movimiento($parametros){
        $query = "INSERT INTO operaciones_movimientos (operacion_id,movimiento_id,tipo_operacion) VALUES (".$parametros['operacio_id'].
                ",".$parametros['movimiento_id'].",'".$parametros['tipo_operacion']."')";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $query;
            die();
        }
    }

}