<?php
namespace Bigtallbill\ShipBattle\V1\Ship;


class ShipClassBattleship extends ShipClass
{
    protected static $shipClass = ShipClasses::CLS_BATTLESHIP;

    public function getHardpoints()
    {
        return 85;
    }

    public function getHitpoints()
    {
        return 1000000;
    }

    public function getAscii()
    {
        $ascii = <<<EOT

                            _ _ ___ _
               ___..---''''= = =    _''''---.....___
       __..--''   | = = = =      .'(``````| \---    '''----...____
  .--''  | ==     |      _..--'''   `-----...........____||      |'''----...
 / |     |        |    .' .`````````````````````````      ''''''----------. \
 \ |     |        |    '. '......................... _____......----------' /
  '--..__| ==     |      '--..___   ......--------'''    ||   ___|...----'''
         ''--..___| =  = = =     '.(______|_/--- ___...----'''
                  ''---...._=_=_=___ ....---'''''

EOT;

        return $ascii;
    }
}
