<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method null onFilter(SimpleFilter $simpleFilter)
 * @method null onRender(SimpleFilter $simpleFilter)
 */
class SimpleFilter extends Mesour\Components\Control\AttributesControl implements Mesour\Filter\ISimpleFilter
{

	use Mesour\Icon\HasIcon;

	const WRAPPER = 'wrapper';
	const RIGHT_GROUP = 'rightGroup';

	static public $maxCheckboxCount = 1000;

	/**
	 * @var Mesour\Filter\Sources\IFilterSource
	 */
	private $source;

	private $isSourceUsed = false;

	/** @var Mesour\Components\Session\ISessionSection */
	private $privateSession;

	private $allowedColumns = [];

	/** @var Mesour\Components\Utils\Html */
	private $rightGroup;

	public $onFilter = [];

	public $onRender = [];

	public $defaults = [
		self::WRAPPER => [
			'el' => 'div',
			'attributes' => [
				'class' => 'mesour-simple-filter',
			],
		],
		self::RIGHT_GROUP => [
			'el' => 'div',
			'attributes' => [
				'class' => 'input-group',
			],
		],
	];

	public function __construct($name = null, Mesour\Components\ComponentModel\IContainer $parent = null)
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

		$this->startPrivateSession(true);

		return $this;
	}

	public function addColumn($name)
	{
		$this->allowedColumns[] = $name;
	}

	/**
	 * @param string[] $allowedColumns
	 * @return static
	 */
	public function setAllowedColumns(array $allowedColumns)
	{
		$this->allowedColumns = $allowedColumns;
		return $this;
	}

	/**
	 * @param Mesour\Filter\Sources\IFilterSource $source
	 * @return $this
	 * @throws Mesour\InvalidStateException
	 * @throws Mesour\InvalidArgumentException
	 */
	public function setSource(Mesour\Filter\Sources\IFilterSource $source)
	{
		if ($this->isSourceUsed) {
			throw new Mesour\InvalidStateException('Cannot change source after using them.');
		}
		if ($source instanceof Mesour\Filter\Sources\ArrayFilterSource) {
			throw new Mesour\NotSupportedException(
				sprintf('%s is not supported by SimpleFilter.', Mesour\Filter\Sources\ArrayFilterSource::class)
			);
		}
		$this->source = $source;

		return $this;
	}

	public function getFilterButton()
	{
		if (!isset($this['button'])) {
			$this['button'] = $button = new Button();
			$button->setIcon('search')
				->setType('primary');
			$button->setAttribute('href', '#');
			$button->setAttribute('data-simple-filter', $this->createLinkName());
		}
		return $this['button'];
	}

	public function createRightGroupPrototype()
	{
		return $this->rightGroup
			? $this->rightGroup
			: ($this->rightGroup = Mesour\Components\Utils\Html::el(
				$this->getOption(self::RIGHT_GROUP, 'el'),
				$this->getOption(self::RIGHT_GROUP, 'attributes')
			));
	}

	/**
	 * @param bool $need
	 * @return Mesour\Filter\Sources\IFilterSource
	 * @throws Mesour\InvalidStateException
	 */
	public function getSource($need = true)
	{
		if ($need && !$this->source) {
			throw new Mesour\InvalidStateException('Data source is not set.');
		}
		$this->isSourceUsed = true;

		return $this->source;
	}

	/**
	 * @param string $query
	 */
	public function handleApplyQuery($query)
	{
		$this->privateSession->set('query', (string) $query);
		$this->onFilter($this);
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->privateSession->get('query', '');
	}

	public function getWrapperPrototype()
	{
		return $this->getHtmlElement();
	}

	public function beforeCreate()
	{
		parent::beforeRender();
	}

	public function create()
	{
		parent::create();

		$wrapper = $this->getWrapperPrototype();

		$this->beforeCreate();

		$this->onRender($this);

		$rightGroup = $this->createRightGroupPrototype();
		$rightGroup->add(
			sprintf(
				'<input type="text" class="form-control" value="%s" data-simple-filter-query="%s">',
				$this->getQuery(),
				$this->createLinkName()
			)
		);

		$groupButton = Mesour\Components\Utils\Html::el('span', ['class' => 'input-group-btn']);
		$groupButton->add($this->getFilterButton());
		$rightGroup->add($groupButton);

		$wrapper->add($rightGroup);

		return $wrapper;
	}

	public function getAllowedColumns()
	{
		$dataStructure = $this->getSource()->getDataStructure();

		$allowedColumns = array_unique($this->allowedColumns);
		foreach ($allowedColumns as $allowedColumn) {
			if (!$dataStructure->hasColumn($allowedColumn)) {
				throw new Mesour\InvalidArgumentException(
					sprintf('Filter column `%s` not exist in data structure.', $allowedColumn)
				);
			}
		}

		if (count($allowedColumns) === 0) {
			foreach ($dataStructure->getColumns() as $column) {
				$allowedColumns[] = $column->getName();
			}
		}

		return $allowedColumns;
	}

	private function startPrivateSession($force = false)
	{
		if ($force || !$this->privateSession) {
			$this->privateSession = $this->getSession()->getSection($this->createLinkName());
		}
	}

}
