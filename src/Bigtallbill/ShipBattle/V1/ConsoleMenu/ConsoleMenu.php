<?php
namespace Bigtallbill\ShipBattle\V1\ConsoleMenu;

use MarketMeSuite\Phranken\Commandline\CommandPrompt;
use MarketMeSuite\Phranken\Commandline\ConsoleTable;

/**
 * Displays an interactive menu with multiple options
 *
 * Class ConsoleMenu
 * @package Bigtallbill\ShipBattle\V1
 */
class ConsoleMenu
{
    const CMD_TURN_TO_PAGE_INDEX = 'turn_to_page_index';
    const CMD_TURN_TO_PAGE       = 'turn_to_page';
    const CMD_QUIT               = 'cmd_quit';

    const GO_BACK_KEY = '~';

    /**
     * @var ConsoleMenuPageOption
     */
    protected $lastChosenOption;

    /**
     * @var ConsoleMenuPage[]
     */
    protected $pages = array();

    /**
     * @var CommandPrompt
     */
    protected $cmdPrompt;

    /**
     * @var ConsoleMenuPage
     */
    protected $currentPage;

    /**
     * @var ConsoleMenuPage
     */
    protected $previousPage;

    /**
     * @var bool
     */
    protected static $clearEnabled = true;

    public function __construct(CommandPrompt $cmdPrompt)
    {
        $this->cmdPrompt = $cmdPrompt;
    }

    /**
     * Adds a page to the menu
     *
     * @param ConsoleMenuPage $page
     */
    public function addPage(ConsoleMenuPage $page)
    {
        $this->pages[] = $page;
    }

    /**
     * Turns the view to the specified page
     *
     * @param ConsoleMenuPage $page
     */
    public function turnToPage(ConsoleMenuPage $page)
    {
        $this->setPreviousPage($this->getCurrentPage());
        $this->setCurrentPage($page);
        $this->drawPage($this->getCurrentPage());
        $this->waitForInput();
    }

    /**
     * Turns the view to the given page at $index
     * provided that index exists in self::$pages
     *
     * @param int $index
     */
    public function turnToIndex($index = 0)
    {
        $this->turnToPage(
            $this->getPageByIndex($index)
        );
    }

    /**
     * Gets a page by its index
     *
     * @param $index
     *
     * @return ConsoleMenuPage
     */
    public function getPageByIndex($index)
    {
        return $this->pages[$index];
    }

    /**
     * waits for user input
     */
    public function waitForInput()
    {
        if ($this->getPreviousPage() !== null) {
            $choice = $this->cmdPrompt->read(
                'choose an option ',
                array(self::GO_BACK_KEY . ' back')
            );
        } else {
            $choice = $this->cmdPrompt->read('choose an option');
        }

        // if the go back character is sent then we just load the
        // previous page
        if ($choice === self::GO_BACK_KEY && $this->getPreviousPage() !== null) {
            $this->turnToPage($this->getPreviousPage());
            return;
        } elseif ($choice === self::GO_BACK_KEY && $this->getPreviousPage() === null) {
            $this->turnToPage($this->getCurrentPage());
            return;
        }

        // gets the chosen option from the current page
        $chosenOpt = $this->getCurrentPage()->getOptionByIndex($choice);

        // store the last chosen option for
        // external exposure
        $this->lastChosenOption = $chosenOpt;

        // $gets the raw option value
        $valueRaw = $chosenOpt->getValue()->getValueRaw();

        // if the raw value is an array, then look for
        // keys which match a command flag
        if (is_array($valueRaw)) {

            // get the key of the first key => value pair
            $commandKey = key($valueRaw);

            switch ($commandKey) {
                case self::CMD_TURN_TO_PAGE:
                    $this->turnToPage($valueRaw[$commandKey]);
                    break;
                case self::CMD_TURN_TO_PAGE_INDEX:
                    $this->turnToIndex($valueRaw[$commandKey]);
                    break;
                case self::CMD_QUIT:
                    $this->displayEnd();
                    break;
            }
        } elseif ($valueRaw !== null) {
            call_user_func($valueRaw);
        }
    }

    /**
     * starts the menu interaction until the menu is quit
     */
    public function displayBegin()
    {
        $this->turnToIndex();
    }

    /**
     * @todo remove this if it is no longer needed
     */
    public function displayEnd()
    {
        self::clearView();
    }

    /**
     * Draws a page to the standard output in a table view
     *
     * @param ConsoleMenuPage $page
     */
    public function drawPage(ConsoleMenuPage $page)
    {
        $this->out("-- " . $page->getTitle() . " --\n");

        $renderedTable = static::buildConsoleTable(
            $page->toTable()
        )->drawTable();

        static::clearView();
        $this->out($renderedTable);
    }

    /**
     * Outputs strings to the standard output
     *
     * @param $string
     */
    public function out($string)
    {
        echo $string;
    }

    /**
     * Supposed to clear a terminal but does not work mostly
     */
    public static function clearView()
    {
        if (static::getClearEnabled()) {
            passthru('clear');
        }
    }

    /**
     * Builds a ConsoleTable
     *
     * @param array $data
     *
     * @return ConsoleTable
     */
    public static function buildConsoleTable(array $data = array())
    {
        return new ConsoleTable($data);
    }

    /**
     * @param ConsoleMenuPage $currentPage
     */
    public function setCurrentPage(ConsoleMenuPage $currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return ConsoleMenuPage
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param ConsoleMenuPage $previousPage
     */
    public function setPreviousPage($previousPage)
    {
        $this->previousPage = $previousPage;
    }

    /**
     * @return ConsoleMenuPage
     */
    public function getPreviousPage()
    {
        return $this->previousPage;
    }

    /**
     * @return ConsoleMenuPageOption
     */
    public function getLastChosenOption()
    {
        return $this->lastChosenOption;
    }

    /**
     * @param boolean $clearEnabled
     */
    public static function setClearEnabled($clearEnabled)
    {
        static::$clearEnabled = $clearEnabled;
    }

    /**
     * @return boolean
     */
    public static function getClearEnabled()
    {
        return static::$clearEnabled;
    }
}
