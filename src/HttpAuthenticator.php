<?php

use Nette\Http;


class HttpAuthenticator extends Nette\Object
{
	/** @var Http\Response */
	protected $response;

	/** @var string */
	protected $username;

	/** @var string */
	protected $password;



	/**
	 * @param  Http\Response
	 * @param  string
	 * @param  string
	 */
	function __construct(Http\Response $response, $username, $password)
	{
		$this->response = $response;
		$this->username = $username;
		$this->password = $password;
	}



	function run()
	{
		if ( !isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $this->username || $_SERVER['PHP_AUTH_PW'] !== $this->password ) {
			$this->response->setHeader( 'WWW-Authenticate', 'Basic realm="Frontend authentication"' );
			$this->response->setCode( Http\IResponse::S401_UNAUTHORIZED );
			echo '<h1>Authentication failed.</h1>';
			die();
		}
	}
}
