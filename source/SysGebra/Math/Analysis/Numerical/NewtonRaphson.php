<?php
/**
 * SysGebra (http://www.sysgebra.com)
 *
 * @link      http://github.com/Pleets/SysGebra
 * @copyright Copyright (c) 2016 SysGebra. (http://www.sysgebra.com)
 * @license   http://www.sysgebra.com/license
 */

namespace SysGebra\Math\Analysis\Numerical;

use Exception;

class NewtonRaphson
{
	/**
	 * @var float
	 */
	private $initialValue;

	/**
	 * @var float
	 */
	private $epsilon;

	/**
	 * @var integer
	 */
	private $numGuesses;

	/**
	 * @var integer
	 */
	private $maxIterations;

	/**
	 * Returns the initial value
	 *
	 * @return float
	 */
	public function getInitialValue()
	{
		return $this->initialValue;
	}

	/**
	 * Returns epsilon
	 *
	 * @return float
	 */
	public function getEpsilon()
	{
		return $this->epsilon;
	}

	/**
	 * Returns the number of guesses
	 *
	 * @return float
	 */
	public function getNumGuesses()
	{
		return $this->numGuesses;
	}

	/**
	 * Returns the max number of iterations
	 *
	 * @return integer
	 */
	public function getMaxIterations()
	{
		return $this->maxIterations;
	}

	/**
	 * Sets the initial value
	 *
	 * @param float $initialValue
	 *
	 * @return float
	 */
	public function setInitialValue($initialValue)
	{
		$this->initialValue = $initialValue;
	}

	/**
	 * Sets the initial value
	 *
	 * @param float $epsilon
	 *
	 * @return float
	 */
	public function setEpsilon($epsilon)
	{
		$this->epsilon = $epsilon;
	}

	/**
	 * Sets the max number of iterations
	 *
	 * @param integer $maxIterations
	 *
	 * @return float
	 */
	public function setMaxIterations($maxIterations)
	{
		$this->maxIterations = $maxIterations;
	}

	/**
	 * Constructor
	 *
	 * @return null
	 */
	public function __construct($options = array())
	{
		if (!is_array($options))
			throw new Exception("Invalid input type given. Array expected!");

		foreach ($options as $option => $value)
		{
	    	if (property_exists(__CLASS__, $option) && method_exists($this, 'set'.ucfirst($option)))
	    	    $this->{'set'.ucfirst($option)}($value);
		}
	}

	/**
	 * Computes the Newton Raphson method
	 *
	 * @param float $function
	 * @param float $function
	 *
	 * @return array
	 */
	public function compute($f, $fprime)
	{
		$this->numGuesses++;

		$x = $this->initialValue - $this->f($f, $this->initialValue) / $this->f($fprime, $this->initialValue);

		if (abs($x - $this->initialValue) <= $this->epsilon * abs($x))
			return $x;

		$this->initialValue = $x;

		if ($this->numGuesses == $this->maxIterations)
			return $x;

		return $this->compute($f, $fprime);
	}

	/**
	 * Polynomial function computation
	 *
	 * @param string $f
	 * @param double $x
	 *
	 * @return float
	 */
	private function f($f, $x)
	{
		return $this->computeExpression(str_replace("x", $x, $f));
	}

	/**
	 * Numeric computation
	 *
	 * @param string $expression
	 *
	 * @return float
	 */
	public function computeExpression($expression)
	{
		$sum = explode("+", $expression);
		$sub = explode("-", $expression);
		$pow = explode("^", $expression);
		$tim = explode("*", $expression);

		if (count($sum) < 2 && count($sub) < 2 && count($pow) < 2 && count($tim) < 2)
			return $expression;

		if (count($sum) > 1)
		{
			$r = 0;

			foreach ($sum as $value)
			{
				$r += $this->computeExpression($value);
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
					$r -= $this->computeExpression($value);
				else
					$r += $this->computeExpression($value);
				$k++;
			}

			return $r;
		}

		if (count($tim) > 1)
		{
			$r = 1;

			foreach ($tim as $value)
			{
				$r *= $this->computeExpression($value);
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

			return $this->computeExpression(implode("*", $collector));
		}
	}
}