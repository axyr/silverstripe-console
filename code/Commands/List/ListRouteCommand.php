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
        $headers = ['Route', 'Controller', 'Allowed Actions'];

        $this->table($headers, $this->routes());
    }

    public function routes()
    {
        $routes = Config::inst()->get('Director', 'rules');

        $list = [];

        foreach ($routes as $route => $controller) {
            $handlers = $this->getUrlHandlersForController($controller);

            $actions = $handlers ? $handlers : $this->getActionsForController($controller);

            $list[$route] = [$route, $controller, implode("\n", $actions)];
        }

        ksort($list);

        return $list;
    }

    /**
     * @param string $controller
     *
     * @return array
     */
    protected function getActionsForController($controller)
    {
        $actions = (array) $this->getValuesOrKeysFromConfig($controller, 'allowed_actions');

        foreach ($actions as $key => $action) {
            if ($action == 'index') {
                unset($actions[$key]);
            }
        }

        return $actions;
    }

    /**
     * @param string $controller
     *
     * @return array
     */
    protected function getUrlHandlersForController($controller)
    {
        $handlers = (array) $this->getValuesOrKeysFromConfig($controller, 'url_handlers');

        foreach ($handlers as $key => $handler) {
            if ($handler == '') {
                unset($handlers[$key]);
            }
        }

        return $handlers;
    }

    /**
     * @param string $controller
     * @param string $config
     *
     * @return array
     */
    protected function getValuesOrKeysFromConfig($controller, $config = 'allowed_actions')
    {
        $values = (array) Config::inst()->get($controller, $config, Config::UNINHERITED);

        if (!isset($values[0])) { // assoc with permissions set as values
            $values = array_keys($values);
        }

        return $values;
    }
}
