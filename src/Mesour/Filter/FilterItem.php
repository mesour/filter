<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 *
 * @method null onRender(FilterItem $filterItem)
 */
abstract class FilterItem extends Mesour\Components\Control\AttributesControl
{

    const WRAPPER = 'wrapper',
        LIST_UL = 'list-ul',
        LIST_LI = 'list-li',
        FILTERS_ITEM = 'filters-item',
        BUTTON = 'button';

    /** @var Mesour\Components\Utils\Html */
    protected $button;

    /** @var Mesour\Components\Utils\Html */
    protected $wrapper;

    /** @var Mesour\Components\Utils\Html */
    protected $list_ul;

    protected $text;

    protected $filters = [];

    protected $valueTranslates = [];

    protected $filters_name = 'Filters';

    protected $hasCheckers = FALSE;

    public $onRender = [];

    protected $defaults = [
        self::BUTTON => [
            'el' => 'button',
            'attributes' => [
                'class' => 'btn btn-default dropdown-toggle',
                'type' => 'button'
            ]
        ],
        self::LIST_UL => [
            'el' => 'ul',
            'attributes' => [
                'class' => 'dropdown-menu',
                'role' => 'menu'
            ]
        ],
        self::LIST_LI => [
            'el' => 'li',
            'attributes' => [
                'role' => 'presentation'
            ]
        ],
        self::WRAPPER => [
            'el' => 'div',
            'attributes' => [
                'class' => 'dropdown filter-dropdown mesour-filter-dropdown',
            ],
        ],
        self::FILTERS_ITEM => [
            'el' => 'a',
            'attributes' => [
                'class' => 'mesour-open-modal',
                'role' => 'menuitem',
                'tabindex' => '-1',
                'href' => '#',
            ],
        ]
    ];

    public function attached(Mesour\Components\ComponentModel\IContainer $parent)
    {
        parent::attached($parent);

        $attributes = $this->getOption(self::WRAPPER, 'attributes');
        $attributes = array_merge($attributes, [
            'data-filter' => $this->getName(),
            'data-filter-name' => $this->getParent()->createLinkName(),
        ]);
        $this->setHtmlElement(
            Mesour\Components\Utils\Html::el(
                $this->getOption(self::WRAPPER, 'el'),
                $attributes
            )
        );
    }

    public function setValueTranslates(array $valueTranslates)
    {
        $this->valueTranslates = $valueTranslates;
        return $this;
    }

    public function setText($text)
    {
        $this->text = $this->getTranslator()->translate($text);
        return $this;
    }

    public function getText()
    {
        if (!$this->text) {
            $this->text = ucfirst($this->getName());
        }
        return $this->text;
    }

    public function setCheckers($hasCheckers = TRUE)
    {
        $this->hasCheckers = $hasCheckers;
        return $this;
    }

    public function getButtonPrototype()
    {
        return $this->button
            ? $this->button
            : ($this->button = Mesour\Components\Utils\Html::el(
                $this->getOption(self::BUTTON, 'el'),
                $this->getOption(self::BUTTON, 'attributes')
            ));
    }

    public function getWrapperPrototype()
    {
        return $this->getHtmlElement();
    }

    protected function getListUlPrototype(array $user_attributes = [])
    {
        $attributes = $this->getOption(self::LIST_UL, 'attributes');
        $attributes = array_merge($attributes, $user_attributes);
        return Mesour\Components\Utils\Html::el($this->getOption(self::LIST_UL, 'el'), $attributes);
    }

    protected function getListLiPrototype(array $userAttributes = [])
    {
        return Mesour\Components\Utils\Html::el(
            $this->getOption(self::LIST_LI, 'el'),
            array_merge($this->getOption(self::LIST_LI, 'attributes'), $userAttributes)
        );
    }

    protected function getFiltersItemPrototype(array $userAttributes = [])
    {
        return Mesour\Components\Utils\Html::el(
            $this->getOption(self::FILTERS_ITEM, 'el'),
            array_merge($this->getOption(self::FILTERS_ITEM, 'attributes'), $userAttributes)
        );
    }

