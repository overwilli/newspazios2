<?php

class Operacion {

    function __construct() {
        
    }

    /*ESTA FUNCION PERMITE SABER SI TIENE PAGOS PENDIENTES*/
    public function tiene_pago_pendiente($id_liquidacion) {        
        $cadena = "SELECT permite_pagos_pendientes FROM  a2_operaciones_inmobiliarias WHERE "
                . "id_operacion_inmobiliaria=" . $id_liquidacion;
        $result = mysql_query($cadena);        
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
        $row = mysql_fetch_array($result);
        return $row["permite_pagos_pendientes"];        
    }
    
    /*
     * Esta funcionalidad permite determinar el total de documentos esperados para una operacion,
     * filtrando por mes y añio y tipo de documento 'factua A, B, etc'
     */
    public function total_documento_por_operacion($parametros) {   
        $id_tipo_factura=0;
        if($parametros['tipo_movimiento']=='factura A'){
            $id_tipo_factura=1;
        }else{
            if($parametros['tipo_movimiento']=='factura B'){
                $id_tipo_factura=2;
            }        
        }
        $cadena = "SELECT count(*) as total FROM  a2_operaciones_items WHERE "
                . "id_operacion=" . $parametros['id_operacion']." AND anio=".$parametros['anio']." "
                . " AND mes=".$parametros['mes']." AND id_factura=".$id_tipo_factura;
        $result = mysql_query($cadena);        
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
        $row = mysql_fetch_array($result);
        return $row["total"];        
    }
    
    
    function renovar_operacion($arreglo){
        $cadena = "UPDATE a2_operaciones_inmobiliarias SET desde_anio=".$arreglo['desde_anio'].","
                . "desde_mes=".$arreglo['desde_mes'].",hasta_anio=".$arreglo['hasta_anio'].",hasta_mes=".$arreglo['hasta_mes']." WHERE "
                . "id_operacion_inmobiliaria=" . $arreglo['operacion_id'];
        mysql_query($cadena);        
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
    }
    
    function nuevo_item_operacion($arreglo){
        $cadena = "INSERT INTO a2_operaciones_items (id_operacion,anio,mes,id_factura,monto) VALUES (".$arreglo['id_operacion'].",".$arreglo['anio'].","
                . "".$arreglo['mes'].",".$arreglo['id_factura'].",".$arreglo['monto'].")";
        mysql_query($cadena);        
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
    }
    
    
    public function obtener_operacion_id($operacion_id) {        
        $cadena = "SELECT * FROM  a2_operaciones_inmobiliarias WHERE id_operacion_inmobiliaria=" . $operacion_id;
        $result = mysql_query($cadena);        
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . $cadena;
            die();
        }
        return $result;       
    }

}