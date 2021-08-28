<?php

class Propiedad {

    public function obtener_propiedad_por_liquidacion($parametros) {

        $sql = "select a2_noticias.id_noticia,a2_noticias.direccion from a2_noticias INNER JOIN a2_operaciones_inmobiliarias ON 
(a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN a2_liquidaciones ON
(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion)
where a2_liquidaciones.id_liquidacion=" . $parametros['id_liquidacion'];
        $result = mysql_query($sql);
        return $result;
    }

    public function obtener_propiedad_historial_por_liquidacion($parametros) {

        $sql = "select a2_noticias.id_noticia,a2_noticias.direccion from a2_noticias INNER JOIN a2_operaciones_inmobiliarias_copy ON 
(a2_noticias.id_noticia=a2_operaciones_inmobiliarias_copy.cod_propiedad) INNER JOIN a2_liquidaciones ON
(a2_operaciones_inmobiliarias_copy.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion)
where a2_liquidaciones.id_liquidacion=" . $parametros['id_liquidacion'];
        $result = mysql_query($sql);
        return $result;
    }

    public function obtener_propiedad_expensas_por_comprobante_id($parametros) {

        $sql = "select a2_noticias.direccion from a2_noticias INNER JOIN a2_operaciones_inmobiliarias ON 
(a2_noticias.id_noticia=a2_operaciones_inmobiliarias.cod_propiedad) INNER JOIN operaciones_expensas ON
(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=operaciones_expensas.operacion_id)
where operaciones_expensas.comprobante_id=" . $parametros['comprobante_id'];
        $result = mysql_query($sql);
        return $result;
    }

    public function obtener_propiedades_por_propietario($propietario_id) {
        $sql = "SELECT id_noticia,titulo,direccion from a2_noticias INNER JOIN inmuebles_propietarios ON
(a2_noticias.id_noticia=inmuebles_propietarios.inmueble_id) WHERE inmuebles_propietarios.propietario_id=" . $propietario_id;
        $result = mysql_query($sql);
        return $result;
    }

    public function obtener_comision_por_inmueble($parametro) {
        $sql = "SELECT comision FROM inmuebles_propietarios WHERE inmueble_id=" . $parametro['inmuebles_id'];
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            return $row['comision'];
        } else {
            return FALSE;
        }
    }

    public function tiene_propietario($inmueble_id) {
        $cadena = "SELECT COUNT(*) as cantidad FROM inmuebles_propietarios WHERE inmueble_id=" . $inmueble_id;
        $resultado = mysql_query($cadena);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n";
            die();
        }                
        if (mysql_num_rows($resultado) > 0) {            
            $row = mysql_fetch_array($resultado);
            if ($row['cantidad']==1) {
                return 1;
            } else {
                return 0;
            }
        } else {            
            return 0;
        }
    }

}
