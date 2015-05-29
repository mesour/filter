<?php
/**
 * Mesour Filter Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Filter Component
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