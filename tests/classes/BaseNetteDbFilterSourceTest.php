<?php

namespace Mesour\Filter\Tests;

use Mesour\Filter\Sources\NetteDbFilterSource;
use Mesour\Sources;
use Nette\Database;


abstract class BaseNetteDbFilterSourceTest extends Sources\Tests\BaseNetteDbSourceTest
{

    public function testApplyCheckersText()
    {
        $source = new NetteDbFilterSource($this->user, $this->tableName);

        DataSourceChecker::matchCheckersText($source, Database\Table\ActiveRow::class);
    }

    public function testApplyCheckersDate()
    {
        $source = new NetteDbFilterSource($this->user, $this->tableName);

        DataSourceChecker::matchCheckersDate($source, Database\Table\ActiveRow::class);
    }

    public function testApplyCheckersRelated()
    {
        $source = new NetteDbFilterSource($this->user, $this->tableName, $this->context);

        $source->setRelated('group', 'group_id', 'name', 'group_name');

        DataSourceChecker::matchCheckersRelated($source, Database\Table\ActiveRow::class);
    }

    public function testApplyCustomText()
    {
        $source = new NetteDbFilterSource($this->user, $this->tableName);

        DataSourceChecker::matchCustomText(clone $source, Database\Table\ActiveRow::class);
    }

    public function testApplyCustomDate()
    {
        $source = new NetteDbFilterSource($this->user, $this->tableName);

        DataSourceChecker::matchCustomDate(clone $source, Database\Table\ActiveRow::class);
    }

    public function testApplyCustomRelated()
    {
        $source = new NetteDbFilterSource($this->user, $this->tableName, $this->context);

        $source->setRelated('group', 'group_id', 'name', 'group_name');

        DataSourceChecker::matchCustomRelated(clone $source, Database\Table\ActiveRow::class);
    }

}