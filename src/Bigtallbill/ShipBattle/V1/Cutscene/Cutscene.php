<?php
namespace Bigtallbill\ShipBattle\V1\Cutscene;


abstract class Cutscene
{
    /**
     * @var KeyFrame[]
     */
    protected $keyframes = array();

    public function addKeyframe(KeyFrame $keyframe)
    {
        $this->keyframes[] = $keyframe;
    }

    public function play()
    {
        foreach ($this->keyframes as $keyframe) {
            self::clearView();

            $out = $keyframe->getText();
            $this->out($out, $keyframe->getUseEol());

            sleep($keyframe->getDuration());
        }

        self::clearView();
    }

    /**
     * Supposed to clear a terminal but does not work mostly
     */
    public static function clearView()
    {
        passthru('clear');
    }

    public static function out($str, $eol = true)
    {
        echo $str . (($eol) ? PHP_EOL : '');
    }
}
