HttpAuthExtension
=================

Installation:

```
composer require "uestla/http-auth-extension":"*"
```



Usage as simple as possible:

**config.neon:**

```
extensions:
	httpAuth: HttpAuthExtension\HttpAuthExtension

httpAuth:
	username: admin
	password: ***
	title: 'Frontend authentication' # [optional]
```
