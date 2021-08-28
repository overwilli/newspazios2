<?php

use yii\db\Migration;

/**
 * Class m200514_021028_yii
 */
class m200514_021028_borrar_tablas extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropTable('kfm_directories');
        $this->dropTable('kfm_files');
        $this->dropTable('kfm_files_images');
        $this->dropTable('kfm_files_images_thumbs');
        $this->dropTable('kfm_parameters');
        $this->dropTable('kfm_plugin_extensions');
        $this->dropTable('kfm_session');
        $this->dropTable('kfm_session_vars');
        $this->dropTable('kfm_settings');
        $this->dropTable('kfm_tagged_files');
        $this->dropTable('kfm_tags');
        $this->dropTable('kfm_translations');
        $this->dropTable('kfm_users');
        $this->dropTable('puntos_gps');
        $this->dropTable('a2_destacadas1');
        $this->dropTable('a2_destacadas2');
        $this->dropTable('a2_noticias_relacionadas');
        $this->dropTable('a2_clientes_1');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "No se pueden recuperar las tablas perdidas.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200514_021028_yii cannot be reverted.\n";

        return false;
    }
    */
}
