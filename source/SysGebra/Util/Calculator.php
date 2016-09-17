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

		if (count($sum) < 2 && count($sub) < 2 && count($pow) < 2 && count($tim) < 2 && count($div) < 2)
			return $expression;

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
	}
}