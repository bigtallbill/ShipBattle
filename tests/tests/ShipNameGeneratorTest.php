<?php
namespace Bigtallbill\ShipBattle\V1;

class ShipNameGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {

    }

    public function testGetName()
    {
        $name = ShipNameGenerator::getName();

        $this->assertInternalType('string', $name);
    }
}
