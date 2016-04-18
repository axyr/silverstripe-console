<?php


class MaintenanceDownCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'down';

    /**
     * @var string
     */
    protected $description = 'Put the application into maintenance mode';

    public function fire()
    {
        touch(BASE_PATH.'/framework/down');

        $this->comment('Application is now in maintenance mode.');
    }
}
