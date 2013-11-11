<?php
namespace Bigtallbill\ShipBattle\V1\Weapon;


class WeaponLaser extends Weapon
{
    public static function getBaselineStats()
    {
        return array(
            self::STAT_KEY_MAX_DAMAGE => 200,
            self::STAT_KEY_MIN_DAMAGE => 150,
            self::STAT_KEY_MAX_FIRERATE => 3000,
            self::STAT_KEY_MIN_FIRERATE => 2000,
            self::STAT_KEY_MAX_COOLDOWN => 5,
            self::STAT_KEY_MIN_COOLDOWN => 4
        );
    }
}
