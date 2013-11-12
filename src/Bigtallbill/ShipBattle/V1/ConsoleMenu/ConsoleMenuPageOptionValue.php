<?php
namespace Bigtallbill\ShipBattle\V1\ConsoleMenu;


class ConsoleMenuPageOptionValue
{

    protected $valueRaw;
    protected $valueString;

    /**
     * @var
     */
    protected $callback;

    public function __construct($valueString, $valueRaw, $callback = null)
    {
        $this->valueRaw    = $valueRaw;
        $this->valueString = $valueString;
        $this->callback    = $callback;
    }


    public function __toString()
    {
        return $this->getValueString();
    }

    /**
     * @param mixed $valueRaw
     */
    public function setValueRaw($valueRaw)
    {
        $this->valueRaw = $valueRaw;
    }

    /**
     * @return mixed
     */
    public function getValueRaw()
    {
        return $this->valueRaw;
    }

    /**
     * @param mixed $valueString
     */
    public function setValueString($valueString)
    {
        $this->valueString = $valueString;
    }

    /**
     * @return mixed
     */
    public function getValueString()
    {
        return $this->valueString;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
