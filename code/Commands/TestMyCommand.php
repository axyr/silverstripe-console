<?php

/**
 * Class TestMyCommand
 */
class TestMyCommand extends SilverstripeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'testmycommand:fire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Fire the command
     */
    public function fire()
    {
        $this->info('TestMyCommand fired');
    }
}
