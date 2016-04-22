<?php

namespace Mesour\FilterTests\Sources;

use Mesour\Sources\Tests\DataSourceTestCase;
use Mesour\UI\Application;
use Nette\Caching\Storages\MemoryStorage;
use Nette\Database;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../../vendor/mesour/sources/tests/classes/Connection.php';
require_once __DIR__ . '/../../../vendor/mesour/sources/tests/classes/DatabaseFactory.php';
require_once __DIR__ . '/../../../vendor/mesour/sources/tests/classes/DataSourceTestCase.php';

class DefaultRenderTest extends DataSourceTestCase
{

	/** @var \Nette\Database\Connection */
	protected $connection;

	/** @var \Nette\Database\Context */
	protected $context;

	/** @var \Nette\Database\Table\Selection */
	protected $user;

	public function __construct($setConfigFiles = true)
	{
		if ($setConfigFiles) {
			$this->configFile = __DIR__ . '/../../config.php';
			$this->localConfigFile = __DIR__ . '/../../config.local.php';
		}

		parent::__construct();

		$this->connection = new Database\Connection(
			$this->baseConnection->getDsn(),
			$this->databaseFactory->getUserName(),
			$this->databaseFactory->getPassword()
		);

		$cacheMemoryStorage = new MemoryStorage();

		$structure = new Database\Structure($this->connection, $cacheMemoryStorage);
		$conventions = new Database\Conventions\DiscoveredConventions($structure);
		$this->context = new Database\Context($this->connection, $structure, $conventions, $cacheMemoryStorage);

		$this->user = $this->context->table('users');
	}

	public function testDefault()
	{
		// APPLICATION

		$application = new Application();

		$application->setRequest($_REQUEST);

		$application->run();

		// SELECTION

		$this->user->select('users.*')
			->select('group.name group_name')
			->select('group.type group_type')
			->select('group.date group_date');

		// SOURCE

		$source = new \Mesour\Filter\Sources\NetteDbFilterSource(
			'users',
			'id',
			$this->user,
			$this->context,
			[
				'group_name' => 'group.name',
				'group_type' => 'group.type',
				'group_date' => 'group.date',
			]
		);

		// FILTER

		$filter = new \Mesour\UI\Filter('test', $application);

		$filter->setSource($source);

		$filter->addTextFilter(
			'action',
			'Status',
			[
				0 => 'Inactive',
				1 => 'Active',
			]
		)->setMainFilter(false);

		$filter->setCustomReference(
			'action',
			[
				0 => 'Inactive',
				1 => 'Active',
			]
		);

		$filter->addTextFilter('name', 'Name');

		$filter->addNumberFilter('amount', 'Amount');

		$filter->addDateFilter('last_login', 'Last login');

		$filter->addTextFilter('group_name', 'Group name');

		$filter->addTextFilter('has_pro', 'Bool value');

		$filter->addDateFilter('timestamp', 'Timestamp');

		Assert::same(file_get_contents(__DIR__ . '/data/DefaultRenderTestOutput.html'), $filter->render());
	}

}

$test = new DefaultRenderTest();
$test->run();
