<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter\Sources;

use Doctrine;
use Mesour;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class DoctrineFilterSource extends Mesour\Sources\DoctrineSource implements IFilterSource
{

	public function applySimple($query, array $allowedColumns)
	{
		if (!$allowedColumns) {
			return;
		}

		$patterns = Mesour\Filter\Sources\Search\SearchPatternsHelper::getPatterns($query);
		$expr = $this->getQueryBuilder()->expr();
		foreach ($patterns as $index => $pattern) {
			$parameter = ':likePattern' . $index;
			$arguments = [];

			foreach ($allowedColumns as $allowedColumn) {
				$arguments[] = $expr->like($this->prefixColumn($allowedColumn), $parameter);
			}

			$this->where(
				call_user_func_array([$expr, 'orX'], $arguments),
				[$parameter => '%' . $pattern . '%']
			);
		}
	}

	public function applyCustom($columnName, array $custom, $type)
	{
		$values = [];
		$columnName = $this->prefixColumn($columnName);
		if (!empty($custom['how1']) && !empty($custom['val1'])) {
			$values[] = SQLHelper::createWherePairs($columnName, $custom['how1'], $custom['val1'], $type, ':val1');
		}
		if (!empty($custom['how2']) && !empty($custom['val2'])) {
			$values[] = SQLHelper::createWherePairs($columnName, $custom['how2'], $custom['val2'], $type, ':val2');
		}
		if (count($values) === 2) {
			if ($custom['operator'] === 'and') {
				$operator = 'and';
			} else {
				$operator = 'or';
			}
			$parameters = [
				'(' . $values[0][0] . ' ' . $operator . ' ' . $values[1][0] . ')',
				[
					substr($values[0][2], 1) => $values[0][1],
					substr($values[1][2], 1) => $values[1][1],
				],
			];
		} else {
			$parameters = [$values[0][0], [substr($values[0][2], 1) => $values[0][1]]];
		}
		call_user_func_array([$this, 'where'], $parameters);
		return $this;
	}

	public function applyCheckers($columnName, array $value, $type)
	{
		$parameters = SQLHelper::createWhereForCheckers($this->prefixColumn($columnName), $value, $type, true);
		call_user_func_array([$this, 'where'], $parameters);
		return $this;
	}

	public function fetchFullData($dateFormat = 'Y-m-d')
	{
		try {
			$this->lastFetchAllResult = $this->cloneQueryBuilder(true)
				->setMaxResults(null)
				->setFirstResult(null)
				->getQuery()
				->getResult();

			$allData = $this->fixResult(
				$this->getEntityArrayAsArrays($this->lastFetchAllResult)
			);

			foreach ($allData as &$currentData) {
				foreach ($currentData as $key => $val) {
					if ($val instanceof \DateTime) {
						$currentData[$key] = $val->format($dateFormat);
					}
				}
			}
			return $allData;
		} catch (Doctrine\ORM\NoResultException $e) {
			return [];
		}
	}

}
