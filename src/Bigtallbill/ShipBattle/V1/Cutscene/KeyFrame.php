<?php
namespace Bigtallbill\ShipBattle\V1\Cutscene;


class KeyFrame
{
    const DEFAULT_DURATION = 1;

    protected $useEol = true;
    protected $text = '';
    protected $duration = self::DEFAULT_DURATION;

    public function __construct($text = '', $duration = self::DEFAULT_DURATION, $useEol = true)
    {
        $this->duration = $duration;
        $this->text     = $text;
        $this->useEol   = $useEol;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param boolean $useEol
     */
    public function setUseEol($useEol)
    {
        $this->useEol = $useEol;
    }

    /**
     * @return boolean
     */
    public function getUseEol()
    {
        return $this->useEol;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
