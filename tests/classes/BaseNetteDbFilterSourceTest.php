<?php

namespace Mesour\Filter\Tests;

use Mesour\Filter\Sources\NetteDbFilterSource;
use Mesour\Sources;
use Nette\Database;


abstract class BaseNetteDbFilterSourceTest extends Sources\Tests\BaseNetteDbSourceTest
{

	public function testApplyCheckersText()
	{
		$source = new NetteDbFilterSource($this->user);

		DataSourceChecker::matchCheckersText($source, Database\Table\ActiveRow::class);
	}

	public function testApplyCheckersDate()
	{
		$source = new NetteDbFilterSource($this->user);

		DataSourceChecker::matchCheckersDate($source, Database\Table\ActiveRow::class);
	}

	public function testApplyCheckersRelated()
	{
		$selection = clone $this->user;
		$selection->select('user.*')
			->select('group.name group_name');

		$source = new NetteDbFilterSource($selection, [
			'group_name' => 'group.name',
		], $this->context);

		$source->setReference('group_name', 'group', 'name');

		DataSourceChecker::matchCheckersRelated($source, Database\Table\ActiveRow::class);
	}

	public function testApplyCustomText()
	{
		$source = new NetteDbFilterSource($this->user);

		DataSourceChecker::matchCustomText(clone $source, Database\Table\ActiveRow::class);
	}

	public function testApplyCustomDate()
	{
		$source = new NetteDbFilterSource($this->user);

		DataSourceChecker::matchCustomDate(clone $source, Database\Table\ActiveRow::class);
	}

	public function testApplyCustomRelated()
	{
		$selection = clone $this->user;
		$selection->select('user.*')
			->select('group.name group_name');

		$source = new NetteDbFilterSource($selection, [
			'group_name' => 'group.name',
		], $this->context);

		$source->setReference('group_name', 'group', 'name');

		DataSourceChecker::matchCustomRelated(clone $source, Database\Table\ActiveRow::class);
	}

}