<?php
/**
 * @package    Joomla.Libraries
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * This file is based on the vendor/composer/autoload_real.php file which is automatically generated by Composer when
 * installing dependencies. The original code comes with no license or copyright notice.
 */

defined('_JEXEC') or die;

class JAutoloaderComposer
{
	private static $loader;

	public static function loadClassLoader($class)
	{
		if ('Composer\Autoload\ClassLoaderJoomla' === $class)
		{
			require __DIR__ . '/ClassLoader.php';
		}
	}

	public static function getLoader()
	{
		if (null !== self::$loader)
		{
			return self::$loader;
		}

		spl_autoload_register(array('JAutoloaderComposer', 'loadClassLoader'), true, true);
		self::$loader = $loader = new \Composer\Autoload\ClassLoaderJoomla();
		spl_autoload_unregister(array('JAutoloaderComposer', 'loadClassLoader'));

		$map = require __DIR__ . '/vendor/composer/autoload_namespaces.php';

		foreach ($map as $namespace => $path)
		{
			$loader->set($namespace, $path);
		}

		$map = require __DIR__ . '/vendor/composer/autoload_psr4.php';

		foreach ($map as $namespace => $path)
		{
			$loader->setPsr4($namespace, $path);
		}

		$classMap = require __DIR__ . '/vendor/composer/autoload_classmap.php';

		if ($classMap)
		{
			$loader->addClassMap($classMap);
		}

		$loader->register(true);

		$includeFiles = require __DIR__ . '/vendor/composer/autoload_files.php';

		foreach ($includeFiles as $file)
		{
			composerJoomlaRequire($file);
		}

		return $loader;
	}
}

function composerJoomlaRequire($file)
{
	require $file;
}

JAutoloaderComposer::getLoader();