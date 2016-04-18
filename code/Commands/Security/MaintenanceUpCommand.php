<?php


class MaintenanceUpCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'up';

    /**
     * @var string
     */
    protected $description = 'Bring the application out of maintenance mode';

    public function fire()
    {
        @unlink(BASE_PATH.'/mysite/down');

        $this->info('Application is now live.');
    }
}
