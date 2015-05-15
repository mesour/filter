<?php
/**
 * Mesour Selection Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Filter;

use Mesour\Components;
use Mesour\UI\IFilterItem;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Selection Component
 */
class Text extends FilterItem implements IFilterItem
{

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
            'name' => 'Contains',
            'attributes' => array(
                'data-type-first' => 'equal'
            )
        ),array(
            'name' => 'Not contains',
            'attributes' => array(
                'data-type-first' => 'not_equal'
            )
        ),array(
            'type' => 'divider'
        ),array(
            'name' => 'Starts with',
            'attributes' => array(
                'data-type-first' => 'start_with'
            )
        ),array(
            'name' => 'Ends with',
            'attributes' => array(
                'data-type-first' => 'end_with'
            )
        ),array(
            'type' => 'divider'
        ),array(
            'name' => 'Custom filter',
            'attributes' => array(
            )
        ),
    );

    public function __construct($name = NULL, Components\IComponent $parent = NULL)
    {
        parent::__construct($name, $parent);
        $this->option = self::$defaults;
    }

}
