<?php

declare(strict_types=1);

namespace App\Core\Database;

use InvalidArgumentException;
use PDO;

final class QueryBuilder
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @param list<string> $columns
     * @param array<string, scalar|null> $where
     * @param list<string> $orderBy
     * @return list<array<string, mixed>>
     */
    public function select(
        string $table,
        array $columns = ['*'],
        array $where = [],
        array $orderBy = [],
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $this->assertIdentifier($table);

        $selectColumns = $columns === ['*']
            ? '*'
            : implode(', ', array_map(fn (string $column): string => $this->sanitizeColumn($column), $columns));

        $sql = sprintf('SELECT %s FROM %s', $selectColumns, $table);
        $params = [];
        $sql .= $this->buildWhereClause($where, $params);

        if ($orderBy !== []) {
            $sql .= ' ORDER BY ' . implode(', ', array_map(
                fn (string $column): string => $this->sanitizeColumn($column),
                $orderBy
            ));
        }

        if ($limit !== null) {
            $sql .= ' LIMIT :__limit';
            $params['__limit'] = $limit;
        }

        if ($offset !== null) {
            if ($limit === null) {
                throw new InvalidArgumentException('OFFSET requiere LIMIT para mantener SQL consistente.');
            }
            $sql .= ' OFFSET :__offset';
            $params['__offset'] = $offset;
        }

        $statement = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $statement->bindValue(':' . $key, $value, PDO::PARAM_INT);
                continue;
            }
            $statement->bindValue(':' . $key, $value);
        }
        $statement->execute();

        /** @var list<array<string, mixed>> $rows */
        $rows = $statement->fetchAll();
        return $rows;
    }

    /**
     * @param list<string> $columns
     * @param array<string, scalar|null> $where
     */
    public function selectOne(string $table, array $columns = ['*'], array $where = []): ?array
    {
        $rows = $this->select($table, $columns, $where, [], 1);
        return $rows[0] ?? null;
    }

    /**
     * @param array<string, scalar|null> $data
     */
    public function insert(string $table, array $data): int
    {
        $this->assertIdentifier($table);
        if ($data === []) {
            throw new InvalidArgumentException('INSERT requiere al menos una columna.');
        }

        $columns = array_keys($data);
        foreach ($columns as $column) {
            $this->assertIdentifier($column);
        }

        $placeholders = array_map(fn (string $column): string => ':' . $column, $columns);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, scalar|null> $data
     * @param array<string, scalar|null> $where
     */
    public function update(string $table, array $data, array $where): int
    {
        $this->assertIdentifier($table);
        if ($data === []) {
            throw new InvalidArgumentException('UPDATE requiere datos a modificar.');
        }
        if ($where === []) {
            throw new InvalidArgumentException('UPDATE requiere condiciones WHERE.');
        }

        $setParts = [];
        $params = [];

        foreach ($data as $column => $value) {
            $this->assertIdentifier($column);
            $paramName = 'set_' . $column;
            $setParts[] = sprintf('%s = :%s', $column, $paramName);
            $params[$paramName] = $value;
        }

        $sql = sprintf('UPDATE %s SET %s', $table, implode(', ', $setParts));
        $sql .= $this->buildWhereClause($where, $params);

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->rowCount();
    }

    /**
     * @param array<string, scalar|null> $where
     */
    public function delete(string $table, array $where): int
    {
        $this->assertIdentifier($table);
        if ($where === []) {
            throw new InvalidArgumentException('DELETE requiere condiciones WHERE.');
        }

        $params = [];
        $sql = sprintf('DELETE FROM %s', $table) . $this->buildWhereClause($where, $params);
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->rowCount();
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>|null
     */
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @param array<string, scalar|null> $where
     * @param array<string, scalar|null|int> $params
     */
    private function buildWhereClause(array $where, array &$params): string
    {
        if ($where === []) {
            return '';
        }

        $parts = [];
        $index = 0;
        foreach ($where as $column => $value) {
            $this->assertIdentifier($column);
            $paramName = sprintf('w_%s_%d', $column, $index);
            if ($value === null) {
                $parts[] = sprintf('%s IS NULL', $column);
            } else {
                $parts[] = sprintf('%s = :%s', $column, $paramName);
                $params[$paramName] = $value;
            }
            $index++;
        }

        return ' WHERE ' . implode(' AND ', $parts);
    }

    private function sanitizeColumn(string $column): string
    {
        if ($column === '*') {
            return $column;
        }
        $this->assertIdentifier($column);
        return $column;
    }

    private function assertIdentifier(string $name): void
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name)) {
            throw new InvalidArgumentException(sprintf('Identificador SQL inválido: %s', $name));
        }
    }
}
