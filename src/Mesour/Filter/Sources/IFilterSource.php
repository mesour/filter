<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter\Sources;

use Mesour\Components;
use Mesour\Sources\ISource;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IFilterSource extends ISource
{

    public function fetchFullData();

    public function applyCheckers($column_name, array $value, $type);

    public function applyCustom($column_name, array $custom, $type);

}