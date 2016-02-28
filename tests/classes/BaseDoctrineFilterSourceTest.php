<?php

namespace Mesour\Filter\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Setup;
use Mesour\Filter\Sources\DateFunction;
use Mesour\Filter\Sources\DoctrineFilterSource;
use Mesour\Sources;
use Nette\Database;
use Tracy\Debugger;


abstract class BaseDoctrineFilterSourceTest extends Sources\Tests\BaseDoctrineSourceTest
{

    public function __construct($entityDir = NULL)
    {
        parent::__construct($entityDir);

        $conn = [
            'driver' => 'pdo_mysql',
            'user' => $this->databaseFactory->getUserName(),
            'password' => $this->databaseFactory->getPassword(),
            'dbname' => $this->databaseFactory->getDatabaseName(),
        ];
        $paths = [$entityDir];

        $config = Setup::createConfiguration(!Debugger::$productionMode);

        $driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(new AnnotationReader(), $paths);
        \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);

        $config->addCustomDatetimeFunction('DATE', DateFunction::class);

        $this->entityManager = EntityManager::create($conn, $config);

        $this->user = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(Sources\Tests\Entity\User::class, 'u');
        $this->empty = $this->entityManager->createQueryBuilder()
            ->select('e')
            ->from(Sources\Tests\Entity\EmptyTable::class, 'e');
    }

    public function testApplyCustomDate()
    {
        $source = new DoctrineFilterSource($this->user, $this->columnMapping);

        DataSourceChecker::matchCustomDate(clone $source, Sources\Tests\Entity\User::class);
        die;
    }

    public function testApplyCheckersText()
    {
        $source = new DoctrineFilterSource($this->user, $this->columnMapping);

        DataSourceChecker::matchCheckersText($source, Sources\Tests\Entity\User::class);
    }

    public function testApplyCheckersDate()
    {
        $source = new DoctrineFilterSource($this->user, $this->columnMapping);

        DataSourceChecker::matchCheckersDate($source, Sources\Tests\Entity\User::class);
    }

    public function testApplyCheckersRelated()
    {
        $queryBuilder = clone $this->user;
        $queryBuilder->addSelect('g.name groupName')
            ->join(Sources\Tests\Entity\Groups::class, 'g', Join::WITH, 'u.groupId = g.id');

        $source = new DoctrineFilterSource($queryBuilder, $this->columnMapping);

        $source->setRelated(Sources\Tests\Entity\Groups::class, 'groupName');

        DataSourceChecker::matchCheckersRelated($source, 'array', 'groupName');
    }

    public function testApplyCustomText()
    {
        $source = new DoctrineFilterSource($this->user, $this->columnMapping);

        DataSourceChecker::matchCustomText(clone $source, Sources\Tests\Entity\User::class);
    }

    //todo: here test custom date

    public function testApplyCustomRelated()
    {
        $queryBuilder = clone $this->user;
        $queryBuilder->addSelect('g.name groupName')
            ->join(Sources\Tests\Entity\Groups::class, 'g', Join::WITH, 'u.groupId = g.id');

        $source = new DoctrineFilterSource($queryBuilder, $this->columnMapping);

        $source->setRelated(Sources\Tests\Entity\Groups::class, 'groupName');

        DataSourceChecker::matchCustomRelated(clone $source, 'array', 'groupName');
    }

}