<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter;

use Mesour;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Number extends FilterItem implements IFilterItem
{

    protected $filtersName = 'Number filters';

    protected $filters = array(
        array(
            'name' => 'Equal to',
            'attributes' => array(
                'data-type-first' => 'equal_to'
            )
        ), array(
            'name' => 'Not equal to',
            'attributes' => array(
                'data-type-first' => 'not_equal_to'
            )
        ), array(
            'type' => 'divider'
        ), array(
            'name' => 'Bigger than',
            'attributes' => array(
                'data-type-first' => 'bigger'
            )
        ), array(
            'name' => 'Bigger than or equal',
            'attributes' => array(
                'data-type-first' => 'bigger',
                'data-type-second' => 'equal_to',
                'data-operator' => 'or'
            )
        ), array(
            'name' => 'Smaller than',
            'attributes' => array(
                'data-type-first' => 'smaller'
            )
        ), array(
            'name' => 'Smaller than or equal',
            'attributes' => array(
                'data-type-first' => 'smaller',
                'data-type-second' => 'equal_to',
                'data-operator' => 'or'
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
        ),
    );

}
