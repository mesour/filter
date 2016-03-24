<?php

namespace Mesour\Filter\Tests;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Mesour\Filter\Sources\DateFunction;
use Mesour\Filter\Sources\DoctrineFilterSource;
use Mesour\Sources;
use Nette\Database;

abstract class BaseDoctrineFilterSourceTest extends Sources\Tests\BaseDoctrineSourceTest
{

	public function __construct()
	{
		$this->configFile = __DIR__ . '/../config.php';
		$this->localConfigFile = __DIR__ . '/../config.local.php';

		//todo: test custom date

		parent::__construct();

		$this->entityManager->getConfiguration()
			->addCustomStringFunction('DATE', DateFunction::class);
	}

	public function testApplyCustomDate()
	{
		$source = $this->createDoctrineSource(Sources\Tests\Entity\User::class, $this->user);
		DataSourceChecker::matchCustomDate(clone $source, Sources\Tests\Entity\User::class);
	}

	public function testApplyCheckersText()
	{
		$source = $this->createDoctrineSource(Sources\Tests\Entity\User::class, $this->user);

		DataSourceChecker::matchCheckersText($source, Sources\Tests\Entity\User::class);
	}

	public function testApplyCheckersDate()
	{
		$source = $this->createDoctrineSource(Sources\Tests\Entity\User::class, $this->user);

		DataSourceChecker::matchCheckersDate($source, Sources\Tests\Entity\User::class);
	}

	public function testApplyCheckersRelated()
	{
		$queryBuilder = clone $this->user;
		$queryBuilder
			->join(Sources\Tests\Entity\Group::class, 'g', Join::WITH, 'u.group = g.id');

		$source = $this->createDoctrineSource(Sources\Tests\Entity\User::class, $queryBuilder);

		DataSourceChecker::matchCheckersRelated($source, Sources\Tests\Entity\User::class, 'group_name');
	}

	public function testApplyCustomText()
	{
		$source = $this->createDoctrineSource(Sources\Tests\Entity\User::class, $this->user);

		DataSourceChecker::matchCustomText(clone $source, Sources\Tests\Entity\User::class);
	}

	public function testApplyCustomRelated()
	{
		$queryBuilder = clone $this->user;
		$queryBuilder
			->join(Sources\Tests\Entity\Group::class, 'g', Join::WITH, 'u.group = g.id');

		$source = $this->createDoctrineSource(Sources\Tests\Entity\User::class, $queryBuilder);

		DataSourceChecker::matchCustomRelated(clone $source, Sources\Tests\Entity\User::class, 'group_name');
	}

	protected function createDoctrineSource($table, QueryBuilder $queryBuilder)
	{
		return new DoctrineFilterSource($table, 'id', $queryBuilder, $this->columnMapping);
	}

}
