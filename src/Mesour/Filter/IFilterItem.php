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
interface IFilterItem extends Mesour\Components\Control\IAttributesControl
{

    public function setText($text);

    /**
     * @return string
     */
    public function getText();

    /**
     * @return Mesour\Components\Utils\Html
     */
    public function getButtonPrototype();

    /**
     * @param bool|TRUE $hasCheckers
     * @return mixed
     */
    public function setCheckers($hasCheckers = TRUE);

    /**
     * @param bool|TRUE $hasMainFilter
     * @return mixed
     */
    public function setMainFilter($hasMainFilter = TRUE);

}