<?php
/**
 * SysGebra (http://www.sysgebra.com)
 *
 * @link      http://github.com/Pleets/SysGebra
 * @copyright Copyright (c) 2016 SysGebra. (http://www.sysgebra.com)
 * @license   http://www.sysgebra.com/license
 */

namespace SysGebra\Math\Combinatorics;

use SysGebra\Math\Algebra\Factorial;
use SysGebra\Math\Algebra\Summation;

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