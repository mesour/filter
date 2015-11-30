<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter\Sources;

use Mesour\Components;
use Mesour\Sources\DoctrineSource;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class DoctrineFilterSource extends DoctrineSource implements IFilterSource
{

    public function applyCustom($columnName, array $custom, $type)
    {
        $values = array();
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
            $parameters = array('(' . $values[0][0] . ' ' . $operator . ' ' . $values[1][0] . ')', [$values[0][1], $values[1][1]]);
        } else {
            $parameters = array($values[0][0], $values[0][1]);
        }
        call_user_func_array(array($this, 'where'), $parameters);
        return $this;
    }

    public function applyCheckers($columnName, array $value, $type)
    {
        $this->where($columnName, $value);
        return $this;
    }

    public function fetchFullData()
    {
        return $this->fixResult($this->cloneQueryBuilder(TRUE)
            ->setMaxResults(null)
            ->setFirstResult(null)
            ->getQuery()->getArrayResult());
    }

}
