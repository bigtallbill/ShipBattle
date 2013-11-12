<?php
namespace Bigtallbill\ShipBattle\V1\Ship;


use MarketMeSuite\Phranken\Util\ArrayUtils;

class ShipNameGenerator
{
    protected static $firstSegment = array(
        'HMS',
        'USS',
        'UEE',
        'EDF'
    );

    protected static $secondSegment = array(
        'Normandy',
        'Forrester',
        'Colchester',
        'Drake',
        'Merlin',
        'Phoenix',
        'Francis',
        'Hector'
    );

    /**
     * @return string A random ship name
     */
    public static function getName()
    {
        $first  = ArrayUtils::betterArrayRand(static::$firstSegment, 1);
        $second = ArrayUtils::betterArrayRand(static::$secondSegment, 1);

        $name = $first[0] . ' ' . $second[0];

        return $name;
    }
}
