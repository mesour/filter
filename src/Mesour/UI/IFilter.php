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
interface IFilter extends Components\IContainer
{

    /**
     * @return array
     */
    public function getValues();

    public function setSource($source);

    public function getSource();

    /**
     * @param $name
     * @param string|null $text
     * @return \Mesour\Filter\Number
     */
    public function addNumberFilter($name, $text = NULL);

    /**
     * @param $name
     * @param string|null $text
     * @return \Mesour\Filter\Text
     */
    public function addTextFilter($name, $text = NULL);

    /**
     * @param $name
     * @param string|null $text
     * @return \Mesour\Filter\Date
     */
    public function addDateFilter($name, $text = NULL);

    /**
     * @param $name
     * @param IFilterItem $filterItem
     * @return IFilterItem
     */
    public function addCustomFilter($name, IFilterItem $filterItem);

    public function createItem($name, $data = array());

    public function renderItem($name, $data = array());

    public function createResetButton();

    public function renderResetButton();

    public function createHiddenInput($data = array());

    public function renderHiddenInput($data = array());

    public function create($data = array());

}