# Http

Simple http Controller layout for `WhiteFrame\Helloquent` REST models and response helpers for browser and api response.

## REST Model Controller

This controller will require a model with valid `Presenter`, `Renderer`, `Transformer` (for API), `Repository`, and a valid endpoint.

Simply extends your controller with `WhiteFrame\Http\Controller\Resource\Controller`.

```php
use WhiteFrame\Http\Controller\Resource\Controller;

class UserController extends Controller
{
    protected $entity = 'Path\To\User';
}
```

## Response Helpers

Add the `WhiteFrame\Http\Controller\Helpers` trait into your controller and you will have the following helpers :

* [`$this->response()`](https://github.com/white-frame/http/wiki/Response) : return a multi format `Response`.
