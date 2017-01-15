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
 */
interface ISimpleFilter extends Mesour\Components\Control\IAttributesControl
{

	/**
	 * @return array
	 */
	public function getQuery();

	public function setSource(Mesour\Filter\Sources\IFilterSource $source);

	public function getSource();

	/**
	 * @return string[]
	 */
	public function getAllowedColumns();

	/**
	 * @return Mesour\UI\Button
	 */
	public function getFilterButton();

	/**
	 * @param string $name
	 * @return static
	 */
	public function addColumn($name);

	/**
	 * @param string[] $allowedColumns
	 * @return static
	 */
	public function setAllowedColumns(array $allowedColumns);

}
