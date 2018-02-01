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
use Nette\Http;
use Nette\Security;


class HttpAuthenticator extends Nette\Object
{

	/** @var Http\IResponse */
	private $response;

	/** @var string */
	private $username;

	/** @var string */
	private $password;

	/** @var string */
	private $title;


	/**
	 * @param  Http\IResponse $response
	 * @param  string $username
	 * @param  string $password
	 * @param  string $title
	 */
	public function __construct(Http\IResponse $response, $username, $password, $title)
	{
		$this->response = $response;
		$this->username = $username;
		$this->password = $password;
		$this->title = $title;
	}


	/** @return void */
	public function run()
	{
		if (!isset($_SERVER['PHP_AUTH_USER'])
				|| $_SERVER['PHP_AUTH_USER'] !== $this->username || Security\Passwords::verify($_SERVER['PHP_AUTH_PW'], $this->password) === FALSE) {
			$this->response->setHeader('WWW-Authenticate', 'Basic realm="' . $this->title . '"');
			$this->response->setCode(Http\IResponse::S401_UNAUTHORIZED);
			echo '<h1>Authentication failed.</h1>';
			die();
		}
	}

}
