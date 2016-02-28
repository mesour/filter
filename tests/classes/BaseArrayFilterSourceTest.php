<?php

namespace Mesour\Filter\Tests;

use Mesour\Filter\Sources\ArrayFilterSource;
use Mesour\Sources;
use Nette\Database;


abstract class BaseArrayFilterSourceTest extends Sources\Tests\BaseArraySourceTest
{

    public function testApplyCheckersText()
    {
        $source = new ArrayFilterSource(self::$user);

        DataSourceChecker::matchCheckersText($source, Sources\ArrayHash::class);
    }

    public function testApplyCheckersDate()
    {
        $source = new ArrayFilterSource(self::$user);

        $source->setStructure([
            'last_login' => 'date',
        ]);

        DataSourceChecker::matchCheckersDate($source, Sources\ArrayHash::class);
    }

    public function testApplyCheckersRelated()
    {
        $source = new ArrayFilterSource(self::$user, $this->relations);

        $source->join('group', 'group_id', 'name', 'group_name');

        DataSourceChecker::matchCheckersRelated($source, Sources\ArrayHash::class);
    }

    public function testApplyCustomText()
    {
        $source = new ArrayFilterSource(self::$user);

        DataSourceChecker::matchCustomText(clone $source, Sources\ArrayHash::class);
    }

    public function testApplyCustomDate()
    {
        $source = new ArrayFilterSource(self::$user);

        DataSourceChecker::matchCustomDate(clone $source, Sources\ArrayHash::class);
    }

    public function testApplyCustomRelated()
    {
        $source = new ArrayFilterSource(self::$user, $this->relations);

        $source->join('group', 'group_id', 'name', 'group_name');

        DataSourceChecker::matchCustomRelated(clone $source, Sources\ArrayHash::class);
    }

}