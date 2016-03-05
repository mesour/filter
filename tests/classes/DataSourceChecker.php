<?php
namespace Mesour\Filter\Tests;

require_once __DIR__ . '/../bootstrap.php';


use Mesour\Filter\Sources\IFilterSource;
use Mesour\Sources\ArrayHash;
use Tester\Assert;

require_once __DIR__ . '/../../vendor/mesour/sources/tests/classes/DataSourceTestCase.php';

class DataSourceChecker
{
	const COUNT_OF_JOHN = 2;
	const COUNT_OF_JOHN_AND_PETER = 0;
	const COUNT_OF_JOHN_OR_PETER = 3;

	const COUNT_OF_GROUP_1 = 7;
	const COUNT_OF_GROUP_1_AND_GROUP_2 = 0;
	const COUNT_OF_GROUP_1_OR_GROUP_2 = 13;

	const COUNT_OF_DATE_14_09_09 = 2;
	const COUNT_OF_DATE_14_09_09_BIGGER = 2;
	const COUNT_OF_DATE_14_09_09_AND_14_08_06 = 0;
	const COUNT_OF_DATE_14_09_09_OR_14_08_06 = 3;

	const TYPE_OR = 'or';
	const TYPE_AND = 'and';
	const TYPE_SIMPLE = 'simple';

	static public $credentials = [
		'user' => 'root',
		'password' => '',
	];

	static public function matchCheckersText(IFilterSource $source, $rawClassType)
	{
		$source->applyCheckers('name', ['John'], $source::TYPE_STRING);

		self::matchCounts($source, self::COUNT_OF_JOHN, \Mesour\Sources\Tests\DataSourceTestCase::FULL_USER_COUNT, $rawClassType);
	}

	static public function matchCheckersDate(IFilterSource $source, $rawClassType)
	{
		$source->applyCheckers('last_login', ['2014-09-09'], $source::TYPE_DATE);

		self::matchCounts($source, self::COUNT_OF_DATE_14_09_09, \Mesour\Sources\Tests\DataSourceTestCase::FULL_USER_COUNT, $rawClassType);
	}

	static public function matchCheckersRelated(IFilterSource $source, $rawClassType, $columnName = 'group_name')
	{
		$source->applyCheckers($columnName, ['Group 1'], $source::TYPE_STRING);

		self::matchCounts($source, self::COUNT_OF_GROUP_1, \Mesour\Sources\Tests\DataSourceTestCase::FULL_USER_COUNT, $rawClassType);
	}

	static public function matchCustomText(IFilterSource $source, $rawClassType)
	{
		foreach ([self::TYPE_AND, self::TYPE_OR, self::TYPE_SIMPLE] as $type) {
			if ($type === self::TYPE_OR) {
				$custom = self::createCustomData('John', 'Peter', 'or');
				$filteredCount = self::COUNT_OF_JOHN_OR_PETER;
			} elseif ($type === self::TYPE_AND) {
				$custom = self::createCustomData('John', 'Peter');
				$filteredCount = self::COUNT_OF_JOHN_AND_PETER;
			} else {
				$custom = self::createCustomData('John');
				$filteredCount = self::COUNT_OF_JOHN;
			}

			$currentSource = clone $source;
			$currentSource->applyCustom('name', $custom, $currentSource::TYPE_STRING);

			self::matchCounts($currentSource, $filteredCount, \Mesour\Sources\Tests\DataSourceTestCase::FULL_USER_COUNT, $rawClassType);
		}
	}

	static public function matchCustomDate(IFilterSource $source, $rawClassType)
	{
		foreach ([self::TYPE_OR, self::TYPE_AND, self::TYPE_SIMPLE] as $type) {
			if ($type === self::TYPE_OR) {
				$custom = self::createCustomData('2014-09-09 13:37:32', '2014-08-06 13:37:17', 'or');
				$filteredCount = self::COUNT_OF_DATE_14_09_09_OR_14_08_06;
			} elseif ($type === self::TYPE_AND) {
				$custom = self::createCustomData('2014-09-09 13:37:32', '2014-08-06 13:37:17');
				$filteredCount = self::COUNT_OF_DATE_14_09_09_AND_14_08_06;
			} else {
				$custom = self::createCustomData('2014-09-09 13:37:32');
				$filteredCount = self::COUNT_OF_DATE_14_09_09_BIGGER;
			}

			$currentSource = clone $source;
			$currentSource->applyCustom('last_login', $custom, $currentSource::TYPE_DATE);

			self::matchCounts($currentSource, $filteredCount, \Mesour\Sources\Tests\DataSourceTestCase::FULL_USER_COUNT, $rawClassType);
		}
	}

	static public function matchCustomRelated(IFilterSource $source, $rawClassType, $columnName = 'group_name')
	{
		foreach ([self::TYPE_AND, self::TYPE_OR, self::TYPE_SIMPLE] as $type) {
			if ($type === self::TYPE_OR) {
				$custom = self::createCustomData('Group 1', 'Group 2', 'or');
				$filteredCount = self::COUNT_OF_GROUP_1_OR_GROUP_2;
			} elseif ($type === self::TYPE_AND) {
				$custom = self::createCustomData('Group 1', 'Group 2');
				$filteredCount = self::COUNT_OF_GROUP_1_AND_GROUP_2;
			} else {
				$custom = self::createCustomData('Group 1');
				$filteredCount = self::COUNT_OF_GROUP_1;
			}

			$currentSource = clone $source;
			$currentSource->applyCustom($columnName, $custom, $currentSource::TYPE_STRING);

			self::matchCounts($currentSource, $filteredCount, \Mesour\Sources\Tests\DataSourceTestCase::FULL_USER_COUNT, $rawClassType);
		}
	}

	static protected function createCustomData($firstValue, $secondValue = null, $operator = 'and')
	{
		$out = [
			'how1' => 'equal_to',
			'val1' => $firstValue,
			'operator' => $operator,
		];
		if ($secondValue) {
			$out['how2'] = 'equal_to';
			$out['val2'] = $secondValue;
		}

		return $out;
	}

	static protected function matchCounts(IFilterSource $source, $filteredCount, $fullCount, $rawClassType)
	{
		static $lastRowsDescription = 'Output from fetchLastRawRows after %s';

		$users = $source->fetchAll();
		if (count($users) > 0) {
			Assert::type(ArrayHash::class, reset($users));
		}
		Assert::count($filteredCount, $users, 'Output from fetchAll');

		$lastRows = $source->fetchLastRawRows();
		if (count($lastRows) > 0) {
			Assert::type($rawClassType, reset($lastRows));
		}
		Assert::count($filteredCount, $lastRows, sprintf($lastRowsDescription, 'fetchAll'));

		$fullData = $source->fetchFullData();
		if (count($fullData) > 0) {
			Assert::type(ArrayHash::class, reset($fullData));
		}
		Assert::count($fullCount, $fullData, 'Output from fetchFullData');

		$lastRows = $source->fetchLastRawRows();
		if (count($lastRows) > 0) {
			Assert::type($rawClassType, reset($lastRows));
		}
		Assert::count($fullCount, $lastRows, sprintf($lastRowsDescription, 'fetchFullData'));
	}

}