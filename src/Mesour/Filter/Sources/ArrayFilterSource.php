<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter\Sources;

use Mesour;
use Mesour\ArrayManage\Searcher\Condition;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class ArrayFilterSource extends Mesour\Sources\ArraySource implements IFilterSource
{

	private $dateFormat = 'Y-m-d';

	public function setDateFormat($dateFormat)
	{
		$this->dateFormat = $dateFormat;

		return $this;
	}

	public function applySimple($query, array $allowedColumns)
	{
		throw new Mesour\NotImplementedException();
	}

	public function applyCustom($columnName, array $custom, $type)
	{
		$values = [];
		if (!empty($custom['how1']) && !empty($custom['val1'])) {
			$values[] = $this->customFilter($custom['how1'], $type);
		}
		if (!empty($custom['how2']) && !empty($custom['val2'])) {
			$values[] = $this->customFilter($custom['how2'], $type);
		}
		if (count($values) === 2) {
			if ($custom['operator'] === 'and') {
				$operator = 'and';
			} else {
				$operator = 'or';
			}
		}
		foreach ($values as $key => $val) {
			$this->where(
				$columnName,
				$type === self::TYPE_DATE ? $this->fixDate($custom['val' . ($key + 1)]) : $custom['val' . ($key + 1)],
				$val,
				isset($operator) ? $operator : 'and'
			);
		}

		return $this;
	}

	public function applyCheckers($columnName, array $value, $type)
	{
		foreach ($value as $val) {
			$val = (string) $val;
			if ($type === self::TYPE_DATE) {
				$this->where($columnName, $val, function ($actual, $expected) {
					if (is_numeric($expected)) {
						$expected = date($this->dateFormat, $expected);
					}
					if (is_numeric($actual)) {
						$actual = date($this->dateFormat, $actual);
					}

					return $expected === $actual;
				}, 'or');
			} else {
				$this->where($columnName, $val, Condition::EQUAL, 'or');
			}
		}

		return $this;
	}

	/**
	 * @param string $dateFormat
	 * @return Mesour\Sources\ArrayHash[]
	 */
	public function fetchFullData($dateFormat = null)
	{
		if (is_null($dateFormat)) {
			$dateFormat = $this->dateFormat;
		}
		$output = [];
		foreach ($this->dataArr as $data) {
			foreach ($data as $key => $val) {
				if ($val instanceof \DateTime) {
					$data[$key] = $val->format($dateFormat);
				}
			}
			$output[] = $this->makeArrayHash($data);
		}
		$this->lastFetchAllResult = $output;

		return $output;
	}

	private function customFilter($how, $type)
	{
		if ($how === 'equal_to') {
			if ($type === self::TYPE_DATE) {
				return function ($actual, $expected) {
					$expected = date($this->dateFormat, $this->fixDate($expected));
					$actual = date($this->dateFormat, $this->fixDate($actual));
					return $expected === $actual;
				};
			} else {
				return Condition::EQUAL;
			}
		} elseif ($how === 'not_equal_to') {
			if ($type === self::TYPE_DATE) {
				return function ($actual, $expected) {
					$expected = date($this->dateFormat, $this->fixDate($expected));
					$actual = date($this->dateFormat, $this->fixDate($actual));
					return $expected !== $actual;
				};
			} else {
				return Condition::NOT_EQUAL;
			}
		} elseif ($how === 'bigger') {
			if ($type === self::TYPE_DATE) {
				return function ($actual, $expected) {
					$expected = date($this->dateFormat, $this->fixDate($expected));
					$actual = date($this->dateFormat, $this->fixDate($actual));
					return $expected < $actual;
				};
			} else {
				return Condition::BIGGER;
			}
		} elseif ($how === 'not_bigger') {
			if ($type === self::TYPE_DATE) {
				return function ($actual, $expected) {
					$expected = date($this->dateFormat, $this->fixDate($expected));
					$actual = date($this->dateFormat, $this->fixDate($actual));
					return $expected >= $actual;
				};
			} else {
				return Condition::NOT_BIGGER;
			}
		} elseif ($how === 'smaller') {
			if ($type === self::TYPE_DATE) {
				return function ($actual, $expected) {
					$expected = date($this->dateFormat, $this->fixDate($expected));
					$actual = date($this->dateFormat, $this->fixDate($actual));
					return $expected > $actual;
				};
			} else {
				return Condition::SMALLER;
			}
		} elseif ($how === 'not_smaller') {
			if ($type === self::TYPE_DATE) {
				return function ($actual, $expected) {
					$expected = date($this->dateFormat, $this->fixDate($expected));
					$actual = date($this->dateFormat, $this->fixDate($actual));
					return $expected <= $actual;
				};
			} else {
				return Condition::NOT_SMALLER;
			}
		} elseif ($how === 'start_with') {
			return Condition::STARTS_WITH;
		} elseif ($how === 'not_start_with') {
			return Condition::NOT_STARTS_WITH;
		} elseif ($how === 'end_with') {
			return Condition::ENDS_WITH;
		} elseif ($how === 'not_end_with') {
			return Condition::NOT_ENDS_WITH;
		} elseif ($how === 'equal') {
			return Condition::CONTAINS;
		} elseif ($how === 'not_equal') {
			return Condition::NOT_CONTAINS;
		} else {
			throw new Mesour\UnexpectedValueException('Unexpected key for custom filtering.');
		}
	}

}
