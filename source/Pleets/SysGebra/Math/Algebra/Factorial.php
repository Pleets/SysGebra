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

namespace Pleets\SysGebra\Math\Algebra;

class Factorial
{
	public function compute($number)
	{
		if ($number == 0)
			return 1;
		else if ($number == 1)
			return $number;

		if ($number < 0)
			return $number * $this->compute((-1) * $number - 1);
		return $number * $this->compute($number - 1);
	}
}