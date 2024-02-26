<?php

namespace Framework\Database\Connection;

use Framework\Database\Migration\Migration;
use Framework\Database\QueryBuilder\QueryBuilder;
use Pdo;
abstract class Connection
{
    /**
     * 获取此连接的底层 Pdo 实例
     * */
    abstract public function pdo(): Pdo;

    /**
     * 在此连接上开始新的查询
     */
    abstract public function query(): QueryBuilder;

    abstract public function createTable(string $table): Migration;

    abstract public function alterTable(string $table): Migration;

    abstract public function getTables(): array;

    abstract public function hasTable(string $name): bool;

    abstract public function dropTables(): int;
}
