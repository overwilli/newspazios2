<?php

namespace app\models;

use Yii;

class Utils extends yii\base\Model {


    public function Fecha_en_Castellano($fecharequerida) {

        $diassemana = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $fecharequerida = strtotime($fecharequerida);
        $mostrar="";
        $mostrar.= "" . $diassemana[(date("w", $fecharequerida))] . " " . date("j", $fecharequerida) . " de " . $meses[(date("n", $fecharequerida)) - 1] . " de " . date("Y", $fecharequerida);
        return $mostrar;
    }

    public function mes_en_letras($fecharequerida){
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", 
        "Octubre", "Noviembre", "Diciembre");
        $fecharequerida = strtotime($fecharequerida);
        $mostrar="";
        $mostrar.= "" .$meses[(date("n", $fecharequerida)) - 1];
        return utf8_encode($mostrar);
    }

    
}
