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
use Nette\Http;


class HttpAuthenticator extends Nette\Object
{

	/** @var Http\Response */
	private $response;

	/** @var string */
	private $username;

	/** @var string */
	private $password;

	/** @var string */
	private $title;


	public function __construct(Http\Response $response, string $username, string $password, string $title)
	{
		$this->response = $response;
		$this->username = $username;
		$this->password = $password;
		$this->title = $title;
	}


	public function run(): void
	{
		if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $this->username || $_SERVER['PHP_AUTH_PW'] !== $this->password) {
			$this->response->setHeader('WWW-Authenticate', 'Basic realm="' . $this->title . '"');
			$this->response->setCode(Http\IResponse::S401_UNAUTHORIZED);
			echo '<h1>Authentication failed.</h1>';
			die();
		}
	}

}
