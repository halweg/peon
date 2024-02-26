<?php

use Framework\Database\Connection\Connection;

class UsersTableCreate
{
    public function migrate(Connection $connection)
    {
        $table = $connection->createTable('users');
        $table->id('id');
        $table->text('name')->default(1);
        $table->text('sex')->default("");
        $table->bool('is_block')->default(false);
        $table->dateTime('created_at')->default('CURRENT_TIMESTAMP');
        $table->text('notes');
        $table->text('city');
        $table->execute();
    }
}
