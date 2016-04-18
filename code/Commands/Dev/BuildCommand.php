<?php

/**
 * Class BuildCommand.
 */
class BuildCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'dev:build';

    /**
     * @var string
     */
    protected $description = 'Rebuild the database';

    public function fire()
    {
        //$this->call('cache:clear');

        /** @var DatabaseAdmin $da */
        $da = DatabaseAdmin::create();

        // hack untill we have something better...
        ob_start();
        $da->doBuild();
        $this->info(ob_get_contents());
        ob_get_clean();
    }
}
