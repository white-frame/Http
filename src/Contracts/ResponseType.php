<?php namespace WhiteFrame\Http\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Interface ResponseType
 * @package WhiteFrame\Http\Contracts\Response
 */
interface ResponseType
{
	/**
	 * Type constructor.
	 * @param Request $request
	 */
	public function __construct(Request $request);

	/**
	 * Set the status of the query
	 * @param int $code
	 * @param null $status
	 * @param null $message
	 * @return mixed
	 */
	public function status($code = 200, $status = null, $message = null);

	/**
	 * Return the Response to be returned to the browser
	 * @return Response
	 */
	public function get();
}