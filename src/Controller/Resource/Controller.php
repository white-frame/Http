<?php namespace WhiteFrame\Http\Controller\Resource;

use App\Http\Controllers\Controller as AppController;
use Illuminate\Http\Request;
use WhiteFrame\Http\Contracts\Eloquent\Model;
use WhiteFrame\Http\Contracts\Eloquent\Repository;
use WhiteFrame\Http\Controller\Helpers;
use WhiteFrame\Http\Exceptions\EntityNotSpecifiedException;

/**
 * Class Controller
 * @package WhiteFrame\Http\Controller\Resource
 */
class Controller extends AppController
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
			throw new EntityNotSpecifiedException("No valid entity found for controller " . get_class($this) . '. Please specify a valid $entity property.');

		return new $this->entity();
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

		return $this->response()
			->collection($this->getModel()->search($request->input('q', ''))->limit(100)->get())
			->view($this->getModel()->render()->index());
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	protected function getDynatable(Request $request)
	{
		return $this->getModel()->toDynatable($request->all())->make();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return $this->response()
			->view($this->getModel()->render()->create());
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->getModel()->getRepository()->create($request->all());

		return $this->response()
			->success("L'élément a été créée avec succès.")
			->redirect($this->getModel()->endpoint());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$entity = $this->getModel()->getRepository()->getById($id);

		return $this->response()
			->item($entity)
			->view($entity->render()->show());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return $this->response()
			->view($this->getModel()->getRepository()->getById($id)->render()->edit());
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
		$this->getModel()->getRepository()->update($id, $request->all());

		return $this->response()
			->success("L'élément a été modifiée avec succès.")
			->redirect($this->getModel()->endpoint());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$entities = $this->getModel()->getRepository();
		$entity = $entities->getById($id);
		$entity->delete();

		return $this->response()
			->success("Suppression de l'élément effectué avec succès.")
			->redirect($this->getModel()->endpoint());
	}
}