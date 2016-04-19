<?php namespace WhiteFrame\Http\Contracts;

/**
 * Interface MessageHandler
 * @package WhiteFrame\Http\Contracts
 */
interface MessageHandler
{
	/**
	 * @param $message
	 * @return mixed
	 */
	public function success($message);

	/**
	 * @param $message
	 * @return mixed
	 */
	public function warning($message);

	/**
	 * @param $message
	 * @return mixed
	 */
	public function error($message);
}