<?php
namespace Bigtallbill\ShipBattle\V1\Weapon;


use Bigtallbill\ShipBattle\V1\Main;
use Bigtallbill\ShipBattle\V1\Ship\Ship;
use MarketMeSuite\Phranken\Util\ArrayUtils;

abstract class Weapon
{
    const TYPE_LASER   = 'type_laser';
    const TYPE_GAUSS   = 'type_gauss';
    const TYPE_MISSILE = 'type_missile';

    const TYPE_HUMAN_LASER   = 'laser';
    const TYPE_HUMAN_GAUSS   = 'gauss';
    const TYPE_HUMAN_MISSILE = 'missile';

    const STAT_KEY_MAX_DAMAGE   = 'max_dmg';
    const STAT_KEY_MIN_DAMAGE   = 'min_dmg';
    const STAT_KEY_MAX_FIRERATE = 'max_fr';
    const STAT_KEY_MIN_FIRERATE = 'min_fr';
    const STAT_KEY_MAX_COOLDOWN = 'max_cd';
    const STAT_KEY_MIN_COOLDOWN = 'min_cd';

    protected $maxDamage = 0;
    protected $minDamage = 0;
    protected $fireRate = 0;
    protected $cooldown = 0;

    protected $cooldownLevel = 0;
    protected $isInCoolDown = false;

    /**
     * @var bool Has the weapon been destroyed
     */
    protected $destroyed = false;

    protected $type = self::TYPE_LASER;

    /**
     * Fires the weapon and returns its damage
     *
     * @param Ship $target
     *
     * @return int
     */
    public function fire(Ship $target)
    {
        // do not fire if weapon is in cooldown
        if ($this->getIsInCoolDown()) {
            return 0;
        }

        $target->getSpeed();


        switch ($this->getType()) {
            case Weapon::TYPE_MISSILE:

                if ($target->getSpeed() > 40 && rand(0, 1) === 1) {
                    return 0;
                }

                if ($target->getTurnSpeed() > 20 && rand(0, 1) === 1) {
                    return 0;
                }
                break;
        }


        $damage = rand($this->minDamage, $this->maxDamage);

        $fireRateMod = (int)(Main::TURN_LENGTH_IN_GAME_TIME / $this->fireRate);

        $totalDamage = $damage * $fireRateMod;

        // apply cooldown
        $this->setCooldown($this->cooldown);

        return $totalDamage;
    }

    /**
     * Randomizes the weapons statistics based of of the baseline
     * performance values
     */
    public function randomizeStats()
    {
        $baseLine = static::getBaselineStats();

        // work out the middle of min and max damage values
        // this is so we dont have the min damage higher than
        // the max damage
        $diff   = $baseLine[self::STAT_KEY_MAX_DAMAGE] - $baseLine[self::STAT_KEY_MIN_DAMAGE];
        $diff   = (int)$diff / 2;
        $middle = $diff + $baseLine[self::STAT_KEY_MIN_DAMAGE];

        $this->maxDamage = rand(
            $baseLine[self::STAT_KEY_MAX_DAMAGE],
            $middle
        );

        $this->minDamage = rand(
            $middle,
            $baseLine[self::STAT_KEY_MIN_DAMAGE]
        );

        $this->fireRate = rand(
            $baseLine[self::STAT_KEY_MAX_FIRERATE],
            $baseLine[self::STAT_KEY_MIN_FIRERATE]
        );

        $this->cooldown = rand(
            $baseLine[self::STAT_KEY_MAX_COOLDOWN],
            $baseLine[self::STAT_KEY_MIN_COOLDOWN]
        );
    }

    /**
     * The minimums & maximums that this weapons performance can
     * vary
     * @return array
     */
    public static function getBaselineStats()
    {
        return array(
            self::STAT_KEY_MAX_DAMAGE   => 0,
            self::STAT_KEY_MIN_DAMAGE   => 0,
            self::STAT_KEY_MAX_FIRERATE => 0,
            self::STAT_KEY_MIN_FIRERATE => 0,
            self::STAT_KEY_MAX_COOLDOWN => 0,
            self::STAT_KEY_MIN_COOLDOWN => 0
        );
    }

    /**
     * @return string A random weapon type
     */
    public static function getRandomType()
    {
        $randomType = ArrayUtils::betterArrayRand(static::getTypes(), 1);
        return $randomType[0];
    }

    /**
     * @return array An array of all possible weapon types
     */
    public static function getTypes()
    {
        return array(
            self::TYPE_LASER,
            self::TYPE_GAUSS,
            self::TYPE_MISSILE
        );
    }

    public static function getTypeFromIndex($index)
    {
        $types = static::getTypes();

        return $types[$index];
    }

    public static function getHumanTypeFromIndex($index)
    {

        $map = array(
            self::TYPE_LASER   => self::TYPE_HUMAN_LASER,
            self::TYPE_GAUSS   => self::TYPE_HUMAN_GAUSS,
            self::TYPE_MISSILE => self::TYPE_HUMAN_MISSILE
        );

        $types = static::getTypes();
        $type  = $types[$index];

        return $map[$type];
    }

    public static function getHumanFromType($type)
    {

        $map = array(
            self::TYPE_LASER   => self::TYPE_HUMAN_LASER,
            self::TYPE_GAUSS   => self::TYPE_HUMAN_GAUSS,
            self::TYPE_MISSILE => self::TYPE_HUMAN_MISSILE
        );

        return $map[$type];
    }

    /**
     * @param $type
     *
     * @return string A fully-qualified class name for the
     *                provided $type
     */
    public static function resolveTypeToClass($type)
    {
        $namespace = 'Bigtallbill\\ShipBattle\\V1\\Weapon';

        $type = str_replace('type_', '', $type);

        $type = ucfirst($type);

        $className = $namespace . '\\' . $type;

        return $className;
    }

    public function decrementCooldown()
    {
        $this->setCooldown($this->getCooldown() - 1);
    }

    /**
     * @param int $cooldown
     */
    public function setCooldown($cooldown)
    {
        // only allow down to zero cooldown
        if ($cooldown < 0) {
            $cooldown = 0;
        }

        if ($cooldown == 0) {
            $this->isInCoolDown = false;
        } else {
            $this->isInCoolDown = true;
        }

        $this->cooldownLevel = $cooldown;
    }

    /**
     * @return int
     */
    public function getCooldown()
    {
        return $this->cooldownLevel;
    }

    /**
     * @param int $fireRate
     */
    public function setFireRate($fireRate)
    {
        $this->fireRate = $fireRate;
    }

    /**
     * @return int
     */
    public function getFireRate()
    {
        return $this->fireRate;
    }

    /**
     * @param int $maxDamage
     */
    public function setMaxDamage($maxDamage)
    {
        $this->maxDamage = $maxDamage;
    }

    /**
     * @return int
     */
    public function getMaxDamage()
    {
        return $this->maxDamage;
    }

    /**
     * @param int $minDamage
     */
    public function setMinDamage($minDamage)
    {
        $this->minDamage = $minDamage;
    }

    /**
     * @return int
     */
    public function getMinDamage()
    {
        return $this->minDamage;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param boolean $destroyed
     */
    public function setDestroyed($destroyed)
    {
        $this->destroyed = $destroyed;
    }

    /**
     * @return boolean
     */
    public function getDestroyed()
    {
        return $this->destroyed;
    }

    /**
     * @return boolean
     */
    public function getIsInCoolDown()
    {
        return $this->isInCoolDown;
    }
}
