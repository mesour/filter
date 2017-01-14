<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/Mesour/Filter/IFilter.php';
require_once __DIR__ . '/../src/Mesour/Filter/IFilterItem.php';
require_once __DIR__ . '/../src/Mesour/UI/Filter.php';
require_once __DIR__ . '/../src/Mesour/Filter/FilterItem.php';
require_once __DIR__ . '/../src/Mesour/Filter/Text.php';
require_once __DIR__ . '/../src/Mesour/Filter/Date.php';
require_once __DIR__ . '/../src/Mesour/Filter/Number.php';
require_once __DIR__ . '/../src/Mesour/Filter/Sources/IFilterSource.php';
require_once __DIR__ . '/../src/Mesour/Filter/Sources/ArrayFilterSource.php';
require_once __DIR__ . '/../src/Mesour/Filter/Sources/DoctrineFilterSource.php';
require_once __DIR__ . '/../src/Mesour/Filter/Sources/NetteDbFilterSource.php';
require_once __DIR__ . '/../src/Mesour/Filter/Sources/SQLHelper.php';
require_once __DIR__ . '/../src/Mesour/Filter/Sources/DateFunction.php';

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}
@mkdir(__DIR__ . "/log");
@mkdir(__DIR__ . "/tmp");

define("TEMP_DIR", __DIR__ . "/tmp/");

Tester\Environment::setup();

function pdump($val)
{
	Tracy\Debugger::$productionMode = false;
	call_user_func_array('dump', func_get_args());
}