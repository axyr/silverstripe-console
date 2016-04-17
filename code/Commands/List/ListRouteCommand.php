<?php

class ListRouteCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $signature = 'list:route';

    /**
     * @var string
     */
    protected $description = 'List all routes to Controllers';

    public function fire()
    {
        $headers = array('Route', 'Controller', 'Allowed Actions');

        $this->table($headers, $this->routes());
    }

    public function routes()
    {
        $routes = Config::inst()->get('Director', 'rules');

        $list = array();

        foreach($routes as $route => $controller) {

            $actions = (array)Config::inst()->get($controller, 'allowed_actions', Config::UNINHERITED);

            $handlers = (array)Config::inst()->get($controller, 'url_handlers', Config::UNINHERITED);

            if(!isset($actions[0])) { // assoc with permissions set as values
                $actions = array_keys($actions);
            }

            if(!isset($handlers[0])) { // assoc with permissions set as values
                $handlers = array_keys($handlers);
            }

            foreach ($actions as $key => $action) {
                if($action == 'index') unset($actions[$key]);
            }

            foreach ($handlers as $key => $handler) {
                if($handler == '') unset($handlers[$key]);
            }

            if($handlers) {
                $actions = $handlers;
            }


            $list[$route] = array($route, $controller, implode("\n", $actions));
        }

        ksort($list);

        return $list;
    }
}
