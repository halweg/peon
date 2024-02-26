<?php

namespace Framework\Database\QueryBuilder;

use Framework\Database\Connection\Connection;
use Framework\Database\Exception\QueryException;
use Pdo;
use PdoStatement;

abstract class QueryBuilder
{
    protected string $type;
    protected array $columns;
    protected string $table;
    protected int $limit;
    protected int $offset;
    protected array $values;
    protected array $wheres = [];


    public function all(): array
    {
        $statement = $this->prepare();
        $statement->execute($this->getWhereValues());

        return $statement->fetchAll(Pdo::FETCH_ASSOC);
    }

    protected function getWhereValues(): array
    {
        $values = [];

        if (count($this->wheres) === 0) {
            return $values;
        }

        foreach ($this->wheres as $where) {
            $values[$where[0]] = $where[2];
        }

        return $values;
    }

    /**
     * 针对特定的连接准备一个查询
     */
    public function prepare(): PdoStatement
    {
        $query = '';

        if ($this->type === 'select') {
            $query = $this->compileSelect($query);
            $query = $this->compileWheres($query);
            $query = $this->compileLimit($query);
        }

        if ($this->type === 'insert') {
            $query = $this->compileInsert($query);
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
        $joinedColumns = join(', ', $this->columns);

        $query .= " SELECT {$joinedColumns} FROM {$this->table}";

        return $query;
    }

    /**
     * 在查询中添加limit和offset子句
     */
    protected function compileLimit(string $query): string
    {
        if (isset($this->limit)) {
            $query .= " LIMIT {$this->limit}";
        }

        if (isset($this->offset)) {
            $query .= " OFFSET {$this->offset}";
        }

        return $query;
    }

    protected function compileWheres(string $query): string
    {
        if (count($this->wheres) === 0) {
            return $query;
        }

        $query .= ' WHERE';

        foreach ($this->wheres as $i => $where) {
            if ($i > 0) {
                $query .= ', ';
            }

            [$column, $comparator, $value] = $where;

            $query .= " {$column} {$comparator} :{$column}";
        }

        return $query;
    }

    /**
     * 在查询中添加insert子句
     */
    protected function compileInsert(string $query): string
    {
        $joinedColumns = join(', ', $this->columns);
        $joinedPlaceholders = join(', ', array_map(fn($column) => ":{$column}", $this->columns));

        $query .= " INSERT INTO {$this->table} ({$joinedColumns}) VALUES ({$joinedPlaceholders})";

        return $query;
    }

    public function first(): array | null
    {
        $statement = $this->take(1)->prepare();
        $statement->execute($this->getWhereValues());

        $result = $statement->fetchAll(Pdo::FETCH_ASSOC);

        if (count($result) === 1) {
            return $result[0];
        }

        return null;
    }

    /**
     * 限制一组查询结果，使其成为可能 获取单个或有限批次的行
     */
    public function take(int $limit, int $offset = 0): static
    {
        $this->limit = $limit;
        $this->offset = $offset;

        return $this;
    }

    /**
     *指出查询的目标表
     *
     */
    public function from(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    /**
    *指出查询类型是“select”并记住哪些字段
     */
    public function select(mixed $columns = '*'): static
    {
        if (is_string($columns)) {
            $columns = [$columns];
        }

        $this->type = 'select';
        $this->columns = $columns;

        return $this;
    }

    /**
    *向查询中指定的表中插入一行数据
    *并返回受影响的行数*/
    public function insert(array $columns, array $values): int
    {
        $this->type = 'insert';
        $this->columns = $columns;
        $this->values = $values;

        $statement = $this->prepare();

        return $statement->execute($values);
    }

    public function where(string $column, mixed $comparator, mixed $value = null): static
    {
        if (is_null($value) && !is_null($comparator)) {
            $this->wheres[] = [$column, '=', $comparator];
        } else {
            $this->wheres[] = [$column, $comparator, $value];
        }

        return $this;
    }
}
