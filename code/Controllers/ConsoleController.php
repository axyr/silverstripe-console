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
        'publish'
    );

    /**
     * @var Symfony\Component\Console\Application
     */
    protected $application;

    public function index()
    {
        parent::init();
        if (!Director::is_cli()) {
            $this->httpError(404);
        }

        $this->application = new Application();

        $this->loadCommands();

        // remove the framework/cli-script.php argument
        array_shift($_SERVER['argv']);
        return $this->application->run(new ArgvInput($_SERVER['argv']));
    }

    public function publish()
    {
        if (Director::is_cli()) {
            $this->writesupersakefile();
            $this->writehtaccess();
            $this->writewebconfig();
        }
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

    protected function writesupersakefile()
    {
        file_put_contents(
            BASE_PATH . '/supersake',
            file_get_contents(BASE_PATH . '/console/publish/supersake')
        );
    }

    /**
     * protect the supersake file with htaccess
     */
    protected function writehtaccess()
    {
        $content = "# Deny access to supersake
<Files supersake>
	Order allow,deny
	Deny from all
</Files>";

    }

    /**
     * protect the supersake file with web.config
     */
    public function writewebconfig()
    {
        //<add fileExtension="supersake" allowed="false"/>
    }


}
