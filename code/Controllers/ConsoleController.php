<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

/**
 * Class ConsoleController
 * The Central Command Access Point and Bootstrapper

 */
class ConsoleController extends Controller
{
    /**
     * @var array
     */
    private static $allowed_actions = array(
        'index'
    );

    /**
     * @var Symfony\Component\Console\Application
     */
    protected $application;

    public function init()
    {
        parent::init();
        if (!Director::is_cli()) {
            $this->httpError(404);
        }

        $this->application = new Application();

        $this->loadCommands();
    }

    public function index()
    {
        // remove the framework/cli-script.php argument
        array_shift($_SERVER['argv']);
        return $this->application->run(new ArgvInput($_SERVER['argv']));
    }

    public function loadCommands()
    {
        //somehow this does not work.
        //$commands = ClassInfo::subclassesFor('SilverstripeCommand');
        //var_dump($commands);exit();

        // and this is will not load other classes
        $commands = ClassInfo::classes_for_folder(BASE_PATH . '/console/');

        /** @var SilverstripeCommand $command */
        foreach ($commands as $command) {
            if (is_subclass_of($command, 'SilverstripeCommand')) {
                $this->application->add(new $command());
            }
        }
    }
}
