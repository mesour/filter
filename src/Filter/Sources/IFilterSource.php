<?php
/**
 * Mesour Filter Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Filter\Sources;

use Mesour\Components;
use Mesour\Sources\ISource;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Filter Component
 */
interface IFilterSource extends ISource
{

    public function fetchFullData();

    public function applyCheckers($column_name, array $value, $type);

    public function applyCustom($column_name, array $custom, $type);

}