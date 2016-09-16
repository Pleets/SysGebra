<?php
/**
 * SysGebra (http://www.sysgebra.com)
 *
 * @link      http://github.com/Pleets/SysGebra
 * @copyright Copyright (c) 2016 SysGebra. (http://www.sysgebra.com)
 * @license   http://www.sysgebra.com/license
 */

namespace SysGebra\Math\Applied\Finance;

use SysGebra\Math\Algebra\Summation;
use SysGebra\Math\Combinatorics\Binomial;

class IRR
{
	public function compute($F, $I)
	{
		$x = 0;
		$epsilon = 0.000001;
		$numGuesses = 0;

		$low = 0;
		$high = 1;
		$irr = ($low + $high) / 2.0;

		$sum = new Summation();
		$binomial = new Binomial();

		$n = count($F);

		while (abs(
			$sum->compute(1, $n, function($i) use ($F, $n, $binomial, $irr)
			{
				$numerator = $F[$i - 1];
				$denominator = $binomial->compute(1, $irr, $i);

				return $numerator / $denominator;
			}) - $I - $x
		) >= $epsilon)
		{
			$numGuesses++;

			$npv = $sum->compute(1, $n, function($i) use ($F, $n, $binomial, $irr)
			{
				$numerator = $F[$i - 1];
				$denominator = $binomial->compute(1, $irr, $i);

				return $numerator / $denominator;
			}) - $I;

			if ($npv > $x)
				$low = $irr;
			else
				$high = $irr;

			$irr = ($low + $high) / 2.0;
		}

		return $irr;
	}
}