<?php

namespace Mesour\Filter\Tests;

use Nette\Database;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/Connection.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DatabaseFactory.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DataSourceTestCase.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/BaseDoctrineSourceTest.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/Entity/EmptyTable.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/Entity/Group.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/Entity/User.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/Entity/Company.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/Entity/UserAddress.php';
require_once __DIR__ . '/../classes/BaseDoctrineFilterSourceTest.php';
require_once __DIR__ . '/../classes/DataSourceChecker.php';

class DoctrineFilterSourceTest extends BaseDoctrineFilterSourceTest
{

}

$test = new DoctrineFilterSourceTest();
$test->run();
