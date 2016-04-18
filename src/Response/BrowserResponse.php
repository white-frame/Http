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
		$this->response = view($view, $params);
	}

	public function redirect($url = null)
	{
		return $this->response = redirect($url);
	}

	public function get()
	{
		if(is_null($this->response)) {
			$this->response = new Response();
		}

		if(isset($this->message)) {
			// flash()->{$this->message['type']}($this->message['message']);
		}

		if(isset($this->code)) {
			$this->response->setStatusCode($this->code);
		}

		return $this->response;
	}
}