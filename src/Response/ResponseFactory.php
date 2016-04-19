<?php namespace WhiteFrame\Http\Response;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use WhiteFrame\Http\Contracts\Eloquent\Model;
use WhiteFrame\Http\Contracts\ResponseType;

/**
 * Class ResponseFactory
 * @package WhiteFrame\Http\Response
 */
class ResponseFactory extends Response
{
	protected $request;
	protected $fractal;
	protected $types;

	public function __construct(Request $request)
	{
		parent::__construct();

		$this->request = $request;
		$this->fractal = new FractalManager();
		$this->types = Collection::make([
			'ajax' => new AjaxResponse($request),
			'browser' => new BrowserResponse($request)
		]);
	}

	/**
	 * @param null $message
	 */
	public function success($message = null)
	{
		$this->status(self::HTTP_OK, 'success', $message);

		return $this;
	}

	/**
	 * @param null $message
	 */
	public function fail($message = null)
	{
		$this->status(self::HTTP_BAD_REQUEST, 'fail', $message);

		return $this;
	}

	public function warning($message = null)
	{
		return $this->fail($message);
	}

	/**
	 * @param null $message
	 */
	public function error($message = null)
	{
		$this->status(self::HTTP_INTERNAL_SERVER_ERROR, 'error', $message);

		return $this;
	}

	/**
	 * @param Model $model
	 */
	public function item(Model $model)
	{
		if($model->hasTransformer()) {
			$resource = new FractalItem($model, $model->transformer());
			$datas = $this->fractal->createData($resource)->toArray()['data'];
		}
		else {
			$datas = $model->toArray();
		}

		$this->types->get('ajax')->datas($datas);

		return $this;
	}

	/**
	 * @param EloquentCollection $models
	 */
	public function collection(EloquentCollection $models)
	{
		if($models->first()->hasTransformer()) {
			$resource = new FractalCollection($models, $models->first()->transformer());
			$datas = $this->fractal->createData($resource)->toArray()['data'];
		}
		else {
			$datas = $models->toArray();
		}

		$this->types->get('ajax')->datas($datas);

		return $this;
	}

	/**
	 * @param $datas
	 */
	public function datas($datas)
	{
		$this->types->get('ajax')->datas($datas);

		return $this;
	}

	/**
	 * @param $view
	 * @param array $params
	 * @return $this
	 */
	public function view($view, $params = [])
	{
		$this->types->get('browser')->view($view, $params);

		return $this;
	}

	/**
	 * @param null $url
	 * @return mixed
	 */
	public function redirect($url = null)
	{
		return $this->types->get('browser')->redirect($url);
	}

	/**
	 *
	 */
	public function send()
	{
		if($this->request->ajax()) {
			if($this->types->get('ajax')->hasValidReponse()) {
				$this->types->get('ajax')->get()->send();
			}
			else {
				app()->abort(406, "No response type set for ajax response.");
			}
		}
		else {
			// In all cases, flash messages
			$this->types->get('browser')->flashMessage();

			// Try to send browser response
			if($this->types->get('browser')->hasValidReponse()) {
				$this->types->get('browser')->get()->send();
			}

			// Or send ajax if we have no browser
			elseif($this->types->get('ajax')->hasValidReponse()) {
				$this->types->get('ajax')->get()->send();
			}

			// Fail if nothing
			else {
				app()->abort(406, "No response type set for browser response.");
			}
		}
	}

	protected function status($code = 200, $status = null, $message = null)
	{
		$this->types->map(function(ResponseType $type) use ($code, $status, $message) {
			$type->status($code, $status, $message);
		});
	}
}