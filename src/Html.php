<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class Html {
    public static function redirectTo(string $url): void {
        header("Location: " . $url);
        exit(0);
    }

    // looks trivial, but useful as a registered plugin inside Smarty tpl files.
    public static function notEmpty(?string $text): bool {
        return ! empty($text);
    }

    // Trivial, but another useful plugin for handling checkboxes.
    public static function checked(int $value): string {
        return ($value == 1 ? " checked " : "");
    }
}