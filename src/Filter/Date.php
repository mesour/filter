<?php
/**
 * Mesour Filter Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Filter;

use Mesour\Components;
use Mesour\UI\IFilterItem;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Filter Component
 */
class Date extends FilterItem implements IFilterItem
{

    protected $filters_name = 'Date filters';

    protected $filters = array(
        array(
            'name' => 'Equal to',
            'attributes' => array(
                'data-type-first' => 'equal_to'
            )
        ), array(
            'type' => 'divider'
        ), array(
            'name' => 'Time period',
            'type' => array(
                array(
                    'name' => 'Last week',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{LAST_WEEK_FIRST}',
                        'data-second-value' => '{LAST_WEEK_SECOND}',
                    )
                ), array(
                    'name' => 'This week',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{THIS_WEEK_FIRST}',
                        'data-second-value' => '{THIS_WEEK_SECOND}',
                    )
                ), array(
                    'name' => 'Next week',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{NEXT_WEEK_FIRST}',
                        'data-second-value' => '{NEXT_WEEK_SECOND}',
                    )
                ), array(
                    'type' => 'divider'
                ), array(
                    'name' => 'Last month',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{LAST_MONTH_FIRST}',
                        'data-second-value' => '{LAST_MONTH_SECOND}',
                    )
                ), array(
                    'name' => 'This month',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{THIS_MONTH_FIRST}',
                        'data-second-value' => '{THIS_MONTH_SECOND}',
                    )
                ), array(
                    'name' => 'Next month',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{NEXT_MONTH_FIRST}',
                        'data-second-value' => '{NEXT_MONTH_SECOND}',
                    )
                ), array(
                    'type' => 'divider'
                ), array(
                    'name' => 'Last quarter',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{LAST_QUARTER_FIRST}',
                        'data-second-value' => '{LAST_QUARTER_SECOND}',
                    )
                ), array(
                    'name' => 'This quarter',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{THIS_QUARTER_FIRST}',
                        'data-second-value' => '{THIS_QUARTER_SECOND}',
                    )
                ), array(
                    'name' => 'Next quarter',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{NEXT_QUARTER_FIRST}',
                        'data-second-value' => '{NEXT_QUARTER_SECOND}',
                    )
                ), array(
                    'type' => 'divider'
                ), array(
                    'name' => 'Last year',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{LAST_YEAR_FIRST}',
                        'data-second-value' => '{LAST_YEAR_SECOND}',
                    )
                ), array(
                    'name' => 'This year',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{THIS_YEAR_FIRST}',
                        'data-second-value' => '{THIS_YEAR_SECOND}',
                    )
                ), array(
                    'name' => 'Next year',
                    'attributes' => array(
                        'data-type-first' => 'bigger',
                        'data-type-second' => 'smaller',
                        'data-first-value' => '{NEXT_YEAR_FIRST}',
                        'data-second-value' => '{NEXT_YEAR_SECOND}',
                    )
                )
            )
        ), array(
            'type' => 'divider'
        ), array(
            'name' => 'Yesterday',
            'attributes' => array(
                'data-type-first' => 'equal_to',
                'data-first-value' => '{YESTERDAY}',
            )
        ), array(
            'name' => 'Today',
            'attributes' => array(
                'data-type-first' => 'equal_to',
                'data-first-value' => '{TODAY}',
            )
        ), array(
            'name' => 'Tomorrow',
            'attributes' => array(
                'data-type-first' => 'equal_to',
                'data-first-value' => '{TOMORROW}',
            )
        ), array(
            'type' => 'divider'
        ), array(
            'name' => 'Beginning of the year',
            'attributes' => array(
                'data-type-first' => 'bigger',
                'data-first-value' => '{THIS_YEAR_FIRST}',
            )
        ), array(
            'type' => 'divider'
        ), array(
            'name' => 'Before',
            'attributes' => array(
                'data-type-first' => 'smaller',
            )
        ), array(
            'name' => 'After',
            'attributes' => array(
                'data-type-first' => 'bigger',
            )
        ), array(
            'name' => 'Between',
            'attributes' => array(
                'data-type-first' => 'bigger',
                'data-type-second' => 'smaller',
            )
        ), array(
            'type' => 'divider'
        ), array(
            'name' => 'Custom filter'
        )
    );

    public function __construct($name = NULL, Components\IContainer $parent = NULL)
    {
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
        $this->option[self::WRAPPER]['attributes']['data-type'] = 'date';
        $one_day = 60 * 60 * 24;
        $quarter = $this->dateQuarter();
        $data = array(
            'YESTERDAY' => date('Y-m-d', strtotime('yesterday midnight')),
            'TODAY' => date('Y-m-d', strtotime('today midnight')),
            'TOMORROW' => date('Y-m-d', strtotime('tomorrow midnight')),
            // ---
            'LAST_WEEK_FIRST' => date('Y-m-d', ($last_week_monday = strtotime('monday', strtotime('last week'))) - $one_day),
            'LAST_WEEK_SECOND' => date('Y-m-d', $last_week_monday + 7 * $one_day),
            'THIS_WEEK_FIRST' => date('Y-m-d', ($this_week_monday = strtotime("last monday midnight")) - $one_day),
            'THIS_WEEK_SECOND' => date('Y-m-d', $this_week_monday + 7 * $one_day),
            'NEXT_WEEK_FIRST' => date('Y-m-d', ($this_week_monday + 7 * $one_day) - $one_day),
            'NEXT_WEEK_SECOND' => date('Y-m-d', $this_week_monday + 14 * $one_day),
            // ---
            'LAST_MONTH_FIRST' => date('Y-m-d', strtotime(date('1-n-Y', $last_month = strtotime('last month'))) - $one_day),
            'LAST_MONTH_SECOND' => date('Y-m-d', strtotime(date('t-n-Y', $last_month)) + $one_day),
            'THIS_MONTH_FIRST' => date('Y-m-d', strtotime(date('1-n-Y')) - $one_day),
            'THIS_MONTH_SECOND' => date('Y-m-d', strtotime(date('t-n-Y')) + $one_day),
            'NEXT_MONTH_FIRST' => date('Y-m-d', strtotime(date('1-n-Y', $next_month = strtotime('next month'))) - $one_day),
            'NEXT_MONTH_SECOND' => date('Y-m-d', strtotime(date('t-n-Y', $next_month)) + $one_day),
            // ---
            'LAST_QUARTER_FIRST' => date('Y-m-d', $this->getStartTimestampForQuarter($quarter - 1 < 1 ? 4 : $quarter - 1, $quarter - 1 < 1 ? date('Y', strtotime('last year')) : date('Y')) - $one_day),
            'LAST_QUARTER_SECOND' => date('Y-m-d', $this->getEndTimestampForQuarter($quarter - 1 < 1 ? 4 : $quarter - 1, $quarter - 1 < 1 ? date('Y', strtotime('last year')) : date('Y')) + $one_day),
            'THIS_QUARTER_FIRST' => date('Y-m-d', $this->getStartTimestampForQuarter($quarter) - $one_day),
            'THIS_QUARTER_SECOND' => date('Y-m-d', $this->getEndTimestampForQuarter($quarter) + $one_day),
            'NEXT_QUARTER_FIRST' => date('Y-m-d', $this->getStartTimestampForQuarter($quarter + 1 > 4 ? 1 : $quarter + 1, $quarter + 1 > 4 ? date('Y', strtotime('next year')) : date('Y')) - $one_day),
            'NEXT_QUARTER_SECOND' => date('Y-m-d', $this->getEndTimestampForQuarter($quarter + 1 > 4 ? 1 : $quarter + 1, $quarter + 1 > 4 ? date('Y', strtotime('next year')) : date('Y')) + $one_day),
            // ---
            'LAST_YEAR_FIRST' => date('Y-m-d', strtotime(date('1-1-Y', ($last_year = strtotime('last year')))) - $one_day),
            'LAST_YEAR_SECOND' => date('Y-m-d', strtotime(date('31-12-Y', $last_year)) + $one_day),
            'THIS_YEAR_FIRST' => date('Y-m-d', strtotime(date('1-1-Y')) - $one_day),
            'THIS_YEAR_SECOND' => date('Y-m-d', strtotime(date('31-12-Y')) + $one_day),
            'NEXT_YEAR_FIRST' => date('Y-m-d', strtotime(date('1-1-Y', strtotime('next year'))) - $one_day),
            'NEXT_YEAR_SECOND' => date('Y-m-d', strtotime(date('31-12-Y', strtotime('next year'))) + $one_day),
        );
        foreach ($this->filters as $_id => $filter) {
            if (isset($filter['type']) && is_array($filter['type'])) {
                foreach ($filter['type'] as $__id => $_filter) {
                    if (isset($_filter['attributes'])) {
                        foreach ($_filter['attributes'] as $key => $value) {
                            $this->filters[$_id]['type'][$__id]['attributes'][$key] = Components\Helper::parseValue($value, $data);
                        }
                    }
                }
            } elseif (!isset($filter['type'])) {
                if (isset($filter['attributes'])) {
                    foreach ($filter['attributes'] as $key => $value) {
                        $this->filters[$_id]['attributes'][$key] = Components\Helper::parseValue($value, $data);
                    }
                }
            }
        }
    }

    private function dateQuarter()
    {
        $thisMonth = (int)date('n');
        if ($thisMonth <= 3) return 1;
        if ($thisMonth <= 6) return 2;
        if ($thisMonth <= 9) return 3;
        return 4;
    }

    private function getEndTimestampForQuarter($quarter, $year = NULL)
    {
        $year = !$year ? date('Y') : $year;
        $quarter = (int)$quarter;
        switch ($quarter) {
            case 1:
                return strtotime($year . '-03-31');
            case 2:
                return strtotime($year . '-06-30');
            case 3:
                return strtotime($year . '-09-30');
            default:
                return strtotime($year . '-12-31');
        }
    }

    public function getStartTimestampForQuarter($quarter, $year = NULL)
    {
        $year = !$year ? date('Y') : $year;
        $quarter = (int)$quarter;
        switch ($quarter) {
            case 1:
                return strtotime($year . '-01-01');
            case 2:
                return strtotime($year . '-04-01');
            case 3:
                return strtotime($year . '-07-01');
            default:
                return strtotime($year . '-10-01');
        }
    }

}
