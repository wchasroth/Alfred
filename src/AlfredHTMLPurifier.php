<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Custom version of HTMLPurifier, that has a very specific configuration and work-arounds:
 * 1. Allow target="" in anchor tags.
 * 2. Allow id="" attributes in any tag.
 * 3. Pass-thru "&nbsp;" entities unchanged (via a hack, admittedly)
 * 4. Allow referrer's in anchor tags.
 * 5. Do not automatically remove 'empty' strings/tags.
 */
class AlfredHTMLPurifier {
   private HTMLPurifier $purifier;

   function __construct() {
      $config = HTMLPurifier_Config::createDefault();
      $config->set('Attr.AllowedFrameTargets', ['_blank']);
      $config->set('HTML.TargetNoreferrer', false);
      $config->set('AutoFormat.RemoveEmpty', false);
      $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', false);
      $config->set('Attr.EnableID', true);
      if (PHP_OS_FAMILY === 'Linux')  $config->set('Cache.SerializerPath', '/tmp');  // Evil!
      $this->purifier = new HTMLPurifier($config);
   }

   public function purify(string $html): string {
      $clean = Str::replaceAll($html, "&nbsp;", "|NBSP;");
      $clean = $this->purifier->purify($clean);
      $clean = Str::replaceAll($clean, "|NBSP;", "&nbsp;");
      return $clean;
   }

}