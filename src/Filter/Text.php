<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter;

use Mesour\Components;
use Mesour\UI\IFilterItem;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Text extends FilterItem implements IFilterItem
{

    protected $filters_name = 'Text filters';

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
            'name' => 'Contains',
            'attributes' => array(
                'data-type-first' => 'equal'
            )
        ), array(
            'name' => 'Not contains',
            'attributes' => array(
                'data-type-first' => 'not_equal'
            )
        ), array(
            'type' => 'divider'
        ), array(
            'name' => 'Starts with',
            'attributes' => array(
                'data-type-first' => 'start_with'
            )
        ), array(
            'name' => 'Ends with',
            'attributes' => array(
                'data-type-first' => 'end_with'
            )
        ), array(
            'type' => 'divider'
        ), array(
            'name' => 'Custom filter'
        ),
    );

    public function __construct($name = NULL, Components\IContainer $parent = NULL)
    {
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
    }

}
