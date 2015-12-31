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
use Doctrine;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class DoctrineFilterSource extends Mesour\Sources\DoctrineSource implements IFilterSource
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
                $operator = 'and';
            } else {
                $operator = 'or';
            }
            $parameters = ['(' . $values[0][0] . ' ' . $operator . ' ' . $values[1][0] . ')', [$values[0][1], $values[1][1]]];
        } else {
            $parameters = [$values[0][0], [$values[0][1]]];
        }
        call_user_func_array([$this, 'where'], $parameters);
        return $this;
    }

    public function applyCheckers($columnName, array $value, $type)
    {
        $parameters = SQLHelper::createWhereForCheckers($this->prefixColumn($columnName), $value, $type, TRUE);
        call_user_func_array([$this, 'where'], $parameters);
        return $this;
    }

    public function fetchFullData($dateFormat = 'Y-m-d')
    {
        try {
            $this->lastFetchAllResult = $this->cloneQueryBuilder(TRUE)
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
