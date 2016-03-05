<?php

namespace Mesour\Filter\Tests;

use Nette\Database;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/Connection.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DatabaseFactory.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DataSourceTestCase.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/BaseDoctrineSourceTest.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/Entity/EmptyTable.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/Entity/Groups.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/Entity/User.php';
require_once __DIR__ . '/../classes/BaseDoctrineFilterSourceTest.php';
require_once __DIR__ . '/../classes/DataSourceChecker.php';


class DoctrineFilterSourceTest extends BaseDoctrineFilterSourceTest
{

	public function __construct()
	{
		self::$credentials = DataSourceChecker::$credentials;

		parent::__construct(__DIR__ . '/../../vendor/mesour/sources/tests/Entity');
	}

}

$test = new DoctrineFilterSourceTest();
$test->run();