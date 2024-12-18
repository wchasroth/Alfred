<?php

namespace CharlesRothDotNet\Alfred;
class Html {
    public static function redirect(string $url): void {
        header("Location: " . $url);
        exit(0);
    }
}