<?php

declare(strict_types=1);

namespace App\Core\Database;

abstract class BaseRepository
{
    protected QueryBuilder $queryBuilder;

    public function __construct(?QueryBuilder $queryBuilder = null)
    {
        $this->queryBuilder = $queryBuilder ?? new QueryBuilder(Connection::getInstance());
    }

    abstract protected function table(): string;

    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        return $this->queryBuilder->select($this->table());
    }

    public function findById(int $id): ?array
    {
        return $this->queryBuilder->selectOne($this->table(), ['*'], ['id' => $id]);
    }

    /**
     * @param array<string, scalar|null> $data
     */
    public function create(array $data): int
    {
        return $this->queryBuilder->insert($this->table(), $data);
    }

    /**
     * @param array<string, scalar|null> $data
     */
    public function updateById(int $id, array $data): int
    {
        return $this->queryBuilder->update($this->table(), $data, ['id' => $id]);
    }

    public function deleteById(int $id): int
    {
        return $this->queryBuilder->delete($this->table(), ['id' => $id]);
    }
}
