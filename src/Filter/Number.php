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
class Number extends FilterItem implements IFilterItem
{

    protected $filters_name = 'Number filters';

    protected $filters = array(
        array(
            'name' => 'Equal to',
            'attributes' => array(
                'data-type-first' => 'equal_to'
            )
        ),array(
            'name' => 'Not equal to',
            'attributes' => array(
                'data-type-first' => 'not_equal_to'
            )
        ),array(
            'type' => 'divider'
        ),array(
            'name' => 'Bigger than',
            'attributes' => array(
                'data-type-first' => 'bigger'
            )
        ),array(
            'name' => 'Bigger than or equal',
            'attributes' => array(
                'data-type-first' => 'bigger',
                'data-type-second' => 'equal_to',
                'data-operator' => 'or'
            )
        ),array(
            'name' => 'Smaller than',
            'attributes' => array(
                'data-type-first' => 'smaller'
            )
        ),array(
            'name' => 'Smaller than or equal',
            'attributes' => array(
                'data-type-first' => 'smaller',
                'data-type-second' => 'equal_to',
                'data-operator' => 'or'
            )
        ),array(
            'name' => 'Between',
            'attributes' => array(
                'data-type-first' => 'bigger',
                'data-type-second' => 'smaller',
            )
        ),array(
            'type' => 'divider'
        ),array(
            'name' => 'Custom filter'
        ),
    );

    public function __construct($name = NULL, Components\IComponent $parent = NULL)
    {
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
    }

}
