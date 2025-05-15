<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use CharlesRothDotNet\Alfred\SqlFields;

class SqlFieldsTest extends TestCase {

   #[Test]
   public function shouldGetSelectFragment_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals (" abc = 123 AND def = 'xyz' ", $fields->getSelectFragment());
   }

   #[Test]
   public function shouldMakeSelect_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals ("SELECT * FROM table WHERE   abc = :abc AND def = :def", $fields->makePreparedStatement("SELECT * FROM table WHERE "));
   }

   #[Test]
   public function shouldGetUpdateFragment_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals (" abc = 123, def = 'xyz' ", $fields->getUpdateFragment());
   }

   #[Test]
   public function shouldMakeUpdate_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals ("UPDATE table SET  abc = :abc, def = :def  WHERE id=17", $fields->makePreparedStatement("UPDATE table SET", "WHERE id=17"));
   }

   #[Test]
   public function shouldGetInsertFragment_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals (" (abc, def) VALUES (123, 'xyz') ", $fields->getInsertFragment());
   }

   #[Test]
   public function shouldMakeInsert_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals ("INSERT INTO table (abc, def) VALUES (:abc, :def)", $fields->makePreparedStatement("INSERT INTO table"));
   }

}