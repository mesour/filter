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
        $output = array();
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

}
