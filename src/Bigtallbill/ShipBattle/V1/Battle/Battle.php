<?php
namespace Bigtallbill\ShipBattle\V1\Battle;

use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenu;
use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenuPage;
use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenuPageOption;
use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenuPageOptionValue;
use Bigtallbill\ShipBattle\V1\Player;
use Bigtallbill\ShipBattle\V1\Weapon\Weapon;
use MarketMeSuite\Phranken\Commandline\CommandPrompt;
use MarketMeSuite\Phranken\Commandline\SimpleLog;
use MarketMeSuite\Phranken\Commandline\ConsoleTable;

class Battle
{
    protected $currentTurn = 0;

    /**
     * @var Player
     */
    protected $player;

    /**
     * @var Player
     */
    protected $opponent;

    /**
     * @var SimpleLog
     */
    protected $log;

    /**
     * @var CommandPrompt
     */
    protected $cmdPrompt;

    protected $isPlayerTurn = true;

    protected $isBattleOver = false;

    /**
     * @var Player
     */
    protected $victor;

    /**
     * @var Player
     */
    protected $looser;

    /**
     * @var Player
     */
    protected $currentTurnPlayer;


    /**
     * @param CommandPrompt $cmdPrompt
     * @param SimpleLog $log
     * @param Player $player
     * @param Player $opponent
     */
    public function __construct(CommandPrompt $cmdPrompt, SimpleLog $log, Player $player, Player $opponent)
    {
        $this->player   = $player;
        $this->opponent = $opponent;
        $this->log = $log;
        $this->cmdPrompt = $cmdPrompt;

        $intro = new CutsceneBattleIntro();
        $intro->play();
    }

    public function nextTurn()
    {
        if ($this->isPlayerTurn) {

            $this->currentTurnPlayer = $this->opponent;
            $this->turnPlayer();

        } else {

            $this->currentTurnPlayer = $this->player;
            $this->turnOpponent();
        }

        // advance turn stats
        $this->currentTurn++;
        $this->isPlayerTurn = !$this->isPlayerTurn;
    }

    protected function turnPlayer()
    {
        $player = $this->player;
        $ship = $this->player->getShip();
        $ship->decrementCooldownOfAllWeapons();

        $opponent = $this->opponent;

        // create a new menu system
        $cm = new ConsoleMenu($this->cmdPrompt);

        // we dont want the menu to remove other stuff
        $cm->setClearEnabled(false);

        // create a new page for battle options
        $battlepage = new ConsoleMenuPage('Battle Options');

        // define options for battle page
        $battlepageOptionFireLasers = new ConsoleMenuPageOption(
            'FIRE laser cannons (' .
            $ship->countWeaponType(Weapon::TYPE_LASER) .
            ':total '.
            $ship->countWeaponType(Weapon::TYPE_LASER, true) .
            ':cooling)',
            Weapon::TYPE_LASER,
            new ConsoleMenuPageOptionValue(
                'Fire laser',
                array(ConsoleMenu::CMD_QUIT => '')
            )
        );

        $battlepageOptionFireGauss = new ConsoleMenuPageOption(
            'FIRE gauss cannons (' .
            $ship->countWeaponType(Weapon::TYPE_GAUSS) .
            ':total ' .
            $ship->countWeaponType(Weapon::TYPE_GAUSS, true) .
            ':cooling)',
            Weapon::TYPE_GAUSS,
            new ConsoleMenuPageOptionValue(
                'Fire Gauss',
                array(ConsoleMenu::CMD_QUIT => '')
            )
        );

        $battlepageOptionFireMissile = new ConsoleMenuPageOption(
            'FIRE missiles (' .
            $ship->countWeaponType(Weapon::TYPE_MISSILE) .
            ':total ' .
            $ship->countWeaponType(Weapon::TYPE_MISSILE, true) .
            ':cooling)',
            Weapon::TYPE_MISSILE,
            new ConsoleMenuPageOptionValue(
                'Fire Gauss',
                array(ConsoleMenu::CMD_QUIT => '')
            )
        );

        $battlepageOptionEvasive = new ConsoleMenuPageOption(
            'Take evasive maneuvers',
            'evasive_man',
            new ConsoleMenuPageOptionValue(
                'evasive_man',
                array(ConsoleMenu::CMD_QUIT => '')
            )
        );

        $battlepageOptionStatus = new ConsoleMenuPageOption(
            $ship->getName() . ' Status',
            'ship_status',
            new ConsoleMenuPageOptionValue(
                'ship_status',
                array(ConsoleMenu::CMD_QUIT => '')
            )
        );

        // add all options and pages
        $battlepage->addOption($battlepageOptionFireLasers);
        $battlepage->addOption($battlepageOptionFireGauss);
        $battlepage->addOption($battlepageOptionFireMissile);
        $battlepage->addOption($battlepageOptionEvasive);
        $battlepage->addOption($battlepageOptionStatus);
        $cm->addPage($battlepage);

        // display options to user
        $cm->displayBegin();

        // add space after input
        $this->log->log('');

        $chosenOption = $cm->getLastChosenOption();

        $inflictedDamage = 0;

        switch ($chosenOption->getKey()) {
            case Weapon::TYPE_LASER:
            case Weapon::TYPE_GAUSS:
            case Weapon::TYPE_MISSILE:

                // display text of weapon fired
                $this->log->log('you fire your ' . Weapon::getHumanFromType($chosenOption->getKey()) . "'s");

                sleep(2);

                $inflictedDamage += $ship->fireWeaponsOfType(
                    $chosenOption->getKey(),
                    $opponent->getShip()
                );

                // display turn results

                $volleyStats = $ship->getVolleyStats();
                if ($volleyStats[$chosenOption->getKey()] > 0) {

                    $this->log->log(
                        $volleyStats[$chosenOption->getKey()] .
                        ' ' .
                        Weapon::getHumanFromType($chosenOption->getKey()) .
                        "'s missed"
                    );
                }

                break;
            case 'ship_status':
                $this->log->log(
                    $ship->getName() ."'s Status " .
                    'Hull Points: ' . $ship->getHitPoints() . ' (' . $ship->getHitPointsPercent() . '%)'
                );
                $this->turnPlayer();
                break;
            default:
                $this->log->log('you twiddle your thumbs');
                break;
        }

        if ($inflictedDamage > 0) {

            $opponent->getShip()->takeDamage($inflictedDamage);

            $this->log->log(
                $opponent->getName() ."'s ship " .
                $opponent->getShip()->getName() . ' took ' .
                $inflictedDamage . ' points of damamge (' .
                $opponent->getShip()->getHitPoints() . ' remaining) (' . $opponent->getShip()->getHitPointsPercent() . '%)'
            );

            // dramatic wait
            sleep(2);

            // check for opponent death
            if ($opponent->getShip()->isDestroyed()) {
                $this->setIsBattleOver(true);
                $this->victor = $player;
                $this->looser = $opponent;

                return;
            }
        }



        sleep(1);
    }

