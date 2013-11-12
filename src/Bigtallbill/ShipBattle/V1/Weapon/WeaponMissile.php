<?php
namespace Bigtallbill\ShipBattle\V1\Weapon;


class WeaponMissile extends Weapon
{
    protected $type = self::TYPE_MISSILE;

    public static function getBaselineStats()
    {
        return array(
            self::STAT_KEY_MAX_DAMAGE   => 125,
            self::STAT_KEY_MIN_DAMAGE   => 50,
            self::STAT_KEY_MAX_FIRERATE => 1000,
            self::STAT_KEY_MIN_FIRERATE => 750,
            self::STAT_KEY_MAX_COOLDOWN => 2,
            self::STAT_KEY_MIN_COOLDOWN => 1
        );
    }
}
