<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
	  integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<?php

define('SRC_DIR', __DIR__ . '/../src/');

require_once __DIR__ . '/../vendor/autoload.php';

@mkdir(__DIR__ . '/log');
@mkdir(__DIR__ . '/tmp');

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT, __DIR__ . '/log');
\Tracy\Debugger::$strictMode = true;

require_once SRC_DIR . 'Mesour/Filter/IFilter.php';
require_once SRC_DIR . 'Mesour/Filter/ISimpleFilter.php';
require_once SRC_DIR . 'Mesour/Filter/IFilterItem.php';
require_once SRC_DIR . 'Mesour/UI/Filter.php';
require_once SRC_DIR . 'Mesour/UI/SimpleFilter.php';
require_once SRC_DIR . 'Mesour/Filter/FilterItem.php';
require_once SRC_DIR . 'Mesour/Filter/Text.php';
require_once SRC_DIR . 'Mesour/Filter/Date.php';
require_once SRC_DIR . 'Mesour/Filter/Number.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/Search/SearchPatternsHelper.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/IFilterSource.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/ArrayFilterSource.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/DoctrineFilterSource.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/NetteDbFilterSource.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/SQLHelper.php';
require_once SRC_DIR . 'Mesour/Filter/Sources/DateFunction.php';

?>

<hr>

<div class="container" id="m_snippet-page">
	<h2>Basic functionality</h2>

	<hr>

	<?php

	// CONNECTION & NDBT

	$connection = new \Nette\Database\Connection(
		'mysql:host=127.0.0.1;dbname=sources_test',
		'root',
		'root'
	);

	$cacheMemoryStorage = new \Nette\Caching\Storages\MemoryStorage();

	$structure = new \Nette\Database\Structure($connection, $cacheMemoryStorage);
	$conventions = new \Nette\Database\Conventions\DiscoveredConventions($structure);
	$context = new \Nette\Database\Context($connection, $structure, $conventions, $cacheMemoryStorage);

	// APPLICATION

	$application = new Mesour\UI\Application;

	$application->setRequest($_REQUEST);

	$config = $application->getConfiguration();

	$config->setTempDir(__DIR__ . '/tmp');

	$application->run();

	// SELECTION

	$selection = $context->table('users');
	$selection->select('users.*')
		->select('group.name group_name')
		->select('group.type group_type')
		->select('group.date group_date');

	// SOURCE

	$source = new \Mesour\Filter\Sources\NetteDbFilterSource('users', 'id', $selection, $context, [
		'id' => 'users.id',
		'name' => 'users.name',
		'wallet_amount' => 'wallet.amount',
		'company_name' => ':user_companies.company.name',
		'companies' => ':user_companies.company.id',
		'address_city' => ':addresses.city',
		'groups' => 'group.id',
		'user_addresses' => ':addresses.id',
		'group_name' => 'group.name',
		'group_type' => 'group.type',
		'group_date' => 'group.date',
	]);

	// FILTER
	$filter = new \Mesour\UI\SimpleFilter('test', $application);

	$filter->setSource($source);

	$createdFilter = $filter->create();

	$source->applySimple($filter->getQuery(), $filter->getAllowedColumns());

	dump($source->fetchAll());

	echo $createdFilter;

	?>
</div>

<hr>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<!-- Latest compiled and minified JavaScript -->
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
		integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
		crossorigin="anonymous"></script>

<script src="../node_modules/mesour-simple-filter/dist/mesour.simple-filter.min.js"></script>