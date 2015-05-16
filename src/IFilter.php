<?php
/**
 * Mesour Selection Component
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Selection Component
 */
interface IFilter
{

    public function setSource($source);

    public function getSource();

    public function addFilterItem($name, IFilterItem $filterItem);

    public function createItem($name, $data = array());

    public function renderItem($name, $data = array());

    public function createResetButton();

    public function renderResetButton();

    public function createHiddenInput($data = array());

    public function renderHiddenInput($data = array());

}