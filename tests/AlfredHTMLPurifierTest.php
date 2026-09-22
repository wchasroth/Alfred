<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\AlfredHTMLPurifier;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AlfredHTMLPurifierTest extends TestCase {

   #[Test]
   public function shouldKeepTargetBlankAttribute() {
      $purifier = new AlfredHTMLPurifier();
      self::assertEquals ("<a href=\"#\" target=\"_blank\" rel=\"noopener\">Link</a>",
         $purifier->purify("<a href='#' target='_blank'>Link</a>"));
   }

}