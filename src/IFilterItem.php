<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour\Components;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IFilterItem
{

    /**
     * @param bool $has_checkers
     * @return mixed
     */
    public function setCheckers($has_checkers = TRUE);

}