<?php namespace WhiteFrame\Http;

use Exception;
use WhiteFrame\Http\Response\AjaxResponse;

/**
 * Class ExceptionHandler
 * @package WhiteFrame\Http
 */
class ExceptionHandler extends \App\Exceptions\Handler
{
	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		if($request->ajax()) {
			$response = new AjaxResponse($request);
			$response->status($request->has('callback') ? 200 : 500, 'error', $this->getAjaxMessage($e));
			return $response->get();
		}
		else {
			return parent::render($request, $e);
		}
	}

	public function getAjaxMessage(Exception $e)
	{
		$message = class_basename($e);

		if(!empty($e->getMessage())) {
			$message .= ' - ' . $e->getMessage();
		}

		return $message;
	}
}
