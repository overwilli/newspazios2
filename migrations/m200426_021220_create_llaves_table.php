<?php

use yii\db\Migration;

/**
 * Handles the creation of table `llaves`.
 */
class m200426_021220_create_llaves_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('llaves', [
            'id' => $this->primaryKey(),
            'inmueble_id' => $this->integer(),
            'numero_llave' => $this->string(),
            'inmobiliaria_id' => $this->integer(),
            'fecha_solicitud' => $this->datetime(),
            'tipo_solicitud' => "ENUM('PRESTAMO','DEVOLUCION')",
            'persona' => $this->string(),
            'observacion' => $this->text(),
            'fecha_devolucion' => $this->datetime(),
            'usuario_devolucion' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('llaves');
    }
}
