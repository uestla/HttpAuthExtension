<?php

declare(strict_types = 1);

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


	public function afterCompile(Nette\PhpGenerator\ClassType $class): void
	{
		$config = $this->getConfig($this->defaults);

		if (isset($config['username'], $config['password'])) {
			$initialize = $class->methods['initialize'];

			$initialize->addBody('$auth = new HttpAuthExtension\HttpAuthenticator( $this->getByType(\'Nette\Http\IResponse\'), ?, ?, ? );',
					array($config['username'], $config['password'], $config['title']));

			$initialize->addBody('$auth->run();');
		}
	}


	public static function register(Nette\Configurator $configurator, string $prefix = 'httpAuth'): void
	{
		$class = __CLASS__;
		$configurator->onCompile[] = static function ($configurator, $compiler) use ($prefix, $class): void {
			$compiler->addExtension($prefix, new $class);
		};
	}

}
