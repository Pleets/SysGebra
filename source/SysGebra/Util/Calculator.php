<?php
/**
 * SysGebra (http://www.sysgebra.com)
 *
 * @link      http://github.com/Pleets/SysGebra
 * @copyright Copyright (c) 2016 SysGebra. (http://www.sysgebra.com)
 * @license   http://www.sysgebra.com/license
 */

namespace SysGebra\Util;

class Calculator
{
	/**
	 * Computes a basic expression
	 *
	 * @param string $expression
	 *
	 * @return float
	 */
	public static function compute($expression)
	{
		# 1^2, 2^-3
		$powRegEx = '/([0-9]+([.][0-9]+)?)?[√]?[0-9]+([.][0-9]+)?[\^]([\+\-]?[√]?[0-9]+([.][0-9]+)?)/';

		# (-1)^2, (-3)^-4
		$powRegEx2 = '/[\(][\+\-]?([0-9]+([.][0-9]+)?)?[√]?[0-9]+([.][0-9]+)?[\)][\^]([\-]?[√]?[0-9]+([.][0-9]+)?)/';

		# (1)^(2), (-3)^(-4)
		$powRegEx3 = '/[\(][\+\-]?([0-9]+([.][0-9]+)?)?[√]?[0-9]+([.][0-9]+)?[\)][\^][\(]([\-]?[√]?[0-9]+([.][0-9]+)?)[\)]/';

		if (preg_match($powRegEx, $expression, $matches) === 1)
		{
			$match = array_shift($matches);
			$pow = explode("^", $match);

			$ans = pow($pow[0], $pow[1]);

			return self::compute(str_replace($match, $ans, $expression));
		}

		if (preg_match($powRegEx2, $expression, $matches) === 1)
		{
			$match = array_shift($matches);

			$pow = explode("^", $match);

			$ans = pow(substr($pow[0], 1, strlen($pow[0] - 2)), $pow[1]);

			return self::compute(str_replace($match, $ans, $expression));
		}

		if (preg_match($powRegEx3, $expression, $matches) === 1)
		{
			$match = array_shift($matches);
			$pow = explode("^", $match);

			$ans = pow(substr($pow[0], 1, strlen($pow[0] - 2)), substr($pow[1], 1, strlen($pow[1] - 2)));

			return self::compute(str_replace($match, $ans, $expression));
		}

		# 1*3, 3*-3
		$prodRegEx = '/([0-9]+([.][0-9]+)?)?[√]?[0-9]+([.][0-9]+)?[\*]([\+\-]?[√]?[0-9]+([.][0-9]+)?)/';

		# (-1)*2, (-3)*-4
		$prodRegEx2 = '/[\(][\+\-]?([0-9]+([.][0-9]+)?)?[√]?[0-9]+([.][0-9]+)?[\)][\*]([\-]?[√]?[0-9]+([.][0-9]+)?)/';

		# (1)*(2), (-3)*(-4)
		$prodRegEx3 = '/[\(][\+\-]?([0-9]+([.][0-9]+)?)?[√]?[0-9]+([.][0-9]+)?[\)][\*][\(]([\-]?[√]?[0-9]+([.][0-9]+)?)[\)]/';

		if (preg_match($prodRegEx, $expression, $matches) === 1)
		{
			$match = array_shift($matches);
			$pow = explode("*", $match);

			$ans = $pow[0] * $pow[1];

			return self::compute(str_replace($match, $ans, $expression));
		}

		if (preg_match($prodRegEx2, $expression, $matches) === 1)
		{
			$match = array_shift($matches);

			$pow = explode("*", $match);

			$ans = substr($pow[0], 1, strlen($pow[0] - 2)) * $pow[1];

			return self::compute(str_replace($match, $ans, $expression));
		}

		if (preg_match($prodRegEx3, $expression, $matches) === 1)
		{
			$match = array_shift($matches);
			$pow = explode("*", $match);

			$ans = substr($pow[0], 1, strlen($pow[0] - 2)) * substr($pow[1], 1, strlen($pow[1] - 2));

			return self::compute(str_replace($match, $ans, $expression));
		}


		$sum = explode("+", $expression);
		$sub = explode("-", $expression);
		$pow = explode("^", $expression);
		$tim = explode("*", $expression);
		$div = explode("/", $expression);

		$sin = explode("sin(", $expression);
		$cos = explode("cos(", $expression);
		$tan = explode("tan(", $expression);

		$brackets = explode("(", $expression);

		if (count($sin) > 1)
		{
			$sin_start = strpos($expression, "sin(");
			$sin_without_left_part = substr($expression, $sin_start);

			$sin_end = strpos($sin_without_left_part, ")");
			$sin_declaration = substr($sin_without_left_part, 0, $sin_end + 1);

			$sin_args = substr($sin_declaration, 4, strpos($sin_declaration, ")") - 4);
			$sin_solved_expression = substr($expression, 0, $sin_start) . sin(self::compute($sin_args)) . substr($sin_without_left_part, $sin_end + 1 );

			return self::compute($sin_solved_expression);
		}

		if (count($cos) > 1)
		{
			$sin_start = strpos($expression, "cos(");
			$sin_without_left_part = substr($expression, $sin_start);

			$sin_end = strpos($sin_without_left_part, ")");
			$sin_declaration = substr($sin_without_left_part, 0, $sin_end + 1);

			$sin_args = substr($sin_declaration, 4, strpos($sin_declaration, ")") - 4);
			$sin_solved_expression = substr($expression, 0, $sin_start) . cos(self::compute($sin_args)) . substr($sin_without_left_part, $sin_end + 1 );

			return self::compute($sin_solved_expression);
		}

		if (count($tan) > 1)
		{
			$sin_start = strpos($expression, "tan(");
			$sin_without_left_part = substr($expression, $sin_start);

			$sin_end = strpos($sin_without_left_part, ")");
			$sin_declaration = substr($sin_without_left_part, 0, $sin_end + 1);

			$sin_args = substr($sin_declaration, 4, strpos($sin_declaration, ")") - 4);
			$sin_solved_expression = substr($expression, 0, $sin_start) . tan(self::compute($sin_args)) . substr($sin_without_left_part, $sin_end + 1 );

			return self::compute($sin_solved_expression);
		}

		if (count($brackets) > 1)
		{

			$bracket_start = strpos($expression, "(");
			$exp_without_left_part = substr($expression, $bracket_start);

			$bracket_end = strpos($exp_without_left_part, ")");
			$bracket_declaration = substr($exp_without_left_part, 0, $bracket_end + 1);

			$bracket_args = substr($bracket_declaration, 1, strpos($bracket_declaration, ")") - 1);
			$bracket_solved_expression = substr($expression, 0, $bracket_start) . self::compute($bracket_args) . substr($exp_without_left_part, $bracket_end + 1 );

			return self::compute($bracket_solved_expression);
		}

		if (count($sum) > 1)
		{
			$r = 0;

			foreach ($sum as $value)
			{
				$r += self::compute($value);
			}

			return $r;
		}

		if (count($sub) > 1)
		{
			$r = 0;

			$k = 0;
			foreach ($sub as $value)
			{
				if ($k != 0)
					$r -= self::compute($value);
				else
					$r += self::compute($value);
				$k++;
			}

			return $r;
		}

		if (count($tim) > 1)
		{
			$r = 1;

			foreach ($tim as $value)
			{
				$r *= self::compute($value);
			}

			return $r;
		}

		if (count($div) > 1)
		{
			$r = null;

			foreach ($div as $value)
			{
				$r = (is_null($r)) ? self::compute($value) : $r / self::compute($value);
			}

			return $r;
		}

		if (count($pow) > 1)
		{
			$r = null;

			foreach ($pow as $value)
			{
				$r = (is_null($r)) ? self::compute($value) : pow($r, self::compute($value));
			}

			return $r;
		}

		return $expression;
	}
}