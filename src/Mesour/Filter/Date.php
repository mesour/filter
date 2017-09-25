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
class Date extends FilterItem implements IFilterItem
{

	use Mesour\Components\DateTimeProvider\HasDateTimeProvider;

	protected $filtersName = 'Date filters';

	protected $filters = [
		[
			'name' => 'Equal to',
			'attributes' => [
				'data-type-first' => 'equal_to',
			],
		],
		[
			'type' => 'divider',
		],
		[
			'name' => 'Time period',
			'type' => [
				[
					'name' => 'Last week',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{LAST_WEEK_FIRST}',
						'data-second-value' => '{LAST_WEEK_SECOND}',
					],
				],
				[
					'name' => 'This week',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{THIS_WEEK_FIRST}',
						'data-second-value' => '{THIS_WEEK_SECOND}',
					],
				],
				[
					'name' => 'Next week',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{NEXT_WEEK_FIRST}',
						'data-second-value' => '{NEXT_WEEK_SECOND}',
					],
				],
				[
					'type' => 'divider',
				],
				[
					'name' => 'Last month',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{LAST_MONTH_FIRST}',
						'data-second-value' => '{LAST_MONTH_SECOND}',
					],
				],
				[
					'name' => 'This month',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{THIS_MONTH_FIRST}',
						'data-second-value' => '{THIS_MONTH_SECOND}',
					],
				],
				[
					'name' => 'Next month',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{NEXT_MONTH_FIRST}',
						'data-second-value' => '{NEXT_MONTH_SECOND}',
					],
				],
				[
					'type' => 'divider',
				],
				[
					'name' => 'Last quarter',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{LAST_QUARTER_FIRST}',
						'data-second-value' => '{LAST_QUARTER_SECOND}',
					],
				],
				[
					'name' => 'This quarter',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{THIS_QUARTER_FIRST}',
						'data-second-value' => '{THIS_QUARTER_SECOND}',
					],
				],
				[
					'name' => 'Next quarter',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{NEXT_QUARTER_FIRST}',
						'data-second-value' => '{NEXT_QUARTER_SECOND}',
					],
				],
				[
					'type' => 'divider',
				],
				[
					'name' => 'Last year',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{LAST_YEAR_FIRST}',
						'data-second-value' => '{LAST_YEAR_SECOND}',
					],
				],
				[
					'name' => 'This year',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{THIS_YEAR_FIRST}',
						'data-second-value' => '{THIS_YEAR_SECOND}',
					],
				],
				[
					'name' => 'Next year',
					'attributes' => [
						'data-type-first' => 'bigger',
						'data-type-second' => 'smaller',
						'data-first-value' => '{NEXT_YEAR_FIRST}',
						'data-second-value' => '{NEXT_YEAR_SECOND}',
					],
				],
			],
		],
		[
			'type' => 'divider',
		],
		[
			'name' => 'Yesterday',
			'attributes' => [
				'data-type-first' => 'equal_to',
				'data-first-value' => '{YESTERDAY}',
			],
		],
		[
			'name' => 'Today',
			'attributes' => [
				'data-type-first' => 'equal_to',
				'data-first-value' => '{TODAY}',
			],
		],
		[
			'name' => 'Tomorrow',
			'attributes' => [
				'data-type-first' => 'equal_to',
				'data-first-value' => '{TOMORROW}',
			],
		],
		[
			'type' => 'divider',
		],
		[
			'name' => 'Beginning of the year',
			'attributes' => [
				'data-type-first' => 'bigger',
				'data-first-value' => '{THIS_YEAR_FIRST}',
			],
		],
		[
			'type' => 'divider',
		],
		[
			'name' => 'Before',
			'attributes' => [
				'data-type-first' => 'smaller',
			],
		],
		[
			'name' => 'After',
			'attributes' => [
				'data-type-first' => 'bigger',
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

	public function __construct($name = null, Mesour\Components\ComponentModel\IContainer $parent = null)
	{
		parent::__construct($name, $parent);

		$attributes = $this->getOption(self::WRAPPER, 'attributes');
		$attributes['data-type'] = 'date';
		$this->setOption(self::WRAPPER, $attributes, 'attributes');
	}

	public function attached(Mesour\Components\ComponentModel\IContainer $parent)
	{
		parent::attached($parent);

		$oneDay = 60 * 60 * 24;
		$quarter = $this->dateQuarter();
		$data = [
			'YESTERDAY' => date('Y-m-d', strtotime('yesterday midnight', $this->getTime())),
			'TODAY' => date('Y-m-d', strtotime('today midnight', $this->getTime())),
			'TOMORROW' => date('Y-m-d', strtotime('tomorrow midnight', $this->getTime())),
			// ---
			'LAST_WEEK_FIRST' => date('Y-m-d', ($lastWeekMonday = strtotime('monday', strtotime('last week', $this->getTime()))) - $oneDay),
			'LAST_WEEK_SECOND' => date('Y-m-d', $lastWeekMonday + 7 * $oneDay),
			'THIS_WEEK_FIRST' => date('Y-m-d', ($thisWeekMonday = strtotime('last monday midnight', $this->getTime())) - $oneDay),
			'THIS_WEEK_SECOND' => date('Y-m-d', $thisWeekMonday + 7 * $oneDay),
			'NEXT_WEEK_FIRST' => date('Y-m-d', ($thisWeekMonday + 7 * $oneDay) - $oneDay),
			'NEXT_WEEK_SECOND' => date('Y-m-d', $thisWeekMonday + 14 * $oneDay),
			// ---
			'LAST_MONTH_FIRST' => date('Y-m-d', strtotime(date('1-n-Y', $lastMonth = strtotime('last month', $this->getTime()))) - $oneDay),
			'LAST_MONTH_SECOND' => date('Y-m-d', strtotime(date('t-n-Y', $lastMonth)) + $oneDay),
			'THIS_MONTH_FIRST' => date('Y-m-d', strtotime(date('1-n-Y', $this->getTime())) - $oneDay),
			'THIS_MONTH_SECOND' => date('Y-m-d', strtotime(date('t-n-Y', $this->getTime())) + $oneDay),
			'NEXT_MONTH_FIRST' => date('Y-m-d', strtotime(date('1-n-Y', $nextMonth = strtotime('next month', $this->getTime()))) - $oneDay),
			'NEXT_MONTH_SECOND' => date('Y-m-d', strtotime(date('t-n-Y', $nextMonth)) + $oneDay),
			// ---
			'LAST_QUARTER_FIRST' => date('Y-m-d', $this->getStartTimestampForQuarter($quarter - 1 < 1 ? 4 : $quarter - 1, $quarter - 1 < 1 ? date('Y', strtotime('last year', $this->getTime())) : date('Y', $this->getTime())) - $oneDay),
			'LAST_QUARTER_SECOND' => date('Y-m-d', $this->getEndTimestampForQuarter($quarter - 1 < 1 ? 4 : $quarter - 1, $quarter - 1 < 1 ? date('Y', strtotime('last year', $this->getTime())) : date('Y', $this->getTime())) + $oneDay),
			'THIS_QUARTER_FIRST' => date('Y-m-d', $this->getStartTimestampForQuarter($quarter) - $oneDay),
			'THIS_QUARTER_SECOND' => date('Y-m-d', $this->getEndTimestampForQuarter($quarter) + $oneDay),
			'NEXT_QUARTER_FIRST' => date('Y-m-d', $this->getStartTimestampForQuarter($quarter + 1 > 4 ? 1 : $quarter + 1, $quarter + 1 > 4 ? date('Y', strtotime('next year', $this->getTime())) : date('Y', $this->getTime())) - $oneDay),
			'NEXT_QUARTER_SECOND' => date('Y-m-d', $this->getEndTimestampForQuarter($quarter + 1 > 4 ? 1 : $quarter + 1, $quarter + 1 > 4 ? date('Y', strtotime('next year', $this->getTime())) : date('Y', $this->getTime())) + $oneDay),
			// ---
			'LAST_YEAR_FIRST' => date('Y-m-d', strtotime(date('1-1-Y', ($nextYear = strtotime('last year', $this->getTime())))) - $oneDay),
			'LAST_YEAR_SECOND' => date('Y-m-d', strtotime(date('31-12-Y', $nextYear)) + $oneDay),
			'THIS_YEAR_FIRST' => date('Y-m-d', strtotime(date('1-1-Y', $this->getTime())) - $oneDay),
			'THIS_YEAR_SECOND' => date('Y-m-d', strtotime(date('31-12-Y', $this->getTime())) + $oneDay),
			'NEXT_YEAR_FIRST' => date('Y-m-d', strtotime(date('1-1-Y', strtotime('next year', $this->getTime()))) - $oneDay),
			'NEXT_YEAR_SECOND' => date('Y-m-d', strtotime(date('31-12-Y', strtotime('next year', $this->getTime()))) + $oneDay),
		];
		foreach ($this->filters as $currentId => $filter) {
			if (isset($filter['type']) && is_array($filter['type'])) {
				foreach ($filter['type'] as $currentInnerId => $currentFilter) {
					if (isset($currentFilter['attributes'])) {
						foreach ($currentFilter['attributes'] as $key => $value) {
							$this->filters[$currentId]['type'][$currentInnerId]['attributes'][$key] = Mesour\Components\Utils\Helpers::parseValue($value, $data);
						}
					}
				}
			} elseif (!isset($filter['type'])) {
				if (isset($filter['attributes'])) {
					foreach ($filter['attributes'] as $key => $value) {
						$this->filters[$currentId]['attributes'][$key] = Mesour\Components\Utils\Helpers::parseValue($value, $data);
					}
				}
			}
		}
	}

	private function getTime()
	{
		return $this->getDateTimeProvider()->getDate()->getTimestamp();
	}

	private function dateQuarter()
	{
		$thisMonth = (int) date('n', $this->getTime());
		if ($thisMonth <= 3) {
			return 1;
		}
		if ($thisMonth <= 6) {
			return 2;
		}
		if ($thisMonth <= 9) {
			return 3;
		}
		return 4;
	}

	private function getEndTimestampForQuarter($quarter, $year = null)
	{
		$year = !$year ? date('Y', $this->getTime()) : $year;
		$quarter = (int) $quarter;
		switch ($quarter) {
			case 1:
				return strtotime($year . '-03-31', $this->getTime());
			case 2:
				return strtotime($year . '-06-30', $this->getTime());
			case 3:
				return strtotime($year . '-09-30', $this->getTime());
			default:
				return strtotime($year . '-12-31', $this->getTime());
		}
	}

	public function getStartTimestampForQuarter($quarter, $year = null)
	{
		$year = !$year ? date('Y', $this->getTime()) : $year;
		$quarter = (int) $quarter;
		switch ($quarter) {
			case 1:
				return strtotime($year . '-01-01', $this->getTime());
			case 2:
				return strtotime($year . '-04-01', $this->getTime());
			case 3:
				return strtotime($year . '-07-01', $this->getTime());
			default:
				return strtotime($year . '-10-01', $this->getTime());
		}
	}

}
