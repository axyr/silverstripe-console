<?php


class RemoveResampledImageCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'task:removeresampled';

    /**
     * @var string
     */
    protected $description = 'Remove all _resampled verions of Images';

    public function fire()
    {
        $this->warn('Not implemented yet!');
    }
}
