<?php
namespace Bigtallbill\ShipBattle\V1\ConsoleMenu;


class ConsoleMenuPageOption
{
    /**
     * @var string
     */
    protected $key = '';

    /**
     * @var string
     */
    protected $keyDisplay = '';

    /**
     * @var ConsoleMenuPageOptionValue
     */
    protected $value;

    /**
     * @param string                     $keyDisplay The display-friendly version of the key
     * @param string                     $key
     * @param ConsoleMenuPageOptionValue $value
     */
    public function __construct($keyDisplay, $key, ConsoleMenuPageOptionValue $value)
    {
        $this->value      = $value;
        $this->key        = $key;
        $this->keyDisplay = $keyDisplay;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getKeyDisplay()
    {
        return $this->keyDisplay;
    }

    /**
     * @param ConsoleMenuPageOptionValue $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return ConsoleMenuPageOptionValue
     */
    public function getValue()
    {
        return $this->value;
    }
}