    protected function createCustomFilterItem(Mesour\Components\Utils\Html & $sub_ul, $filter)
    {
        if (!isset($filter['type'])) {
            if (!isset($filter['name'])) {
                throw new Mesour\InvalidArgumentException('Key name is required in filter items.');
            }

            $sub_li = $this->getListLiPrototype();

            $filter_item = $this->getFiltersItemPrototype(isset($filter['attributes']) ? $filter['attributes'] : []);

            $filter_item->setText($this->getTranslator()->translate($filter['name']));

            $sub_li->add($filter_item);
        } elseif (is_array($filter['type'])) {
            if (!isset($filter['name'])) {
                throw new Mesour\InvalidArgumentException('Key name is required in filter items.');
            }

            $_ul = $this->getListUlPrototype();
            foreach ($filter['type'] as $_filter) {
                $this->createCustomFilterItem($_ul, $_filter);
            }
            $sub_li = $this->getListLiPrototype([
                'class' => 'dropdown-submenu'
            ]);
            $sub_li->add('<span tabindex="-1">' . $this->getTranslator()->translate($filter['name']) . '</span>');
            $sub_li->add($_ul);
        } elseif ($filter['type'] === 'divider') {
            $sub_li = $this->getListLiPrototype([
                'class' => 'divider'
            ]);
        } else {
            throw new Mesour\InvalidArgumentException('Unknown type ' . $filter['type'] . ' possible are only array, "divider" or NULL.');
        }
        $sub_ul->add($sub_li);
    }

    public function create()
    {
        parent::create();

        $wrapper = $this->getWrapperPrototype();

        $button = $this->getButtonPrototype();

        $this->onRender($this);

        $button->add('<span class="glyphicon glyphicon-ok" style="display: none;"></span>');
        $button->add('&nbsp;' . $this->getText() . '&nbsp;');
        $button->add('<span class="caret"></span>');

        if (count($this->valueTranslates)) {
            $wrapper->add(Mesour\Components\Utils\Html::el('input', [
                'value' => json_encode($this->valueTranslates),
                'type' => 'hidden',
                'data-translates' => 1
            ]));
        }

        $wrapper->add($button);

        $ul = $this->getListUlPrototype();

        if (count($this->filters) > 0) {
            $subMenu = $this->getListLiPrototype([
                'class' => 'dropdown-submenu'
            ]);

            $subMenu->add('<span>
                                <button type="button" class="btn btn-success btn-xs reset-filter" title="Reset filter" style="display: none;"><span class="glyphicon glyphicon-ok"></span><span class="glyphicon glyphicon-remove"></span></button>
                                <button type="button" class="btn btn-primary btn-xs mesour-open-modal edit-filter" title="Edit filter" style="display: none;"><span class="glyphicon glyphicon-pencil"></span></button>
                                ' . $this->getTranslator()->translate($this->filters_name) . '
                            </span>');

            $sub_ul = Mesour\Components\Utils\Html::el('ul', [
                'class' => 'dropdown-menu'
            ]);

            foreach ($this->filters as $filter) {
                $this->createCustomFilterItem($sub_ul, $filter);
            }

            $subMenu->add($sub_ul);

            $ul->add($subMenu);
        }

        if ($this->hasCheckers) {
            $ul->add($this->getListLiPrototype([
                'class' => 'divider'
            ]));

            $checkers_li = $this->getListLiPrototype();
            $inline_box = Mesour\Components\Utils\Html::el('div', ['class' => 'inline-box']);

            $search = Mesour\Components\Utils\Html::el('div', ['class' => 'search']);
            $search->add('<input type="text" class="form-control search-input" placeholder="' . $this->getTranslator()->translate('Search...') . '">');

            $checkers_li->add($inline_box);
            $inline_box->add($search);

            $box_inner = Mesour\Components\Utils\Html::el('div', ['class' => 'box-inner']);

            $checkers_li->add($box_inner);

            $inner_ul = Mesour\Components\Utils\Html::el('ul');
            $box_inner->add($inner_ul);

            $link_name = $this->createLinkName();
            $inner_ul->add('<li class="all-select-li">
                                <input type="checkbox" class="select-all" id="select-all-' . $link_name . '">
                                <label for="select-all-' . $link_name . '">' . $this->getTranslator()->translate('Select all') . '</label>
                            </li>
                            <li class="all-select-searched-li">
                                <input type="checkbox" class="select-all-searched" id="selected-' . $link_name . '">
                                <label for="selected-' . $link_name . '">' . $this->getTranslator()->translate('Select all searched') . '</label>
                            </li>');

            $ul->add($checkers_li);
        }

        $wrapper->add($ul);

        return $wrapper;
    }

}
