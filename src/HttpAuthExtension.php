<?php


class HttpAuthExtension extends Nette\Config\CompilerExtension
{
	function afterCompile(\Nette\Utils\PhpGenerator\ClassType $class)
	{
		$initialize = $class->methods['initialize'];
		$config = $this->getConfig();

		$initialize->addBody('$context = $this;');
		$initialize->addBody('$auth = new HttpAuthenticator( $context->getByType(\'Nette\Http\IResponse\'), ?, ? );',
				array( $config['username'], $config['password'] ));
		$initialize->addBody('$auth->run();');
	}



	static function register(Nette\Config\Configurator $configurator, $prefix = 'httpAuth')
	{
		$class = __CLASS__;
		$configurator->onCompile[] = function ($configurator, $compiler) use ($prefix, $class) {
			$compiler->addExtension( $prefix, new $class );
		};
	}
}
