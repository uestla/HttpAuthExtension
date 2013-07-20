HttpAuthExtension
=================

Installation:

```
composer require "uestla/http-auth-extension":"*"
```



Usage as simple as possible:

**bootstrap.php**
```php
HttpAuthExtension::register( $configurator );
```



**config.neon:**

```
httpAuth:
	username: admin
	password: ***
	title: 'Frontend authentication' ; [optional]
```
