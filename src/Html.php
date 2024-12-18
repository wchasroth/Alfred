<?php

namespace CharlesRothDotNet\Alfred;
class Html {
    public static function redirectTo(string $url): void {
        header("Location: " . $url);
        exit(0);
    }
}