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

class Summation
{
	public function compute($start, $end, $function)
	{
		if ($start == $end)
			return call_user_func($function, $end);

		return call_user_func($function, $start) + $this->compute($start + 1, $end, $function);
	}
}