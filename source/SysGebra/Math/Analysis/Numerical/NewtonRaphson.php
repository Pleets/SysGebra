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
use SysGebra\Util\Calculator;

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
	 * Calculator to do computations
	 *
	 * @var Calculator
	 */
	private $calculator;

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
	 * Returns the Calculator
	 *
	 * @return Calculator
	 */
	public function getCalculator()
	{
		return $this->calculator;
	}

	/**
	 * Sets the initial value
	 *
	 * @param float $initialValue
	 *
	 * @return null
	 */
	public function setInitialValue($initialValue)
	{
		$this->initialValue = $initialValue;
	}

	/**
	 * Sets epsilon
	 *
	 * @param float $epsilon
	 *
	 * @return null
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
	 * @return null
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
		$this->calculator  = new Calculator;
		$this->computation = [];

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
	 * Computes the function
	 *
	 * @param string $f
	 * @param double $x
	 *
	 * @return float
	 */
	private function f($f, $x)
	{
		return $this->calculator->compute(str_replace("x", "(".$x.")", $f));
	}
}