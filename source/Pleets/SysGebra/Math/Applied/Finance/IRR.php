<?php

/*
 * SysGebra - Computer algebra system
 * http://www.pleets.org
 * Copyright 2015, Pleets Apps
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Date: 2015-11-25
 */

namespace Pleets\SysGebra\Math\Applied\Finance;

use Pleets\SysGebra\Math\Algebra\Summation;
use Pleets\SysGebra\Math\Combinatorics\Binomial;

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