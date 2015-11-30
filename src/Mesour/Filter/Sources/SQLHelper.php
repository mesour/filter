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
use Mesour\Sources\DoctrineSource;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class SQLHelper
{

    static public function createWherePairs($columnName, $how, $value, $type)
    {
        $output = [];
        $columnName = $type === 'date' ? ('DATE(' . $columnName . ')') : $columnName;
        switch ($how) {
            case 'equal_to';
                $output[] = $columnName . ' = ?';
                $output[] = $value;
                break;
            case 'not_equal_to';
                $output[] = $columnName . ' != ?';
                $output[] = $value;
                break;
            case 'bigger';
                $output[] = $columnName . ' > ?';
                $output[] = $value;
                break;
            case 'not_bigger';
                $output[] = $columnName . ' <= ?';
                $output[] = $value;
                break;
            case 'smaller';
                $output[] = $columnName . ' < ?';
                $output[] = $value;
                break;
            case 'not_smaller';
                $output[] = $columnName . ' >= ?';
                $output[] = $value;
                break;
            case 'start_with';
                $output[] = $columnName . ' LIKE ?';
                $output[] = $value . '%';
                break;
            case 'not_start_with';
                $output[] = $columnName . ' NOT LIKE ?';
                $output[] = $value . '%';
                break;
            case 'end_with';
                $output[] = $columnName . ' LIKE ?';
                $output[] = '%' . $value;
                break;
            case 'not_end_with';
                $output[] = $columnName . ' NOT LIKE ?';
                $output[] = '%' . $value;
                break;
            case 'equal';
                $output[] = $columnName . ' LIKE ?';
                $output[] = '%' . $value . '%';
                break;
            case 'not_equal';
                $output[] = $columnName . ' NOT LIKE ?';
                $output[] = '%' . $value . '%';
                break;
            default:
                throw new Components\InvalidArgumentException('Unexpected key for custom filtering.');
        }
        return $output;
    }

    static public function createWhereForCheckers($columnName, array $value, $type, $isDoctrine = FALSE)
    {
        $fixedValues = [];
        $hasNull = FALSE;
        foreach ($value as $val) {
            if (is_null($val)) {
                $hasNull = TRUE;
            } else {
                $fixedValues[] = $val;
            }
        }
        $fixedValues = array_unique($fixedValues);
        $paramName = self::fixParameterName($columnName) . '_source';

        if ($type === 'date') {
            $is_timestamp = TRUE;
            foreach ($value as $val) {
                if (!is_numeric($val)) {
                    $is_timestamp = FALSE;
                    break;
                }
            }

            if ($is_timestamp) {
                $where = '(';
                $i = 1;
                foreach ($value as $val) {
                    $where .= '(' . $columnName . ' >= ' . (int)$val . ' AND ' . $columnName . ' <= ' . ((int)$val + 86398) . ')';
                    if ($i < count($value)) {
                        $where .= ' OR ';
                    }
                    $i++;
                }
                $where .= ')';
                return [$where];
            } else {
                if ($isDoctrine) {
                    $parameters = [$paramName => $fixedValues];
                    if ($hasNull) {
                        $columnName = '(DATE(' . $columnName . ') IN (:' . $paramName . ') OR ' . $columnName . ' IS NULL)';
                        return [$columnName, $parameters];
                    } else {
                        return ['DATE(' . $columnName . ') IN (:' . $paramName . ')', [$paramName => $fixedValues]];
                    }
                }
                return ['DATE(' . $columnName . ')', $value];
            }
        } else {
            if ($isDoctrine) {
                $parameters = [$paramName => $fixedValues];
                if ($hasNull) {
                    $columnName = '(' . $columnName . ' IN :' . $paramName . ' OR ' . $columnName . ' IS NULL)';
                    return [$columnName, $parameters];
                } else {
                    return [$columnName . ' IN (:' . $paramName . ')', [$paramName => $fixedValues]];
                }
            }
            if ($hasNull) {
                $columnName = '(' . $columnName . ' IN ? OR ' . $columnName . ' IS NULL)';
            }
            return [$columnName, $fixedValues];
        }
    }

    static private function fixParameterName($columnName)
    {
        return str_replace(['.', '-'], '', $columnName);
    }

}
