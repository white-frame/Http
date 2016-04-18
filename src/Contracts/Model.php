<?php namespace WhiteFrame\Http\Contracts;

/**
 * Interface Model
 * @package WhiteFrame\Http\Contracts
 */
interface Model
{
	/**
	 * @return Repository
	 */
	public function getRepository();

	/**
	 * @param $name
	 * @return boolean
	 */
	public function hasTransformer($name = null);

	/**
	 * @param $name
	 * @return array
	 */
	public function getTransformer($name = null);

	/**
	 * @return array
	 */
	public function toArray();

	/**
	 * @return string
	 */
	public function getViewPath();
}