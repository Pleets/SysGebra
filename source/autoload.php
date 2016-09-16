<?php

/*
 *	App Autoloader
 */

function SysGebra_Loader($name)
{

	$class = __DIR__ . "/". str_replace('\\', '/', $name) . ".php";

	if (file_exists($class))
		include $class;
}

spl_autoload_register("SysGebra_Loader");