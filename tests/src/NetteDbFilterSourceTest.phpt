<?php

namespace Mesour\Filter\Tests;

use Nette\Database;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/Connection.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DatabaseFactory.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DataSourceTestCase.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/BaseNetteDbSourceTest.php';
require_once __DIR__ . '/../classes/BaseNetteDbFilterSourceTest.php';
require_once __DIR__ . '/../classes/DataSourceChecker.php';


class NetteDbFilterSourceTest extends BaseNetteDbFilterSourceTest
{

	public function __construct()
	{
		self::$credentials = DataSourceChecker::$credentials;

		parent::__construct();
	}

}

$test = new NetteDbFilterSourceTest();
$test->run();