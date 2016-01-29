<?php

namespace Mesour\Filter\Tests;

use Nette\Database;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/Connection.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DatabaseFactory.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DataSourceTestCase.php';
require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/BaseArraySourceTest.php';
require_once __DIR__ . '/../classes/BaseArrayFilterSourceTest.php';
require_once __DIR__ . '/../classes/DataSourceChecker.php';


class ArrayFilterSourceTest extends BaseArrayFilterSourceTest
{

    public function __construct()
    {
        $this->credentials = DataSourceChecker::$credentials;

        parent::__construct();
    }

}

$test = new ArrayFilterSourceTest();
$test->run();