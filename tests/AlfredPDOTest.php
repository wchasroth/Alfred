<?php
declare(strict_types=1);

use CharlesRothDotNet\Alfred\Str;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

use CharlesRothDotNet\Alfred\AlfredPDO;
use CharlesRothDotNet\Alfred\PdoRunResult;

require_once('nullstr.php');

//---These have to be run manually on a MySQL database.  It assumes root access, which is bad!
//   So they are not strictly speaking unit-tests, but are here to help during development.

class AlfredPDOTest extends TestCase {
    private bool $skipTests = true;   // Set to false when manually testing against a real MySQL database.

   #[Test]
   public function shouldDropAndCreateTestTable_usingPdoRun() {
       if ($this->skipTests)  { self::assertNoOp();  return; }   // So that PHPUnit doesn't complain, sigh!

       $pdo = new AlfredPDO("mivoterdm", "root", "");
       $result = $pdo->run("DROP TABLE UnitTest");
       self::assertErrorContains($result, "SQLSTATE[42S02]");

       $result = $pdo->run("DROP TABLE IF EXISTS UnitTest");
       self::assertEmpty($result->getError());

       $result = $pdo->run("CREATE TABLE UnitTest (id nosuchtype, name varchar(20))");
       self::assertErrorContains($result, "SQLSTATE[42000]");
       self::assertTrue($result->failed());

       $result = $pdo->run("CREATE TABLE UnitTest (id int, name varchar(20))");
       self::assertEmpty($result->getError());
       self::assertTrue ($result->succeeded());

       $result = $pdo->run("DROP TABLE UnitTest");
       self::assertEmpty($result->getError());
   }

   private static function assertErrorContains(PdoRunResult $result, string $errorText): void {
       self::assertTrue(Str::contains($result->getError(), $errorText));
   }

   private static function assertNoOp(): void {
       self::assertTrue(true);
   }

   #[Test]
   public function shouldInsertAndSelectFromTable_usingPdoRun() {
       if ($this->skipTests)  { self::assertNoOp();  return; }

       $pdo = new AlfredPDO("mivoterdm", "root", "");
       $pdo->run("DROP TABLE IF EXISTS UnitTest");
       $pdo->run("CREATE TABLE UnitTest (id int, name varchar(20))");

       $result = $pdo->run("INSERT INTO UnitTest (id, name) VALUES (:id, :name)", [":id" => 1, ":name" => "Test"], true);
       self::assertTrue($result->succeeded());
       self::assertSame("INSERT INTO UnitTest (id, name) VALUES (1, 'Test')", $result->getRawSql());

       $result = $pdo->run("SELECT * FROM UnitTest");
       self::assertTrue($result->succeeded());
       self::assertCount(1, $result->getRows());
       self::assertEmpty($result->getRawSql());
       $row = $result->getRows()[0];
       self::assertSame(1,      $row['id']);
       self::assertSame("Test", $row['name']);
       self::assertSame("Test", $result->getSingleValue('name'));

       $pdo->run("DROP TABLE UnitTest");
   }
    
   #[Test]
   public function shouldGetRawSql_for1ParameterPdo(): void {
      if ($this->skipTests)  { self::assertNoOp();  return; }
      $pdo = new AlfredPDO("mivoterdm,root,");
      $this->assertGetsRawSql_withFilledInParameterValues_forMySQL($pdo);
   }

   #[Test]
   public function shouldGetRawSql_for3ParameterPdo(): void {
      if ($this->skipTests)  { self::assertNoOp();  return; }
      $pdo = new AlfredPDO("mivoterdm", "root", "");
      $this->assertGetsRawSql_withFilledInParameterValues_forMySQL($pdo);
   }

   private function assertGetsRawSql_withFilledInParameterValues_forMySQL(AlfredPDO $pdo): void {
      self::assertFalse ($pdo->failed());
      self::assertTrue  ($pdo->succeeded());
      self::assertEmpty ($pdo->getError());
   
      $sql = "SELECT tid, miv_title, ballot_order FROM title WHERE tid LIKE :tid AND ballot_order = :order";
      $stm = $pdo->prepare($sql);
      $pdo->bindKeyValueArray($stm, [":tid"=>"mi:%", ":order"=>5000]);
      $stm->execute();
      $expected = "SELECT tid, miv_title, ballot_order FROM title "
                . "WHERE tid LIKE 'mi:%' AND ballot_order = 5000";
      self::assertSame ($expected, $pdo->getRawSql($stm));
   }

   #[Test]
   public function shouldDetectMySqlConnectionFailure_toNonExistentDatabase(): void {
      if ($this->skipTests)  { self::assertNoOp();  return; }
      $pdo = new AlfredPDO("noSuchDatabase", "root", "");
      self::assertTrue  ($pdo->failed());
      self::assertFalse ($pdo->succeeded());
      $expectedError = "DSN: mysql:host=localhost;dbname=noSuchDatabase;port=3306;charset=utf8, "
         .  "user=root, dbpw=, error = SQLSTATE[HY000] [1049] Unknown database 'nosuchdatabase'";
      self::assertSame ($expectedError, $pdo->getError());
   }

   #[Test]
   public function shouldDetectMySqlConnectionFailure_byNonExistentUser(): void {
      if ($this->skipTests)  { self::assertNoOp();  return; }
      $pdo = new AlfredPDO("noSuchDatabase", "root2", "");
      self::assertTrue  ($pdo->failed());
      self::assertFalse ($pdo->succeeded());
      $expectedError = "DSN: mysql:host=localhost;dbname=noSuchDatabase;port=3306;charset=utf8, "
         . "user=root2, dbpw=, error = SQLSTATE[HY000] [1045] Access denied for user 'root2'@'localhost' (using password: NO)";
      self::assertSame ($expectedError, $pdo->getError());
   }
}
