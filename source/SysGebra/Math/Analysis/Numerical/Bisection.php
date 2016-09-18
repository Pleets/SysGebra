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

class Bisection
{
	/**
	 * @var array
	 */
	private $interval = [];

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
	 * Returns the interval
	 *
	 * @return array
	 */
	public function getInterval()
	{
		return $this->interval;
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
	 * Sets the interval
	 *
	 * @param array $interval
	 *
	 * @return null
	 */
	public function setInterval($interval)
	{
		$this->interval = $interval;
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
		if (!is_array($options))
			throw new Exception("Invalid input type given. Array expected!");

		foreach ($options as $option => $value)
		{
			if (property_exists(__CLASS__, $option) && method_exists($this, 'set'.ucfirst($option)))
				$this->{'set'.ucfirst($option)}($value);
		}
	}

	/**
	 * Computes the Bisection method
	 *
	 * @param float $function
	 * @param float $function
	 *
	 * @return array
	 */
	public function compute($f)
	{
		$this->numGuesses++;

		$c = ($this->interval["a"] + $this->interval["b"]) / 2;

		# check opposites
		if (
			$this->f($f, $this->interval["a"]) > 0 &&  $this->f($f, $this->interval["b"]) > 0 ||
			$this->f($f, $this->interval["a"]) < 0 &&  $this->f($f, $this->interval["b"]) < 0
		)
		throw new Exception("f(a) and f(b) have the same sign");

		$sign_ref = ($this->f($f, $this->interval["a"]) > 0);

		if ($this->f($f, $c) > 0 && $sign_ref)
			$this->interval["a"] = $c;
		else if ($this->f($f, $c) < 0 && $sign_ref)
			$this->interval["b"] = $c;
		else if ($this->f($f, $c) > 0 && $sign_ref)
			$this->interval["b"] = $c;
		else
			$this->interval["a"] = $c;

		if ($this->numGuesses == $this->maxIterations)
			return $c;

		return $this->compute($f);
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
		return Calculator::compute(str_replace("x", "(".$x.")", $f));
	}
}