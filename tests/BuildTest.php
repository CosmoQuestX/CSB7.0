<?php
declare(strict_types=1);

namespace CosmoQuestX\Tests;

use CosmoQuestX\Authorization;
use CosmoQuestX\Applesauce;
use PHPUnit\Framework\TestCase;

class BuildTest extends TestCase
{
    /** @test */
    public function testingTest()
    {
        $applesauce = new Applesauce();
        $this->assertEquals(3, $applesauce->TheWordThree());
    }

    public function testLogin()
    {
        $db = new class {
            public function runQueryWhere()
            {
                return [["name" => "name"]];
            }
        };
        $toCheck = \CosmoQuestX\Authorization::chk_UserId($db, "id", "name");
        $this->assertEquals(TRUE, $toCheck);
    }


}
