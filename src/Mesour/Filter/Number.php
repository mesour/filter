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
class Number extends FilterItem implements IFilterItem
{

	protected $filtersName = 'Number filters';

	protected $filters = [
		[
			'name' => 'Equal to',
			'attributes' => [
				'data-type-first' => 'equal_to',
			],
		],
		[
			'name' => 'Not equal to',
			'attributes' => [
				'data-type-first' => 'not_equal_to',
			],
		],
		[
			'type' => 'divider',
		],
		[
			'name' => 'Bigger than',
			'attributes' => [
				'data-type-first' => 'bigger',
			],
		],
		[
			'name' => 'Bigger than or equal',
			'attributes' => [
				'data-type-first' => 'bigger',
				'data-type-second' => 'equal_to',
				'data-operator' => 'or',
			],
		],
		[
			'name' => 'Smaller than',
			'attributes' => [
				'data-type-first' => 'smaller',
			],
		],
		[
			'name' => 'Smaller than or equal',
			'attributes' => [
				'data-type-first' => 'smaller',
				'data-type-second' => 'equal_to',
				'data-operator' => 'or',
			],
		],
		[
			'name' => 'Between',
			'attributes' => [
				'data-type-first' => 'bigger',
				'data-type-second' => 'smaller',
			],
		],
		[
			'type' => 'divider',
		],
		[
			'name' => 'Custom filter',
		],
	];

}
