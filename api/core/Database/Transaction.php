<?php

declare(strict_types=1);

namespace App\Core\Database;

use PDO;

final class Transaction
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function begin(): void
    {
        if (!$this->pdo->inTransaction()) {
            $this->pdo->beginTransaction();
        }
    }

    public function commit(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }

    public function rollback(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }
}
