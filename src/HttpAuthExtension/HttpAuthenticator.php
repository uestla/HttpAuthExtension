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
use Nette\Http;


class HttpAuthenticator extends Nette\Object
{

	/** @var Http\Response */
	protected $response;

	/** @var string */
	protected $username;

	/** @var string */
	protected $password;

	/** @var string */
	protected $title;



	/**
	 * @param  Http\Response
	 * @param  string
	 * @param  string
	 * @param  string
	 */
	function __construct(Http\Response $response, $username, $password, $title)
	{
		$this->response = $response;
		$this->username = $username;
		$this->password = $password;
		$this->title = $title;
	}



	/** @return void */
	function run()
	{
		if (!isset($_SERVER['PHP_AUTH_USER'])
				|| $_SERVER['PHP_AUTH_USER'] !== $this->username || $_SERVER['PHP_AUTH_PW'] !== $this->password) {
			$this->response->setHeader('WWW-Authenticate', 'Basic realm="' . $this->title . '"');
			$this->response->setCode(Http\IResponse::S401_UNAUTHORIZED);
			echo '<h1>Authentication failed.</h1>';
			die();
		}
	}

}
