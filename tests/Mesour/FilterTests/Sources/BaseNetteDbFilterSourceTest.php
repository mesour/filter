<?php

namespace Mesour\FilterTests\Sources;

use Mesour\Filter\Sources\NetteDbFilterSource;
use Mesour\Sources;
use Nette\Database;

abstract class BaseNetteDbFilterSourceTest extends Sources\Tests\BaseNetteDbSourceTest
{

	public function __construct($setConfigFiles = true)
	{
		$this->columnMapping = array_merge([
			'id' => 'users.id',
			'name' => 'users.name',
			'wallet_amount' => 'wallet.amount',
			'company_name' => ':user_companies.company.name',
			'address_city' => ':addresses.city',
		], $this->columnMapping);

		if ($setConfigFiles) {
			$this->configFile = __DIR__ . '/../../../config.php';
			$this->localConfigFile = __DIR__ . '/../../../config.local.php';
		}

		parent::__construct();
	}

	public function testApplySimple()
	{
		$source = $this->createJoinedSource();
		DataSourceChecker::matchSimple($source, Database\Table\ActiveRow::class);
	}

	public function testApplySimpleOneToOne()
	{
		$source = $this->createJoinedSource();
		DataSourceChecker::matchSimpleReference($source, Database\Table\ActiveRow::class);
	}

	public function testApplySimpleManyToOne()
	{
		$source = $this->createJoinedSource();
		DataSourceChecker::matchSimpleManyToOne($source, Database\Table\ActiveRow::class);
	}

	public function testApplySimpleManyToMany()
	{
		$source = $this->createJoinedSource();
		DataSourceChecker::matchSimpleManyToMany($source, Database\Table\ActiveRow::class);
	}

	public function testApplySimpleOneToMany()
	{
		$source = $this->createJoinedSource();
		DataSourceChecker::matchSimpleOneToMany($source, Database\Table\ActiveRow::class);
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

	protected function createJoinedSource()
	{
		$selection = clone $this->user;
		$selection->select('users.*')
			->select('group.name group_name')
			->select('group.type group_type');

		return new NetteDbFilterSource($this->tableName, 'id', $selection, $this->context, $this->columnMapping);
	}

}
