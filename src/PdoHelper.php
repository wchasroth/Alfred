<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

use CharlesRothDotNet\Alfred\EnvFile;
use CharlesRothDotNet\Alfred\AlfredPDO;

class PdoHelper {

   public static function makePdo(EnvFile $env): AlfredPDO {
      $pdo = new AlfredPDO($env->getValues('dbname,dbuser,dbpw'));
      if ($pdo->failed()) {
         fwrite(STDERR, "Error: database access failed: " . $pdo->getError() . "\n");
         exit(1);
      }
      return $pdo;
   }
}
