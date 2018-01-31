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
	private $defaults = [
		'title' => 'Frontend authentication',
		'enable' => true,
	];


	/**
	 * @param  Nette\PhpGenerator\ClassType $class
	 * @return void
	 */
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$config = $this->getConfig($this->defaults);

		if (!$config['enable']) {
			return;
		}

		if (isset($config['username'], $config['password'])) {
			$initialize = $class->getMethod('initialize');

			$initialize->addBody('if (php_sapi_name() === \'cli\') {');

			$initialize->addBody('(new ' . HttpAuthenticator::class . '( $this->getByType(\'' . Nette\Http\IResponse::class . '\'), ?, ?, ? ))->run();',
				[$config['username'], $config['password'], $config['title']]);

			$initialize->addBody('}');
		}
	}

}
