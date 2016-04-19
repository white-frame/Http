<?php namespace WhiteFrame\Http\Controller;

use WhiteFrame\Http\Controller\Resource\Runner;
use WhiteFrame\Http\Response\ResponseFactory;

/**
 * Trait Helpers
 * @package WhiteFrame\Http\Controller
 */
trait Helpers
{
	/**
	 * @return ResponseFactory
	 */
	public function response()
	{
		return app('WhiteFrame\Http\Response\ResponseFactory');
	}

	/**
	 * @return Runner
	 */
	public function run()
	{
		return app('WhiteFrame\Http\Controller\Runner');
	}
}