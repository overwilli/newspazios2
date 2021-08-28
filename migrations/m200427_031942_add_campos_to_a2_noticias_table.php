<?php

use yii\db\Migration;

/**
 * Class m200427_031942_add_campos_to_a2_noticias_table
 */
class m200427_031942_add_campos_to_a2_noticias_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('a2_noticias', 'luz', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'gas', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'cloaca', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'agua', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'parrilla', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'salon_u_m', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'piscina', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'seguridad', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'amueblado', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'descripcion', $this->text());        
        $this->addColumn('a2_noticias', 'apto_comercial', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'apto_profesional', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'portero_electrico', $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'disposicion', "ENUM('FRENTE','CONTRAFRENTE')");
        $this->addColumn('a2_noticias', 'antiguedad', $this->integer());
        //$this->addColumn('a2_noticias', 'ambiente',  "TINYINT NULL");
        $this->addColumn('a2_noticias', 'ascensor',  "TINYINT NULL");
        $this->addColumn('a2_noticias', 'cochera',  "TINYINT NULL");
        $this->addColumn('a2_noticias', 'patio',   $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'balcon',   $this->boolean()->defaultValue(FALSE));
        $this->addColumn('a2_noticias', 'barrio', $this->string());
        $this->addColumn('a2_noticias', 'estado_reg', "ENUM('ACTIVO','PENDIENTE','ELIMINADO')");
        $this->addColumn('a2_noticias', 'porcion',  "TINYINT NULL");
        $this->addColumn('a2_noticias', 'codigo_postal', $this->integer());
        $this->alterColumn('a2_noticias', 'frente', $this->float());
        $this->alterColumn('a2_noticias', 'fondo', $this->float());

        //se modifica a null las columnas
        $this->alterColumn('a2_noticias', 'ambientes', "INT NULL");
        $this->alterColumn('a2_noticias', 'sup_cubierta', "INT NULL");
        $this->alterColumn('a2_noticias', 'sup_terreno', "INT NULL");
        $this->alterColumn('a2_noticias', 'habitaciones', "INT NULL");
        $this->alterColumn('a2_noticias', 'dormitorios', "INT NULL");
        $this->alterColumn('a2_noticias', 'banios', "INT NULL");
        $this->alterColumn('a2_noticias', 'conv_desocup', "INT NULL");
        $this->alterColumn('a2_noticias', 'direccion', "varchar NULL");
        $this->alterColumn('a2_noticias', 'id_estado', "INT NULL");
        $this->alterColumn('a2_noticias', 'id_grupo', "INT NULL");
        $this->alterColumn('a2_noticias', 'precio', "float NULL");
        $this->alterColumn('a2_noticias', 'fechacarga', "datetime NULL");
        $this->alterColumn('a2_noticias', 'operacion', "INT NULL");
        $this->alterColumn('a2_noticias', 'alq_vendida', "INT NULL");
        $this->alterColumn('a2_noticias', 'fondo', "float NULL");
        $this->alterColumn('a2_noticias', 'padroniibb', "varchar(250) NULL");
        $this->alterColumn('a2_noticias', 'padronaguas', "varchar(250) NULL");
        $this->alterColumn('a2_noticias', 'padronmunicipal', "varchar(250) NULL");
        


        $this->update('{{%a2_noticias}}', [
            'estado_reg' => 'ACTIVO',
        ]);

       
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('a2_noticias', 'luz');
        $this->dropColumn('a2_noticias', 'gas');
        $this->dropColumn('a2_noticias', 'cloaca');
        $this->dropColumn('a2_noticias', 'agua');
        $this->dropColumn('a2_noticias', 'parrilla');
        $this->dropColumn('a2_noticias', 'salon_u_m');
        $this->dropColumn('a2_noticias', 'piscina');
        $this->dropColumn('a2_noticias', 'seguridad');
        $this->dropColumn('a2_noticias', 'amueblado');
        $this->dropColumn('a2_noticias', 'descripcion');
        $this->dropColumn('a2_noticias', 'apto_comercial');
        $this->dropColumn('a2_noticias', 'apto_profesional');
        $this->dropColumn('a2_noticias', 'portero_electrico');
        $this->dropColumn('a2_noticias', 'disposicion');
        $this->dropColumn('a2_noticias', 'antiguedad');
        $this->dropColumn('a2_noticias', 'ambiente');
        $this->dropColumn('a2_noticias', 'ascensor');
        $this->dropColumn('a2_noticias', 'cochera');
        $this->dropColumn('a2_noticias', 'patio');
        $this->dropColumn('a2_noticias', 'balcon');
        $this->dropColumn('a2_noticias', 'barrio');
        $this->dropColumn('a2_noticias', 'estado_reg');
        $this->dropColumn('a2_noticias', 'porcion');
        $this->dropColumn('a2_noticias', 'codigo_postal');
        $this->alterColumn('a2_noticias', 'frente', $this->integer());
        $this->alterColumn('a2_noticias', 'fondo', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200427_031942_add_xxx_campos_to_a2_noticias_table cannot be reverted.\n";

        return false;
    }
    */
}
