<?php
   declare(strict_types=1);

   use PHPUnit\Framework\TestCase;
   use PHPUnit\Framework\Attributes\Test;
   use PHPUnit\Framework\Attributes\IgnoreDeprecations;

   use CharlesRothDotNet\Alfred\PdoHelper;

   require_once('nullstr.php');

   class PdoHelperTest extends TestCase {

      #[Test]
      public function shouldBindKeyValues_toPDOStatement(): void {
         $options = [PDO::ATTR_EMULATE_PREPARES => true];
         $pdo = new PDO("sqlite::memory:", "", "", $options);

         $sql = "CREATE TABLE abc (id int, a varchar(20), b int)";
         $stm = $pdo->prepare($sql);
         $stm->execute();

         $sql = "INSERT INTO abc (id, a, b) VALUES (100, 'hello', 17)";
         $stm = $pdo->prepare($sql);
         $stm->execute();

         $sql = "SELECT id, a, b FROM abc WHERE a=:a AND b=:b";
         $stm = $pdo->prepare($sql);
         PdoHelper::bindKeyValueArray($stm, array(":a" => "hello", ":b" => 17));
         $stm->execute();

         $result = $stm->fetch(PDO::FETCH_ASSOC);
         self::assertSame (100,     $result['id']);
         self::assertSame ("hello", $result['a']);
         self::assertSame (17,      $result['b']);
      }

      //---This has to be run manually on a MySQL database.  It assumes root access, which is bad!
      //   So it is not strictly speaking a unit-test, but is here to help in case of any future issues.
      //#[Test]
      public function shouldGetRawSql_withFilledInParameterValues_forMySQL(): void {
         $options = [PDO::ATTR_EMULATE_PREPARES => true];
         $pdo = new PDO("mysql:host=localhost;dbname=mivoterdm;port=3306;charset=utf8", "root", "", $options);

         $sql = "SELECT tid, miv_title, ballot_order FROM title WHERE tid LIKE :tid AND ballot_order = :order";
         $stm = $pdo->prepare($sql);
         $stm->bindValue(":tid",   "mi:%");
         $stm->bindValue(":order", 5000, PDO::PARAM_INT);
         $stm->execute();
         $expected = "SELECT tid, miv_title, ballot_order FROM title "
                   . "WHERE tid LIKE 'mi:%' AND ballot_order = 5000";
         self::assertSame ($expected, PdoHelper::getRawSql($stm));
      }

   }
