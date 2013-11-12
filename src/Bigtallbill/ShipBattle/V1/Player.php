<?php
namespace Bigtallbill\ShipBattle\V1;


use MarketMeSuite\Phranken\Database\Interfaces\IDbObject;
use MarketMeSuite\Phranken\Database\Object\DbObject;
use Bigtallbill\ShipBattle\V1\Ship\Ship;

class Player extends DbObject implements IDbObject
{
    private $id;
    private $name;

    /**
     * @var Ship
     */
    private $ship;

    public function getMap()
    {
        return array(
            '_id'  => 'id',
            'name' => 'name',
        );
    }

    public function fromArray(array $data)
    {
        parent::fromArray($data);

        $map = $this->getMap();

        foreach ($map as $dbKey => $variableName) {

            // if the key exists then set the property
            if (isset($data[$dbKey])) {

                // convert to actual object from database
                if ($dbKey === 'ship') {
                    $ship = new Ship();
                    $ship->fromArray($data[$dbKey]);
                    $this->{$variableName} = $ship;
                }
            }
        }
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Ship $ship
     */
    public function setShip(Ship $ship)
    {
        $this->ship = $ship;
    }

    /**
     * @return Ship
     */
    public function getShip()
    {
        return $this->ship;
    }
}
