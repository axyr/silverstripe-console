<?php


class ProtectSuperSakeCommand extends SilverstripeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'protect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prevent direct file access by .htaccess and web.config';

    public function fire()
    {
        $this->warn('Not implemented yet!');
    }

    public function writeHtAccessFile()
    {

    }


}
