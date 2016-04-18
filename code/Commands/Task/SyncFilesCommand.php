<?php


class SyncFilesCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'task:filesync';

    /**
     * @var string
     */
    protected $description = 'Sync the filesystem and database';

    public function fire()
    {
        $this->info(Filesystem::sync());
    }
}
