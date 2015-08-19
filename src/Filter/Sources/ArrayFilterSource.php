<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter\Sources;

use Mesour\ArrayManage\Searcher\Condition;
use Mesour\Components;
use Mesour\Sources\ArraySource;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class ArrayFilterSource extends ArraySource implements IFilterSource
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
                throw new Components\Exception('Unexpected key for custom filtering.');
        }
    }

    public function applyCustom($column_name, array $custom, $type)
    {
        $values = array();
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
            $this->where($column_name, $custom['val' . ($key + 1)], $val, isset($operator) ? $operator : 'and');
        }
        return $this;
    }

    public function applyCheckers($column_name, array $value, $type)
    {
        foreach ($value as $val) {
            $this->where($column_name, $val, Condition::EQUAL, 'or');
        }
        return $this;
    }

    public function fetchFullData($date_format = 'Y-m-d')
    {
        $output = array();
        foreach ($this->data_arr as $data) {
            foreach ($data as $key => $val) {
                if ($val instanceof \DateTime) {
                    $data[$key] = $val->format($date_format);
                }
            }
            $output[] = $data;
        }
        return $output;
    }

}
