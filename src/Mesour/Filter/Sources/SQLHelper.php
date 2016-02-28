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


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class SQLHelper
{

    static public function createWherePairs($columnName, $how, $value, $type, $wildcard = '?')
    {
        if ($type === IFilterSource::TYPE_DATE) {
            $value = date('Y-m-d', is_numeric($value) ? $value : strtotime($value));
            $columnName = 'DATE(' . $columnName . ')';
        }

        $output = [];
        $output[1] = $value;
        $output[2] = $wildcard;

        switch ($how) {
            case 'equal_to';
                $output[0] = $columnName . ' = ' . $wildcard;
                break;
            case 'not_equal_to';
                $output[0] = $columnName . ' != ' . $wildcard;
                break;
            case 'bigger';
                $output[0] = $columnName . ' > ' . $wildcard;
                break;
            case 'not_bigger';
                $output[0] = $columnName . ' <= ' . $wildcard;
                break;
            case 'smaller';
                $output[0] = $columnName . ' < ' . $wildcard;
                break;
            case 'not_smaller';
                $output[0] = $columnName . ' >= ' . $wildcard;
                break;
            case 'start_with';
                $output[0] = $columnName . ' LIKE ' . $wildcard;
                $output[1] = $value . '%';
                break;
            case 'not_start_with';
                $output[0] = $columnName . ' NOT LIKE ' . $wildcard;
                $output[1] = $value . '%';
                break;
            case 'end_with';
                $output[0] = $columnName . ' LIKE ' . $wildcard;
                $output[1] = '%' . $value;
                break;
            case 'not_end_with';
                $output[0] = $columnName . ' NOT LIKE ' . $wildcard;
                $output[1] = '%' . $value;
                break;
            case 'equal';
                $output[0] = $columnName . ' LIKE ' . $wildcard;
                $output[1] = '%' . $value . '%';
                break;
            case 'not_equal';
                $output[0] = $columnName . ' NOT LIKE ' . $wildcard;
                $output[1] = '%' . $value . '%';
                break;
            default:
                throw new Mesour\InvalidArgumentException('Unexpected key for custom filtering.');
        }

        return $output;
    }

    static public function createWhereForCheckers($columnName, array $value, $type, $isDoctrine = false)
    {
        $fixedValues = [];
        $hasNull = false;
        foreach ($value as $val) {
            if (is_null($val)) {
                $hasNull = true;
            } else {
                $fixedValues[] = $val;
            }
        }
        $fixedValues = array_unique($fixedValues);
        $paramName = self::fixParameterName($columnName) . '_source';

        if ($type === IFilterSource::TYPE_DATE) {
            $isTimestamp = true;
            foreach ($value as $val) {
                if (!is_numeric($val)) {
                    $isTimestamp = false;
                    break;
                }
            }

            if ($isTimestamp) {
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
