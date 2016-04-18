<?php namespace WhiteFrame\Http\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WhiteFrame\Http\Contracts\Model;
use WhiteFrame\Http\Contracts\Repository;
use WhiteFrame\Http\Exceptions\EntityNotSpecifiedException;

/**
 * Class ResourceController
 * @package WhiteFrame\Http\Controller
 */
class ResourceController extends Controller
{
	use Helpers;
	
	protected $entity;

	/**
	 * @return Model
	 * @throws EntityNotSpecifiedException
	 */
	public function getModel()
	{
		if(empty($this->entity))
			throw new EntityNotSpecifiedException("No valid entity found for controller " . get_class($this));

		return new $this->entity();
	}

	/**
	 * @return Repository
	 * @throws \Exception
	 */
	public function getRepository()
	{
		return $this->getModel()->getRepository();
	}

	/**
	 * @return string
	 */
	public function getViewPath()
	{
		return $this->getModel()->getViewPath();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if($request->has('dynatable'))
			return $this->getDynatable($request);

		return $this->make()
			->resource($this->getRepository()->all()->search($request->input('q', ''))->limit(100)->get())
			->view($this->getViewPath() . '.index');
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	protected function getDynatable(Request $request)
	{
		return $this->getRepository()->all()->toDynatable($request->all())->make();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view($this->getViewPath() . '.create', [
			'entity' => $this->getModel()
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		return $this->run()->store($request->all());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return view($this->getViewPath() . '.show', [
			'entity' => $this->getRepository()->getById($id)
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return view($this->getViewPath() . '.edit', [
			'entity' => $this->getRepository()->getById($id)
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		return $this->run()->update($id, $request->all());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		return $this->run()->destroy($id);
	}
}