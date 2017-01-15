<?php

namespace Mesour\Filter\Sources\Search;

use Nette\Utils\Strings;

class SearchPatternsHelper
{

	/**
	 * @param string $query
	 * @return string[]
	 */
	public static function getPatterns($query)
	{
		$words = [];
		foreach (Strings::split($query, '#\s+#') as $word) {
			if ($word !== '') {
				$words[] = $word;
			}
		}
		return $words;
	}

}
