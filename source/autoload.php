<?php

/*
 *	App Autoloader
 */

function Pleets_SysGebra_Loader($name)
{

	$class = __DIR__ . "/". str_replace('\\', '/', $name) . ".php";

	if (file_exists($class))
		include $class;
}

spl_autoload_register("Pleets_SysGebra_Loader");