<?php

namespace Mesour\Filter\Tests;

use Mesour\Filter\Sources\NetteDbFilterSource;
use Mesour\Sources;
use Nette\Database;

abstract class BaseNetteDbFilterSourceTest extends Sources\Tests\BaseNetteDbSourceTest
{

	public function __construct()
	{
		$this->configFile = __DIR__ . '/../config.php';
		$this->localConfigFile = __DIR__ . '/../config.local.php';

		parent::__construct();
	}

	public function testApplyCheckersText()
	{
		$source = new NetteDbFilterSource($this->tableName, 'id', $this->user, $this->context);

		DataSourceChecker::matchCheckersText($source, Database\Table\ActiveRow::class);
	}

	public function testApplyCheckersDate()
	{
		$source = new NetteDbFilterSource($this->tableName, 'id', $this->user, $this->context);

		DataSourceChecker::matchCheckersDate($source, Database\Table\ActiveRow::class);
	}

	public function testApplyCheckersRelated()
	{
		$selection = clone $this->user;
		$selection->select('users.*')
			->select('group.name group_name');

		$source = new NetteDbFilterSource($this->tableName, 'id', $selection, $this->context, $this->columnMapping);

		DataSourceChecker::matchCheckersRelated($source, Database\Table\ActiveRow::class);
	}

	public function testApplyCustomText()
	{
		$source = new NetteDbFilterSource($this->tableName, 'id', $this->user, $this->context);

		DataSourceChecker::matchCustomText(clone $source, Database\Table\ActiveRow::class);
	}

	public function testApplyCustomDate()
	{
		$source = new NetteDbFilterSource($this->tableName, 'id', $this->user, $this->context);

		DataSourceChecker::matchCustomDate(clone $source, Database\Table\ActiveRow::class);
	}

	public function testApplyCustomRelated()
	{
		$selection = clone $this->user;
		$selection->select('users.*')
			->select('group.name group_name');

		$source = new NetteDbFilterSource($this->tableName, 'id', $selection, $this->context, $this->columnMapping);

		DataSourceChecker::matchCustomRelated(clone $source, Database\Table\ActiveRow::class);
	}

}
