<?php
namespace Bigtallbill\ShipBattle\V1\Ship;

use MarketMeSuite\Phranken\Factory\Factory;
use MarketMeSuite\Phranken\Factory\IFactory;

use Bigtallbill\ShipBattle\V1\Ship\ShipClass;

class FactoryShipClass extends Factory implements IFactory
{
    /**
     * @param $type
     *
     * @return ShipClass
     * @throws \Exception
     */
    public function buildClass($type)
    {
        // the base name for methods
        $baseName = 'build';

        // make the first char uppercase
        $type = str_replace('cls_', '', $type);
        $type = ucfirst($type);

        // build the full method name
        $methodName = $baseName . $type;

        if (!method_exists($this, $methodName)) {
            throw new \Exception('Method ' . $methodName . ' does not exist');
        }

        // finally call the appropriate function
        return call_user_func(array($this, $methodName));
    }

    /**
     * @return ShipClass A laser weapon
     */
    public function buildFrigate()
    {
        return $this->build('Bigtallbill\\ShipBattle\\V1\\Ship\\ShipClassFrigate');
    }

    /**
     * @return ShipClass A laser weapon
     */
    public function buildDestroyer()
    {
        return $this->build('Bigtallbill\\ShipBattle\\V1\\Ship\\ShipClassDestroyer');
    }

    /**
     * @return ShipClass A laser weapon
     */
    public function buildBattleship()
    {
        return $this->build('Bigtallbill\\ShipBattle\\V1\\Ship\\ShipClassBattleship');
    }
}
