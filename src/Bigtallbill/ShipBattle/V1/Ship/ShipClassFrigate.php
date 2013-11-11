<?php
namespace Bigtallbill\ShipBattle\V1\Ship;


class ShipClassFrigate extends ShipClass
{
    protected static $shipClass = ShipClasses::CLS_FRIGATE;

    public function getHardpoints()
    {
        return 8;
    }

    public function getHitpoints()
    {
        return 10000;
    }

    public function getAscii()
    {
        return <<<EOT
                                                         _._        *
                                       _______..........-`-'-..__  /
                                 ...###/   \        \         ____\/
                           ...########/     \  ___...\--     / _   \
                 __..---#############/_..---'''     ========/ //  __\___
         __..--''  /     /  / --..__  ```-------________________//      =
   __--''       /      /   /________=        \                 //_______=
  `-.._____  /       /    /             ___   \               ______/__
           `````-----------------------////----\----------'''' ______//  LS
                                               /_____.....-----

EOT;
    }
}
