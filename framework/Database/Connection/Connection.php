<?php

namespace Framework\Database\Connection;

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
}