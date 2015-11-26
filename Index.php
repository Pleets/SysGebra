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

include("source/autoload.php");

$f = new Pleets\SysGebra\Math\Algebra\Factorial();
$sum = new Pleets\SysGebra\Math\Algebra\Summation();
$binomial = new Pleets\SysGebra\Math\Combinatorics\Binomial();
$irr = new Pleets\SysGebra\Math\Applied\Finance\IRR();

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

echo "TIR of (-100, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20) = ";
echo $irr->compute(array(20, 20, 20, 20, 20, 20, 20, 20, 20, 20), 100) * 100 . "%";
echo "<br /><br />";
