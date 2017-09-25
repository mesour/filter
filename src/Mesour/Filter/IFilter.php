<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter;

use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface IFilter extends Mesour\Components\Control\IAttributesControl
{

	/**
	 * @return array
	 */
	public function getValues();

	public function setSource(Mesour\Filter\Sources\IFilterSource $source);

	public function getSource();

	/**
	 * @param string $name
	 * @param null $text
	 * @param array $valueTranslates
	 * @return Mesour\Filter\Number
	 */
	public function addNumberFilter($name, $text = null, array $valueTranslates = []);

	/**
	 * @param string $name
	 * @param string|null $text
	 * @param array $valueTranslates
	 * @return Mesour\Filter\Text
	 */
	public function addTextFilter($name, $text = null, array $valueTranslates = []);

	/**
	 * @param string $name
	 * @param string|null $text
	 * @param array $valueTranslates
	 * @return Mesour\Filter\Date
	 */
	public function addDateFilter($name, $text = null, array $valueTranslates = []);

	/**
	 * @param string $name
	 * @param IFilterItem $filterItem
	 * @return IFilterItem
	 */
	public function addCustomFilter($name, IFilterItem $filterItem);

	/**
	 * @param string $name
	 * @param array $data
	 * @return IFilterItem
	 */
	public function getItem($name, $data = []);

	public function setCustomReference($column, array $data);

	/**
	 * @param string $name
	 * @param array $data
	 * @return Mesour\Components\Utils\Html
	 */
	public function renderItem($name, $data = []);

	/**
	 * @return Mesour\Components\Utils\Html
	 */
	public function createResetButton();

	public function renderResetButton();

	public function createHiddenInput($data = []);

	public function renderHiddenInput($data = []);

}
