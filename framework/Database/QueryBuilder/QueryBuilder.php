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

    public function all(): array
    {
        $statement = $this->prepare();
        $statement->execute();

        return $statement->fetchAll(Pdo::FETCH_ASSOC);
    }

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

    protected function compileSelect(string $query): string
    {
        $query .= " SELECT {$this->columns} FROM {$this->table}";

        return $query;
    }

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


    public function first(): array
    {
        $statement = $this->take(1)->prepare();
        $statement->execute();

        return $statement->fetchAll(Pdo::FETCH_ASSOC);
    }


    public function take(int $limit, int $offset = 0): static
    {
        $this->limit = $limit;
        $this->offset = $offset;

        return $this;
    }


    public function from(string $table): static
    {
        $this->table = $table;
        return $this;
    }


    public function select(string $columns = '*'): static
    {
        $this->type = 'select';
        $this->columns = $columns;

        return $this;
    }
}
