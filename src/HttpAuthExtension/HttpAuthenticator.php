<?php

/**
 * This file is part of the HttpAuthExtension package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/HttpAuthExtension
 */

namespace HttpAuthExtension;

use Nette\Http;
use Nette\Security;

class HttpAuthenticator
{
	/** @var string */
	private $username;

	/** @var string */
	private $password;

	/** @var string */
	private $title;

	/** @var Http\IResponse */
	private $response;

	/** @var Security\Passwords */
	private $securityPassword;


	public function __construct(
		string $username,
		string $password,
		string $title,
		Http\IResponse $response,
		Security\Passwords $securityPassword
	)
	{
		$this->username = $username;
		$this->password = $password;
		$this->title = $title;
		$this->response = $response;
		$this->securityPassword = $securityPassword;
	}


	public function run(): void
	{
		if (!isset($_SERVER['PHP_AUTH_USER'])
				|| $_SERVER['PHP_AUTH_USER'] !== $this->username || $this->securityPassword->verify($_SERVER['PHP_AUTH_PW'], $this->password) === FALSE) {
			$this->response->setHeader('WWW-Authenticate', 'Basic realm="' . $this->title . '"');
			$this->response->setCode(Http\IResponse::S401_UNAUTHORIZED);
			die('<h1>Authentication failed.</h1>');
		}
	}

}
