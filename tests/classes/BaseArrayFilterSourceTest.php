<?php

namespace Mesour\Filter\Tests;

use Mesour\Filter\Sources\ArrayFilterSource;
use Mesour\Sources;
use Nette\Database;

abstract class BaseArrayFilterSourceTest extends Sources\Tests\BaseArraySourceTest
{

	public function __construct()
	{
		$this->configFile = __DIR__ . '/../config.php';
		$this->localConfigFile = __DIR__ . '/../config.local.php';

		parent::__construct();
	}

	public function testApplyCheckersText()
	{
		$source = new ArrayFilterSource('users', 'id', self::$user);

		DataSourceChecker::matchCheckersText($source, Sources\ArrayHash::class);
	}

	public function testApplyCheckersDate()
	{
		$source = new ArrayFilterSource('users', 'id', self::$user);

		$source->getDataStructure()
			->addDate('last_login');

		DataSourceChecker::matchCheckersDate($source, Sources\ArrayHash::class);
	}

	public function testApplyCheckersRelated()
	{
		$source = new ArrayFilterSource('users', 'id', self::$user, $this->relations);

		$source->getDataStructure()
			->addOneToOne('group_name', 'groups', 'name');

		$source->joinField('groups', 'group_id', 'name', 'group_name');

		DataSourceChecker::matchCheckersRelated($source, Sources\ArrayHash::class);
	}

	public function testApplyCustomText()
	{
		$source = new ArrayFilterSource('users', 'id', self::$user);

		DataSourceChecker::matchCustomText(clone $source, Sources\ArrayHash::class);
	}

	public function testApplyCustomDate()
	{
		$source = new ArrayFilterSource('users', 'id', self::$user);

		DataSourceChecker::matchCustomDate(clone $source, Sources\ArrayHash::class);
	}

	public function testApplyCustomRelated()
	{
		$source = new ArrayFilterSource('users', 'id', self::$user, $this->relations);

		$source->getDataStructure()
			->addOneToOne('group_name', 'groups', 'name');

		$source->joinField('groups', 'group_id', 'name', 'group_name');

		DataSourceChecker::matchCustomRelated(clone $source, Sources\ArrayHash::class);
	}

}
