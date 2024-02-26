<?php

use Framework\Database\Connection\Connection;

class UsersTableDropCity
{
    public function migrate(Connection $connection)
    {
        $table = $connection->alterTable('users');
        $table->dropColumn('city');
        $table->execute();
    }
}
