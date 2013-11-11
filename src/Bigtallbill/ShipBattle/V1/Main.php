<?php
namespace Bigtallbill\ShipBattle\V1;

use Bigtallbill\ShipBattle\V1\Battle\Battle;
use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenu;
use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenuPage;
use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenuPageOption;
use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenuPageOptionValue;
use Bigtallbill\ShipBattle\V1\Cutscene\CutsceneBasic;
use Bigtallbill\ShipBattle\V1\Cutscene\KeyFrame;
use Bigtallbill\ShipBattle\V1\Weapon\FactoryWeapon;
use Bigtallbill\ShipBattle\V1\Weapon\Weapon;
use Bigtallbill\ShipBattle\V1\Ship\Ship;
use Bigtallbill\ShipBattle\V1\Ship\ShipClass;
use Bigtallbill\ShipBattle\V1\Ship\ShipClasses;
use Bigtallbill\ShipBattle\V1\Ship\FactoryShipClass;
use Bigtallbill\ShipBattle\V1\Ship\ShipNameGenerator;
use MarketMeSuite\Phranken\Commandline\CommandPrompt;
use MarketMeSuite\Phranken\Commandline\SimpleLog;

use Bigtallbill\ShipBattle\V1\Player;

class Main
{

    const TURN_LENGTH_IN_GAME_TIME = 10000;

    /**
     * @var SimpleLog
     */
    protected $log;

    /**
     * @var CommandPrompt
     */
    protected $cmdPrompt;

    /**
     * @var Player
     */
    protected $player;

    protected $needsSetup = true;

    /**
     * @var int The number of turns elapsed for this battle
     */
    protected $currentTurn = 0;

    /**
     * @var Battle
     */
    protected $currentBattle;

    /**
     * @param $cmdPrompt CommandPrompt
     * @param $log SimpleLog
     */
    public function __construct(CommandPrompt $cmdPrompt, SimpleLog $log)
    {
        $this->cmdPrompt = $cmdPrompt;
        $this->log       = $log;
    }

    /**
     * executes the main game loop
     */
    public function gameLoop()
    {
        while (true) {

            // if there is no save then run a new game
            if ($this->needsSetup) {
                $this->setup();
                $this->currentBattle = $this->newBattle();
            }

            $this->currentBattle->nextTurn();

            // check if the battle is over
            if ($this->currentBattle->getIsBattleOver()) {

                // @todo make a better victor screen
                $victor = $this->currentBattle->getVictor();
                $looser = $this->currentBattle->getLooser();

                $this->log->log('');

                $cs = new CutsceneBasic();

                // talk of looser

                $cs->addKeyframe(
                    new KeyFrame(
                        'The ' . $looser->getShip()->getName() .
                        " errupts in a fireball of twisted metal",
                        5
                    )
                );

                $cs->addKeyframe(
                    new KeyFrame(
                        'The cowardly captain ' . $looser->getName() .
                        ' is seen escaping in a life-pod.',
                        5
                    )
                );

                $cs->addKeyframe(
                    new KeyFrame(
                        'The cowardly captain ' . $looser->getName() .
                        ' is seen escaping in a life-pod.',
                        5
                    )
                );

                $cs->addKeyframe(new KeyFrame('', 0));

                // talk of victor

                $cs->addKeyframe(
                    new KeyFrame(
                        $victor->getName() .
                        ' captain of the ' . $victor->getShip()->getName() .
                        ' is victorious!',
                        5
                    )
                );

                $cs->play();

                $this->showMainMenu();

                break;
            }

            $this->log->log('');
            $this->log->log("-- Turn $this->currentTurn (" . $this->currentBattle->getCurrentTurnPlayer()->getName() . ") --");
            $this->log->log('');

            $this->currentTurn++;
            sleep(1);
        }
    }

