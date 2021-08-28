<?php

use yii\db\Migration;

/**
 * Class m200507_030437_add_campos_to_a2_operaciones_inmobiliarias_table
 */
class m200507_030437_add_campos_to_a2_operaciones_inmobiliarias_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('a2_operaciones_inmobiliarias', 'plazo', "TINYINT NULL");
        $this->addColumn('a2_operaciones_inmobiliarias', 'tipo_contrato', "ENUM('LOCACION','COMODATO')");
        $this->addColumn('a2_operaciones_inmobiliarias', 'firma_representante', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_operaciones_inmobiliarias', 'representante', $this->string());
        $this->addColumn('a2_operaciones_inmobiliarias', 'representante_cuit', $this->string());
        $this->addColumn('a2_operaciones_inmobiliarias', 'locador', $this->integer());
        $this->addColumn('a2_operaciones_inmobiliarias', 'locador_1', $this->integer());
        $this->addColumn('a2_operaciones_inmobiliarias', 'locador_2', $this->integer());
        $this->addColumn('a2_operaciones_inmobiliarias', 'inquilino_1', $this->integer());
        $this->addColumn('a2_operaciones_inmobiliarias', 'inquilino_2', $this->integer());
        $this->addColumn('a2_operaciones_inmobiliarias', 'deposito_garantia', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_operaciones_inmobiliarias', 'deposito_monto', $this->decimal(10, 2));
        $this->addColumn('a2_operaciones_inmobiliarias', 'deposito_cuotas', "TINYINT NULL");
        $this->addColumn('a2_operaciones_inmobiliarias', 'deposito_contrato_monto', $this->decimal(10, 2));
        $this->addColumn('a2_operaciones_inmobiliarias', 'excento', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_operaciones_inmobiliarias', 'destino_contrato', "ENUM('LOCAL COMERCIAL','DOMESTICO','OFICINAS')");
        $this->addColumn('a2_operaciones_inmobiliarias', 'honorarios', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_operaciones_inmobiliarias', 'excento_monto', $this->decimal(10, 2));
        $this->addColumn('a2_operaciones_inmobiliarias', 'excento_cuotas', $this->decimal(10, 2));
        $this->addColumn('a2_operaciones_inmobiliarias', 'estado_renovacion', "ENUM('RENOVADO','NUEVO') DEFAULT 'NUEVO'");
        $this->addColumn('a2_operaciones_inmobiliarias', 'contrato_firmado', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_operaciones_inmobiliarias', 'fecha_firma_contrato',$this->date());
        $this->addColumn('a2_operaciones_inmobiliarias', 'fecha_firma_convenio',$this->date());
        $this->addColumn('a2_operaciones_inmobiliarias', 'expensas', $this->text());
        $this->addColumn('a2_operaciones_inmobiliarias', 'nota', $this->text());
        $this->addColumn('a2_operaciones_inmobiliarias', 'ultimo_contacto',$this->datetime());
        $this->addColumn('a2_operaciones_inmobiliarias', 'usuario_contacto', $this->string());
        $this->addColumn('a2_operaciones_inmobiliarias', 'estado', "ENUM('ACTIVO','PENDIENTE','RENOVADO','FINALIZADO','ELIMINADO')");

        //se modifica a null las columnas
        $this->alterColumn('a2_operaciones_inmobiliarias', 'cod_garante', "INT NULL");
        

        $this->update('{{%a2_operaciones_inmobiliarias}}', [
            'estado' => 'ACTIVO',
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('a2_operaciones_inmobiliarias', 'plazo');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'tipo_contrato');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'firma_representante');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'representante');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'representante_cuit');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'locador');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'locador_1');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'locador_2');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'inquilino_1');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'inquilino_2');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'deposito_garantia');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'deposito_monto');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'deposito_cuotas');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'deposito_contrato_monto');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'excento');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'destino_contrato');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'honorarios');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'excento_monto');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'excento_cuotas');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'estado_renovacion');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'contrato_firmado');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'fecha_firma_contrato');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'fecha_firma_convenio');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'expensas');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'nota');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'ultimo_contacto');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'usuario_contacto');
        $this->dropColumn('a2_operaciones_inmobiliarias', 'estado');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200507_030437_add_campos_to_a2_operaciones_inmobiliarias_table cannot be reverted.\n";

        return false;
    }
    */
}
