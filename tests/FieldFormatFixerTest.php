<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use CharlesRothDotNet\Alfred\FieldFormatFixer;

class FieldFormatFixerTest extends TestCase {
   #[Test]
   public function shouldFixMichiganAddresses() {
      self::assertEquals ("40 N. Main St, Mount Clemens, MI 48043", FieldFormatFixer::fixMI("40 N. Main St, Mount Clemens, MI 48043"));
      self::assertEquals ("201 McMorran Blvd.Port Huron MI 48060", FieldFormatFixer::fixMI("201 McMorran Blvd.Port Huron MI 48060"));
      self::assertEquals ("925 W. Ottawa St., P.O. Box 30022 Lansing, MI 48909-7522", FieldFormatFixer::fixMI("925 W. Ottawa St., P.O. Box 30022 Lansing, Michigan 48909-7522"));
      self::assertEquals ("1045 Independence Boulevard, Suite 200, Charlotte MI 48813", FieldFormatFixer::fixMI("1045 Independence Boulevard, Suite 200, Charlotte MI, 48813"));
      self::assertEquals ("200 N. Moore St. Bessemer, MI 49911", FieldFormatFixer::fixMI("200 N. Moore St. Bessemer, MI. 49911"));
      self::assertEquals ("566 CHOB, Washington, DC 20515", FieldFormatFixer::fixMI("566 CHOB, Washington, DC 20515"));
      self::assertEquals ("111 main street", FieldFormatFixer::fixMI("111 main street"));
      self::assertEquals ("125 East Second Street Monroe, MI 48161-2197", FieldFormatFixer::fixMI("125 East Second Street Monroe,Michigan 48161-2197"));
      self::assertEquals ("234 West Baraga AvenueMarquette, MI 49855", FieldFormatFixer::fixMI("234 West Baraga AvenueMarquette, MI&nbsp; &nbsp;49855"));
      self::assertEquals ("4109 LaPlaisance Rd.LaSalle, MI", FieldFormatFixer::fixMI("4109 LaPlaisance Rd.LaSalle, Michigan,"));
      self::assertEquals ("707 North Bridge Street, Linden, MI 48451", FieldFormatFixer::fixMI("707 North Bridge Street, Linden, Michigan, 48451"));
      self::assertEquals ("4357 Buckeye Street P.O. Box 375 Luna Pier, MI 48157", FieldFormatFixer::fixMI("4357 Buckeye Street P.O. Box 375 Luna Pier, Mi. 48157"));
      self::assertEquals ("6900 Rives Junction Road Jackson MI 49201", FieldFormatFixer::fixMI("6900 Rives Junction Road JacksonMI49201"));
      self::assertEquals ("3255 E. Pontaluna Rd, Fruitport, MI 49415", FieldFormatFixer::fixMI("3255 E. Pontaluna Rd, Fruitport, 49415"));
      self::assertEquals ("31301 Evergreen Road Beverly Hills MI 48025", FieldFormatFixer::fixMI("31301 Evergreen Road Beverly Hills Michigan 48025"));
      self::assertEquals ("495 E. Huron Blvd. Marysville, MI 48040", FieldFormatFixer::fixMI("495 E. Huron Blvd. Marysville,  MI- 48040"));
      self::assertEquals ("1333 RadcliffGarden City, MI 48135", FieldFormatFixer::fixMI("1333 RadcliffGarden City,MI 48135"));
   }

   #[Test]
   public function shouldFixPhoneFormats() {
      self::assertEquals ("",                          FieldFormatFixer::fixPhone(""));
      self::assertEquals ("",                          FieldFormatFixer::fixPhone(" "));
      self::assertEquals ("313-256-9833",              FieldFormatFixer::fixPhone("313-256-9833"));
      self::assertEquals ("313-256-9833",              FieldFormatFixer::fixPhone(" 313-256-9833 "));
      self::assertEquals ("313-256-9833",              FieldFormatFixer::fixPhone("'313-256-9833'"));
      self::assertEquals ("810-257-3257 810-235-3204", FieldFormatFixer::fixPhone("810-257-3257,810-235-3204"));
      self::assertEquals ("517-373-0154 x111",         FieldFormatFixer::fixPhone("517-373-0154 x111"));
      self::assertEquals ("517-373-0154 x111",         FieldFormatFixer::fixPhone("517-373-0154 x 111"));
      self::assertEquals ("123-456",                   FieldFormatFixer::fixPhone("123-456"));
      self::assertEquals ("269-695-6442",              FieldFormatFixer::fixPhone("(269) 695-6442"));
      self::assertEquals ("517-331-2113",              FieldFormatFixer::fixPhone("(517)331-2113"));
      self::assertEquals ("517-648-3528",              FieldFormatFixer::fixPhone("(517)-648-3528"));
      self::assertEquals ("734-461-6117 x201",         FieldFormatFixer::fixPhone("734-461-6117, ext 201"));
   }

}
