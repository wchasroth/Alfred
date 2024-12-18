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

    public static function removeHtmlTags ($text): string {
        $result = [];
        $state  = 0;
        foreach (str_split($text) as $char) {
            if ($state == 0) {
                if ($char == '<')  $state = 1;
                else $result[] = $char;
            }
            else {
                if ($char == '>')  $state = 0;
            }
        }
        return implode('', $result);
    }
}