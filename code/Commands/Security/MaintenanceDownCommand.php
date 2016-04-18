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
        $this->warn('Not implemented yet!');
    }
}
