<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_124604_create_table_stations extends Migration
{
    public function up()
    {
        $this->createTable('{{%station}}', [
            'id' => 'pk',
            'igis_id' => 'int not null',
            'izhget_id' => 'int not null',
            'igis_name' => 'varchar(255) not null',
            'lat' => 'decimal(20,18)',
            'lon' => 'decimal(20,18)'
        ]);
        
        $this->createIndex('igis_id', '{{%station}}', 'igis_id', true);
        $this->createIndex('izhget_id', '{{%station}}', 'izhget_id');
    }

    public function down()
    {
        $this->dropTable('{{%station}}');
    }
}
