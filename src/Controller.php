<?php
namespace WhiteFrame\Http;

/**
 * Class Controller
 */
class Controller extends \App\Http\Controllers\Controller
{
	protected $entity;

	/**
	 * @return Model
	 */
	public function getModel()
	{
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
		return $this->make()
			->resource($this->getRepository()->all()->search($request->input('q', ''))->limit(100)->get())
			->view($this->getViewPath() . '.index', [
				'dynatable' => new Dynatable($this->getRepository()->getModel())
			]);
	}

	/**
	 * @param Request $request
	 * @throws \Exception
	 */
	public function dynatable(Request $request)
	{
		$datas = $this->getRepository()->all()->toDynatable($request->all())->make();

		$response = new DynatableResponse($this->getRepository(), $datas);

		switch ($request->input('format', 'json')) {
			default:
			case 'json':
				return $response->toJson();
				break;
			case 'csv':
				return $response->toCsv(true);
				break;
			case 'xls':
				return $response->toExcel(true);
				break;
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view($this->getViewPath() . '.create', [
			'entity' => $this->getRepository()->getModel()
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

    /**
     * Get a automated controller worker
     *
     * @return ControllerWorker
     */
    public function run()
    {
        return new ControllerWorker($this);
    }

    /**
     * Make a manual automated response
     *
     * @return Response
     */
    public function make()
    {
        return new Response();
    }
}