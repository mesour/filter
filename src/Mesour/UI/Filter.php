<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour;
use Nette;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 *
 * @method null onFilter(Filter $filter)
 * @method null onRender(Filter $filter)
 * @method Mesour\Filter\IFilterItem current()
 */
class Filter extends Mesour\Components\Control\AttributesControl implements Mesour\Filter\IFilter
{

    const ITEMS = 'items',
        RESET_BUTTON = 'reset-button',
        WRAPPER = 'wrapper',
        HIDDEN = 'hidden';

    const VALUE_TRUE = '-mesour-bool-1';
    const VALUE_FALSE = '-mesour-bool-0';
    const VALUE_NULL = '-mesour-null';

    const ICON_ITEM_ACTIVE = 'itemIsActive';
    const ICON_EDIT_CUSTOM = 'editCustom';
    const ICON_REMOVE_CUSTOM = 'removeCustom';
    const ICON_PLUS = 'plus';
    const ICON_MINUS = 'minus';
    const ICON_CALENDAR = 'calendar';

    static public $maxCheckboxCount = 1000;

    static public $icons = [
        self::ICON_ITEM_ACTIVE => 'check',
        self::ICON_EDIT_CUSTOM => 'pencil',
        self::ICON_REMOVE_CUSTOM => 'pencil',
        self::ICON_PLUS => 'plus-square',
        self::ICON_MINUS => 'minus-square',
        self::ICON_CALENDAR => 'calendar',
    ];

    /** @var Mesour\Components\Utils\Html */
    protected $hidden;

    /** @var Mesour\Components\Utils\Html */
    protected $resetButton;

    /** @var Mesour\Components\Utils\Html */
    protected $wrapper;

    /** @var Mesour\Components\Session\ISessionSection */
    private $privateSession;

    public $onFilter = [];

    public $onRender = [];

    public $defaults = [
        self::HIDDEN => [
            'el' => 'input',
            'attributes' => [
                'type' => 'hidden',
                'value' => ''
            ]
        ],
        self::ITEMS => [
            'el' => 'a',
            'attributes' => [
                'class' => 'btn btn-default btn-xs select-checkbox',
            ],
            'content' => '&nbsp;&nbsp;&nbsp;&nbsp;',
        ],
        self::RESET_BUTTON => [
            'el' => 'a',
            'attributes' => [
                'href' => '#',
                'class' => 'btn btn-danger button red float-l full-reset',
                'name' => '_reset',
            ],
            'content' => 'Reset',
        ],
        self::WRAPPER => [
            'el' => 'div',
            'attributes' => [
                'class' => 'mesour-filter',
            ],
        ]
    ];

    public function __construct($name = NULL, Mesour\Components\ComponentModel\IContainer $parent = NULL)
    {
        if (is_null($name)) {
            throw new Mesour\InvalidArgumentException('Component name is required.');
        }
        parent::__construct($name, $parent);

        $this->startPrivateSession();

        $this->setHtmlElement(
            Mesour\Components\Utils\Html::el(
                $this->getOption(self::WRAPPER, 'el'),
                $this->getOption(self::WRAPPER, 'attributes')
            )
        );
    }

    public function attached(Mesour\Components\ComponentModel\IContainer $parent)
    {
        parent::attached($parent);
        $this->startPrivateSession(TRUE);
        return $this;
    }

    /**
     * @var Mesour\Filter\Sources\IFilterSource
     */
    private $source;

    private $is_source_used = FALSE;

    private $date_format = 'Y-m-d';

    /**
     * @param mixed $source
     * @return $this
     * @throws Mesour\InvalidStateException
     * @throws Mesour\InvalidArgumentException
     */
    public function setSource($source)
    {
        if ($this->is_source_used) {
            throw new Mesour\InvalidStateException('Cannot change source after using them.');
        }
        if (!$source instanceof Mesour\Filter\Sources\IFilterSource) {
            if (is_array($source)) {
                $source = new Mesour\Filter\Sources\ArrayFilterSource($source);
            } else {
                throw new Mesour\InvalidArgumentException('Source must be instance of \Mesour\Filter\Sources\IFilterSource or array.');
            }
        }
        $this->source = $source;
        return $this;
    }

    /**
     * @param bool $need
     * @return Mesour\Filter\Sources\IFilterSource
     * @throws Mesour\InvalidStateException
     */
    public function getSource($need = TRUE)
    {
        if ($need && !$this->source) {
            throw new Mesour\InvalidStateException('Data source is not set.');
        }
        $this->is_source_used = TRUE;
        return $this->source;
    }

    public function handleApplyFilter(array $filterData = [])
    {
        $this->privateSession->set('values', $this->translateData($filterData));
        $this->onFilter($this);
    }

    private function translateData($filterData)
    {
        foreach ($filterData as $name => $item) {
            if (isset($item['checkers'])) {
                foreach ($item['checkers'] as $key => $value) {
                    $filterData[$name]['checkers'][$key] = $this->fixCheckerValue($value);
                }
            }
        }
        return $filterData;
    }

    private function fixCheckerValue($val)
    {
        if ($val === self::VALUE_FALSE) {
            return FALSE;
        } else if ($val === self::VALUE_TRUE) {
            return TRUE;
        } else if ($val === self::VALUE_NULL) {
            return NULL;
        }
        return $val;
    }