    protected function turnOpponent()
    {
        $ship = $this->opponent->getShip();
        $ship->decrementCooldownOfAllWeapons();

        $player = $this->opponent;
        $opponent = $this->player;

        sleep(rand(2, 5));

        $weaponFired = Weapon::getRandomType();

        $inflictedDamage = 0;
        $inflictedDamage += $ship->fireWeaponsOfType(
            $weaponFired,
            $opponent->getShip()
        );

        switch ($weaponFired) {
            case Weapon::TYPE_LASER:
                $this->log->log($player->getName() . ' fired his laser cannons');

                break;
            case Weapon::TYPE_GAUSS:
                $this->log->log($player->getName() . ' fired his gauss cannons');

                break;
            case Weapon::TYPE_MISSILE:
                $this->log->log($player->getName() . ' fired his missiles');

                break;
            default:
                $this->log->log($player->getName() . ' twiddles his thumbs');
                break;
        }

        // dramatic wait
        sleep(2);

        $volleyStats = $ship->getVolleyStats();
        if ($volleyStats[$weaponFired] > 0) {

            $this->log->log($volleyStats[$weaponFired] . ' ' . Weapon::getHumanFromType($weaponFired) . "'s missed");

            // dramatic wait
            sleep(2);
        }

        if ($inflictedDamage > 0) {

            $opponent->getShip()->takeDamage($inflictedDamage);

            $this->log->log(
                $opponent->getName() ."'s ship " .
                $opponent->getShip()->getName() . ' took ' .
                $inflictedDamage . ' points of damamge (' .
                $opponent->getShip()->getHitPoints() . ' remaining) (' . $opponent->getShip()->getHitPointsPercent() . '%)'
            );

            // dramatic wait
            sleep(2);

            // check for opponent death
            if ($opponent->getShip()->isDestroyed()) {
                $this->setIsBattleOver(true);
                $this->victor = $player;
                $this->looser = $opponent;

                return;
            }
        }

        sleep(2);
    }

    protected function showOptions(array $opts)
    {
        $ct = new ConsoleTable($opts);

        $out = $ct->drawTable();

        $this->log->log($out);
    }

    /**
     * @param boolean $isBattleOver
     */
    public function setIsBattleOver($isBattleOver)
    {
        $this->isBattleOver = $isBattleOver;
    }

    /**
     * @return boolean
     */
    public function getIsBattleOver()
    {
        return $this->isBattleOver;
    }

    /**
     * @return Player
     */
    public function getVictor()
    {
        return $this->victor;
    }

    /**
     * @return Player
     */
    public function getLooser()
    {
        return $this->looser;
    }

    /**
     * @return \Bigtallbill\ShipBattle\V1\Player
     */
    public function getCurrentTurnPlayer()
    {
        return $this->currentTurnPlayer;
    }
}
