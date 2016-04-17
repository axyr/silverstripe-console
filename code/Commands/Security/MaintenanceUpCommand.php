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
        $this->warn('Not implemented yet!');
    }

}
