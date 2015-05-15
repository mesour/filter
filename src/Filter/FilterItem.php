<?php
/**
 * Mesour Selection Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Filter;

use Mesour\Components;
use Mesour\UI\Control;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Selection Component
 */
abstract class FilterItem extends Control
{

    const WRAPPER = 'wrapper',
        LIST_UL = 'list-ul',
        LIST_LI = 'list-li',
        FILTERS_ITEM = 'filters-item',
        BUTTON = 'button';

    protected $option = array();

    /**
     * @var Components\Html
     */
    protected $button;

    /**
     * @var Components\Html
     */
    protected $wrapper;

    /**
     * @var Components\Html
     */
    protected $list_ul;

    protected $text;

    protected $filters = array();

    protected $filters_name = 'Filters';

    //public $onRender = array();

    static public $defaults = array(
        self::BUTTON => array(
            'el' => 'button',
            'attributes' => array(
                'class' => 'btn btn-default dropdown-toggle',
                'type' => 'button'
            )
        ),
        self::LIST_UL => array(
            'el' => 'ul',
            'attributes' => array(
                'class' => 'dropdown-menu',
                'role' => 'menu'
            )
        ),
        self::LIST_LI => array(
            'el' => 'li',
            'attributes' => array(
                'role' => 'presentation'
            )
        ),
        self::WRAPPER => array(
            'el' => 'div',
            'attributes' => array(
                'class' => 'dropdown filter-dropdown mesour-filter-dropdown',
            ),
        ),
        self::FILTERS_ITEM => array(
            'el' => 'a',
            'attributes' => array(
                'class' => 'mesour-open-modal',
                'role' => 'menuitem',
                'tabindex' => '-1',
                'href' => '#',
            ),
        )
    );

    public function __construct($name = NULL, Components\IComponent $parent = NULL)
    {
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
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

    public function getButtonPrototype()
    {
        $attributes = $this->option[self::BUTTON]['attributes'];
        return $this->button
            ? $this->button
            : ($this->button = Components\Html::el($this->option[self::BUTTON]['el'], $attributes));
    }

    public function getWrapperPrototype()
    {
        $attributes = $this->option[self::WRAPPER]['attributes'];
        $attributes = array_merge($attributes, array(
            'data-filter' => $this->getName(),
            'data-filter-name' => $this->getParent()->createLinkName(),
        ));
        return $this->wrapper
            ? $this->wrapper
            : ($this->wrapper = Components\Html::el($this->option[self::WRAPPER]['el'], $attributes));
    }

    protected function getListUlPrototype(array $user_attributes = array())
    {
        $attributes = $this->option[self::LIST_UL]['attributes'];
        $attributes = array_merge($attributes, $user_attributes);
        return Components\Html::el($this->option[self::LIST_UL]['el'], $attributes);
    }

    protected function getListLiPrototype(array $user_attributes = array())
    {
        return Components\Html::el($this->option[self::LIST_LI]['el'], array_merge($this->option[self::LIST_LI]['attributes'], $user_attributes));
    }

    protected function getFiltersItemPrototype(array $user_attributes = array())
    {
        return Components\Html::el($this->option[self::FILTERS_ITEM]['el'], array_merge($this->option[self::FILTERS_ITEM]['attributes'], $user_attributes));
    }

    protected function createCustomFilterItem(Components\Html & $sub_ul, $filter)
    {
        if (!isset($filter['type'])) {
            if (!isset($filter['name'])) {
                throw new Components\InvalidArgumentException('Key name is required in filter items.');
            }

            $sub_li = $this->getListLiPrototype();

            $filter_item = $this->getFiltersItemPrototype(isset($filter['attributes']) ? $filter['attributes'] : array());

            $filter_item->setText($this->getTranslator()->translate($filter['name']));

            $sub_li->add($filter_item);
        } elseif (is_array($filter['type'])) {
            if (!isset($filter['name'])) {
                throw new Components\InvalidArgumentException('Key name is required in filter items.');
            }

            $_ul = $this->getListUlPrototype();
            foreach($filter['type'] as $_filter) {
                $this->createCustomFilterItem($_ul, $_filter);
            }
            $sub_li = $this->getListLiPrototype(array(
                'class' => 'dropdown-submenu'
            ));
            $sub_li->add('<span tabindex="-1">' . $this->getTranslator()->translate($filter['name']) . '</span>');
            $sub_li->add($_ul);
        } elseif ($filter['type'] === 'divider') {
            $sub_li = $this->getListLiPrototype(array(
                'class' => 'divider'
            ));
        } else {
            throw new Components\InvalidArgumentException('Unknown type ' . $filter['type'] . ' possible are only array, "divider" or NULL.');
        }
        $sub_ul->add($sub_li);
    }

    public function create($data = array())
    {
        parent::create();

        $wrapper = $this->getWrapperPrototype();

        $button = $this->getButtonPrototype();

        $button->add('<span class="glyphicon glyphicon-ok" style="display: none;"></span>');
        $button->add('&nbsp;' . $this->getText() . '&nbsp;');
        $button->add('<span class="caret"></span>');

        $wrapper->add($button);

        $ul = $this->getListUlPrototype();

        if (count($this->filters) > 0) {
            $submenu = $this->getListLiPrototype(array(
                'class' => 'dropdown-submenu'
            ));

            $submenu->add('<span>
				<button type="button" class="btn btn-success btn-xs reset-filter" title="Reset filter" style="display: none;"><span class="glyphicon glyphicon-ok"></span><span class="glyphicon glyphicon-remove"></span></button>
				<button type="button" class="btn btn-primary btn-xs mesour-open-modal edit-filter" title="Edit filter" style="display: none;"><span class="glyphicon glyphicon-pencil"></span></button>
				'.$this->getTranslator()->translate($this->filters_name).'
			</span>');

            $sub_ul = Components\Html::el('ul', array(
                'class' => 'dropdown-menu'
            ));

            foreach ($this->filters as $filter) {
                $this->createCustomFilterItem($sub_ul, $filter);
            }

            $submenu->add($sub_ul);

            $ul->add($submenu);
        }

        $wrapper->add($ul);

        return $wrapper;
    }

}
