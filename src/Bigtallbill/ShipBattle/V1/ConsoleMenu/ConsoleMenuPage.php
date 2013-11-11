<?php
namespace Bigtallbill\ShipBattle\V1\ConsoleMenu;

use Bigtallbill\ShipBattle\V1\ConsoleMenu\ConsoleMenuPageOption;

/**
 * Controls a single page in the menu.
 *
 * Manages an array of options and rendering to a table.
 *
 * Class ConsoleMenuPage
 * @package Bigtallbill\ShipBattle\V1
 */
class ConsoleMenuPage
{
    /**
     * @var ConsoleMenuPageOption[]
     */
    protected $options = array();

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @param string $title The title of the page
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Adds an option to the page
     *
     * Pages can have as many options as they like
     *
     * @param ConsoleMenuPageOption $option
     */
    public function addOption(ConsoleMenuPageOption $option)
    {
        $this->options[] = $option;
    }

    /**
     * Converts this page to the format that ConsoleTable
     * expects for display
     *
     * @return array
     */
    public function toTable()
    {
        $keys   = array();
        $values = array();

        foreach ($this->getOptions() as $key => $option) {
            $keys[]   = $key;
            $values[] = $option->getKeyDisplay();
        }

        $table = array(
            '~'       => $keys,
            'options' => $values,
        );

        return $table;
    }

    /**
     * Gets an option by its index
     *
     * @param int $index
     *
     * @return ConsoleMenuPageOption
     */
    public function getOptionByIndex($index = 0)
    {
        $opts = $this->getOptions();
        return $opts[$index];
    }

    /**
     * @return ConsoleMenuPageOption[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
