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
	protected $code;
	protected $status;
	protected $message;

	protected $datas;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function status($code = 200, $status = null, $message = null)
	{
		$this->code = $code;
		$this->status = $status;
		$this->message = $message;
	}

	public function datas($datas)
	{
		$this->datas = $datas;
	}

	public function get()
	{
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