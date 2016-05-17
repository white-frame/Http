<?php namespace WhiteFrame\Http\Response;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use WhiteFrame\Http\Contracts\ResponseType;

/**
 * Class BrowserResponse
 * @package WhiteFrame\Http\Response
 */
class BrowserResponse implements ResponseType
{
	protected $request;

	protected $message;
	protected $code;

	protected $response;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function status($code = 200, $type = null, $message = null)
	{
		$this->code = $code;

		if(isset($type) AND isset($message)) {
			$this->message = [
				'type' => $type,
				'message' => $message,
			];
		}
	}

	public function view($view, $params)
	{
		if(is_a($view, 'Illuminate\View\View')) {
			$this->response = new Response($view);
		}
		else {
			$this->response = new Response(view($view, $params));
		}
	}

	public function redirect($url = null)
	{
		$this->response = redirect($url);
	}

	public function hasValidReponse()
	{
		return !empty($this->response);
	}

	public function flashMessage()
	{
		if(isset($this->message)) {
			// Handling fail to warning
			$messageType = $this->message['type'] == 'fail' ? 'warning' : $this->message['type'];

			app('WhiteFrame\Http\Contracts\MessageHandler')->$messageType($this->message['message']);
		}
	}

	public function get()
	{
		if(isset($this->code)) {
			$this->response->setStatusCode($this->code);
		}

		return $this->response;
	}
}