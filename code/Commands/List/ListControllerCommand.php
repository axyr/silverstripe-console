<?php


class ListControllerCommand extends SilverstripeCommand
{

    /**
     * @var string
     */
    protected $signature = 'list:controller';

    /**
     * @var string
     */
    protected $description = 'List all subclasses of Controller';

    public function fire()
    {
        $headers = array('Controller', 'Extensions', 'Module');

        $this->table($headers, $this->controllers());
    }

    protected function controllers()
    {
        $controllers = ClassInfo::subclassesFor('Controller');

        unset($controllers['Controller']);

        foreach($controllers as $controller) {
            $controllers[$controller] = array(
                $controller,
                implode("\n", (array)$this->extensions($controller)),
                $this->module($controller)
            );
        }

        ksort($controllers);

        return $controllers;
    }

    protected function extensions($className)
    {
        return Config::inst()->get($className, 'extensions', Config::UNINHERITED);
    }

    protected function module($className)
    {
        $reflection = new \ReflectionClass($className);
        $file = $reflection->getFileName();

        $path = str_replace(array(BASE_PATH . '/'), '', $file);

        $parts = explode(DIRECTORY_SEPARATOR, $path);
        return array_shift($parts);
    }
}
