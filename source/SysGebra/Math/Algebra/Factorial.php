<?php
/**
 * SysGebra (http://www.sysgebra.com)
 *
 * @link      http://github.com/Pleets/SysGebra
 * @copyright Copyright (c) 2016 SysGebra. (http://www.sysgebra.com)
 * @license   http://www.sysgebra.com/license
 */

namespace SysGebra\Math\Algebra;

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