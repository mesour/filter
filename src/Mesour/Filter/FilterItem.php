<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method null onRender(FilterItem $filterItem)
 */
abstract class FilterItem extends Mesour\Components\Control\AttributesControl
{

	const WRAPPER = 'wrapper';
	const LIST_UL = 'list-ul';
	const LIST_LI = 'list-li';
	const FILTERS_ITEM = 'filters-item';
	const BUTTON = 'button';

	protected $iconOk = 'check';

	protected $iconClose = 'times';

	protected $iconEdit = 'pencil';

	/** @var Mesour\Components\Utils\Html */
	protected $button;

	/** @var Mesour\Components\Utils\Html */
	protected $wrapper;

	/** @var Mesour\Components\Utils\Html */
	protected $list_ul;

	protected $text;

	protected $referenceSettings = false;

	protected $filters = [];

	protected $valueTranslates = [];

	protected $filtersName = 'Filters';

	protected $hasCheckers = false;

	protected $hasMainFilter = true;

	public $onRender = [];

	protected $defaults = [
		self::BUTTON => [
			'el' => 'button',
			'attributes' => [
				'class' => 'btn btn-default dropdown-toggle',
				'type' => 'button',
			],
		],
		self::LIST_UL => [
			'el' => 'ul',
			'attributes' => [
				'class' => 'dropdown-menu',
				'role' => 'menu',
			],
		],
		self::LIST_LI => [
			'el' => 'li',
			'attributes' => [
				'role' => 'presentation',
			],
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
		],
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

	public function setText($text, $translated = true)
	{
		$this->text = $translated ? $this->getTranslator()->translate($text) : $text;
		return $this;
	}

	public function setReferenceSettings($table)
	{
		$this->referenceSettings = $table;
		return $this;
	}

	public function getText()
	{
		if (!$this->text) {
			$this->text = ucfirst($this->getName());
		}
		return $this->text;
	}

	/**
	 * @param bool|TRUE $hasCheckers
	 * @return $this
	 */
	public function setCheckers($hasCheckers = true)
	{
		$this->hasCheckers = (bool) $hasCheckers;
		return $this;
	}

	/**
	 * @param bool|TRUE $hasMainFilter
	 * @return $this
	 */
	public function setMainFilter($hasMainFilter = true)
	{
		$this->hasMainFilter = (bool) $hasMainFilter;
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

	protected function getListUlPrototype(array $userAttributes = [])
	{
		$attributes = $this->getOption(self::LIST_UL, 'attributes');
		$attributes = array_merge($attributes, $userAttributes);

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

	protected function createCustomFilterItem(Mesour\Components\Utils\Html & $subUl, $filter)
	{
		if (!isset($filter['type'])) {
			if (!isset($filter['name'])) {
				throw new Mesour\InvalidArgumentException('Key name is required in filter items.');
			}

			$subLi = $this->getListLiPrototype();

			$filterItem = $this->getFiltersItemPrototype(isset($filter['attributes']) ? $filter['attributes'] : []);

			$filterItem->setText($this->getTranslator()->translate($filter['name']));

			$subLi->add($filterItem);
		} elseif (is_array($filter['type'])) {
			if (!isset($filter['name'])) {
				throw new Mesour\InvalidArgumentException('Key name is required in filter items.');
			}

			$currentUl = $this->getListUlPrototype();
			foreach ($filter['type'] as $currentFilter) {
				$this->createCustomFilterItem($currentUl, $currentFilter);
			}
			$subLi = $this->getListLiPrototype([
				'class' => 'dropdown-submenu',
			]);
			$subLi->add('<span tabindex="-1">' . $this->getTranslator()->translate($filter['name']) . '</span>');
			$subLi->add($currentUl);
		} elseif ($filter['type'] === 'divider') {
			$subLi = $this->getListLiPrototype([
				'class' => 'divider',
			]);
		} else {
			throw new Mesour\InvalidArgumentException('Unknown type ' . $filter['type'] . ' possible are only array, "divider" or NULL.');
		}
		$subUl->add($subLi);
	}

	public function create()
	{
		parent::create();

		$wrapper = $this->getWrapperPrototype();

		if ($this->referenceSettings) {
			$wrapper->addAttributes([
				'data-reference-settings' => $this->referenceSettings,
			]);
		}

		$button = $this->getButtonPrototype();

		$this->onRender($this);

		/** @var Mesour\UI\Icon $icon */
		$iconClass = $this->getIconClass();
		$icon = new $iconClass();

		$icon->setType($this->iconOk);
		$icon->setAttribute('style', 'display: none');
		$icon->setAttribute('data-filter-icon', 'check');
		$button->add($icon->render());
		$button->add('&nbsp;' . $this->getText() . '&nbsp;');
		$button->add('<span class="caret"></span>');
		$icon->setAttribute('style', false);

		if (count($this->valueTranslates)) {
			$wrapper->add(Mesour\Components\Utils\Html::el('input', [
				'value' => json_encode($this->valueTranslates),
				'type' => 'hidden',
				'data-translates' => 1,
			]));
		}

		$wrapper->add($button);

		$ul = $this->getListUlPrototype();

		if ($this->hasMainFilter && count($this->filters) > 0) {
			$subMenu = $this->getListLiPrototype([
				'class' => 'dropdown-submenu',
			]);

			$icons = '<span><button type="button" class="btn btn-success btn-xs reset-filter" title="';
			$icons .= $this->getTranslator()->translate('Reset filter') . '" style="display: none;">';

			$icon->setAttribute('data-filter-icon', 'has-custom');
			$icons .= $icon->render();

			$icon->setAttribute('style', 'display: none');
			$icon->setType($this->iconClose);
			$icon->setAttribute('data-filter-icon', 'reset');
			$icons .= $icon->render();
			$icon->setAttribute('style', false);

			$icons .= '</button><button type="button" class="btn btn-primary btn-xs mesour-open-modal edit-filter" title="';
			$icons .= $this->getTranslator()->translate('Edit filter') . '" style="display: none;">';

			$icon->setAttribute('data-filter-icon', 'edit');
			$icon->setType($this->iconEdit);
			$icons .= $icon->render();

			$icons .= sprintf('</button>%s</span>', $this->getTranslator()->translate($this->filtersName));

			$subMenu->add($icons);

			$subUl = Mesour\Components\Utils\Html::el('ul', [
				'class' => 'dropdown-menu',
			]);

			foreach ($this->filters as $filter) {
				$this->createCustomFilterItem($subUl, $filter);
			}

			$subMenu->add($subUl);

			$ul->add($subMenu);
		}

		if ($this->hasCheckers || $this->referenceSettings !== false) {
			if ($this->hasMainFilter) {
				$ul->add($this->getListLiPrototype([
					'class' => 'divider',
				]));
			}

			$checkersLi = $this->getListLiPrototype();
			$inlineBox = Mesour\Components\Utils\Html::el('div', ['class' => 'inline-box']);

			$search = Mesour\Components\Utils\Html::el('div', ['class' => 'search']);
			$search->add('<input type="text" class="form-control search-input" placeholder="' . $this->getTranslator()->translate('Search...') . '">');

			$checkersLi->add($inlineBox);
			$inlineBox->add($search);

			$boxInner = Mesour\Components\Utils\Html::el('div', ['class' => 'box-inner']);

			$checkersLi->add($boxInner);

			$innerUl = Mesour\Components\Utils\Html::el('ul');
			$boxInner->add($innerUl);

			$linkName = $this->createLinkName();
			$innerUl->add('<li class="all-select-li">
                                <input type="checkbox" class="select-all" id="select-all-' . $linkName . '">
                                <label for="select-all-' . $linkName . '">' . $this->getTranslator()->translate('Select all') . '</label>
                            </li>
                            <li class="all-select-searched-li">
                                <input type="checkbox" class="select-all-searched" id="selected-' . $linkName . '">
                                <label for="selected-' . $linkName . '">' . $this->getTranslator()->translate('Select all searched') . '</label>
                            </li>');

			$ul->add($checkersLi);
		}

		$wrapper->add($ul);

		return $wrapper;
	}

}
