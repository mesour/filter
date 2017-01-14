<?php

namespace Mesour\FilterTests\Sources;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\DBAL\Types\StringType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Setup;
use Mesour\Filter\Sources\DateFunction;
use Mesour\Filter\Sources\DoctrineFilterSource;
use Mesour\Sources;
use Nette\Database;

abstract class BaseDoctrineFilterSourceTest extends Sources\Tests\DataSourceTestCase
{

	/** @var \Doctrine\ORM\EntityManager */
	protected $entityManager;

	/** @var \Doctrine\ORM\QueryBuilder */
	protected $user;

	/** @var \Doctrine\ORM\QueryBuilder */
	protected $empty;

	protected $columnMapping = [
		'id' => 'u.id',
		'group_id' => 'u.groups',
		'last_login' => 'u.lastLogin',
		'group_name' => 'g.name',
		'group_type' => 'g.type',
		'group_date' => 'g.date',
	];

	public function __construct($setConfigFiles = true)
	{
		if ($setConfigFiles) {
			$this->configFile = __DIR__ . '/../../../config.php';
			$this->localConfigFile = __DIR__ . '/../../../config.local.php';
		}
		parent::__construct();

		$isDevMode = false;

		$cache = new \Doctrine\Common\Cache\FilesystemCache(__DIR__ . '/../../../tmp');
		$config = Setup::createConfiguration($isDevMode, __DIR__ . '/../../../tmp', $cache);
		$config->setProxyDir(__DIR__ . '/../../../tmp');
		$config->setProxyNamespace('MyProject\Proxies');

		$config->setAutoGenerateProxyClasses(true);

		$paths = [__DIR__ . '/../Entity'];

		$driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(new AnnotationReader(), $paths);
		\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
		$config->setMetadataDriverImpl($driver);
		//$config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
		$conn = [
			'driver' => 'mysqli',
			'host' => '127.0.0.1',
			'user' => $this->databaseFactory->getUserName(),
			'password' => $this->databaseFactory->getPassword(),
			'dbname' => $this->databaseFactory->getDatabaseName(),
		];

		$this->entityManager = EntityManager::create($conn, $config);

		$this->entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
		\Doctrine\DBAL\Types\Type::addType('enum', StringType::class);

		$this->entityManager->getConfiguration()
			->addCustomStringFunction('DATE', DateFunction::class);

		$this->user = $this->entityManager->createQueryBuilder()
			->select('u')
			->from(Sources\Tests\Entity\User::class, 'u');
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
		return new DoctrineFilterSource(
			$table,
			'id',
			$queryBuilder,
			$this->columnMapping
		);
	}

}
