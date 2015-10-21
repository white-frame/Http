<?php
namespace WhiteFrame\Http;

use Ifnot\WhiteFrame\Database\Eloquent\Repository;

/**
 * Class ControllerWorker
 */
class ControllerWorker
{

    protected $controller;

    /**
     * @param $entity
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param $inputs
     *
     * @return mixed
     * @throws \Exception
     */
    public function store($inputs)
    {
        $this->controller->getRepository()->create($inputs);

        return $this->controller->make()
            ->success("L'élément a été créée avec succès.")
            ->to('/' . $this->getEndpoint());
    }

    /**
     * @param $id
     * @param $inputs
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, $inputs)
    {
        $this->controller->getRepository()->update($id, $inputs);

        return $this->controller->make()
            ->success("L'élément a été modifiée avec succès.")
            ->back();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $entities = $this->controller->getRepository();
        $entity = $entities->getById($id);

        $entity->delete();

        return $this->controller->make()
            ->success("Suppression de l'élément effectué avec succès.")
            ->back();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getEndpoint()
    {
        return $this->controller->getRepository()->getModel()->endpoint;
    }
}