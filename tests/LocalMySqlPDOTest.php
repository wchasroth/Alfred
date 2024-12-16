<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

use CharlesRothDotNet\Alfred\LocalMySqlPDO;

require_once('nullstr.php');

//---These have to be run manually on a MySQL database.  It assumes root access, which is bad!
//   So they are not strictly speaking unit-tests, but are here to help during development.
//   Uncomment the #[Test] attribute to run.

class LocalMySqlPDOTest extends TestCase {

// #[Test]
   public function shouldGetRawSql_for1ParameterPdo(): void {
      $pdo = new LocalMySqlPDO("mivoterdm,root,");
      $this->assertGetsRawSql_withFilledInParameterValues_forMySQL($pdo);
   }

// #[Test]
   public function shouldGetRawSql_for3ParameterPdo(): void {
      $pdo = new LocalMySqlPDO("mivoterdm", "root", "");
      $this->assertGetsRawSql_withFilledInParameterValues_forMySQL($pdo);
   }

   private function assertGetsRawSql_withFilledInParameterValues_forMySQL(LocalMySqlPDO $pdo): void {
      self::assertFalse ($pdo->failed());
      self::assertTrue  ($pdo->succeeded());
      self::assertEmpty ($pdo->getError());
   
      $sql = "SELECT tid, miv_title, ballot_order FROM title WHERE tid LIKE :tid AND ballot_order = :order";
      $stm = $pdo->prepare($sql);
      $pdo->bindKeyValueArray($stm, array(":tid"=>"mi:%", ":order"=>5000));
      $stm->execute();
      $expected = "SELECT tid, miv_title, ballot_order FROM title "
                . "WHERE tid LIKE 'mi:%' AND ballot_order = 5000";
      self::assertSame ($expected, $pdo->getRawSql($stm));
   }

// #[Test]
   public function shouldDetectMySqlConnectionFailure_toNonExistentDatabase(): void {
      $pdo = new LocalMySqlPDO("noSuchDatabase", "root", "");
      self::assertTrue  ($pdo->failed());
      self::assertFalse ($pdo->succeeded());
      $expectedError = "DSN: mysql:host=localhost;dbname=noSuchDatabase;port=3306;charset=utf8, "
         .  "user=root, dbpw=, error = SQLSTATE[HY000] [1049] Unknown database 'nosuchdatabase'";
      self::assertSame ($expectedError, $pdo->getError());
   }

// #[Test]
   public function shouldDetectMySqlConnectionFailure_byNonExistentUser(): void {
      $pdo = new LocalMySqlPDO("noSuchDatabase", "root2", "");
      self::assertTrue  ($pdo->failed());
      self::assertFalse ($pdo->succeeded());
      $expectedError = "DSN: mysql:host=localhost;dbname=noSuchDatabase;port=3306;charset=utf8, "
         . "user=root2, dbpw=, error = SQLSTATE[HY000] [1045] Access denied for user 'root2'@'localhost' (using password: NO)";
      self::assertSame ($expectedError, $pdo->getError());
   }

   #[Test]  // This is only here to prevent the warning about no tests, when the above tests are commented out.
   public function shouldAlwaysSucceed(): void {
      self::assertTrue(true);
   }

}
