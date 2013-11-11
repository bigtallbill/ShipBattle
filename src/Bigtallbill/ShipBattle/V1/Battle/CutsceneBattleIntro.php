<?php
namespace Bigtallbill\ShipBattle\V1\Battle;


use Bigtallbill\ShipBattle\V1\Cutscene\Cutscene;
use Bigtallbill\ShipBattle\V1\Cutscene\KeyFrame;

class CutsceneBattleIntro extends Cutscene
{

    public function __construct()
    {
        $this->addKeyframe(
            new KeyFrame(
                'You set out on an EPIC QUEST!!!!',
                3
            )
        );

        $this->addKeyframe(
            new KeyFrame(
                'Only you are not alone...',
                3
            )
        );

        $this->addKeyframe(
            new KeyFrame(
                'Truely a tale of strife!',
                5
            )
        );

        $this->addKeyframe(
            new KeyFrame(
                'A good test of the cutscene system indeed!',
                2
            )
        );

        $this->addKeyframe(
            new KeyFrame(
                'Let the battle commence!',
                5
            )
        );
    }
}
