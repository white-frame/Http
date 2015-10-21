<?php
namespace WhiteFrame\Http;

use Laracasts\Flash\Flash;
use Redirect;
use View;

/**
 * Class Response
 * @package B2B\Core\Http\Responses
 */
class Response
{
    private $resource;
    private $message;
    private $target;

    /**
     * @param $message
     *
     * @return $this
     */
    public function success($message = "Opération effectuée avec succès")
    {
        $this->message = [
            'type' => 'success',
            'message' => $message
        ];

        return $this;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function error($message = "Une erreur est survenue")
    {
        $this->message = [
            'type' => 'error',
            'message' => $message
        ];

        return $this;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function warning($message = "Opération effectuée avec des erreurs")
    {
        $this->message = [
            'type' => 'warning',
            'message' => $message
        ];

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function resource($data)
    {
        $this->resource = $data;
        return $this;
    }

    /**
     * @param       $view
     * @param array $data
     *
     * @return array|string|void
     */
    public function view($view, $data = [])
    {
        $this->target = [
            'type' => 'view',
            'view' => View::make($view, $data)
        ];

        return $this->get();
    }

    /**
     * @return $this
     */
    public function back()
    {
        $this->target = [
            'type' => 'back',
        ];

        return $this->get();
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function to($path)
    {
        $this->target = [
            'type' => 'to',
            'path' => $path
        ];

        return $this->get();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|string
     */
    private function get()
    {
        if (\Request::ajax()) return $this->getAjax();
        else return $this->getDirect();
    }

    /**
     * @return string
     */
    private function getAjax()
    {
        if (isset($this->resource)) {
            return json_encode($this->resource);
        } elseif (isset($this->message)) {
            return json_encode([
                'status' => $this->message['type'],
                'message' => $this->message['message']
            ]);
        }

        return json_encode([]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|string
     */
    private function getDirect()
    {
        if (isset($this->message)) {
            $type = $this->message['type'];
            $message = $this->message['message'];

	        Flash::$type($message);
        }

        if (isset($this->target)) {
            switch ($this->target['type']) {
                case "back":
                    return Redirect::back();
                    break;

                case "to":
                    return Redirect::to($this->target['path']);
                    break;

                case "view":
                    return $this->target['view'];
                    break;

                default:
                    return "";
            }
        }
    }
}