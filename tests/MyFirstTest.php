<?php
declare(strict_types=1);

namespace CosmoQuestX\Tests;

use CosmoQuestX\Tests\email;
use PHPUnit\Framework\TestCase;
class MyFirstTest extends TestCase
{
    /** @test */
    public function navigation_compiles()
    {
        // Assert

        $foo = new email([]);
//        $foo->sendmail("a", "b");
        $this->assertEquals(3, 3);
    }


}
