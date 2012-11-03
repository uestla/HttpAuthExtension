<?php


class HttpAuthExtension extends Nette\Config\CompilerExtension
{
	function afterCompile( Nette\Utils\PhpGenerator\ClassType $class )
	{
		$config = $this->getConfig();

		if ( isset($config['username']) || isset($config['password']) ) {
			$initialize = $class->methods['initialize'];

			$initialize->addBody('$context = $this;');
			$initialize->addBody('$auth = new HttpAuthenticator( $context->getByType(\'Nette\Http\IResponse\'), ?, ? );',
					array( $config['username'], $config['password'] ));
			$initialize->addBody('$auth->run();');
		}
	}



	static function register( Nette\Config\Configurator $configurator, $prefix = 'httpAuth' )
	{
		$class = __CLASS__;
		$configurator->onCompile[] = function ($configurator, $compiler) use ($prefix, $class) {
			$compiler->addExtension( $prefix, new $class );
		};
	}
}
