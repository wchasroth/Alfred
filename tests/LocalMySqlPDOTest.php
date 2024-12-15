<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

use CharlesRothDotNet\Alfred\LocalMySqlPDO;

require_once('nullstr.php');

//---These have to be run manually on a MySQL database.  It assumes root access, which is bad!
//   So they are not strictly speaking unit-tests, but are here to help during development.

class LocalMySqlPDOTest extends TestCase {

// #[Test]  // Uncomment to run manually
   public function shouldGetRawSql_withFilledInParameterValues_forMySQL(): void {
      $pdo = new LocalMySqlPDO("mivoterdm", "root", "");
      self::assertFalse ($pdo->failed());
      self::assertEmpty ($pdo->getError());

      $sql = "SELECT tid, miv_title, ballot_order FROM title WHERE tid LIKE :tid AND ballot_order = :order";
      $stm = $pdo->prepare($sql);
      $pdo->bindKeyValueArray($stm, array(":tid"=>"mi:%", ":order"=>5000));
      $stm->execute();
      $expected = "SELECT tid, miv_title, ballot_order FROM title "
                . "WHERE tid LIKE 'mi:%' AND ballot_order = 5000";
      self::assertSame ($expected, $pdo->getRawSql($stm));
   }

// #[Test]  // Uncomment to run manually
   public function shouldDetectMySqlConnectionFailure_toNonExistentDatabase(): void {
      $pdo = new LocalMySqlPDO("noSuchDatabase", "root", "");
      self::assertTrue ($pdo->failed());
      $expectedError = "DSN: mysql:host=localhost;dbname=noSuchDatabase;port=3306;charset=utf8, "
         .  "user=root, dbpw=, error = SQLSTATE[HY000] [1049] Unknown database 'nosuchdatabase'";
      self::assertSame ($expectedError, $pdo->getError());
   }

   #[Test]  // This is only here to prevent the "warning" that the above tests are normally commented out.
   public function shouldAlwaysSucceed(): void {
      self::assertTrue(true);
   }

}
