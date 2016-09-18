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
		$sum = explode("+", $expression);
		$sub = explode("-", $expression);
		$pow = explode("^", $expression);
		$tim = explode("*", $expression);
		$div = explode("/", $expression);

		$sin = explode("sin(", $expression);
		$cos = explode("cos(", $expression);
		$tan = explode("tan(", $expression);

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
			$collector = [];

			for ($i = 0; $i < $pow[1]; $i++)
			{
				$collector[] = $pow[0];
			}

			return self::compute(implode("*", $collector));
		}

		return $expression;
	}
}