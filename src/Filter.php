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
use Mesour\Filter\Sources\ArrayFilterSource;
use Mesour\Filter\Sources\IFilterSource;

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

    /**
     * @var Components\Session\ISessionSection
     */
    private $privateSession;

    public $onRender = array();

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
        if (is_null($name)) {
            throw new Components\InvalidArgumentException('Component name is required.');
        }
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
        $this->privateSession = $this->getSession()->getSection($this->createLinkName());
    }

    /**
     * @var IFilterSource
     */
    private $source;

    private $is_source_used = FALSE;

    /**
     * @param mixed $source
     * @return $this
     * @throws Components\Exception
     */
    public function setSource($source)
    {
        if ($this->is_source_used) {
            throw new Components\Exception('Cannot change source after using them.');
        }
        if(!$source instanceof IFilterSource) {
            if(is_array($source)) {
                $source = new ArrayFilterSource($source);
            } else {
                throw new Components\InvalidArgumentException('Source must be instance of \Mesour\Source\ISource or array.');
            }
        }
        $this->source = $source;
        return $this;
    }

    /**
     * @return IFilterSource
     * @throws Components\Exception
     */
    public function getSource()
    {
        if(!$this->source) {
            throw new Components\Exception('Data source is not set.');
        }
        $this->is_source_used = TRUE;
        return $this->source;
    }

    public function handleApplyFilter(array $filterData = array())
    {
        $this->privateSession->set('values', $filterData);
    }

    public function addFilterItem($name, IFilterItem $filterItem)
    {
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

    public function createItem($name, $data = array())
    {
        return $this[$name]->create($data);
    }

    public function renderItem($name, $data = array())
    {
        echo $this->createItem($name, $data);
    }

    public function createResetButton()
    {
        return $this->getResetButtonPrototype();
    }

    public function renderResetButton()
    {
        echo $this->createResetButton();
    }

    public function createHiddenInput($data = array())
    {
        $hidden = $this->getHiddenPrototype();
        $hidden->addAttributes(array(
            'data-mesour-data' => json_encode($data),
            'value' => json_encode($this->privateSession->get('values', array()))
        ));
        return $hidden;
    }

    public function renderHiddenInput($data = array())
    {
        echo $this->createHiddenInput();
    }

    public function create($data = array())
    {
        parent::create();

        $wrapper = $this->getWrapperPrototype();

        $hidden = $this->createHiddenInput($data);

        $this->onRender($this, $data);

        foreach ($this->getContainer() as $name => $_) {
            /** @var FilterItem $item */
            $item = $this->createItem($name, $data);

            $wrapper->add($item);
        }

        $wrapper->add($this->createResetButton());

        $wrapper->add($hidden);

        return $wrapper;
    }

}
