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
    protected $description = 'PRevent direct file access by .htaccess and web.config';

    public function fire()
    {
        // $this->writeHtAccessFile();
    }
}
