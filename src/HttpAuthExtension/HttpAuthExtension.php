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

use Nette\Configurator;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;


class HttpAuthExtension extends CompilerExtension
{

	public function __construct()
	{
		$this->config = new class {
			/** @var string */
			public $title = 'Frontend authentication';

			/** @var string|null */
			public $username;

			/** @var string|null */
			public $password;
		};
	}


	public function afterCompile(ClassType $class): void
	{
		$config = $this->config;

		if (isset($config->username, $config->password, $config->title)) {
			$initialize = $class->methods['initialize'];

			$initialize->addBody('$auth = new HttpAuthExtension\HttpAuthenticator( $this->getByType(\'Nette\Http\IResponse\'), ?, ?, ? );',
					[$config->username, $config->password, $config->title]);

			$initialize->addBody('$auth->run();');
		}
	}


	public static function register(Configurator $configurator, string $prefix = 'httpAuth'): void
	{
		$class = __CLASS__;
		$configurator->onCompile[] = static function ($configurator, $compiler) use ($prefix, $class): void {
			$compiler->addExtension($prefix, new $class);
		};
	}

}
