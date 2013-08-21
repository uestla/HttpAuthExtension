<?php

/**
 * This file is part of the HttpAuthExtension package
 *
 * Copyright (c) 2013 Petr Kessler (http://kesspess.1991.cz)
 *
 * @license  MIT
 * @link     https://github.com/uestla/HttpAuthExtension
 */

namespace HttpAuthExtension;

use Nette;


class HttpAuthExtension extends Nette\DI\CompilerExtension
{

	/** @var array */
	public $defaults = array(
		'title' => 'Frontend authentication',
	);



	/**
	 * @param  Nette\Utils\PhpGenerator\ClassType
	 * @return void
	 */
	function afterCompile(Nette\Utils\PhpGenerator\ClassType $class)
	{
		$config = $this->getConfig($this->defaults);

		if (isset($config['username'], $config['password'])) {
			$initialize = $class->methods['initialize'];

			$initialize->addBody('$auth = new HttpAuthExtension\HttpAuthenticator( $this->getByType(\'Nette\Http\IResponse\'), ?, ?, ? );',
					array($config['username'], $config['password'], $config['title']));
			$initialize->addBody('$auth->run();');
		}
	}



	/**
	 * @param  Nette\Config\Configurator
	 * @param  string
	 * @return void
	 */
	static function register(Nette\Config\Configurator $configurator, $prefix = 'httpAuth')
	{
		$class = __CLASS__;
		$configurator->onCompile[] = function ($configurator, $compiler) use ($prefix, $class) {
			$compiler->addExtension($prefix, new $class);
		};
	}

}
