<?php

use yii\db\Migration;

/**
 * Handles the creation of table `synoperacionesinmobiliariacopy`.
 */
class m200510_212027_create_synoperacionesinmobiliariacopy_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('a2_operaciones_inmobiliarias_copy', 'estado', "ENUM('ACTIVO','PENDIENTE','RENOVADO','FINALIZADO','ELIMINADO')");
        
        $this->update('{{%a2_operaciones_inmobiliarias_copy}}', [
            'estado' => 'FINALIZADO',
        ]);

        $sql="INSERT INTO a2_operaciones_inmobiliarias (`id_operacion_inmobiliaria`,`nro_ope`,`desde_anio`,
        `desde_mes`,`hasta_anio`,`hasta_mes`,`fecha_ope`,`fecha_desde`,`fecha_hasta`,`cod_propiedad`,
        `cod_cliente`,`cod_garante`,`observaciones`,`confirmada`,`fecha_confirmacion`,`conv_desocup`,
        `dia_venc_mensual`,`id_inmobiliaria`,`interes_dia_mora`,`editor`,`fechatimestamp`,
        `permite_pagos_pendientes`,`tiene_expensas`,estado)
        SELECT `id_operacion_inmobiliaria`,`nro_ope`,`desde_anio`,`desde_mes`,`hasta_anio`,`hasta_mes`,
        `fecha_ope`,`fecha_desde`,`fecha_hasta`,`cod_propiedad`,`cod_cliente`,`cod_garante`,`observaciones`,
        `confirmada`,`fecha_confirmacion`,`conv_desocup`,`dia_venc_mensual`,`id_inmobiliaria`,
        `interes_dia_mora`,`editor`,`fechatimestamp`,`permite_pagos_pendientes`,`tiene_expensas`,estado
        FROM a2_operaciones_inmobiliarias_copy; ";
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('a2_operaciones_inmobiliarias_copy', 'estado');
        $sql="DELETE FROM a2_operaciones_inmobiliarias WHERE id_operacion_inmobiliaria IN 
        (SELECT id_operacion_inmobiliaria FROM a2_operaciones_inmobiliarias_copy) ";
        $this->execute($sql);
    }
}
