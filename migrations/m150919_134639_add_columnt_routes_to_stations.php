<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_134639_add_columnt_routes_to_stations extends Migration
{
    public function up()
    {
        $this->addColumn('{{%station}}', 'routes', 'varchar(255)');
    }

    public function down()
    {
        $this->dropColumn('{{%station}}', 'routes');
    }
}
