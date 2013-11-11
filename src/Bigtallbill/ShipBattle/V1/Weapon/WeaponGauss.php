<?php
namespace Bigtallbill\ShipBattle\V1\Weapon;


class WeaponGauss extends Weapon
{
    protected $type = self::TYPE_GAUSS;

    public static function getBaselineStats()
    {
        return array(
            self::STAT_KEY_MAX_DAMAGE => 10,
            self::STAT_KEY_MIN_DAMAGE => 5,
            self::STAT_KEY_MAX_FIRERATE => 600,
            self::STAT_KEY_MIN_FIRERATE => 500,
            self::STAT_KEY_MAX_COOLDOWN => 1,
            self::STAT_KEY_MIN_COOLDOWN => 1
        );
    }
}
