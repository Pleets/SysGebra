<?php
/**
 * SysGebra (http://www.sysgebra.com)
 *
 * @link      http://github.com/Pleets/SysGebra
 * @copyright Copyright (c) 2016 SysGebra. (http://www.sysgebra.com)
 * @license   http://www.sysgebra.com/license
 */

namespace SysGebra\Util;

class Calculator
{
	/**
	 * Done computations
	 *
	 * @return array
	 */
	private $computation = [];

	/**
	 * Gets done computations
	 *
	 * @return array
	 */
	public function getComputation()
	{
		return $this->computation;
	}

	/**
	 * Cleans computation buffer
	 *
	 * @return null
	 */
	public function cleanBuffer()
	{
		$this->computation = [];
	}

	/**
	 * Computes a basic expression
	 *
	 * @param string $expression
	 *
	 * @return float
	 */
	public static function compute($expression)
	{
		$sign_op = '/[-]{2}/';

		if (preg_match($sign_op, $expression, $matches) === 1)
		{
			return self::compute(str_replace("--", "+", $expression));
		}

		$LNUM = "[0-9]+";
		$DNUM = "$LNUM([\.]$LNUM)?";
		$ENUM = "$DNUM([eE][\+\-]?[0-9]+)?";

		if (preg_match('/^[\-]?'.$ENUM.'$/', $expression, $match))
			return $expression;

		$POW1 = "$ENUM".'[\^]'."$ENUM";										# 1^3, 2^2.5, 2.5^2e2, 2e2^2e3
		$POW2 = '[\(][\-]?'."$ENUM".'[\)][\^][\-]?'."$ENUM";				# (1)^-3, (-2)^2.5, (-2.5)^-2e2, (2e2)^2e3
		$POW3 = "$ENUM".'[\^][\(][\-]?'."$ENUM".'[\)]';						# 1^(-3), 2^(2.5), 2.5^(-2e2), 2e2^(2e3)
		$POW4 = '[\(][\-]?'."$ENUM".'[\)][\^][\(][\-]?'."$ENUM".'[\)]';		# (-1)^(-3), (-2)^(2.5), (-2.5)^(-2e2), (2e2)^(2e3)

		if (
			preg_match('/'.$POW1.'/', $expression, $match) === 1 ||
			preg_match('/'.$POW2.'/', $expression, $match) === 1 ||
			preg_match('/'.$POW3.'/', $expression, $match) === 1 ||
			preg_match('/'.$POW4.'/', $expression, $match) === 1
		) {
			$subexpression = array_shift($match);

			preg_match_all('/[\-]?'.$ENUM.'/', $subexpression, $args);

			$r = null;

			foreach (array_shift($args) as $arg)
			{
				$r = (is_null($r)) ? $arg : pow($r, $arg);
			}

			return self::compute(str_replace($subexpression, $r, $expression));
		}

		$TIM1 = "$ENUM".'[\*]'."$ENUM";										# 1*3, 2*2.5, 2.5*2e2, 2e2*2e3
		$TIM2 = '[\(][\-]?'."$ENUM".'[\)][\*][\-]?'."$ENUM";				# (1)*-3, (-2)*2.5, (-2.5)*-2e2, (2e2)*2e3
		$TIM3 = "$ENUM".'[\*][\(][\-]?'."$ENUM".'[\)]';						# 1*(-3), 2*(2.5), 2.5*(-2e2), 2e2*(2e3)
		$TIM4 = '[\(][\-]?'."$ENUM".'[\)][\*][\(][\-]?'."$ENUM".'[\)]';		# (-1)*(-3), (-2)*(2.5), (-2.5)*(-2e2), (2e2)*(2e3)

		if (
			preg_match('/'.$TIM1.'/', $expression, $match) === 1 ||
			preg_match('/'.$TIM2.'/', $expression, $match) === 1 ||
			preg_match('/'.$TIM3.'/', $expression, $match) === 1 ||
			preg_match('/'.$TIM4.'/', $expression, $match) === 1
		) {
			$subexpression = array_shift($match);

			preg_match_all('/[\-]?'.$ENUM.'/', $subexpression, $args);

			$r = 1;

			foreach (array_shift($args) as $arg)
			{
				$r *= $arg;
			}

			return self::compute(str_replace($subexpression, $r, $expression));
		}

		$SUM1 = '[\-]?'."$ENUM".'[\+]'."$ENUM";								# 1+3, -2+2.5, 2.5+2e2, 2e2+2e3
		$SUM2 = '[\(][\-]?'."$ENUM".'[\)][\+]'."$ENUM";						# (1)+3, (-2)+2.5, (-2.5)+2e2, (2e2)+2e3
		$SUM3 = '[\-]?'."$ENUM".'[\+][\(][\-]'."$ENUM".'[\)]';				# 1+(3), -2+(2.5), 2.5+(-2e2), 2e2+(2e3)
		$SUM4 = '[\(][\-]?'."$ENUM".'[\)][\+][\(][\-]?'."$ENUM".'[\)]';		# (-1)+(-3), (-2)+(2.5), (-2.5)+(-2e2), (2e2)+(2e3)
		$SUB1 = '[\-]?'."$ENUM".'[\-]'."$ENUM";								# 1-3, -2-2.5, 2.5-2e2, 2e2-2e3
		$SUB2 = '[\(][\-]?'."$ENUM".'[\)][\-]'."$ENUM";						# (1)-3, (-2)-2.5, (-2.5)-2e2, (2e2)-2e3
		$SUB3 = '[\-]?'."$ENUM".'[\-][\(][\-]'."$ENUM".'[\)]';				# 1-(3), -2-(2.5), 2.5-(-2e2), 2e2-(2e3)
		$SUB4 = '[\(][\-]?'."$ENUM".'[\)][\-][\(][\-]?'."$ENUM".'[\)]';		# (-1)-(-3), (-2)-(2.5), (-2.5)-(-2e2), (2e2)-(2e3)

		if (
			preg_match('/'.$SUM1.'/', $expression, $match) === 1 ||
			preg_match('/'.$SUM2.'/', $expression, $match) === 1 ||
			preg_match('/'.$SUM3.'/', $expression, $match) === 1 ||
			preg_match('/'.$SUM4.'/', $expression, $match) === 1 ||
			preg_match('/'.$SUB1.'/', $expression, $match) === 1 ||
			preg_match('/'.$SUB2.'/', $expression, $match) === 1 ||
			preg_match('/'.$SUB3.'/', $expression, $match) === 1 ||
			preg_match('/'.$SUB4.'/', $expression, $match) === 1
		) {
			$subexpression = array_shift($match);

			preg_match_all('/[\-]?'.$ENUM.'/', $subexpression, $args);

			$r = 0;

			foreach (array_shift($args) as $arg)
			{
				if (empty($arg))
					continue;

				$r += $arg;
			}

			$r = ($r > 0) ? "+".$r : $r;

			return self::compute(str_replace($subexpression, $r, $expression));
		}

		$sum = explode("+", $expression);
		$sub = explode("-", $expression);
		$pow = explode("^", $expression);
		$tim = explode("*", $expression);
		$div = explode("/", $expression);

		$sin = explode("sin(", $expression);
		$cos = explode("cos(", $expression);
		$tan = explode("tan(", $expression);

		$brackets = explode("(", $expression);

		if (count($sin) > 1)
		{
			$sin_start = strpos($expression, "sin(");
			$sin_without_left_part = substr($expression, $sin_start);

			$sin_end = strrpos($sin_without_left_part, ")");
			$sin_declaration = substr($sin_without_left_part, 0, $sin_end + 1);

			$sin_args = substr($sin_declaration, 4, strrpos($sin_declaration, ")") - 4);
			$sin_solved_expression = substr($expression, 0, $sin_start) . sin(self::compute($sin_args)) . substr($sin_without_left_part, $sin_end + 1 );

			return self::compute($sin_solved_expression);
		}

		if (count($cos) > 1)
		{
			$sin_start = strpos($expression, "cos(");
			$sin_without_left_part = substr($expression, $sin_start);

			$sin_end = strrpos($sin_without_left_part, ")");
			$sin_declaration = substr($sin_without_left_part, 0, $sin_end + 1);

			$sin_args = substr($sin_declaration, 4, strrpos($sin_declaration, ")") - 4);
			$sin_solved_expression = substr($expression, 0, $sin_start) . cos(self::compute($sin_args)) . substr($sin_without_left_part, $sin_end + 1 );

			return self::compute($sin_solved_expression);
		}

		if (count($tan) > 1)
		{
			$sin_start = strpos($expression, "tan(");
			$sin_without_left_part = substr($expression, $sin_start);

			$sin_end = strrpos($sin_without_left_part, ")");
			$sin_declaration = substr($sin_without_left_part, 0, $sin_end + 1);

			$sin_args = substr($sin_declaration, 4, strrpos($sin_declaration, ")") - 4);
			$sin_solved_expression = substr($expression, 0, $sin_start) . tan(self::compute($sin_args)) . substr($sin_without_left_part, $sin_end + 1 );

			return self::compute($sin_solved_expression);
		}

		if (count($brackets) > 1)
		{
			$end_pos = strpos($expression, ")");
			$sub = substr($expression, 0, $end_pos);

			$ini_pos = strrpos($sub, "(");
			$args = substr($sub, $ini_pos + 1);

			$solved = substr($sub, 0, $ini_pos) . self::compute($args) . substr($expression, $end_pos + 1);

			return self::compute($solved);
		}

		if (count($sum) > 1)
		{
			$r = 0;

			foreach ($sum as $value)
			{
				if (empty($value))
					continue;

				$r += self::compute($value);
			}

			return $r;
		}

		if (count($sub) > 1)
		{
			$r = 0;

			$k = 0;
			foreach ($sub as $value)
			{
				$k++;

				if (empty($value))
					continue;


				if ($k != 1)
					$r -= self::compute($value);
				else
					$r += self::compute($value);
			}

			return $r;
		}

		if (count($tim) > 1)
		{
			$r = 1;

			foreach ($tim as $value)
			{
				$r *= self::compute($value);
			}

			return $r;
		}

		if (count($div) > 1)
		{
			$r = null;

			foreach ($div as $value)
			{
				$r = (is_null($r)) ? self::compute($value) : $r / self::compute($value);
			}

			return $r;
		}

		if (count($pow) > 1)
		{
			$r = null;

			foreach ($pow as $value)
			{
				$r = (is_null($r)) ? self::compute($value) : pow($r, self::compute($value));
			}

			return $r;
		}

		return $expression;
	}
}