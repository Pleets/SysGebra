<?php
/**
 * SysGebra (http://www.sysgebra.com)
 *
 * @link      http://github.com/Pleets/SysGebra
 * @copyright Copyright (c) 2016 SysGebra. (http://www.sysgebra.com)
 * @license   http://www.sysgebra.com/license
 */

namespace SysGebra\Math\Algebra;

class Summation
{
	public function compute($start, $end, $function)
	{
		if ($start == $end)
			return call_user_func($function, $end);

		return call_user_func($function, $start) + $this->compute($start + 1, $end, $function);
	}
}