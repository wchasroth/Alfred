<?php

namespace CharlesRothDotNet\Alfred;

class PdoRunResult {
    private string $error;
    private string $rawSql;
    private array  $rows;

    public function __construct(array $rows, string $error, string $rawSql) {
        $this->error = $error;
        $this->rows = $rows;
        $this->rawSql = $rawSql;
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
}