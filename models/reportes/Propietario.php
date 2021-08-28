<?php

class Propietario {

    function obtener_todos($filtro = "") {
        $query = "SELECT * FROM propietarios WHERE ";
        if (!empty($filtro)) {
            $query.=" (apellido LIKE '%" . $filtro . "%' OR nombre LIKE '%" . $filtro . "%') AND";
        }
        $query.=" estado=1 ORDER BY apellido,nombre";
        
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }

    function obtener_propietario_por_id($propietario_id) {
        $query = "SELECT * FROM propietarios WHERE id={$propietario_id}";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
        return $result;
    }

    function nuevo_propietarios($arreglo) {
        $query = "INSERT INTO propietarios (apellido,nombre,cuit,direccion,telefono,observacion) VALUES ('" . $arreglo['apellido'] . "','" . 
                $arreglo['nombre'] . "','" . $arreglo['cuit'] .
                "','" . $arreglo['direccion']. "','" . $arreglo['telefono'] . "','" . $arreglo['observacion'] . "')";
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
    }
    
    function actualizar_propietarios($arreglo) {
        $query = "UPDATE propietarios SET apellido='" . $arreglo['apellido'] . "',nombre='" . $arreglo['nombre'] ."',cuit='" . $arreglo['cuit'] ."',direccion='" . $arreglo['direccion']. "',"
                . "telefono='" . $arreglo['telefono'] . "',observacion='" . $arreglo['observacion'] . "' WHERE id=".$arreglo['id'];
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
    }
    
    function eliminar_propietarios($arreglo) {
        $query = "UPDATE propietarios SET estado=0 WHERE id=".$arreglo['id'];
        $result = mysql_query($query);
        if (mysql_errno() > 0) {
            print mysql_errno() . " " . mysql_error() . "\n" . $query;
            die();
        }
    }

}
