<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

/**
 * Class ConsoleController
 * The Central Command Access Point and Bootstrapper.
 */
class ConsoleController extends Controller
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'publish',
    ];

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

        $this->application = new SilverstripeApplication();

        // remove the framework/cli-script.php argument
        array_shift($_SERVER['argv']);

        $this->application->run(new ArgvInput($_SERVER['argv']));
    }

    public function publish()
    {
        if (Director::is_cli()) {
            $this->writeSuperSakeFileToWebRoot();
            $this->writehtaccess();
            $this->writewebconfig();
        }
    }

    protected function writeSuperSakeFileToWebRoot()
    {
        file_put_contents(
            BASE_PATH.'/supersake',
            file_get_contents(BASE_PATH.'/console/publish/supersake')
        );
    }

    /**
     * protect the supersake file with htaccess.
     */
    protected function writehtaccess()
    {
        $content = '# Deny access to supersake
<Files supersake>
	Order allow,deny
	Deny from all
</Files>';
    }

    /**
     * protect the supersake file with web.config.
     */
    public function writewebconfig()
    {
        //<add fileExtension="supersake" allowed="false"/>
    }
}