    /**
     * @param $name
     * @param string|null $text
     * @param array $valueTranslates
     * @return Mesour\Filter\Number
     */
    public function addNumberFilter($name, $text = NULL, array $valueTranslates = [])
    {
        /** @var Mesour\Filter\Number $filter */
        $filter = $this->addCustomFilter($name, new Mesour\Filter\Number);
        $filter->setText($text);
        $filter->setValueTranslates($valueTranslates);
        return $filter;
    }

    /**
     * @param $name
     * @param string|null $text
     * @param array $valueTranslates
     * @return Mesour\Filter\Text
     */
    public function addTextFilter($name, $text = NULL, array $valueTranslates = [])
    {
        /** @var Mesour\Filter\Text $filter */
        $filter = $this->addCustomFilter($name, new Mesour\Filter\Text);
        $filter->setText($text);
        $filter->setValueTranslates($valueTranslates);
        return $filter;
    }

    /**
     * @param $name
     * @param string|null $text
     * @param array $valueTranslates
     * @return Mesour\Filter\Date
     */
    public function addDateFilter($name, $text = NULL, array $valueTranslates = [])
    {
        /** @var Mesour\Filter\Date $filter */
        $filter = $this->addCustomFilter($name, new Mesour\Filter\Date);
        $filter->setText($text);
        $filter->setValueTranslates($valueTranslates);
        return $filter;
    }

    /**
     * @param $name
     * @param Mesour\Filter\IFilterItem $filterItem
     * @return Mesour\Filter\IFilterItem
     */
    public function addCustomFilter($name, Mesour\Filter\IFilterItem $filterItem)
    {
        return $this[$name] = $filterItem;
    }

    public function getHiddenPrototype()
    {
        $attributes = $this->getOption(self::HIDDEN, 'attributes');
        $attributes = array_merge($attributes, [
            'data-mesour-filter' => $this->createLinkName(),
        ]);
        return $this->hidden
            ? $this->hidden
            : ($this->hidden = Mesour\Components\Utils\Html::el($this->getOption(self::HIDDEN, 'el'), $attributes));
    }

    public function getWrapperPrototype()
    {
        return $this->getHtmlElement();
    }

    public function getResetButtonPrototype()
    {
        $attributes = $this->getOption(self::RESET_BUTTON, 'attributes');
        $attributes = array_merge($attributes, [
            'data-filter-name' => $this->createLinkName(),
        ]);
        return $this->resetButton
            ? $this->resetButton
            : ($this->resetButton = Mesour\Components\Utils\Html::el($this->getOption(self::RESET_BUTTON, 'el'), $attributes)
                ->setHtml($this->getOption(self::RESET_BUTTON, 'content')));
    }

    public function getItem($name, $data = [])
    {
        $this[$name]->setOption('data', $data);
        return $this[$name];
    }

    public function renderItem($name, $data = [])
    {
        return $this->getItem($name, $data)->create();
    }

    public function createResetButton()
    {
        return $this->getResetButtonPrototype();
    }

    public function renderResetButton()
    {
        return $this->createResetButton();
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
        return $this->privateSession->get('values', (object)[]);
    }

    public function createHiddenInput($data = [])
    {
        /** @var Mesour\Icon\IIcon $icon */
        $className = $this->getIconClass();
        $icon = new $className;
        $hidden = $this->getHiddenPrototype();
        $attributes = [
            'data-mesour-data' => json_encode($data),
            'value' => json_encode($this->getValues()),
            'data-mesour-date' => $this->getDateFormat(),
            'data-icon-prefix' => $icon->getPrefix(),
            'data-icons' => Nette\Utils\Json::encode(self::$icons),
            'data-mesour-js-date' => Mesour\Components\Utils\Helpers::convertDateToJsFormat($this->getDateFormat()),
        ];
        $hidden->addAttributes($attributes);
        return $hidden;
    }

    public function renderHiddenInput($data = [])
    {
        return $this->createHiddenInput();
    }

    public function beforeCreate($inner = FALSE)
    {
        if ($inner === TRUE) {
            parent::beforeRender();
        }
        $full_data = [];
        $source = $this->getSource(FALSE);
        if ($source && $source->getTotalCount() > 0 && $source->getTotalCount() < self::$maxCheckboxCount) {
            $full_data = $source->fetchFullData();
        }
        return $full_data;
    }

    public function create()
    {
        parent::create();

        $wrapper = $this->getWrapperPrototype();

        $fullData = $this->beforeCreate(TRUE);

        $hidden = $this->createHiddenInput($fullData);

        $this->onRender($this);

        $hasCheckers = count($fullData) > 0;
        foreach ($this as $name => $itemInstance) {
            /** @var Mesour\Filter\IFilterItem $itemInstance */
            $itemInstance->setCheckers($hasCheckers);

            $item = $this->getItem($name)->create();

            $wrapper->add($item);
        }

        $wrapper->add($this->createResetButton());

        $wrapper->add($hidden);

        return $wrapper;
    }

    private function startPrivateSession($force = FALSE)
    {
        if ($force || !$this->privateSession) {
            $this->privateSession = $this->getSession()->getSection($this->createLinkName());
        }
    }

}
