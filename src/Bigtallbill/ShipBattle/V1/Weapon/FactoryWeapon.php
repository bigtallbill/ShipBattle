<?php
namespace Bigtallbill\ShipBattle\V1\Weapon;


use MarketMeSuite\Phranken\Factory\Factory;
use MarketMeSuite\Phranken\Factory\IFactory;

class FactoryWeapon extends Factory implements IFactory
{
    /**
     * @param $type
     *
     * @return Weapon
     * @throws \Exception
     */
    public function buildWeapon($type)
    {
        // the base name for methods
        $baseName = 'build';

        // make the first char uppercase
        $type = str_replace('type_', '', $type);
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
     * @return Weapon A laser weapon
     */
    public function buildLaser()
    {
        return $this->build('Bigtallbill\\ShipBattle\\V1\\Weapon\\WeaponLaser');
    }

    /**
     * @return Weapon A missile weapon
     */
    public function buildMissile()
    {
        return $this->build('Bigtallbill\\ShipBattle\\V1\\Weapon\\WeaponMissile');
    }

    /**
     * @return Weapon A gauss weapon
     */
    public function buildGauss()
    {
        return $this->build('Bigtallbill\\ShipBattle\\V1\\Weapon\\WeaponGauss');
    }
}
