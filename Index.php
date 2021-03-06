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

ini_set('display_errors', 1);
error_reporting(-1);

include("source/autoload.php");

$f = new SysGebra\Math\Algebra\Factorial();
$sum = new SysGebra\Math\Algebra\Summation();
$binomial = new SysGebra\Math\Combinatorics\Binomial();
$irr = new SysGebra\Math\Applied\Finance\IRR();

echo "Factorial of 5: ";
echo $f->compute(5);
echo "<br />";

echo "Sum since 0 to 5 of n first natural numbers: ";
echo $sum->compute(0, 5, function($n) {
	return $n;
});
echo "<br />";

echo "Binomial coefficient C(15,3): ";
echo $binomial->coefficient(15,3);
echo "<br />";

echo "Binomial theorem (16 + 3)^4 = ";
echo $binomial->compute(16, 3, 4);
echo "<br />";

echo "IRR of (-100, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20) = ";
echo $irr->compute(array(20, 20, 20, 20, 20, 20, 20, 20, 20, 20), 100) * 100 . "%";
echo "<br /><br />";

echo "NewtonRaphson (2*x^2+1-2.718281828^x) = ";

$newton = new SysGebra\Math\Analysis\Numerical\NewtonRaphson(["initialValue" => -0.5, "epsilon" => 0.001, "maxIterations" => 5]);
echo $newton->compute("2*x^2+1-2.718281828^x", "4*x-2.718281828^x") . "<br />";

echo "Bisection (sin(x)-2*x+1) = ";

$bisection = new SysGebra\Math\Analysis\Numerical\Bisection(["interval" => ["a" => 0.5, "b" => 1.5], "epsilon" => 0.001, "maxIterations" => 6]);
echo $bisection->compute("sin(x)-2*x+1") . "<br />";