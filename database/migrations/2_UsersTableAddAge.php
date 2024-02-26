<?php

use Framework\Database\Connection\Connection;

class UsersTableAddAge
{
    public function migrate(Connection $connection)
    {
        $table = $connection->alterTable('users');
        $table->int('age');
        $table->execute();
    }
}
