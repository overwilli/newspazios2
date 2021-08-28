<?php

class Facturacion {

    public function obtener_facturacion_por_fechas($parametros) {

        $sql = "SELECT * FROM a2_facturacion  WHERE (tipo_movimiento='factura A' OR tipo_movimiento='factura B' OR 
            tipo_movimiento='Nota de Crédito') AND (fecha>='" .
                $parametros['fecha_desde'] . "' AND fecha<='" . $parametros['fecha_hasta'] . "') ORDER BY fecha ASC,id_facturacion ASC";
        $result = mysql_query($sql);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
            die();
        }
        return $result;
    }

    public function total_facturas_por_liquidacion($parametros) {

        $sql = "SELECT count(*) as total FROM a2_facturacion  WHERE  id_liquidacion=" . $parametros['id_liquidacion'] . " AND "
                . "tipo_movimiento='" . $parametros['tipo_movimiento'] . "'";
        $result = mysql_query($sql);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
            die();
        }
        $row = mysql_fetch_array($result);
        return $row['total'];
    }

    public function obtener_factura_por_liquidacion($parametros) {

        $sql = "SELECT tipo_movimiento FROM a2_facturacion  WHERE tipo_movimiento<>'Nota de Crédito'  AND"
                . "  id_liquidacion=" . $parametros['id_liquidacion'] . " ORDER BY fecha DESC";
        $result = mysql_query($sql);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
            die();
        }
        $row = mysql_fetch_array($result);
        return $row['tipo_movimiento'];
    }

    public function obtener_todas_facturas() {
        $cadena = "SELECT * FROM a2_facturacion ORDER BY fecha DESC";
        $results = mysql_query($cadena);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $cadena;
            die();
        }
        return $results;
    }

}