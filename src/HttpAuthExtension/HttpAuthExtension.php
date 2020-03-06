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
use Nette\DI;
use Nette\Schema;

class HttpAuthExtension extends DI\CompilerExtension
{
	private const SERVICE_NAME_AUTHENTICATOR = 'httpAuthenticator';

	/** @var bool */
	private $consoleMode;

	/** @var bool */
	private $isAuthenticationAvailable;


	public function __construct(bool $consoleMode)
	{
		$this->consoleMode = $consoleMode;
	}


	public function loadConfiguration()
	{
		$this->isAuthenticationAvailable = !$this->consoleMode && isset($this->config->username, $this->config->password);
		if ($this->isAuthenticationAvailable) {
			$this->buildHttpAuthenticator($this->config->username, $this->config->password, $this->config->title);
		}
	}


	public function getConfigSchema(): Schema\Schema
	{
		return Schema\Expect::structure([
			'title' => Schema\Expect::string('Frontend authentication'),
			'username' => Schema\Expect::string(),
			'password' => Schema\Expect::string(),
		]);
	}


	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		if ($this->isAuthenticationAvailable) {
			$initialize = $class->getMethods()['initialize'];
			$initialize->addBody('$this->getService(?)->run();', [$this->prefix(self::SERVICE_NAME_AUTHENTICATOR)]);
		}
	}


	private function buildHttpAuthenticator(string $username, string $password, string $title): void
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix(self::SERVICE_NAME_AUTHENTICATOR))
			->setFactory(HttpAuthenticator::class)
			->setArguments([$username, $password, $title])
			->setAutowired(FALSE);
	}

}
