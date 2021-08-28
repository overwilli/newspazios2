<?php

Class Cajas {

    function obtener_cajas_abiertas() {
        $cadenaSQL = "SELECT a2_caja.id_caja,a2_caja.inicio_fecha,a2_caja.inicio,
            a2_caja.caja_final as aux_caja_final,a2_caja.sobrante_faltante as aux_sobrante_faltante,
            a2_caja.caja_cierre,a2_caja.rendicion,a2_caja.cierre_usuario,a2_caja.cierre_fecha,
            a2_caja.cierre_hora,a2_caja.numero_caja,nikname FROM a2_caja INNER JOIN a_noticias_usuarios ON 
            a2_caja.numero_caja=a_noticias_usuarios.numero_caja WHERE cierre_usuario IS NULL";
        $result = mysql_query($cadenaSQL);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $cadenaSQL;
            die();
        }
        if (mysql_num_rows($result) <= 0) {
            return false;
        }
        return $result;
    }

    function obtener_caja_abierta($numero_caja) {
        $query = "SELECT a2_caja.id_caja, a2_caja.inicio_fecha, a2_caja.inicio,a2_caja.inicio_usuario,
            a2_caja.caja_final as aux_caja_final, a2_caja.sobrante_faltante as aux_sobrante_faltante,
            a2_caja.caja_cierre, a2_caja.rendicion, a2_caja.cierre_usuario, a2_caja.cierre_fecha, a2_caja.cierre_hora FROM a2_caja 
            WHERE numero_caja={$numero_caja} AND cierre_fecha IS NULL ";

        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $query;
            die();
        }
        return $result;
    }

    function obtener_caja_by_numero_e_id_caja($numero_caja, $id_caja) {
        $query = "SELECT a2_caja.id_caja, a2_caja.inicio_fecha, a2_caja.inicio,
            a2_caja.caja_final as aux_caja_final, a2_caja.sobrante_faltante as aux_sobrante_faltante,
            a2_caja.caja_cierre, a2_caja.rendicion, a2_caja.cierre_usuario, a2_caja.cierre_fecha, a2_caja.cierre_hora FROM a2_caja 
            WHERE numero_caja={$numero_caja} AND id_caja=" . $id_caja;

        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $query;
            die();
        }
        return $result;
    }

    function existe_caja_cerrada($numero_caja, $fecha) {
        $query = "SELECT COUNT(*) as cantidad FROM a2_caja WHERE numero_caja={$numero_caja} AND cierre_fecha='" . $fecha . "'";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $query;
            die();
        }
        $row = mysql_fetch_array($result);

        if ($row['cantidad'] == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function obtener_las_cajas_existentes() {
        $query = "SELECT numero_caja FROM a_noticias_usuarios ORDER BY numero_caja ASC;";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $query;
            die();
        }
        return $result;
    }

    function calcular_importe($id_liquidacion,$numero_caja) {
        $importe_pagado = 0;
        /* $query = "SELECT monto FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON 
          (a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' "
          . "AND movimiento_tipo<> 9 AND movimiento_tipo<> 7 AND id_liquidacion=" . $id_liquidacion." "
          . " GROUP BY a2_movimientos.movimiento_tipo, a2_movimientos.id_liquidacion
          ORDER BY id_movimiento DESC"; */
        $query = "SELECT * FROM (SELECT * FROM  a2_movimientos INNER JOIN a2_movimiento_tipo ON 
    (a2_movimientos.movimiento_tipo=a2_movimiento_tipo.id_tipo) WHERE tipo_registro='pago' AND movimiento_tipo<> 9 AND 
		movimiento_tipo<> 7 AND id_liquidacion=" . $id_liquidacion . " AND a2_movimientos.numero_caja={$numero_caja}
ORDER BY id_movimiento DESC)AS  t1
GROUP BY t1.movimiento_tipo, t1.id_liquidacion
ORDER BY id_movimiento DESC";

        $resultado = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n<br><br><br><br>" . $query;
            die();
        }
        while ($fila = mysql_fetch_array($resultado)) {
            $importe_pagado+=$fila['monto'];
        }
        echo $importe_pagado . "-{$id_liquidacion}<br/>";
        return $importe_pagado;
    }

}

?>