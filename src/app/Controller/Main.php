<?php namespace App\Controller;

class Main {

    public $app = null;
    public $api = null;

    public function __construct($app, $api)
    {
        $this->app = $app;
        $this->api = $api;
    }

    private function renderTwig($view, $data = null) {
        if( ! $data ) {
            $data = [];
        }

        $renderData = array_merge(
            $data,
            [
                'actions_tree' => $this->api->actions_tree,
                'objects_tree' => $this->api->objects_tree,
            ]
        );

        return $this->app['twig']->render($view, $renderData);
    }

    public function before() {
    	if( ! $this->api ) {
    		throw new \Exception("Invalid or missing API file.  Expected api.json in project root.");
    	}
    }

    public function Index() {
        return $this->renderTwig('view/main/index.html.twig');
    }

    public function Action($ref) {
    	$ref = strtolower($ref);

    	if( ! isset($this->api->actions_index[$ref]) ) {
            // Throw an exception - 404
            throw new \Exception("Test");
    	}

        return $this->renderTwig('view/main/action.html.twig',
            [
                'action' => $this->api->actions[$this->api->actions_index[$ref]],
            ]
        );
    }

    public function Object($ref) {
        $ref = strtolower($ref);

        if( ! isset($this->api->objects_index[$ref]) ) {
            // Throw an exception - 404
        }

        return $this->renderTwig('view/main/object.html.twig',
            [
                'object' => $this->api->objects[$this->api->objects_index[$ref]],
            ]
        );
    }

    public function Error($code, $message = null) {
        return $this->renderTwig('view/main/error.html.twig',
            [
                'error' => [
                    'code' => $code,
                    'message' => $message,
                ],
            ]
        );
    }
}