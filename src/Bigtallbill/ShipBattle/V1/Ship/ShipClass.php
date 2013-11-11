<?php
namespace Bigtallbill\ShipBattle\V1\Ship;


use Bigtallbill\ShipBattle\V1\Weapon\FactoryWeapon;
use Bigtallbill\ShipBattle\V1\Weapon\Weapon;

class ShipClass
{
    protected static $shipClass = ShipClasses::CLS_FRIGATE;

    /**
     * @return string The class identifier as defined in ShipClasses
     * @see ShipClasses
     */
    public function getClassId()
    {
        return static::$shipClass;
    }

    public function getHardpoints()
    {
        return 1;
    }

    /**
     * @return int How many hitpoints this class has
     */
    public function getHitpoints()
    {
        return 10000;
    }

    /**
     * @return int The speed of the ship in MPS
     */
    public function getSpeed()
    {
        return 80;
    }

    /**
     * @return int The speed of the ship can turn in MPS
     */
    public function getTurnSpeed()
    {
        return 20;
    }

    public function getRandomLoadout(FactoryWeapon $factoryWeapon, $hardpoints = 1)
    {
        $weapons = array();

        for ($i = 0; $i < $hardpoints; $i++) {

            $randomType = Weapon::getRandomType();

            $weapon = $factoryWeapon->buildWeapon($randomType);
            $weapon->randomizeStats();

            $weapons[] = $weapon;
        }

        return $weapons;
    }

    public function getAscii()
    {
        return '';
    }
}
