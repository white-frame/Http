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
	protected $is_set;
	protected $code;
	protected $status;
	protected $message;

	protected $datas;

	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->is_set = false;
	}

	public function status($code = 200, $status = null, $message = null)
	{
		$this->code = $code;
		$this->status = $status;
		$this->message = $message;

		$this->is_set = true;
	}

	public function datas($datas)
	{
		$this->datas = $datas;

		$this->is_set = true;
	}

	public function get()
	{
		if($this->is_set) {
			app()->abort(406, "No response type set for ajax response.");
		}

		$content = [];

		if(isset($this->status)) {
			$content['status'] = $this->status;
		}
		if(isset($this->message)) {
			$content['message'] = $this->message;
		}
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
		return 'json';
	}
}