    public function showMainMenu()
    {
        $consoleMenu = new ConsoleMenu($this->cmdPrompt);

        $mainMenu = new ConsoleMenuPage('Main Menu');

        $optionMenu = new ConsoleMenuPage('Option Menu');
        $optionLoad = new ConsoleMenuPageOption(
            'Load Game',
            'load_game',
            new ConsoleMenuPageOptionValue(
                'load_game',
                function () {
                    global $sl;
                    $sl->log('NOOP');
                }
            )
        );

        $optionNewGame = new ConsoleMenuPageOption(
            'New Game',
            'new_game',
            new ConsoleMenuPageOptionValue(
                'new_game',
                function () {
                    global $main;
                    $main->gameLoop();
                }
            )
        );

        $optionGoToOptions = new ConsoleMenuPageOption(
            'Options',
            'options',
            new ConsoleMenuPageOptionValue(
                'options',
                array(
                    ConsoleMenu::CMD_TURN_TO_PAGE => $optionMenu
                )
            )
        );

        $optionQuit = new ConsoleMenuPageOption(
            'Quit Game',
            'quit_game',
            new ConsoleMenuPageOptionValue(
                'quit_game',
                function () {
                    global $sl;
                    $sl->log('bye!');
                }
            )
        );

        $mainMenu->addOption($optionNewGame);
        $mainMenu->addOption($optionGoToOptions);
        $mainMenu->addOption($optionQuit);

        $optionMenu->addOption($optionLoad);

        $consoleMenu->addPage($mainMenu);
        $consoleMenu->addPage($optionMenu);

        $consoleMenu->displayBegin();
    }

    public function setup()
    {
        // ask for name
        $playerName = $this->cmdPrompt->read('enter player name');

        $this->log->log("you are '$playerName'");

        // create a new player
        $this->player = new Player();
        $this->player->setName($playerName);


        // ask for ship class
        do {
            $this->log->log('choose your ship class');
            $shipClass = $this->cmdPrompt->read('', ShipClasses::getNumericHumanOptions());
        } while ($this->cmdPrompt->isEnumerable(
            ShipClasses::resolveNumericToClass($shipClass),
            ShipClasses::getNumericOptions()
        ) !== true);

        $this->log->log(
            'your chosen ship class is: ',
            ShipClasses::resolveNumericToHuman($shipClass)
        );

        $shipClassFactory = new FactoryShipClass();

        /**
         * @var ShipClass
         */
        $shipClassObject = $shipClassFactory->buildClass(ShipClasses::resolveNumericToClass($shipClass));

        // create ship object
        $ship = new Ship();
        $ship->setClass(
            $shipClassObject
        );

        $this->log->log($shipClassObject->getAscii());

        // ask for name
        $shipName = $this->cmdPrompt->read(
            'enter name for your ' . ShipClasses::resolveNumericToHuman($shipClass),
            array('leave blank for a random one')
        );

        usleep(750000);

        // generate a random name if the player did not enter a name
        if (empty($shipName)) {
            $shipName = ShipNameGenerator::getName();
            $this->log->log("your ship's name is '$shipName'");
        } else {
            $this->log->log("you named your ship '$shipName'");
        }

        $ship->setName($shipName);

        sleep(1);


        // generate a random loadout of weapons
        $weapons = $ship->getClass()->getRandomLoadout(
            new FactoryWeapon(),
            $shipClassObject->getHardpoints()
        );

        $ship->setWeapons($weapons);


        $this->log->log('');

        usleep(750000);
        $this->log->log($ship->getName() . ' is loaded with:');
        usleep(750000);
        $this->log->log($ship->countWeaponType(Weapon::TYPE_GAUSS) . ' Gauss Cannons');
        usleep(750000);
        $this->log->log($ship->countWeaponType(Weapon::TYPE_MISSILE) . ' Missile Launchers');
        usleep(750000);
        $this->log->log($ship->countWeaponType(Weapon::TYPE_LASER) . ' Laser Cannons');
        usleep(750000);
        $this->log->log('');

        $this->player->setShip($ship);

        // we no longer need setup
        $this->setNeedsSetup(false);
    }

    protected function newBattle()
    {
        $opponent = new Player();

        $opponent->setName('Jim');


        $opponentShip = new Ship();

        $opponentShip->setName(ShipNameGenerator::getName());

        $factoryClass = new FactoryShipClass();

        // build the same class ship as the player
        $opponentShipClass = $factoryClass->buildClass(
            $this->player->getShip()->getClass()->getClassId()
        );

        $opponentShip->setClass($opponentShipClass);

        $weapons = $opponentShipClass->getRandomLoadout(
            new FactoryWeapon(),
            $opponentShipClass->getHardpoints()
        );

        $opponentShip->setWeapons($weapons);

        $opponent->setShip($opponentShip);


        return new Battle($this->cmdPrompt, $this->log, $this->player, $opponent);
    }

    //-------------------------
    // GETTERS/SETTERS
    //-------------------------

    /**
     * @param boolean $needsSetup
     */
    public function setNeedsSetup($needsSetup)
    {
        $this->needsSetup = $needsSetup;
    }

    /**
     * @return boolean
     */
    public function getNeedsSetup()
    {
        return $this->needsSetup;
    }
}
