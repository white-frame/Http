# Http

Predefined http `Controllers` for `WhiteFrame\Helloquent` models, tools for API responses.

## Usage

The main usage of this package is to power up your controllers.

Add the `WhiteFrame\Http\Controller\Helpers` trait into your controller and you will have the following helpers :

* [`$this->response()`](https://github.com/white-frame/http/wiki/Response) : return a `Response`.
* [`$this->run()`](https://github.com/white-frame/http/wiki/Runner) : return a `Runner`.

## Components

#### [Response](https://github.com/white-frame/http/wiki/Response)

Make http responses for ajax and browser queries in a single return.

#### [Runner](https://github.com/white-frame/http/wiki/Runner)

Execute traditionnal REST controller methods for a [WhiteFrame\Helloquent](https://github.com/white-frame/helloquent) model.
