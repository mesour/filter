<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter\Sources;

use Mesour;
use Mesour\ArrayManage\Searcher\Condition;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class ArrayFilterSource extends Mesour\Sources\ArraySource implements IFilterSource
{

    private function customFilter($how)
    {
        switch ($how) {
            case 'equal_to';
                return Condition::EQUAL;
            case 'not_equal_to';
                return Condition::NOT_EQUAL;
            case 'bigger';
                return Condition::BIGGER;
            case 'not_bigger';
                return Condition::NOT_BIGGER;
            case 'smaller';
                return Condition::SMALLER;
            case 'not_smaller';
                return Condition::NOT_SMALLER;
            case 'start_with';
                return Condition::STARTS_WITH;
            case 'not_start_with';
                return Condition::NOT_STARTS_WITH;
            case 'end_with';
                return Condition::ENDS_WITH;
            case 'not_end_with';
                return Condition::NOT_ENDS_WITH;
            case 'equal';
                return Condition::CONTAINS;
            case 'not_equal';
                return Condition::NOT_CONTAINS;
            default:
                throw new Mesour\UnexpectedValueException('Unexpected key for custom filtering.');
        }
    }

    public function applyCustom($columnName, array $custom, $type)
    {
        $values = [];
        if (!empty($custom['how1']) && !empty($custom['val1'])) {
            $values[] = $this->customFilter($custom['how1']);
        }
        if (!empty($custom['how2']) && !empty($custom['val2'])) {
            $values[] = $this->customFilter($custom['how2']);
        }
        if (count($values) === 2) {
            if ($custom['operator'] === 'and') {
                $operator = 'and';
            } else {
                $operator = 'or';
            }
        }
        foreach ($values as $key => $val) {
            $this->where($columnName, $custom['val' . ($key + 1)], $val, isset($operator) ? $operator : 'and');
        }
        return $this;
    }

    public function applyCheckers($columnName, array $value, $type)
    {
        foreach ($value as $val) {
            $val = (string)$val;
            $this->where($columnName, $val, Condition::EQUAL, 'or');
        }
        return $this;
    }

    /**
     * @param string $dateFormat
     * @return Mesour\Sources\ArrayHash[]
     */
    public function fetchFullData($dateFormat = 'Y-m-d')
    {
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

}
