<?php

namespace Framework\Database\QueryBuilder;
use Framework\Database\Connection\Connection;
use Framework\Database\Exception\QueryException;
use Pdo;
use PdoStatement;


abstract class QueryBuilder
{
    protected string $type;
    protected string $columns;
    protected string $table;
    protected int $limit;
    protected int $offset;

    /**
     * 获取此查询的基础连接实例
     */
    abstract public function connection(): Connection;
    /**
     * 获取与当前查询匹配的所有行
     */
    public function all(): array
    {
        $statement = $this->prepare();
        $statement->execute();
        return $statement->fetchAll(Pdo::FETCH_ASSOC);
    }
    /**
     * 针对特定的连接准备一个查询
     */
    public function prepare(): PdoStatement
    {
        $query = '';
        if ($this->type === 'select') {
            $query = $this->compileSelect($query);
            $query = $this->compileLimit($query);
        }
        if (empty($query)) {
            throw new QueryException('Unrecognised query type');
        }
        return $this->connection->pdo()->prepare($query);
    }
    /**
     * 在查询中添加select子句
     */
    protected function compileSelect(string $query): string
    {
        $query .= " SELECT {$this->columns} FROM {$this->table}";
        return $query;
    }
    /**
     * 在查询中添加limit和offset子句
     */
    protected function compileLimit(string $query): string
    {
        if ($this->limit) {
            $query .= " LIMIT {$this->limit}";
        }
        if ($this->offset) {
            $query .= " OFFSET {$this->offset}";
        }
        return $query;
    }
    /**
     * 第一行
     */
    public function first(): array
    {
        $statement = $this->take(1)->prepare();
        $statement->execute();
        return $statement->fetchAll(Pdo::FETCH_ASSOC);
    }

    /**
    *限制一组查询结果，使其成为可能
    *获取单个或有限批次的行
     * */
    public function take(int $limit, int $offset = 0): static
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
    *指出查询的目标表*/
    public function from(string $table): static
    {
        $this->table = $table;
        return $this;
    }
    /**
    *指出查询类型是“select”并记下哪些字段应该由查询返回
     */
    public function select(string $columns = '*'): static
    {
        $this->type = 'select';
        $this->columns = $columns;
        return $this;
    }
}