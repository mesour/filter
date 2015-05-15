<?php
/**
 * Mesour Selection Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components;
use Mesour\Filter\FilterItem;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Selection Component
 */
class Filter extends Control implements IFilter
{

    const ITEMS = 'items',
        RESET_BUTTON = 'reset-button',
        WRAPPER = 'wrapper',
        HIDDEN = 'hidden';

    protected $option = array();

    protected $data = array();

    /**
     * @var Components\Html
     */
    protected $hidden;

    /**
     * @var Components\Html
     */
    protected $resetButton;

    /**
     * @var Components\Html
     */
    protected $wrapper;

    //public $onRender = array();

    static public $defaults = array(
        self::HIDDEN => array(
            'el' => 'input',
            'attributes' => array(
                'type' => 'hidden',
                'value' => ''
            )
        ),
        self::ITEMS => array(
            'el' => 'a',
            'attributes' => array(
                'class' => 'btn btn-default btn-xs select-checkbox',
            ),
            'content' => '&nbsp;&nbsp;&nbsp;&nbsp;',
        ),
        self::RESET_BUTTON => array(
            'el' => 'a',
            'attributes' => array(
                'href' => '#',
                'class' => 'btn btn-danger button red float-l full-reset',
                'name' => '_reset',
            ),
            'content' => 'Reset',
        ),
        self::WRAPPER => array(
            'el' => 'div',
            'attributes' => array(
                'class' => 'mesour-filter',
            ),
        )
    );

    public function __construct($name = NULL, Components\IComponent $parent = NULL)
    {
        if(is_null($name)) {
            throw new Components\InvalidArgumentException('Component name is required.');
        }
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function getData() {
        return $this->data;
    }

    public function addFilterItem($name, IFilterItem $filterItem) {
        $this[$name] = $filterItem;
        return $this;
    }

    public function getHiddenPrototype()
    {
        $attributes = $this->option[self::HIDDEN]['attributes'];
        $attributes = array_merge($attributes, array(
            'data-mesour-filter' => $this->createLinkName(),
        ));
        return $this->hidden
            ? $this->hidden
            : ($this->hidden = Components\Html::el($this->option[self::HIDDEN]['el'], $attributes));
    }

    public function getWrapperPrototype()
    {
        return $this->wrapper
            ? $this->wrapper
            : ($this->wrapper = Components\Html::el($this->option[self::WRAPPER]['el'], $this->option[self::WRAPPER]['attributes']));
    }

    public function getResetButtonPrototype()
    {
        $attributes = $this->option[self::RESET_BUTTON]['attributes'];
        $attributes = array_merge($attributes, array(
            'data-filter-name' => $this->createLinkName(),
        ));
        return $this->resetButton
            ? $this->resetButton
            : ($this->resetButton = Components\Html::el($this->option[self::RESET_BUTTON]['el'], $attributes)->setHtml($this->option[self::RESET_BUTTON]['content']));
    }

    public function createItem($name, $data = array()) {
        return $this[$name]->create($data);
    }

    public function renderItem($name, $data = array()) {
        echo $this->createItem($name, $data);
    }

    public function createResetButton() {
        return $this->getResetButtonPrototype();
    }

    public function renderResetButton() {
        echo $this->createResetButton();
    }

    public function create($data = array()) {
        parent::create();

        $data = array_merge_recursive($this->data, $data);

        $wrapper = $this->getWrapperPrototype();

        $hidden = $this->getHiddenPrototype();

        $hidden->addAttributes(array('data-mesour-data' => json_encode($data)));

        foreach($this->getContainer() as $name => $_) {
            /** @var FilterItem $item */
            $item = $this->createItem($name, $data);

            $wrapper->add($item);
        }

        $wrapper->add($this->createResetButton());

        $wrapper->add($hidden);

        return $wrapper;
    }

}
