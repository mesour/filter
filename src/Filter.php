<?php
/**
 * Mesour Filter Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components;
use Mesour\Filter\Date;
use Mesour\Filter\Number;
use Mesour\Filter\Sources\ArrayFilterSource;
use Mesour\Filter\Sources\IFilterSource;
use Mesour\Filter\Text;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Filter Component
 */
class Filter extends Control implements IFilter
{

    const ITEMS = 'items',
        RESET_BUTTON = 'reset-button',
        WRAPPER = 'wrapper',
        HIDDEN = 'hidden';

    static public $maxCheckboxCount = 1000;

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

    public $onFilter = array();

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

    public function __construct($name = NULL, Components\IContainer $parent = NULL)
    {
        if (is_null($name)) {
            throw new Components\InvalidArgumentException('Component name is required.');
        }
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
        if(!$this->privateSession) {
            $this->privateSession = $this->getSession()->getSection($this->createLinkName());
        }
    }

    public function attached(Components\IContainer $parent)
    {
        parent::attached($parent);
        $this->privateSession = $this->getSession()->getSection($this->createLinkName());
        return $this;
    }

    /**
     * @var IFilterSource
     */
    private $source;

    private $is_source_used = FALSE;

    private $date_format = 'Y-m-d';

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
        if (!$source instanceof IFilterSource) {
            if (is_array($source)) {
                $source = new ArrayFilterSource($source);
            } else {
                throw new Components\InvalidArgumentException('Source must be instance of \Mesour\Filter\Sources\IFilterSource or array.');
            }
        }
        $this->source = $source;
        return $this;
    }

    /**
     * @param bool $need
     * @return IFilterSource
     * @throws Components\Exception
     */
    public function getSource($need = TRUE)
    {
        if ($need && !$this->source) {
            throw new Components\Exception('Data source is not set.');
        }
        $this->is_source_used = TRUE;
        return $this->source;
    }

    public function handleApplyFilter(array $filterData = array())
    {
        $this->privateSession->set('values', $filterData);
        $this->onFilter($this);
    }

    /**
     * @param $name
     * @param string|null $text
     * @return Number
     */
    public function addNumberFilter($name, $text = NULL)
    {
        /** @var Number $filter */
        $filter = $this->addCustomFilter($name, new Number);
        $filter->setText($text);
        return $filter;
    }

    /**
     * @param $name
     * @param string|null $text
     * @return Text
     */
    public function addTextFilter($name, $text = NULL)
    {
        /** @var Text $filter */
        $filter = $this->addCustomFilter($name, new Text);
        $filter->setText($text);
        return $filter;
    }

    /**
     * @param $name
     * @param string|null $text
     * @return Date
     */
    public function addDateFilter($name, $text = NULL)
    {
        /** @var Date $filter */
        $filter = $this->addCustomFilter($name, new Date);
        $filter->setText($text);
        return $filter;
    }

    /**
     * @param $name
     * @param IFilterItem $filterItem
     * @return IFilterItem
     */
    public function addCustomFilter($name, IFilterItem $filterItem)
    {
        return $this[$name] = $filterItem;
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

    public function setDateFormat($date_format)
    {
        $this->date_format = $date_format;
        return $this;
    }

    public function getDateFormat()
    {
        return $this->date_format;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->privateSession->get('values', (object)array());
    }

    public function createHiddenInput($data = array())
    {
        $hidden = $this->getHiddenPrototype();
        $attributes = array(
            'data-mesour-data' => json_encode($data),
            'value' => json_encode($this->getValues()),
            'data-mesour-date' => $this->getDateFormat(),
            'data-mesour-js-date' => Components\Helper::convertDateToJsFormat($this->getDateFormat()),
        );
        $hidden->addAttributes($attributes);
        return $hidden;
    }

    public function renderHiddenInput($data = array())
    {
        echo $this->createHiddenInput();
    }

    public function beforeCreate($inner = FALSE)
    {
        if ($inner === TRUE) {
            parent::beforeRender();
        }
        $full_data = array();
        $source = $this->getSource(FALSE);
        if ($source && $source->getTotalCount() > 0 && $source->getTotalCount() < self::$maxCheckboxCount) {
            $full_data = $source->fetchFullData();
        }
        return $full_data;
    }

    public function create($data = array())
    {
        parent::create();

        $wrapper = $this->getWrapperPrototype();

        $full_data = $this->beforeCreate(TRUE);

        $hidden = $this->createHiddenInput($full_data);

        $this->onRender($this, $data);

        $has_checkers = count($full_data) > 0;
        foreach ($this as $name => $item_instance) {
            /** @var IFilterItem $item_instance */
            $item_instance->setCheckers($has_checkers);

            $item = $this->createItem($name, $data);

            $wrapper->add($item);
        }

        $wrapper->add($this->createResetButton());

        $wrapper->add($hidden);

        return $wrapper;
    }

}
