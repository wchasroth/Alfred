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
   public function shouldMakeSelectFragment_givenFields() {
      $keyValues = ["abc" => 123, "def" => "xyz"];
      $fields = new SqlFields($keyValues);
      self::assertEquals (" abc = :abc AND def = :def ", $fields->makeSelectFragment());
      self::assertEquals ($keyValues, $fields->getKeyValuePairs());
   }

   #[Test]
   public function shouldGetUpdateFragment_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals (" abc = 123, def = 'xyz' ", $fields->getUpdateFragment());
   }

   #[Test]
   public function shouldMakeUpdateFragment_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals (" abc = :abc, def = :def ", $fields->makeUpdateFragment());
   }

   #[Test]
   public function shouldGetInsertFragment_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals (" (abc, def) VALUES (123, 'xyz') ", $fields->getInsertFragment());
   }

   #[Test]
   public function shouldMakeInsertFragment_givenFields() {
      $fields = new SqlFields(["abc" => 123, "def" => "xyz"]);
      self::assertEquals (" (abc, def) VALUES (:abc, :def) ", $fields->makeInsertFragment());
   }

}