<?php namespace WhiteFrame\Http\Response;

use Illuminate\Http\Request;
use WhiteFrame\Http\Contracts\ResponseType;
use WhiteFrame\Http\Exceptions\UnknownAjaxResponseFormat;

/**
 * Class AjaxResponse
 * @package WhiteFrame\Http\Response
 */
class AjaxResponse implements ResponseType
{
	protected $hasReponse;
	protected $code;
	protected $status;
	protected $message;
	protected $request;
	protected $datas;

	public function __construct(Request $request)
	{
		$this->code = 200;
		$this->status = 'success';
		$this->message = null;

		$this->request = $request;
		$this->hasReponse = false;
	}

	public function status($code = null, $status = null, $message = null)
	{
		$this->code = isset($code) ? $code : $this->code;
		$this->status = isset($status) ? $status : $this->status;
		$this->message = isset($message) ? $message : $this->message;

		$this->hasReponse = true;
	}

	public function datas($datas)
	{
		$this->datas = $datas;

		$this->hasReponse = true;
	}

	public function hasValidReponse()
	{
		return $this->hasReponse;
	}

	public function get()
	{
		$content = [];

		// Success by default
		$content['status'] = isset($this->status) ? $this->status : 'success';

		// Message if exists
		if(isset($this->message)) {
			$content['message'] = $this->message;
		}

		// Insert datas
		if(isset($this->datas)) {
			$content['data'] = $this->datas;
		}

		switch ($this->getFormat())
		{
			case 'json':
				$response = response()->json($content);
				break;

			case 'jsonp':
				$response = response()->json($content)->setCallback($this->request->input('callback'));
				break;
			
			default:
				throw new UnknownAjaxResponseFormat();
				break;
		}

		$response->setStatusCode($this->code);

		return $response;
	}

	protected function getFormat()
	{
		if($this->request->has('callback')) {
			return 'jsonp';
		}
		else {
			return 'json';
		}
	}
}