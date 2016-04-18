<?php


class ProtectSuperSakeCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'console:protect';

    /**
     * @var string
     */
    protected $description = 'Prevent direct file access by .htaccess and web.config';

    public function fire()
    {
        // $this->writeHtAccessFile();
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
