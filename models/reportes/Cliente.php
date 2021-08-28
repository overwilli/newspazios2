<?php
class Cliente{
           
    public function obtener_cliente_por_liquidacion($parametros){
        
        $sql = "select a2_clientes.* from a2_clientes INNER JOIN a2_operaciones_inmobiliarias ON 
(a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente) INNER JOIN a2_liquidaciones ON
(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion)
where a2_liquidaciones.id_liquidacion=".$parametros['id_liquidacion'];
        $result = mysql_query($sql);
        return $result;
    }
    
    public function obtener_cliente_historial_por_liquidacion($parametros){
        
        $sql = "select a2_clientes.* from a2_clientes INNER JOIN a2_operaciones_inmobiliarias_copy ON 
(a2_clientes.id_cliente=a2_operaciones_inmobiliarias_copy.cod_cliente) INNER JOIN a2_liquidaciones ON
(a2_operaciones_inmobiliarias_copy.id_operacion_inmobiliaria=a2_liquidaciones.id_operacion)
where a2_liquidaciones.id_liquidacion=".$parametros['id_liquidacion'];
        $result = mysql_query($sql);
        return $result;
    }
    
    /*
     * PERMITE OBTENER EL NOMBRE DEL CLIENTE QUE TIENE UNA OPERACION DE EXPENSAS
     * QUE FUE PAGADA, FILTRANDO POR EL ID DE COMPROBANTE UNICO.
     */
    public function obtener_cliente_expensas_por_comprobante_id($parametros){
        
        $sql = "select a2_clientes.* from a2_clientes INNER JOIN a2_operaciones_inmobiliarias ON 
(a2_clientes.id_cliente=a2_operaciones_inmobiliarias.cod_cliente) INNER JOIN operaciones_expensas ON
(a2_operaciones_inmobiliarias.id_operacion_inmobiliaria=operaciones_expensas.operacion_id)
where operaciones_expensas.comprobante_id=".$parametros['comprobante_id'];
        $result = mysql_query($sql);
        return $result;
    }
}