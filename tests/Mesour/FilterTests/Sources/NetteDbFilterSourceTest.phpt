<?php

namespace Mesour\FilterTests\Sources;

use Nette\Database;

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../../../vendor/mesour/sources/tests/classes/Connection.php';
require_once __DIR__ . '/../../../../vendor/mesour/sources/tests/classes/DatabaseFactory.php';
require_once __DIR__ . '/../../../../vendor/mesour/sources/tests/classes/DataSourceTestCase.php';
require_once __DIR__ . '/../../../../vendor/mesour/sources/tests/classes/BaseNetteDbSourceTest.php';
require_once __DIR__ . '/BaseNetteDbFilterSourceTest.php';
require_once __DIR__ . '/DataSourceChecker.php';

class NetteDbFilterSourceTest extends BaseNetteDbFilterSourceTest
{

}

$test = new NetteDbFilterSourceTest();
$test->run();
