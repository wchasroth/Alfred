<?php

namespace CharlesRothDotNet\Alfred;

class GoogleUserProfile {
    private string $email;
    private string $error;

    public function __construct(string $email, string $error) {
        $this->email = $email;
        $this->error = $error;
    }

    public function getEmail(): string {
       return $this->email;
    }

    public function succeeded(): bool {
        return ! empty($this->email);
    }

    public function failed(): bool {
        return empty($this->email);
    }

    public function getError(): string {
        return $this->error;
    }

}