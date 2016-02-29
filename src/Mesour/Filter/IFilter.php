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
interface IFilter extends Mesour\Components\Control\IAttributesControl
{

    /**
     * @return array
     */
    public function getValues();

    public function setSource($source);

    public function getSource();

    /**
     * @param $name
     * @param null $text
     * @param array $valueTranslates
     * @return Mesour\Filter\Number
     */
    public function addNumberFilter($name, $text = NULL, array $valueTranslates = []);

    /**
     * @param $name
     * @param string|null $text
     * @param array $valueTranslates
     * @return Mesour\Filter\Text
     */
    public function addTextFilter($name, $text = NULL, array $valueTranslates = []);

    /**
     * @param $name
     * @param string|null $text
     * @param array $valueTranslates
     * @return Mesour\Filter\Date
     */
    public function addDateFilter($name, $text = NULL, array $valueTranslates = []);

    /**
     * @param $name
     * @param IFilterItem $filterItem
     * @return IFilterItem
     */
    public function addCustomFilter($name, IFilterItem $filterItem);

    /**
     * @param $name
     * @param array $data
     * @return IFilterItem
     */
    public function getItem($name, $data = []);

    public function setCustomReference($column, array $data);

    /**
     * @param $name
     * @param array $data
     * @return Mesour\Components\Utils\Html
     */
    public function renderItem($name, $data = []);

    /**
     * @return Mesour\Components\Utils\Html
     */
    public function createResetButton();

    public function renderResetButton();

    public function createHiddenInput($data = []);

    public function renderHiddenInput($data = []);

}