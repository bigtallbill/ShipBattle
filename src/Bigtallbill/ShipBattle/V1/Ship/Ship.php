<?php
namespace Bigtallbill\ShipBattle\V1\Ship;


use MarketMeSuite\Phranken\Database\Interfaces\IDbObject;
use MarketMeSuite\Phranken\Database\Object\DbObject;
use Bigtallbill\ShipBattle\V1\Ship\ShipClass;
use Bigtallbill\ShipBattle\V1\Weapon\Weapon;

class Ship extends DbObject implements IDbObject
{
    private $id;

    /**
     * @var ShipClass
     */
    private $class;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Weapon[] An array of Weapon objects
     */
    private $weapons = array();

    private $hitPoints = 0;
    private $speed = 0;
    private $turnSpeed = 0;

    /**
     * @var array
     */
    private $volleyStats = array();

    public function getMap()
    {
        return array(
            '_id'   => 'id',
            'class' => 'class',
            'name'  => 'name'
        );
    }

    /**
     * @param Weapon $weapon
     */
    public function addWeapon(Weapon $weapon)
    {
        $this->weapons[] = $weapon;
    }

    /**
     * @param Weapon[] $weapons
     */
    public function setWeapons(array $weapons)
    {
        $this->weapons = $weapons;
    }

    public function decrementCooldownOfAllWeapons()
    {
        $weapons = $this->weapons;

        foreach ($weapons as $weapon) {
            $weapon->decrementCooldown();
        }
    }

    /**
     * Fires all the weapons of the given type and returns
     * all of the combined damage
     * @param $type
     * @param Ship $target
     *
     * @return int
     */
    public function fireWeaponsOfType($type, Ship $target)
    {
        $this->volleyStats = array($type => 0);

        $weapons = $this->getWeaponsOfType($type);

        $combinedDamage = 0;

        foreach ($weapons as $weapon) {

            $damage = $weapon->fire($target);

            if ($damage === 0) {
                $this->volleyStats[$type]++;
            }

            $combinedDamage += $damage;
        }

        return $combinedDamage;
    }

    /**
     * @param $type
     *
     * @return Weapon[]
     */
    public function getWeaponsOfType($type)
    {
        $weapons = array();
        foreach ($this->weapons as $weapon) {
            if ($type === $weapon->getType()) {
                $weapons[] = $weapon;
            }
        }

        return $weapons;
    }

    /**
     * @param string $type
     * @param bool   $inCooldown
     *
     * @return int The number of $type in current loadout
     */
    public function countWeaponType($type, $inCooldown = false)
    {
        $count = 0;
        foreach ($this->weapons as $weapon) {
            if ($type === $weapon->getType()) {


                // exclude weapons which are not in cooldown
                // if $inCooldown is true
                if ($inCooldown && $weapon->getIsInCoolDown()) {
                    $count++;
                } elseif (!$inCooldown) {
                    $count++;
                }


            }
        }

        return $count;
    }

    /**
     * @param int $hitpoints The number of points to deduct
     */
    public function takeDamage($hitpoints = 0)
    {
        $this->hitPoints -= $hitpoints;
    }

    /**
     * @return bool true if the ship is destroyed
     */
    public function isDestroyed()
    {
        return $this->hitPoints <= 0;
    }

    /**
     * @param ShipClass $class
     */
    public function setClass(ShipClass $class)
    {
        // copy some class statistics that will change
        // over the battle
        $this->hitPoints = $class->getHitpoints();
        $this->speed = $class->getSpeed();
        $this->turnSpeed = $class->getTurnSpeed();

        $this->class = $class;
    }

    /**
     * @return ShipClass
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getHitPoints()
    {
        return $this->hitPoints;
    }

    /**
     * @return float
     */
    public function getHitPointsPercent()
    {
        $hp = $this->getHitPoints();
        $hpTotal = $this->getClass()->getHitpoints();

        $percentage = ($hp / $hpTotal) * 100;

        return round($percentage, 2);
    }

    /**
     * @return int
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return int
     */
    public function getTurnSpeed()
    {
        return $this->turnSpeed;
    }

    /**
     * @return array
     */
    public function getVolleyStats()
    {
        return $this->volleyStats;
    }
}
