<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use CharlesRothDotNet\Alfred\Html;

class HtmlTest extends TestCase
{
    #[Test]
    public function shouldExerciseNotEmpty() {
        self::assertTrue(Html::notEmpty("hello"));
        self::assertFalse(Html::notEmpty(""));
        self::assertFalse(Html::notEmpty(null));
    }

    #[Test]
    public function shouldExerciseChecked(): void {
        self::assertSame (" checked ", Html::checked(1));
        self::assertEmpty(Html::checked(0));
    }
}