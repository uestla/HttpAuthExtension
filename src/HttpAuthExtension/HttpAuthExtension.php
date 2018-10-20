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
	public $defaults = array(
		'title' => 'Frontend authentication',
	);

	/** @var bool */
	private $consoleMode;


	public function __construct($consoleMode)
	{
		$this->consoleMode = $consoleMode;
	}


	/**
	 * @param  Nette\PhpGenerator\ClassType $class
	 * @return void
	 */
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$config = $this->getConfig($this->defaults);

		if (!$this->consoleMode && isset($config['username'], $config['password'])) {
			$initialize = $class->methods['initialize'];

			$initialize->addBody('(new HttpAuthExtension\HttpAuthenticator( $this->getByType(\'Nette\Http\IResponse\'), ?, ?, ? ))->run();',
					array($config['username'], $config['password'], $config['title']));
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
