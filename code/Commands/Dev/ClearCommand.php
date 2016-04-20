<?php


class ClearCommand extends SilverstripeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'clear:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the cache by deleting the files and rebuild the manifest';

    public function fire()
    {
        $this->info('Used cache location: ' . TEMP_FOLDER);

        SS_ClassLoader::instance()->getManifest()->regenerate();
        $this->info('regenerated manifest!');

        ClassInfo::reset_db_cache();
        $this->info('resetted db cache!');
    }
}
