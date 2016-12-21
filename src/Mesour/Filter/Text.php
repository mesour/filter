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
class Text extends FilterItem implements IFilterItem
{

	protected $filtersName = 'Text filters';

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
			'name' => 'Contains',
			'attributes' => [
				'data-type-first' => 'equal',
			],
		],
		[
			'name' => 'Not contains',
			'attributes' => [
				'data-type-first' => 'not_equal',
			],
		],
		[
			'type' => 'divider',
		],
		[
			'name' => 'Starts with',
			'attributes' => [
				'data-type-first' => 'start_with',
			],
		],
		[
			'name' => 'Ends with',
			'attributes' => [
				'data-type-first' => 'end_with',
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
