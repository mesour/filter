<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter\Sources;

use Mesour;
use Nette;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class NetteDbFilterSource extends Mesour\Sources\NetteDbTableSource implements IFilterSource
{

	public function applyCustom($columnName, array $custom, $type)
	{
		$values = [];
		$columnName = $this->prefixColumn($columnName);
		if (!empty($custom['how1']) && !empty($custom['val1'])) {
			$values[] = SQLHelper::createWherePairs($columnName, $custom['how1'], $custom['val1'], $type);
		}
		if (!empty($custom['how2']) && !empty($custom['val2'])) {
			$values[] = SQLHelper::createWherePairs($columnName, $custom['how2'], $custom['val2'], $type);
		}
		if (count($values) === 2) {
			if ($custom['operator'] === 'and') {
				$operator = 'AND';
			} else {
				$operator = 'OR';
			}
			$parameters = [
				'(' . $values[0][0] . ' ' . $operator . ' ' . $values[1][0] . ')',
				$values[0][1],
				$values[1][1],
			];
		} else {
			$parameters = [$values[0][0], $values[0][1]];
		}
		call_user_func_array([$this, 'where'], $parameters);
		return $this;
	}

	public function applyCheckers($columnName, array $value, $type)
	{
		$columnName = $this->prefixColumn($columnName);
		$parameters = SQLHelper::createWhereForCheckers($columnName, $value, $type);

		call_user_func_array([$this, 'where'], $parameters);
		return $this;
	}

	/**
	 * @param string $dateFormat
	 * @return Mesour\Sources\ArrayHash[]
	 */
	public function fetchFullData($dateFormat = 'Y-m-d')
	{
		$output = [];
		$selection = $this->getSelection(false, false);
		$this->lastFetchAllResult = [];
		foreach ($selection as $data) {
			/** @var Nette\Database\Table\ActiveRow $data */
			$this->lastFetchAllResult[] = $data;
			$currentData = $data->toArray();
			foreach ($currentData as $key => $val) {
				if ($val instanceof \DateTime) {
					$currentData[$key] = $val->format($dateFormat);
				}
			}
			$output[] = $this->makeArrayHash($currentData);
		}
		return $output;
	}

}
