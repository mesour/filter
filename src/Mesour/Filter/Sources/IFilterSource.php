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
interface IFilterSource extends Mesour\Sources\ISource
{

    const TYPE_STRING = 'string';
    const TYPE_DATE = 'date';

    /**
     * @param string $dateFormat
     * @return Mesour\Sources\ArrayHash[]
     */
    public function fetchFullData($dateFormat = 'Y-m-d');

    public function applyCheckers($columnName, array $value, $type);

    public function applyCustom($columnName, array $custom, $type);

}