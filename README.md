# Http

Predefined http `Controllers` for `WhiteFrame\Helloquent` models, tools for API responses.

## Helpers

The main usage of this package is to power up your controllers.

Add the `WhiteFrame\Http\Controller\Helpers` trait into your controller and you will have the following helpers :

* [`$this->response()`](https://github.com/white-frame/http/wiki/Response) : return a `Response`.
* [`$this->run()`](https://github.com/white-frame/http/wiki/Runner) : return a `Runner`.

## REST Controller

Simply extends your controller with `WhiteFrame\Http\Controller\Resource\Controller`.

```php
use WhiteFrame\Http\Controller\Resource\Controller;

class UserController extends Controller
{
    protected $entity = 'Path\To\User';
}
