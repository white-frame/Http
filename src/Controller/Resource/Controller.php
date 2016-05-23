<?php namespace WhiteFrame\Http\Controller\Resource;

use App\Http\Controllers\Controller as AppController;
use Illuminate\Http\Request;
use WhiteFrame\Http\Contracts\Eloquent\Model;
use WhiteFrame\Http\Contracts\Eloquent\Repository;
use WhiteFrame\Http\Controller\Helpers;
use WhiteFrame\Http\Exceptions\EndpointNotSpecifiedException;
use WhiteFrame\Http\Exceptions\EntityNotSpecifiedException;
use WhiteFrame\Http\Exceptions\InvalidEntityException;
use WhiteFrame\Http\Exceptions\ViewsNotSpecifiedException;

/**
 * Class Controller
 * @package WhiteFrame\Http\Controller\Resource
 */
class Controller extends AppController
{
	use Helpers;
	
	protected $entity;
	protected $views;
	protected $endpoint;

	/**
	 * @return Model
	 * @throws EntityNotSpecifiedException
	 */
	public function getModel()
	{
		if(empty($this->entity))
			throw new EntityNotSpecifiedException("No valid entity found for controller " . get_class($this) . '. Please specify a valid $entity property.');

		$instance = new $this->entity();

		if(!is_a($instance, 'WhiteFrame\Helloquent\Model'))
			throw new InvalidEntityException("Invalid entity found for controller " . get_class($this) . '. Please check that ' . $this->entity . ' is a valid WhiteFrame\Helloquent\Model class.');

		return $instance;
	}

	/**
	 * @param $path
	 * @return string
	 * @throws ViewsNotSpecifiedException
	 */
	protected function getView($path)
	{
		if(empty($this->views)) {
			throw new ViewsNotSpecifiedException("Empty views found for controller " . get_class($this) . '. Please specify a valid $views property.');
		}

		return $this->views . '.' . $path;
	}

	protected function getEndpoint($path = '')
	{
		if(empty($this->endpoint)) {
			throw new EndpointNotSpecifiedException("Empty endpoint found for controller " . get_class($this) . '. Please specify a valid $endpoint property.');
		}

		return empty($path) ? $this->endpoint : $this->endpoint . '/' . $path;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		return $this->response()
			->collection($this->getModel()->search($request->input('q', ''))->limit(100)->get())
			->view($this->getView('index'), [
				'entities' => $this->getModel()->getRepository()->all()->get()
			]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return $this->response()
			->view($this->getView('create'), [
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
		$this->getModel()->getRepository()->create($request->all());

		return $this->response()
			->success("L'élément a été créée avec succès.")
			->redirect($this->getEndpoint());
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
			->view($this->getView('show'), [
				'entity' => $this->getModel()->getRepository()->getById($id)
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
		return $this->response()
			->view($this->getView('edit'), [
				'entity' => $this->getModel()->getRepository()->getById($id)
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
		$this->getModel()->getRepository()->update($id, $request->all());

		return $this->response()
			->success("L'élément a été modifiée avec succès.")
			->redirect($this->getEndpoint());
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
			->redirect($this->getEndpoint());
	}
}