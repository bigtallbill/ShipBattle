<?php
namespace Bigtallbill\ShipBattle\V1\Ship;


class ShipClasses
{
    const CLS_FRIGATE    = 'cls_frigate';
    const CLS_DESTROYER  = 'cls_destroyer';
    const CLS_BATTLESHIP = 'cls_battleship';

    public static function getHumanNames()
    {
        return array(
            self::CLS_FRIGATE    => 'frigate',
            self::CLS_DESTROYER  => 'destroyer',
            self::CLS_BATTLESHIP => 'battleship',
        );
    }

    public static function getNumericOptions()
    {
        return array(
            0 => self::CLS_FRIGATE,
            1 => self::CLS_DESTROYER,
            2 => self::CLS_BATTLESHIP,
        );
    }

    public static function resolveNumericToClass($index)
    {
        $opts = static::getNumericOptions();
        return $opts[$index];
    }

    public static function resolveNumericToHuman($index)
    {
        $shipClass  = static::resolveNumericToClass($index);
        $humanNames = static::getHumanNames();
        return $humanNames[$shipClass];
    }

    public static function getNumericHumanOptions()
    {
        $opts = array();
        foreach (static::getNumericOptions() as $key => $value) {
            $opts[$key] = $key . ':' . $value;
        }

        return $opts;
    }

    public static function getFullyQualifiedClassName($shipClass)
    {
        $namespace = 'Bigtallbill\\ShipBattle\\V1';

        $baseName = 'ShipClass';

        // get the detail name and capitalise it
        $name = str_replace('cls_', '', $shipClass);
        $name = ucfirst($name);

        $fullName = $namespace . '\\' . $baseName . $name;

        return $fullName;
    }
}
