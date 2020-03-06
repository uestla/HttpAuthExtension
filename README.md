# HttpAuthExtension

## Installation:

```
composer require uestla/http-auth-extension
```


## Configuration:

**config.neon:**

```
extensions:
	httpAuth: HttpAuthExtension\HttpAuthExtension

httpAuth:
	username: 'admin'
	password: '***'
	title: 'Frontend authentication' # [optional]
```
