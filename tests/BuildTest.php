<?php
declare(strict_types=1);

namespace CosmoQuestX\Tests;

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


}
