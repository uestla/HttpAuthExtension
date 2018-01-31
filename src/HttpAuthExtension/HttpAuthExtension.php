<?php

/**
 * This file is part of the HttpAuthExtension package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/HttpAuthExtension
 */

namespace HttpAuthExtension;

use Nette;


class HttpAuthExtension extends Nette\DI\CompilerExtension
{

	/** @var array */
	private $defaults = array(
		'title' => 'Frontend authentication',
	);


	/**
	 * @param  Nette\PhpGenerator\ClassType $class
	 * @return void
	 */
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$config = $this->getConfig($this->defaults);

		if (isset($config['username'], $config['password'])) {
			$initialize = $class->getMethod('initialize');

			$initialize->addBody('$auth = new HttpAuthExtension\HttpAuthenticator( $this->getByType(\'Nette\Http\IResponse\'), ?, ?, ? );',
					array($config['username'], $config['password'], $config['title']));

			$initialize->addBody('$auth->run();');
		}
	}


	/**
	 * @param  Nette\Configurator $configurator
	 * @param  string $prefix
	 * @return void
	 */
	public static function register(Nette\Configurator $configurator, $prefix = 'httpAuth')
	{
		$class = __CLASS__;
		$configurator->onCompile[] = function ($configurator, $compiler) use ($prefix, $class) {
			$compiler->addExtension($prefix, new $class);
		};
	}

}
