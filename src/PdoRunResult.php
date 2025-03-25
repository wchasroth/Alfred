<?php

namespace CharlesRothDotNet\Alfred;

class PdoRunResult {
    private string $error;
    private string $rawSql;
    private array  $rows;
    private int    $insertId;

    public function __construct(array $rows, string $error, string $rawSql, int $insertId = -1) {
        $this->error = $error;
        $this->rows = $rows;
        $this->rawSql = $rawSql;
        $this->insertId = $insertId;
    }

    public function getError(): string {
        return $this->error;
    }

    public function failed(): bool {
        return ! empty($this->error);
    }

    public function succeeded(): bool {
        return empty($this->error);
    }

    public function getRows(): array {
        return $this->rows;
    }

    public function getRawSql(): string {
        return $this->rawSql;
    }

    public function getInsertId(): int {
       return $this->insertId;
    }

    public function getSingleValue(string $key) {
        if (sizeof($this->rows) === 0)  return "";
        return $this->rows[0][$key];
    }
}