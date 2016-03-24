<?php
/**
 * This file is part of the Mesour Filter (http://components.mesour.com/component/filter)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Filter\Sources;

use Doctrine\ORM\Query;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class DateFunction extends Query\AST\Functions\FunctionNode
{

	private $arg;

	public function getSql(Query\SqlWalker $sqlWalker)
	{
		return sprintf('DATE(%s)', $this->arg->dispatch($sqlWalker));
	}

	public function parse(Query\Parser $parser)
	{
		$parser->match(Query\Lexer::T_IDENTIFIER);
		$parser->match(Query\Lexer::T_OPEN_PARENTHESIS);

		$this->arg = $parser->ArithmeticPrimary();

		$parser->match(Query\Lexer::T_CLOSE_PARENTHESIS);
	}

}
