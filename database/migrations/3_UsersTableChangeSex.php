<?php

use Framework\Database\Connection\Connection;

class UsersTableChangeSex
{
    public function migrate(Connection $connection)
    {
        $table = $connection->alterTable('users');
        $table->int('sex')->nullable()->alter();
        $table->execute();
    }
}
