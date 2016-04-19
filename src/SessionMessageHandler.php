<?php namespace WhiteFrame\Http;

use Illuminate\Http\Request;
use WhiteFrame\Http\Contracts\MessageHandler;

/**
 * Class SessionMessageHandler
 * @package WhiteFrame\Http
 */
class SessionMessageHandler implements MessageHandler
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @param $message
	 * @return mixed
	 */
	public function success($message)
	{
		$this->message('success', $message);
	}

	/**
	 * @param $message
	 * @return mixed
	 */
	public function warning($message)
	{
		$this->message('warning', $message);
	}

	/**
	 * @param $message
	 * @return mixed
	 */
	public function error($message)
	{
		$this->message('error', $message);
	}

	protected function message($status, $message)
	{
		if($this->request->hasSession()) {
			$this->request->session()->flash('flash_notification.level', $status);
			$this->request->session()->flash('flash_notification.message', $message);
		}
	}
}