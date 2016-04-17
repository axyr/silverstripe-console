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

    public function publish()
    {
        if (Director::is_cli()) {
            $this->writesupersakefile();
            $this->writehtaccess();
            $this->writewebconfig();
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
