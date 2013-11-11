<?php
namespace Bigtallbill\ShipBattle\V1\Ship;


class ShipClassDestroyer extends ShipClass
{
    protected static $shipClass = ShipClasses::CLS_DESTROYER;

    public function getHardpoints()
    {
        return 24;
    }

    public function getHitpoints()
    {
        return 100000;
    }

    public function getAscii()
    {
        $ascii = <<<EOT


         __..,,-----l"|-.
     __/"__  |----""  |  i--voo..,,__
  .-'=|:|/\|-------o.,,,---. Y88888888o,,_
|_+=:_|_|__|_|   ___|__|___-|  """"````"""`----------.........___
/__============:' "" |==|__\===========(=>=+    |           ,_, .-"`--..._
|  ;="|"|  |"| `.____|__|__/===========(=>=+----+===-|---------<---------_=-
  | ==|:|\/| |   | o|.-'__,-|   .'  _______|o  `----'|        __\ __,.-'"
   "`--""`--"'"""`.-+------'" .'  _L___,,...-----------"""""""   "
                   `------""""""""

EOT;
        return $ascii;
    }
}
