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

namespace Pleets\SysGebra\Math\Combinatorics;

use Pleets\SysGebra\Math\Algebra\Factorial;
use Pleets\SysGebra\Math\Algebra\Summation;

class Binomial
{
	public function coefficient($n, $k)
	{
		$f = new Factorial();

		if ($k < 0 or $k > $n)
			return 0;

		$numerator = $f->compute($n);
		$denominator = $f->compute($k) * $f->compute($n - $k);

		return $numerator / $denominator;
	}

	public function compute($x, $y, $n)
	{
		$sum = new Summation();

		return $sum->compute(
			0,
			$n,
			function ($i) use ($x, $y, $n) {
				return $this->coefficient($n, $i) * pow($x, $n - $i) * pow($y, $i);
			}
		);
	}
}