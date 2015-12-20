<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IFilterItem extends Mesour\Components\Control\IAttributesControl
{

    /**
     * @param bool $has_checkers
     * @return mixed
     */
    public function setCheckers($has_checkers = TRUE);

    public function setText($text);

    /**
     * @return string
     */
    public function getText();

    /**
     * @return Mesour\Components\Utils\Html
     */
    public function getButtonPrototype();

}