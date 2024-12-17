<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\DumbFileLogger;
use CharlesRothDotNet\Alfred\Str;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DumbFileLoggerTest extends TestCase {

    #[Test]
    public function shouldCatchAttemptToLog_toNonExistingDirectoryAndFile(): void {
        $logger = new DumbFileLogger("/noSuchDirectory/NorFile");
        $error = "";
        try                  { $logger->log("test"); }
        catch (Exception $e) { $error = $e->getMessage(); }
        self::assertTrue(Str::contains($error, "Cannot write to /noSuchDirectory/NorFile"));
    }

    #[Test]
    public function shouldLogToFile(): void {
        $filename = getcwd() . "/unittest.tmp";
        unlink($filename);

        $logger = new DumbFileLogger($filename);
        $logger->log("test");

        $contents = file_get_contents($filename);
        unlink($filename);
        self::assertSame ("test\n", $contents);
    }

}